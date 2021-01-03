<?php

namespace GromIT\Tenancy\Concerns;

trait UsesTenantConnection
{
    use UsesTenancyConfig;

    public function getConnectionName(): string
    {
        return $this->getTenantConnectionName();
    }
}
