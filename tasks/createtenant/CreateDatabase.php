<?php

namespace SergeyKasyanov\Tenancy\Tasks\CreateTenant;

use SergeyKasyanov\Tenancy\Models\Tenant;
use SergeyKasyanov\Tenancy\Services\MysqlDatabaseManager;
use SergeyKasyanov\Tenancy\Tasks\CreateTenantTask;

class CreateDatabase implements CreateTenantTask
{
    public function handle(Tenant $tenant): void
    {
        MysqlDatabaseManager::make()->createDatabase($tenant->database_name);
    }
}
