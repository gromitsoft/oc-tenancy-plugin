<?php

/**
 * Big thanks to devs from spatie.be
 */

namespace GromIT\Tenancy\Actions\TenantAwareness;

use GromIT\Tenancy\Actions\CurrentTenant\MakeTenantCurrent;
use GromIT\Tenancy\Classes\TenancyManager;
use GromIT\Tenancy\Concerns\TenantAwareJob;
use GromIT\Tenancy\Exceptions\TenantNotFoundInTenantAwareJob;
use GromIT\Tenancy\Models\Tenant;
use Illuminate\Broadcasting\BroadcastEvent;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Mail\SendQueuedMailable;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Queue\Events\JobProcessing;
use October\Rain\Support\Arr;
use ReflectionClass;

class MakeQueueTenantAware
{
    /**
     * @var \GromIT\Tenancy\Classes\TenancyManager
     */
    protected $tenancyManager;

    public function __construct()
    {
        $this->tenancyManager = TenancyManager::instance();
    }

    public static function make(): MakeQueueTenantAware
    {
        return new static();
    }

    public function execute(): void
    {
        $this
            ->listenForJobsBeingQueued()
            ->listenForJobsBeingProcessed();
    }

    protected function listenForJobsBeingProcessed(): self
    {
        app('events')->listen(JobProcessing::class, function (JobProcessing $event) {
            if (!array_key_exists('tenantId', $event->job->payload())) {
                return;
            }

            $tenant = $this->findTenant($event);

            MakeTenantCurrent::make()->execute($tenant);
        });

        return $this;
    }

    /**
     * @param \Illuminate\Queue\Events\JobProcessing $event
     *
     * @return \GromIT\Tenancy\Models\Tenant
     * @throws \GromIT\Tenancy\Exceptions\TenantNotFoundInTenantAwareJob
     */
    protected function findTenant(JobProcessing $event): Tenant
    {
        $tenantId = Arr::get($event->job->payload(), 'tenantId');

        if (!$tenantId) {
            $event->job->delete();

            throw TenantNotFoundInTenantAwareJob::noIdSet($event->job->getName());
        }

        /** @var Tenant $tenant */
        $tenant = Tenant::query()->find($tenantId);

        if ($tenant === null) {
            $event->job->delete();

            throw TenantNotFoundInTenantAwareJob::noTenantFound($event->job->getName());
        }

        return $tenant;
    }

    protected function listenForJobsBeingQueued(): self
    {
        app('queue')->createPayloadUsing(function ($connectionName, $queue, $payload) {
            $queueable = app($payload['job']);

            if (!$this->isTenantAware($queueable)) {
                return [];
            }

            return [
                'data' => array_merge(
                    $payload['data'],
                    ['tenantId' => optional($this->tenancyManager->getCurrent())->id]
                )
            ];
        });

        return $this;
    }

    /**
     * @param object $queueable
     *
     * @return bool
     */
    protected function isTenantAware(object $queueable): bool
    {
        $reflection = new ReflectionClass($this->getJobFromQueueable($queueable));

        return $reflection->implementsInterface(TenantAwareJob::class);
    }

    protected function getJobFromQueueable(object $queueable): object
    {
        switch (get_class($queueable)) {
            case SendQueuedMailable::class:
                return $queueable->mailable;
            case SendQueuedNotifications::class:
                return $queueable->notification;
            case CallQueuedListener::class:
                return $queueable->class;
            case BroadcastEvent::class:
                return $queueable->event;
            default:
                return $queueable;
        }
    }
}
