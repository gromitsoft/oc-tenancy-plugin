<?php

namespace GromIT\Tenancy\Exceptions;

use October\Rain\Exception\SystemException;

class TenantAwarePluginUpdateException extends SystemException
{
    public static function pluginNotFound(string $pluginName): TenantAwarePluginUpdateException
    {
        $msg = __('gromit.tenancy::lang.exceptions.tenanted_plugin_updated_exception.plugin_not_found', [
            'pluginName' => $pluginName
        ]);

        return new static($msg);
    }

    public static function pluginIsNotTenantAware(string $pluginName): TenantAwarePluginUpdateException
    {
        $msg = __('gromit.tenancy::lang.exceptions.tenanted_plugin_updated_exception.plugin_is_not_tenanted', [
            'pluginName' => $pluginName
        ]);

        return new static($msg);
    }
}
