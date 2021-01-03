<?php

namespace SergeyKasyanov\Tenancy\Tasks\SwitchTenant;

use SergeyKasyanov\Tenancy\Models\Tenant;
use SergeyKasyanov\Tenancy\Tasks\SwitchTenantTask;

class PrefixStorage implements SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void
    {
        // TODO: Implement makeCurrent() method.
    }

    public function forgetCurrent(): void
    {
        // TODO: Implement forgetCurrent() method.
    }
}
