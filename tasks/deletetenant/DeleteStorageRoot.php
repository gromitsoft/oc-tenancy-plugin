<?php

namespace SergeyKasyanov\Tenancy\Tasks\DeleteTenant;

use SergeyKasyanov\Tenancy\Models\Tenant;
use SergeyKasyanov\Tenancy\Tasks\DeleteTenantTask;

class DeleteStorageRoot implements DeleteTenantTask
{
    public function handle(Tenant $tenant): void
    {
        // TODO: Implement handle() method.
    }
}
