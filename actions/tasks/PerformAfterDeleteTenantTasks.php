<?php

namespace GromIT\Tenancy\Actions\Tasks;

use GromIT\Tenancy\Models\Tenant;

class PerformAfterDeleteTenantTasks
{
    public static function make(): PerformAfterDeleteTenantTasks
    {
        return new static();
    }

    public function execute(Tenant $tenant): void
    {
        $tasks = config('gromit.tenancy::tenancy.delete_tenant_tasks');

        foreach ($tasks as $taskClass) {
            /** @var \GromIT\Tenancy\Tasks\DeleteTenantTask $task */
            $task = new $taskClass;
            $task->handle($tenant);
        }
    }
}
