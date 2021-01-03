<?php

namespace SergeyKasyanov\Tenancy\Tasks;

use SergeyKasyanov\Tenancy\Models\Tenant;

interface CreateTenantTask
{
    public function handle(Tenant $tenant): void;
}
