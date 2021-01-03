<?php

namespace GromIT\Tenancy\Models;

use GromIT\Tenancy\Concerns\UsesTenancyConfig;

/**
 * File model for using with attachOne and attachMany relations in models of tenant aware plugins.
 */
class File extends \System\Models\File
{
    use UsesTenancyConfig;

    public function getConnectionName(): string
    {
        $tenantDatabaseConnectionDefinition = config("database.connections.{$this->getTenantConnectionName()}");

        if (empty($tenantDatabaseConnectionDefinition)) {
            return $this->getDefaultConnectionName();
        }

        return $this->getTenantConnectionName();
    }

    protected function getLocalRootPath()
    {
        if ($this->usingIsolatedResourcesStorage()) {
            $disk = config('cms.storage.uploads.disk');

            return config("filesystems.disks.$disk.root", parent::getLocalRootPath());
        }

        return parent::getLocalRootPath();
    }
}
