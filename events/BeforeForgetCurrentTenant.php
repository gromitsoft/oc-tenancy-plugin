<?php

namespace SergeyKasyanov\Tenancy\Events;

use Illuminate\Queue\SerializesModels;
use SergeyKasyanov\Tenancy\Models\Tenant;

class BeforeForgetCurrentTenant
{
    use SerializesModels;

    /**
     * @var \SergeyKasyanov\Tenancy\Models\Tenant|null
     */
    private $tenant;

    public function __construct(?Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * @return \SergeyKasyanov\Tenancy\Models\Tenant|null
     */
    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }
}
