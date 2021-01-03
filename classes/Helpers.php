<?php

namespace GromIT\Tenancy\Classes;

class Helpers
{
    public static function isPluginTenantAware(string $pluginCode): bool
    {
        $tenantAwarePlugins = config('gromit.tenancy::database.tenant_aware_plugins');

        return in_array($pluginCode, $tenantAwarePlugins, true);
    }
}
