<?php

namespace SergeyKasyanov\Tenancy\Actions;

use SergeyKasyanov\Tenancy\Models\Tenant;

class SetCurrentTenantByDomain
{
    public static function make(): SetCurrentTenantByDomain
    {
        return new static();
    }

    public function execute(string $domain): void
    {
        /** @var Tenant|null $tenant */
        $tenant = Tenant::query()
            ->onlyActive()
            ->whereDomainUrl($domain)
            ->first();

        if ($tenant === null) {
            return;
        }

        MakeTenantCurrent::make()->execute($tenant);
    }
}
