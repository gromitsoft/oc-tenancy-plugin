<?php

namespace GromIT\Tenancy\Tasks\CreateTenant;

use GromIT\Tenancy\Concerns\UsesTenancyConfig;
use GromIT\Tenancy\Models\Tenant;
use GromIT\Tenancy\Tasks\CreateTenantTask;

class CreateDatabase implements CreateTenantTask
{
    use UsesTenancyConfig;

    /**
     * @param \GromIT\Tenancy\Models\Tenant $tenant
     */
    public function handle(Tenant $tenant): void
    {
        $dbname = $this->getDatabaseName($tenant);

        $this->getDatabaseCreator()->createDatabase($dbname);

        $this->saveDatabaseName($dbname, $tenant);
    }

    /**
     * @param \GromIT\Tenancy\Models\Tenant $tenant
     *
     * @return string
     */
    protected function getDatabaseName(Tenant $tenant): string
    {
        return $this->getDefaultDatabaseName() . '_tenant_' . $tenant->id;
    }

    /**
     * @param string                                $dbname
     * @param \GromIT\Tenancy\Models\Tenant $tenant
     */
    protected function saveDatabaseName(string $dbname, Tenant $tenant): void
    {
        $tenant->database_name = $dbname;
        $tenant->save();
    }
}
