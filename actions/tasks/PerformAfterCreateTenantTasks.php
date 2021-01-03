<?php

namespace GromIT\Tenancy\Actions\Tasks;

use GromIT\Tenancy\Models\Tenant;

class PerformAfterCreateTenantTasks
{
    public static function make(): PerformAfterCreateTenantTasks
    {
        return new static();
    }

    public function execute(Tenant $tenant): void
    {
        $tasks = config('gromit.tenancy::tenancy.create_tenant_tasks');

        foreach ($tasks as $taskClass) {
            /** @var \GromIT\Tenancy\Tasks\CreateTenantTask $task */
            $task = new $taskClass;
            $task->handle($tenant);
        }
    }
}
