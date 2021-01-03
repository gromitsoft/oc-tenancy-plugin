<?php

namespace SergeyKasyanov\Tenancy\Events;

use Illuminate\Queue\SerializesModels;
use SergeyKasyanov\Tenancy\Models\Tenant;

class BeforeMakeTenantCurrent
{
    use SerializesModels;

    /**
     * @var \SergeyKasyanov\Tenancy\Models\Tenant
     */
    private $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * @return \SergeyKasyanov\Tenancy\Models\Tenant
     */
    public function getTenant(): Tenant
    {
        return $this->tenant;
    }
}
