<?php

namespace GromIT\Tenancy\Events;

use Illuminate\Queue\SerializesModels;
use GromIT\Tenancy\Models\Tenant;

class AfterForgetCurrentTenant
{
    use SerializesModels;

    /**
     * @var \GromIT\Tenancy\Models\Tenant|null
     */
    private $tenant;

    public function __construct(?Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * @return \GromIT\Tenancy\Models\Tenant|null
     */
    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }
}
