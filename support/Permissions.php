<?php

namespace SergeyKasyanov\Tenancy\Support;

class Permissions
{
    public const MANAGE_TENANTS = 'sergeykasyanov.tenancy.manage_tenants';

    public static function lists(): array
    {
        return [
            self::MANAGE_TENANTS => [
                'tab'   => 'sergeykasyanov.tenancy::lang.permissions.tabs.tenants',
                'label' => 'sergeykasyanov.tenancy::lang.permissions.manage_tenants'
            ]
        ];
    }
}
