<?php

namespace GromIT\Tenancy\Middleware;

use Backend\Facades\BackendAuth;
use Closure;
use Illuminate\Http\Request;
use GromIT\Tenancy\Actions\CurrentTenant\SetCurrentTenantByOverride;
use GromIT\Tenancy\Actions\CurrentTenant\SetCurrentTenantByRequest;
use GromIT\Tenancy\Classes\Permissions;

class CurrentTenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = BackendAuth::getUser();

        $overridden = false;

        if ($user && $user->hasAccess(Permissions::OVERRIDE_CURRENT_TENANT)) {
            $overridden = SetCurrentTenantByOverride::make()->execute();
        }

        if (!$overridden) {
            SetCurrentTenantByRequest::make()->execute($request);
        }

        return $next($request);
    }
}
