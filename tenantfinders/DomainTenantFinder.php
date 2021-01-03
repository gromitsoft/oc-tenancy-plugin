<?php

namespace GromIT\Tenancy\TenantFinders;

use Illuminate\Http\Request;
use GromIT\Tenancy\Models\Tenant;

class DomainTenantFinder implements TenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        /** @var Tenant|null $tenant */
        $tenant = Tenant::query()
            ->onlyActive()
            ->whereDomainUrl($request->getHost())
            ->first();

        return $tenant;
    }
}
