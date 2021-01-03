<?php

namespace GromIT\Tenancy\TenantFinders;

use Illuminate\Http\Request;
use GromIT\Tenancy\Models\Tenant;

interface TenantFinder
{
    public function findForRequest(Request $request): ?Tenant;
}
