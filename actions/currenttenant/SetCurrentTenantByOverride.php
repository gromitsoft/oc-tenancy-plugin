<?php

namespace GromIT\Tenancy\Actions\CurrentTenant;

use GromIT\Tenancy\Classes\CurrentTenantOverrider;
use GromIT\Tenancy\Classes\TenancyManager;
use GromIT\Tenancy\Models\Tenant;

class SetCurrentTenantByOverride
{
    /**
     * @var \GromIT\Tenancy\Classes\TenancyManager
     */
    protected $tenancyManager;

    public function __construct()
    {
        $this->tenancyManager = TenancyManager::instance();
    }

    public static function make(): SetCurrentTenantByOverride
    {
        return new static();
    }

    public function execute(): bool
    {
        $overrideId = CurrentTenantOverrider::make()->getOverride();

        if ($overrideId === null) {
            return false;
        }

        /** @var Tenant $tenant */
        $tenant = Tenant::query()->onlyActive()->find($overrideId);

        if ($tenant === null) {
            return false;
        }

        MakeTenantCurrent::make()->execute($tenant);

        return true;
    }
}
