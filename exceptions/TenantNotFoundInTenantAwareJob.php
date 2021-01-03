<?php

namespace GromIT\Tenancy\Exceptions;

use October\Rain\Exception\SystemException;

class TenantNotFoundInTenantAwareJob extends SystemException
{
    public static function noIdSet(string $jobName): TenantNotFoundInTenantAwareJob
    {
        $msg = __('gromit.tenancy::lang.exceptions.tenant_not_found_in_tenant_aware_job.no_id_set', [
            'jobName' => $jobName
        ]);

        return new static($msg);
    }

    public static function noTenantFound(string $jobName): TenantNotFoundInTenantAwareJob
    {
        $msg = __('gromit.tenancy::lang.exceptions.tenant_not_found_in_tenant_aware_job.no_tenant_found', [
            'jobName' => $jobName
        ]);

        return new static($msg);
    }
}
