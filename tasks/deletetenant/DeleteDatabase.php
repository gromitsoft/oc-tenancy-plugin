<?php

namespace SergeyKasyanov\Tenancy\Tasks\DeleteTenant;

use SergeyKasyanov\Tenancy\Models\Tenant;
use SergeyKasyanov\Tenancy\Services\MysqlDatabaseManager;
use SergeyKasyanov\Tenancy\Tasks\DeleteTenantTask;

class DeleteDatabase implements DeleteTenantTask
{
    public function handle(Tenant $tenant): void
    {
        MysqlDatabaseManager::make()->dropDatabase($tenant->database_name);
    }
}
