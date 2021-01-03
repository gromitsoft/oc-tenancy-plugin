<?php

namespace SergeyKasyanov\Tenancy\Actions;

use Illuminate\Support\Facades\Event;
use SergeyKasyanov\Tenancy\Events\AfterMakeTenantCurrent;
use SergeyKasyanov\Tenancy\Events\BeforeMakeTenantCurrent;
use SergeyKasyanov\Tenancy\Models\Tenant;
use SergeyKasyanov\Tenancy\Services\TenancyManager;

class MakeTenantCurrent
{
    /**
     * @var \SergeyKasyanov\Tenancy\Services\TenancyManager
     */
    private $tenancyManager;

    /**
     * @var string[]
     */
    private $switchTenantTasks;

    public function __construct()
    {
        $this->tenancyManager = TenancyManager::instance();

        $this->switchTenantTasks = config('sergeykasyanov.tenancy::switch_tenant_tasks');
    }

    public static function make(): MakeTenantCurrent
    {
        return new static();
    }

    public function execute(Tenant $tenant): void
    {
        ForgetCurrentTenant::make()->execute();

        Event::dispatch(new BeforeMakeTenantCurrent($tenant));

        $this->performMakeTenantCurrentTasks($tenant);

        $this->tenancyManager->setCurrent($tenant);

        Event::dispatch(new AfterMakeTenantCurrent($tenant));
    }

    private function performMakeTenantCurrentTasks(Tenant $tenant): void
    {
        foreach ($this->switchTenantTasks as $taskClass) {
            /** @var \SergeyKasyanov\Tenancy\Tasks\SwitchTenantTask $task */
            $task = new $taskClass;

            $task->makeCurrent($tenant);
        }
    }
}
