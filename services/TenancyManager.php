<?php

namespace SergeyKasyanov\Tenancy\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use October\Rain\Support\Traits\Singleton;
use SergeyKasyanov\Tenancy\Models\Tenant;

class TenancyManager
{
    use Singleton;

    /**
     * @var string
     */
    private $containerKey;

    protected function init(): void
    {
        $this->containerKey = config('sergeykasyanov.tenancy::current_tenant_container_key');
    }

    public function getCurrent(): ?Tenant
    {
        try {
            return app()->make($this->containerKey);
        } catch (BindingResolutionException $exception) {
            return null;
        }
    }

    public function setCurrent(Tenant $tenant): void
    {
        app()->instance($this->containerKey, $tenant);
    }

    public function forgetCurrent(): void
    {
        if ($this->getCurrent() !== null) {
            app()->forgetInstance($this->containerKey);
        }
    }
}
