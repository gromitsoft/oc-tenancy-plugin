<?php

namespace GromIT\Tenancy\Classes;

class Permissions
{
    public const MANAGE_TENANTS = 'gromit.tenancy.manage_tenants';
    public const OVERRIDE_CURRENT_TENANT = 'gromit.tenancy.override_current_tenant';

    public static function lists(): array
    {
        return [
            self::MANAGE_TENANTS => [
                'tab'   => 'gromit.tenancy::lang.permissions.tabs.tenants',
                'label' => 'gromit.tenancy::lang.permissions.manage_tenants'
            ],
            self::OVERRIDE_CURRENT_TENANT => [
                'tab'   => 'gromit.tenancy::lang.permissions.tabs.tenants',
                'label' => 'gromit.tenancy::lang.permissions.override_current_tenant'
            ],
        ];
    }
}
