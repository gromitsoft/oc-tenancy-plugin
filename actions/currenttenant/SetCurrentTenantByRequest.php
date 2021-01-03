<?php

namespace GromIT\Tenancy\Actions\CurrentTenant;

use Illuminate\Http\Request;
use GromIT\Tenancy\Concerns\UsesTenancyConfig;

class SetCurrentTenantByRequest
{
    use UsesTenancyConfig;

    public static function make(): SetCurrentTenantByRequest
    {
        return new static();
    }

    public function execute(Request $request): void
    {
        $tenant = $this->getTenantFinder()->findForRequest($request);

        if ($tenant === null) {
            return;
        }

        MakeTenantCurrent::make()->execute($tenant);
    }
}
