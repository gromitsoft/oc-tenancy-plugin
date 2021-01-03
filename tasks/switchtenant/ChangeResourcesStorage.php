<?php

namespace GromIT\Tenancy\Tasks\SwitchTenant;

use October\Rain\Support\Arr;
use GromIT\Tenancy\Concerns\UsesTenancyConfig;
use GromIT\Tenancy\Models\Tenant;
use GromIT\Tenancy\Tasks\SwitchTenantTask;

class ChangeResourcesStorage implements SwitchTenantTask
{
    use UsesTenancyConfig;

    protected $originalConfig = [];

    public function __construct()
    {
        $this->loadOriginalConfigValues();
    }

    protected function loadOriginalConfigValues(): void
    {
        $this->originalConfig = config('__defaults.cms.storage') ?? config('cms.storage');
    }

    public function makeCurrent(Tenant $tenant): void
    {
        if (!$this->usingIsolatedResourcesStorage()) {
            return;
        }

        $this->saveOriginalConfigValues();

        $tenantStorageConfig = config('gromit.tenancy::storage.resources_storage');

        $pathKeys = [
            'uploads.path',
            'media.path',
            'resized.path',
        ];

        foreach ($pathKeys as $key) {
            $path       = Arr::get($tenantStorageConfig, $key);
            $tenantPath = str_replace('{tenant_id}', $tenant->id, $path);

            Arr::set($tenantStorageConfig, $key, $tenantPath);
        }

        config([
            'cms.storage' => $tenantStorageConfig,
        ]);
    }

    protected function saveOriginalConfigValues(): void
    {
        config([
            '__defaults.cms.storage' => config('cms.storage'),
        ]);
    }

    public function forgetCurrent(): void
    {
        if (!$this->usingIsolatedResourcesStorage()) {
            return;
        }

        config([
            'cms.storage' => config('__defaults.cms.storage'),
        ]);
    }
}
