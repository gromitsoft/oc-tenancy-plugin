<?php

namespace GromIT\Tenancy\Tasks\SwitchTenant;

use Illuminate\Database\DatabaseManager;
use October\Rain\Support\Facades\Schema;
use GromIT\Tenancy\Concerns\UsesTenancyConfig;
use GromIT\Tenancy\Exceptions\ChangeTenantDatabaseException;
use GromIT\Tenancy\Models\Tenant;
use GromIT\Tenancy\Tasks\SwitchTenantTask;

class ChangeTenantDatabaseConnection implements SwitchTenantTask
{
    use UsesTenancyConfig;

    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    public function __construct()
    {
        $this->databaseManager = app(DatabaseManager::class);
    }

    /**
     * @param \GromIT\Tenancy\Models\Tenant $tenant
     *
     * @throws \GromIT\Tenancy\Exceptions\ChangeTenantDatabaseException
     */
    public function makeCurrent(Tenant $tenant): void
    {
        $this->setTenantDatabaseConnection($tenant);
    }

    /**
     * @throws \GromIT\Tenancy\Exceptions\ChangeTenantDatabaseException
     */
    public function forgetCurrent(): void
    {
        $this->setTenantDatabaseConnection(null);
    }

    /**
     * @param \GromIT\Tenancy\Models\Tenant|null $tenant
     *
     * @throws \GromIT\Tenancy\Exceptions\ChangeTenantDatabaseException
     */
    protected function setTenantDatabaseConnection(?Tenant $tenant): void
    {
        $connectionName = $this->getTenantConnectionName();

        $connectionConfig = config("database.connections.$connectionName");

        if ($connectionConfig === null) {
            throw ChangeTenantDatabaseException::noTenantDatabaseConfig($connectionName);
        }

        $this->databaseManager->purge($connectionName);

        if ($tenant) {
            config([
                "database.connections.$connectionName.database" => $tenant->database_name,
            ]);

            $this->databaseManager->reconnect($connectionName);

            Schema::connection($connectionName)->getConnection()->reconnect();
        } else {
            config([
                "database.connections.$connectionName.database" => null,
            ]);

            Schema::connection($connectionName)->getConnection()->disconnect();
        }
    }
}
