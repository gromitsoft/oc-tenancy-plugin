<?php

namespace SergeyKasyanov\Tenancy\Actions;

use SergeyKasyanov\Tenancy\Models\Tenant;

class PerformAfterDeleteTenantTasks
{
    public static function make(): PerformAfterDeleteTenantTasks
    {
        return new static();
    }

    public function execute(Tenant $tenant): void
    {
        $tasks = config('sergeykasyanov.tenancy::delete_tenant_tasks');

        foreach ($tasks as $taskClass) {
            /** @var \SergeyKasyanov\Tenancy\Tasks\DeleteTenantTask $task */
            $task = new $taskClass;
            $task->handle($tenant);
        }
    }
}
