<?php

namespace GromIT\Tenancy\Concerns;

use GromIT\Tenancy\DatabaseCreators\DatabaseCreator;
use GromIT\Tenancy\TenantFinders\TenantFinder;

trait UsesTenancyConfig
{
    //
    // Tenancy
    //

    protected function getTenantContainerKey(): string
    {
        return config('gromit.tenancy::tenancy.current_tenant_container_key');
    }

    protected function getTenantFinder(): TenantFinder
    {
        $finderClass = config('gromit.tenancy::tenancy.tenant_finder');

        return new $finderClass;
    }

    //
    // Database
    //

    protected function getDefaultConnectionName(): string
    {
        return config('database.default');
    }

    protected function getTenantConnectionName(): string
    {
        return config('gromit.tenancy::database.tenant_connection_name');
    }

    protected function getDefaultDatabaseName(): string
    {
        return config("database.connections.{$this->getDefaultConnectionName()}.database");
    }

    protected function getDatabaseCreator(): DatabaseCreator
    {
        $creatorClass = config('gromit.tenancy::database.database_creator');

        return new $creatorClass;
    }

    //
    // Storage
    //

    protected function getDefaultDiskName(): string
    {
        return config('filesystems.default');
    }

    protected function getTenantDiskName(): string
    {
        return config('gromit.tenancy::storage.tenant_disk_name');
    }

    protected function usingIsolatedResourcesStorage()
    {
        return config('gromit.tenancy::storage.isolate_resources_for_tenants');
    }

    //
    // Logging
    //

    protected function getDefaultLoggingChannel(): string
    {
        return config('logging.default');
    }

    protected function getTenantLoggingChannel(): string
    {
        return config('gromit.tenancy::logging.tenant_logging_channel');
    }
}
