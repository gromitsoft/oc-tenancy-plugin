<?php

namespace GromIT\Tenancy\Tasks;

use GromIT\Tenancy\Models\Tenant;

interface SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void;

    public function forgetCurrent(): void;
}
