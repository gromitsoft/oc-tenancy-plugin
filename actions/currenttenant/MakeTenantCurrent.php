<?php

namespace GromIT\Tenancy\Actions\CurrentTenant;

use Illuminate\Support\Facades\Event;
use GromIT\Tenancy\Classes\TenancyManager;
use GromIT\Tenancy\Events\AfterMakeTenantCurrent;
use GromIT\Tenancy\Events\BeforeMakeTenantCurrent;
use GromIT\Tenancy\Models\Tenant;

class MakeTenantCurrent
{
    /**
     * @var \GromIT\Tenancy\Classes\TenancyManager
     */
    protected $tenancyManager;

    /**
     * @var string[]
     */
    protected $switchTenantTasks;

    public function __construct()
    {
        $this->tenancyManager = TenancyManager::instance();

        $this->switchTenantTasks = config('gromit.tenancy::tenancy.switch_tenant_tasks');
    }

    public static function make(): MakeTenantCurrent
    {
        return new static();
    }

    public function execute(Tenant $tenant): void
    {
        if ($this->isTenantAlreadyCurrent($tenant)) {
            return;
        }

        ForgetCurrentTenant::make()->execute();

        Event::dispatch(new BeforeMakeTenantCurrent($tenant));

        $this->performMakeTenantCurrentTasks($tenant);

        $this->tenancyManager->setCurrent($tenant);

        Event::dispatch(new AfterMakeTenantCurrent($tenant));
    }

    protected function performMakeTenantCurrentTasks(Tenant $tenant): void
    {
        foreach ($this->switchTenantTasks as $taskClass) {
            /** @var \GromIT\Tenancy\Tasks\SwitchTenantTask $task */
            $task = new $taskClass;

            $task->makeCurrent($tenant);
        }
    }

    private function isTenantAlreadyCurrent(Tenant $tenant): bool
    {
        $currentTenant = $this->tenancyManager->getCurrent();

        return $currentTenant && $currentTenant->id === $tenant->id;
    }
}
