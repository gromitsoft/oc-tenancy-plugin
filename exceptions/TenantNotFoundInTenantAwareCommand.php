<?php

namespace GromIT\Tenancy\Exceptions;

use October\Rain\Exception\SystemException;

class TenantNotFoundInTenantAwareCommand extends SystemException
{
    public static function forCommand(string $commandName): TenantNotFoundInTenantAwareCommand
    {
        $msg = __('gromit.tenancy::lang.exceptions.tenant_not_found_in_tenant_aware_command', [
            'command' => $commandName
        ]);

        return new static($msg);
    }
}
