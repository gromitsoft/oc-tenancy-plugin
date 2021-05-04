<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */

return [
    /*
     * Name of tenant database connection.
     */
    'tenant_connection_name'       => 'tenant',

    /*
     * List of plugins for migrate to tenants databases.
     */
    'tenant_aware_plugins'         => [
        // 'RainLab.User',
        // 'RainLab.Blog',
    ],

    /*
     * Class using for creating and deleting databases for tenants.
     *
     * Must implement \GromIT\Tenancy\DatabaseCreators\DatabaseCreator interface.
     */
    'database_creator'             => \GromIT\Tenancy\DatabaseCreators\MysqlDatabaseCreator::class,

    /*
     * Username and password for creating tenant's databases.
     *
     * If null, username and password of tenant connection will be used.
     */
    'create_database_auth'         => [
        'username' => null,
        'password' => null,
    ],

    /*
     * Migrations for applying on new tenants databases.
     * Used by \GromIT\Tenancy\Tasks\CreateTenant\Migrate
     */
    'tenant_db_default_migrations' => [
        base_path('modules/system/database/migrations/2013_10_01_000001_Db_Deferred_Bindings.php'),
        base_path('modules/system/database/migrations/2013_10_01_000002_Db_System_Files.php'),
        base_path('modules/system/database/migrations/2013_10_01_000003_Db_System_Plugin_Versions.php'),
        base_path('modules/system/database/migrations/2013_10_01_000004_Db_System_Plugin_History.php'),
        base_path('modules/system/database/migrations/2013_10_01_000005_Db_System_Settings.php'),
        base_path('modules/system/database/migrations/2015_10_01_000017_Db_System_Revisions.php'),
    ],
];
