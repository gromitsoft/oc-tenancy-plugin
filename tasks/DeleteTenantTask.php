<?php

namespace GromIT\Tenancy\Tasks;

use GromIT\Tenancy\Models\Tenant;

interface DeleteTenantTask
{
    public function handle(Tenant $tenant): void;
}
