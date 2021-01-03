<?php namespace SergeyKasyanov\Tenancy\Console;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use October\Rain\Support\Facades\Schema;
use SergeyKasyanov\Tenancy\Actions\MakeTenantCurrent;
use SergeyKasyanov\Tenancy\Models\Tenant;
use System\Classes\PluginManager;
use System\Classes\UpdateManager;

class PluginsUpdate extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'tenancy:plugins-update';

    /**
     * @var string The console command description.
     */
    protected $description = 'Updates plugins in tenants databases';

    /**
     * @var string
     */
    protected $originalDefaultConnectionDatabase;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $pluginManager = PluginManager::instance();
        $updateManager = UpdateManager::instance();

        $tenants = Tenant::query()->get();

        foreach ($tenants as $tenant) {
            MakeTenantCurrent::make()->execute($tenant);

            $this->setTenantDatabaseAsDefault($tenant->database_name);

            $plugins = $pluginManager->getPlugins();
            foreach ($plugins as $code => $plugin) {
                if (property_exists($plugin, 'tenanted') && $plugin->tenanted) {
                    $updateManager->updatePlugin($code);
                }
            }

            $this->returnDefaultDatabase();
        }
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
