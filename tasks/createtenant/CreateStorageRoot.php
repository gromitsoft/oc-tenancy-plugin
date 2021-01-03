<?php

namespace SergeyKasyanov\Tenancy\Tasks\CreateTenant;

use SergeyKasyanov\Tenancy\Models\Tenant;
use SergeyKasyanov\Tenancy\Tasks\CreateTenantTask;

class CreateStorageRoot implements CreateTenantTask
{
    public function handle(Tenant $tenant): void
    {
        // TODO: Implement handle() method.
    }
}
