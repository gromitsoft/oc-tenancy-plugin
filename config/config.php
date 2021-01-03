<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */

return [
    /*
     * Name of tenant database connection.
     */
    'tenant_connection_name'       => 'tenant',

    /*
     * Tasks performing on switching tenants.
     * All tasks must implement \SergeyKasyanov\Tenancy\Tasks\SwitchTenantTask interface.
     */
    'switch_tenant_tasks'          => [
        \SergeyKasyanov\Tenancy\Tasks\SwitchTenant\ChangeTenantDatabaseConnection::class,
//        \SergeyKasyanov\Tenancy\Tasks\SwitchTenant\PrefixCache::class,
//        \SergeyKasyanov\Tenancy\Tasks\SwitchTenant\PrefixStorage::class,
    ],

    /*
     * Tasks performing after creating tenant.
     * All tasks must implement \SergeyKasyanov\Tenancy\Tasks\CreateTenantTask interface.
     */
    'create_tenant_tasks'          => [
        \SergeyKasyanov\Tenancy\Tasks\CreateTenant\CreateDatabase::class,
        \SergeyKasyanov\Tenancy\Tasks\CreateTenant\Migrate::class,
//        \SergeyKasyanov\Tenancy\Tasks\CreateTenant\CreateStorageRoot::class,
    ],

    /*
     * Tasks performing after deleting tenant.
     * All tasks must implement \SergeyKasyanov\Tenancy\Tasks\DeleteTenantTask interface.
     */
    'delete_tenant_tasks'          => [
        \SergeyKasyanov\Tenancy\Tasks\DeleteTenant\DeleteDatabase::class,
//        \SergeyKasyanov\Tenancy\Tasks\DeleteTenant\DeleteStorageRoot::class,
    ],

    /*
     * Application container key for storing current tenant.
     */
    'current_tenant_container_key' => '_current_tenant',

    /*
     * Migrations for applying on new tenants databases.
     */
    'tenant_db_default_migrations' => [
        base_path('modules/system/database/migrations/2013_10_01_000001_Db_Deferred_Bindings.php'),
        base_path('modules/system/database/migrations/2013_10_01_000002_Db_System_Files.php'),
        base_path('modules/system/database/migrations/2013_10_01_000003_Db_System_Plugin_Versions.php'),
        base_path('modules/system/database/migrations/2013_10_01_000004_Db_System_Plugin_History.php'),
        base_path('modules/system/database/migrations/2013_10_01_000005_Db_System_Settings.php'),
        base_path('modules/system/database/migrations/2013_10_01_000007_Db_System_Add_Disabled_Flag.php'),
        base_path('modules/system/database/migrations/2014_10_01_000010_Db_Jobs.php'),
        base_path('modules/system/database/migrations/2014_10_01_000011_Db_System_Event_Logs.php'),
        base_path('modules/system/database/migrations/2015_10_01_000015_Db_System_Add_Frozen_Flag.php'),
        base_path('modules/system/database/migrations/2015_10_01_000017_Db_System_Revisions.php'),
        base_path('modules/system/database/migrations/2015_10_01_000018_Db_FailedJobs.php'),
        base_path('modules/system/database/migrations/2016_10_01_000019_Db_System_Plugin_History_Detail_Text.php'),
        base_path('modules/system/database/migrations/2017_08_04_121309_Db_Deferred_Bindings_Add_Index_Session.php'),
        plugins_path('sergeykasyanov/tenancy/migrations/2016_10_01_000020_Db_System_Timestamp_Fix.php')
    ],
];
