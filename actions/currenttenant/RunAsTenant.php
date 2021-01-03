<?php

namespace GromIT\Tenancy\Actions\CurrentTenant;

use GromIT\Tenancy\Classes\TenancyManager;
use GromIT\Tenancy\Models\Tenant;

class RunAsTenant
{
    /**
     * @var \GromIT\Tenancy\Classes\TenancyManager
     */
    protected $tenancyManager;

    public function __construct()
    {
        $this->tenancyManager = TenancyManager::instance();
    }

    public static function make(): RunAsTenant
    {
        return new static();
    }

    /**
     * @param \GromIT\Tenancy\Models\Tenant $tenant
     * @param callable                              $callable
     *
     * @return mixed
     */
    public function execute(Tenant $tenant, callable $callable)
    {
        $originalTenant = $this->tenancyManager->getCurrent();

        MakeTenantCurrent::make()->execute($tenant);

        $result = $callable();

        if ($originalTenant) {
            MakeTenantCurrent::make()->execute($originalTenant);
        } else {
            ForgetCurrentTenant::make()->execute();
        }

        return $result;
    }
}
