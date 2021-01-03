<?php

namespace GromIT\Tenancy\Actions\CurrentTenant;

use Illuminate\Support\Facades\Event;
use GromIT\Tenancy\Classes\TenancyManager;
use GromIT\Tenancy\Events\AfterForgetCurrentTenant;
use GromIT\Tenancy\Events\BeforeForgetCurrentTenant;

class ForgetCurrentTenant
{
    /**
     * @var \GromIT\Tenancy\Classes\TenancyManager
     */
    protected $tenancyManager;

    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $switchTenantTasks;

    public function __construct()
    {
        $this->tenancyManager = TenancyManager::instance();

        $this->switchTenantTasks = config('gromit.tenancy::tenancy.switch_tenant_tasks');
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

    protected function performForgetCurrentTenantTasks(): void
    {
        foreach ($this->switchTenantTasks as $taskClass) {
            /** @var \GromIT\Tenancy\Tasks\SwitchTenantTask $task */
            $task = new $taskClass;

            $task->forgetCurrent();
        }
    }
}
