<?php

namespace SergeyKasyanov\Tenancy\Services;

use Illuminate\Database\DatabaseManager;
use October\Rain\Support\Facades\Schema;
use October\Rain\Support\Traits\Singleton;
use SergeyKasyanov\Tenancy\Models\Tenant;
use System\Classes\PluginManager;
use System\Classes\UpdateManager;

class PluginsUpdater
{
    use Singleton;

    /**
     * @var \System\Classes\PluginManager
     */
    private $pluginManager;

    /**
     * @var \System\Classes\UpdateManager
     */
    private $updateManager;

    /**
     * @var string
     */
    private $originalDefaultConnectionDatabase;

    protected function init(): void
    {
        $this->pluginManager = PluginManager::instance();
        $this->updateManager = UpdateManager::instance();
    }

    public function updatePluginsForTenant(Tenant $tenant): void
    {
        $this->setTenantDatabaseAsDefault($tenant->database_name);

        $plugins = $this->pluginManager->getPlugins();

        foreach ($plugins as $code => $plugin) {
            if (property_exists($plugin, 'tenanted') && $plugin->tenanted) {
                $this->updateManager->updatePlugin($code);
            }
        }

        $this->returnDefaultDatabase();
    }

    protected function setTenantDatabaseAsDefault(string $databaseName): void
    {
        $this->originalDefaultConnectionDatabase =
            config("database.connections.{$this->getDefaultConnectionName()}.database");

        $this->setDefaultConnectionDatabaseTo($databaseName);
    }

    protected function returnDefaultDatabase(): void
    {
        $this->setDefaultConnectionDatabaseTo($this->originalDefaultConnectionDatabase);
    }

    /**
     * @param string $databaseName
     */
    protected function setDefaultConnectionDatabaseTo(string $databaseName): void
    {
        config(["database.connections.{$this->getDefaultConnectionName()}.database" => $databaseName]);

        app(DatabaseManager::class)->reconnect();

        Schema::connection($this->getDefaultConnectionName())->getConnection()->reconnect();
    }

    protected function getDefaultConnectionName(): string
    {
        return config('database.default');
    }
}
