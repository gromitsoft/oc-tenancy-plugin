<?php

namespace GromIT\Tenancy\Exceptions;

use Exception;
use October\Rain\Exception\SystemException;

class CurrentTenantIsNotSet extends SystemException
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        $message = $message ?? __('gromit.tenancy::lang.exceptions.current_tenant_is_not_set');

        parent::__construct($message, $code, $previous);
    }
}
