<?php

namespace GromIT\Tenancy\Tasks\SwitchTenant;

use GromIT\Tenancy\Concerns\UsesTenancyConfig;
use GromIT\Tenancy\Models\Tenant;
use GromIT\Tenancy\Tasks\SwitchTenantTask;

class ChangeTenantLoggingChannel implements SwitchTenantTask
{
    use UsesTenancyConfig;

    protected $originalConfig = [];

    public function __construct()
    {
        $this->loadOriginalConfigValues();
    }

    protected function loadOriginalConfigValues(): void
    {
        $this->originalConfig = config("__defaults.logging.channels.{$this->getTenantLoggingChannel()}")
            ?? config("logging.channels.{$this->getTenantLoggingChannel()}");
    }

    public function makeCurrent(Tenant $tenant): void
    {
        $this->saveOriginalConfigValues();

        $tenantLoggingChannelConfig         = config("logging.channels.{$this->getTenantLoggingChannel()}");
        $tenantLoggingChannelConfig['path'] =
            str_replace('{tenant_id}', $tenant->id, $tenantLoggingChannelConfig['path']);

        config([
            "logging.channels.{$this->getTenantLoggingChannel()}" => $tenantLoggingChannelConfig
        ]);
    }

    protected function saveOriginalConfigValues(): void
    {
        config([
            "__defaults.logging.channels.{$this->getTenantLoggingChannel()}" =>
                config("logging.channels.{$this->getTenantLoggingChannel()}"),
        ]);
    }

    public function forgetCurrent(): void
    {
        config([
            "logging.channels.{$this->getTenantLoggingChannel()}" =>
                config("__defaults.logging.channels.{$this->getTenantLoggingChannel()}")
        ]);
    }
}
