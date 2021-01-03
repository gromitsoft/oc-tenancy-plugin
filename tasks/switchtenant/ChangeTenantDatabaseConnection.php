<?php

namespace SergeyKasyanov\Tenancy\Tasks\SwitchTenant;

use Illuminate\Database\DatabaseManager;
use October\Rain\Exception\SystemException;
use October\Rain\Support\Facades\Schema;
use SergeyKasyanov\Tenancy\Models\Tenant;
use SergeyKasyanov\Tenancy\Tasks\SwitchTenantTask;

class ChangeTenantDatabaseConnection implements SwitchTenantTask
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    public function __construct()
    {
        $this->databaseManager = app(DatabaseManager::class);
    }

    /**
     * @param \SergeyKasyanov\Tenancy\Models\Tenant $tenant
     *
     * @throws \October\Rain\Exception\SystemException
     */
    public function makeCurrent(Tenant $tenant): void
    {
        $this->setTenantDatabaseConnection($tenant);
    }

    /**
     * @throws \October\Rain\Exception\SystemException
     */
    public function forgetCurrent(): void
    {
        $this->setTenantDatabaseConnection(null);
    }

    /**
     * @param \SergeyKasyanov\Tenancy\Models\Tenant|null $tenant
     *
     * @throws \October\Rain\Exception\SystemException
     */
    private function setTenantDatabaseConnection(?Tenant $tenant): void
    {
        $connectionName = config('sergeykasyanov.tenancy::tenant_connection_name');

        $connectionConfig = config("database.connections.$connectionName");

        if ($connectionConfig === null) {
            $msg = __('sergeykasyanov.tenancy::lang.exceptions.tenant_connection_is_not_set', [
                'connectionName' => $connectionName
            ]);

            throw new SystemException($msg);
        }

        $this->databaseManager->purge($connectionName);

        if ($tenant === null) {
            return;
        }

        config(["database.connections.$connectionName.database" => $tenant->database_name]);

        $this->databaseManager->reconnect($connectionName);

        Schema::connection($connectionName)->getConnection()->reconnect();
    }
}
