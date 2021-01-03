<?php

namespace SergeyKasyanov\Tenancy\Tasks;

use SergeyKasyanov\Tenancy\Models\Tenant;

interface SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void;

    public function forgetCurrent(): void;
}
