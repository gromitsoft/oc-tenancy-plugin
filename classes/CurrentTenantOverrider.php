<?php

namespace GromIT\Tenancy\Classes;

use GromIT\Tenancy\Models\Tenant;

class CurrentTenantOverrider
{
    public const CURRENT_TENANT_OVERRIDE_KEY = 'current_tenant_override_id';

    public static function make(): CurrentTenantOverrider
    {
        return new static;
    }

    public function setOverride(Tenant $tenant): void
    {
        session([self::CURRENT_TENANT_OVERRIDE_KEY => $tenant->id]);
    }

    public function getOverride(): ?int
    {
        return session(self::CURRENT_TENANT_OVERRIDE_KEY);
    }

    public function forgetOverride(): void
    {
        session()->forget(self::CURRENT_TENANT_OVERRIDE_KEY);
    }
}
