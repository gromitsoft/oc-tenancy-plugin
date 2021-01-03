<?php

namespace GromIT\Tenancy\Exceptions;

use October\Rain\Exception\SystemException;

class ChangeTenantDatabaseException extends SystemException
{
    public static function noTenantDatabaseConfig(string $connectionName): ChangeTenantDatabaseException
    {
        return new static(__('gromit.tenancy::lang.exceptions.tenant_connection_is_not_set', [
            'connectionName' => $connectionName
        ]));
    }
}
