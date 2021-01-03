<?php

namespace SergeyKasyanov\Tenancy\Actions;

use Illuminate\Support\Facades\Event;
use SergeyKasyanov\Tenancy\Events\AfterForgetCurrentTenant;
use SergeyKasyanov\Tenancy\Events\BeforeForgetCurrentTenant;
use SergeyKasyanov\Tenancy\Services\TenancyManager;

class ForgetCurrentTenant
{
    /**
     * @var \SergeyKasyanov\Tenancy\Services\TenancyManager
     */
    private $tenancyManager;

    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $switchTenantTasks;

    public function __construct()
    {
        $this->tenancyManager = TenancyManager::instance();

        $this->switchTenantTasks = config('sergeykasyanov.tenancy::switch_tenant_tasks');
    }

    public static function make(): ForgetCurrentTenant
    {
        return new static();
    }

    public function execute(): void
    {
        $currentTenant = $this->tenancyManager->getCurrent();

        Event::dispatch(new BeforeForgetCurrentTenant($currentTenant));

        if ($currentTenant) {
            $this->performForgetCurrentTenantTasks();
        }

        $this->tenancyManager->forgetCurrent();

        Event::dispatch(new AfterForgetCurrentTenant($currentTenant));
    }

    private function performForgetCurrentTenantTasks(): void
    {
        foreach ($this->switchTenantTasks as $taskClass) {
            /** @var \SergeyKasyanov\Tenancy\Tasks\SwitchTenantTask $task */
            $task = new $taskClass;

            $task->forgetCurrent();
        }
    }
}
