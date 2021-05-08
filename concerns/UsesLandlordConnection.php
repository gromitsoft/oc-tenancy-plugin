<?php

namespace GromIT\Tenancy\Concerns;

trait UsesLandlordConnection
{
    use UsesTenancyConfig;

    public function getConnectionName(): string
    {
        return $this->getDefaultConnectionName();
    }
}
