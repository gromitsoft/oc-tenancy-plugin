<?php

namespace GromIT\Tenancy\Tasks\DeleteTenant;

use GromIT\Tenancy\Concerns\UsesTenancyConfig;
use GromIT\Tenancy\Models\Tenant;
use GromIT\Tenancy\Tasks\DeleteTenantTask;

class DeleteDatabase implements DeleteTenantTask
{
    use UsesTenancyConfig;

    public function handle(Tenant $tenant): void
    {
        $this->getDatabaseCreator()->dropDatabase($tenant->database_name);
    }
}
