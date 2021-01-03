<?php

namespace SergeyKasyanov\Tenancy\Actions;

use SergeyKasyanov\Tenancy\Models\Tenant;

class PerformAfterCreateTenantTasks
{
    public static function make(): PerformAfterCreateTenantTasks
    {
        return new static();
    }

    public function execute(Tenant $tenant): void
    {
        $tasks = config('sergeykasyanov.tenancy::create_tenant_tasks');

        foreach ($tasks as $taskClass) {
            /** @var \SergeyKasyanov\Tenancy\Tasks\CreateTenantTask $task */
            $task = new $taskClass;
            $task->handle($tenant);
        }
    }
}
