<?php

namespace GromIT\Tenancy\Classes;

use Illuminate\Contracts\Container\BindingResolutionException;
use October\Rain\Support\Traits\Singleton;
use GromIT\Tenancy\Concerns\UsesTenancyConfig;
use GromIT\Tenancy\Models\Tenant;

class TenancyManager
{
    use Singleton, UsesTenancyConfig;

    public function getCurrent(): ?Tenant
    {
        try {
            return app()->make($this->getTenantContainerKey());
        } catch (BindingResolutionException $exception) {
            return null;
        }
    }

    public function hasCurrent(): bool
    {
        return $this->getCurrent() !== null;
    }

    public function setCurrent(Tenant $tenant): void
    {
        app()->instance($this->getTenantContainerKey(), $tenant);
    }

    public function forgetCurrent(): void
    {
        if ($this->getCurrent() !== null) {
            app()->forgetInstance($this->getTenantContainerKey());
        }
    }
}
