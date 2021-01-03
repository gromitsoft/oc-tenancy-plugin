<?php

namespace SergeyKasyanov\Tenancy\Tasks;

use SergeyKasyanov\Tenancy\Models\Tenant;

interface DeleteTenantTask
{
    public function handle(Tenant $tenant): void;
}
