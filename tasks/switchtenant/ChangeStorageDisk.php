<?php

namespace GromIT\Tenancy\Tasks\SwitchTenant;

use GromIT\Tenancy\Concerns\UsesTenancyConfig;
use GromIT\Tenancy\Models\Tenant;
use GromIT\Tenancy\Tasks\SwitchTenantTask;

class ChangeStorageDisk implements SwitchTenantTask
{
    use UsesTenancyConfig;

    protected $originalConfig = [
        'root' => null,
        'url'  => null,
    ];

    /**
     * @var string
     */
    protected $diskName;

    /**
     * @var string
     */
    protected $diskRootKey;

    /**
     * @var string
     */
    protected $diskUrlKey;

    public function __construct()
    {
        $this->diskName = $this->getTenantDiskName();

        $this->diskRootKey = "filesystems.disks.{$this->diskName}.root";
        $this->diskUrlKey  = "filesystems.disks.{$this->diskName}.url";

        $this->loadOriginalConfigValues();
    }

    public function makeCurrent(Tenant $tenant): void
    {
        $this->saveOriginalConfigValues();

        $root = str_replace('{tenant_id}', $tenant->id, $this->originalConfig['root']);
        $url  = str_replace('{tenant_id}', $tenant->id, $this->originalConfig['url']);

        config([
            $this->diskRootKey => $root,
            $this->diskUrlKey  => $url,
        ]);

        app('filesystem')->forgetDisk($this->diskName);
    }

    public function forgetCurrent(): void
    {
        config([
            $this->diskRootKey => $this->originalConfig['root'],
            $this->diskUrlKey  => $this->originalConfig['url'],
        ]);

        app('filesystem')->forgetDisk($this->diskName);
    }

    protected function loadOriginalConfigValues(): void
    {
        $this->originalConfig['root'] = config("__defaults.{$this->diskRootKey}") ?? config($this->diskRootKey);
        $this->originalConfig['url']  = config("__defaults.{$this->diskUrlKey}") ?? config($this->diskUrlKey);
    }

    protected function saveOriginalConfigValues(): void
    {
        $keys = [
            $this->diskRootKey => $this->originalConfig['root'],
            $this->diskUrlKey  => $this->originalConfig['url'],
        ];

        foreach ($keys as $key => $value) {
            $saved = config("__defaults.{$key}");

            if ($saved === null) {
                config([
                    "__defaults.{$key}" => $value,
                ]);
            }
        }
    }
}
