<?php

namespace GromIT\Tenancy\Classes;

use GromIT\Tenancy\Actions\CurrentTenant\RunAsTenant;
use GromIT\Tenancy\Concerns\UsesTenancyConfig;
use GromIT\Tenancy\Events\UpdatingPluginForTenant;
use GromIT\Tenancy\Models\Tenant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use October\Rain\Exception\SystemException;
use October\Rain\Support\Facades\Schema;
use October\Rain\Support\Traits\Singleton;
use ReflectionClass;
use ReflectionException;
use System\Classes\PluginManager;
use System\Classes\UpdateManager;
use System\Classes\VersionManager;

class PluginsUpdater
{
    use Singleton, UsesTenancyConfig;

    /**
     * @var \System\Classes\PluginManager
     */
    protected $pluginManager;

    /**
     * @var \System\Classes\UpdateManager
     */
    protected $updateManager;

    /**
     * @var array
     */
    protected $originalDatabaseConnectionConfig;

    /**
     * @var \GromIT\Tenancy\Classes\TenancyManager
     */
    protected $tenancyManager;

    protected function init(): void
    {
        $this->pluginManager  = PluginManager::instance();
        $this->updateManager  = UpdateManager::instance();
        $this->tenancyManager = TenancyManager::instance();
    }

    /**
     * @param \Illuminate\Support\Collection|\GromIT\Tenancy\Models\Tenant[]|Tenant         $tenants
     * @param \Illuminate\Support\Collection|array<string, \System\Classes\PluginBase>|null $plugins
     * @param bool                                                                          $refresh
     *
     * @throws \October\Rain\Exception\SystemException
     */
    public function updatePluginsForTenants(
        $tenants,
        ?Collection $plugins = null,
        bool $refresh = false
    ): void
    {
        if ($tenants instanceof Tenant) {
            $tenants = collect([$tenants]);
        } elseif (is_array($tenants)) {
            $tenants = collect($tenants);
        }

        $this->saveOriginalDefaultDatabaseConnectionConfig();

        if ($plugins === null) {
            $plugins = collect($this->pluginManager->getAllPlugins());
        }

        foreach ($tenants as $tenant) {
            RunAsTenant::make()->execute($tenant, function () use ($refresh, $plugins, $tenant) {
                $this->replaceDefaultDatabaseConnectionConfig(
                    $this->getTenantDbConnectionConfig($tenant)
                );

                foreach ($plugins as $code => $plugin) {
                    if (Helpers::isPluginTenantAware($code)) {
                        Event::dispatch(new UpdatingPluginForTenant($tenant, $plugin));

                        $this->clearVersionManagersDatabaseVersions();

                        if ($refresh) {
                            $this->updateManager->rollbackPlugin($code);
                        }

                        $this->updateManager->updatePlugin($code);
                    }
                }

                $this->replaceDefaultDatabaseConnectionConfig(
                    $this->originalDatabaseConnectionConfig
                );
            });
        }

        $this->clearVersionManagersDatabaseVersions();
    }

    protected function saveOriginalDefaultDatabaseConnectionConfig(): void
    {
        $this->originalDatabaseConnectionConfig =
            config("database.connections.{$this->getDefaultConnectionName()}");
    }

    protected function replaceDefaultDatabaseConnectionConfig(array $tenantDatabaseConnectionConfig): void
    {
        $defaultConnection = $this->getDefaultConnectionName();

        DB::purge($defaultConnection);

        config([
            "database.connections.{$defaultConnection}" => $tenantDatabaseConnectionConfig,
        ]);

        DB::reconnect($defaultConnection);

        Schema::connection($defaultConnection)->getConnection()->reconnect();
    }

    /**
     * @param \GromIT\Tenancy\Models\Tenant $tenant
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected function getTenantDbConnectionConfig(Tenant $tenant)
    {
        $dbConnectionConfig             = config("database.connections.{$this->getTenantConnectionName()}");
        $dbConnectionConfig['database'] = $tenant->database_name;

        return $dbConnectionConfig;
    }

    /**
     * @throws \October\Rain\Exception\SystemException
     * @noinspection PhpRedundantOptionalArgumentInspection
     */
    protected function clearVersionManagersDatabaseVersions(): void
    {
        // Plugins versions in different databases may not be equal.
        // So we need to force VersionManager to reload versions of installed plugins.
        // To do that we need to clear protected properties $databaseVersions and $databaseHistory.

        $versionManager = VersionManager::instance();

        try {
            $reflection               = new ReflectionClass($versionManager);
            $databaseVersionsProperty = $reflection->getProperty('databaseVersions');
            $databaseVersionsProperty->setAccessible(true);
            $databaseVersionsProperty->setValue($versionManager, null);

            $databaseHistoryProperty = $reflection->getProperty('databaseHistory');
            $databaseHistoryProperty->setAccessible(true);
            $databaseHistoryProperty->setValue($versionManager, null);
        } catch (ReflectionException $e) {
            throw new SystemException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
