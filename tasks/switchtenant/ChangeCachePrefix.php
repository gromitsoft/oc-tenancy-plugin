<?php

namespace GromIT\Tenancy\Tasks\SwitchTenant;

use GromIT\Tenancy\Models\Tenant;
use GromIT\Tenancy\Tasks\SwitchTenantTask;

class ChangeCachePrefix implements SwitchTenantTask
{
    /**
     * @var string
     */
    protected $cacheStore;

    /**
     * @var string
     */
    protected $cachePrefixKey;

    /**
     * @var string
     */
    protected $originalPrefix;

    public function __construct()
    {
        $this->cacheStore = config('cache.default');

        $this->cachePrefixKey = 'cache.prefix';

        $this->loadOriginalPrefix();
    }

    public function makeCurrent(Tenant $tenant): void
    {
        $this->saveOriginalPrefix();

        config([
            $this->cachePrefixKey => "{$this->originalPrefix}_tenant_{$tenant->id}"
        ]);

        app('cache')->forgetDriver($this->cacheStore);
    }

    public function forgetCurrent(): void
    {
        config([
            $this->cachePrefixKey => $this->originalPrefix
        ]);

        app('cache')->forgetDriver($this->cacheStore);
    }

    protected function loadOriginalPrefix(): void
    {
        $this->originalPrefix = config("__defaults.{$this->cachePrefixKey}") ?? config($this->cachePrefixKey);
    }

    protected function saveOriginalPrefix(): void
    {
        $saved = config("__defaults.{$this->cachePrefixKey}");

        if ($saved === null) {
            config([
                "__defaults.{$this->cachePrefixKey}" => $this->originalPrefix,
            ]);
        }
    }
}
