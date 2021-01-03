<?php

namespace GromIT\Tenancy\Tasks;

use GromIT\Tenancy\Models\Tenant;

interface CreateTenantTask
{
    public function handle(Tenant $tenant): void;
}
