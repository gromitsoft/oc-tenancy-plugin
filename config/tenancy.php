<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */

return [

    /*
     * Tenant finder for request.
     * Must implement \GromIT\Tenancy\TenantFinder\TenantFinder.
     */
    'tenant_finder'                => \GromIT\Tenancy\TenantFinders\DomainTenantFinder::class,

    /*
     * Application container key for storing current tenant.
     */
    'current_tenant_container_key' => '_current_tenant',

    /*
     * Tasks performing after creating tenant.
     * All tasks must implement \GromIT\Tenancy\Tasks\CreateTenantTask interface.
     */
    'create_tenant_tasks'          => [
        \GromIT\Tenancy\Tasks\CreateTenant\CreateDatabase::class,
        \GromIT\Tenancy\Tasks\CreateTenant\Migrate::class,
        \GromIT\Tenancy\Tasks\CreateTenant\CreateStorageRoot::class,
    ],

    /*
     * Tasks performing after deleting tenant.
     * All tasks must implement \GromIT\Tenancy\Tasks\DeleteTenantTask interface.
     */
    'delete_tenant_tasks'          => [
        \GromIT\Tenancy\Tasks\DeleteTenant\DeleteDatabase::class,
        \GromIT\Tenancy\Tasks\DeleteTenant\DeleteStorageRoot::class,
    ],

    /*
     * Tasks performing on switching tenants.
     * All tasks must implement \GromIT\Tenancy\Tasks\SwitchTenantTask interface.
     */
    'switch_tenant_tasks'          => [
        \GromIT\Tenancy\Tasks\SwitchTenant\ChangeTenantDatabaseConnection::class,
        \GromIT\Tenancy\Tasks\SwitchTenant\ChangeCachePrefix::class,
        \GromIT\Tenancy\Tasks\SwitchTenant\ChangeStorageDisk::class,
        \GromIT\Tenancy\Tasks\SwitchTenant\ChangeResourcesStorage::class,
        \GromIT\Tenancy\Tasks\SwitchTenant\ChangeTenantLoggingChannel::class,
    ],
];
