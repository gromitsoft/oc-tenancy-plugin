<?php

return [
    /*
     * Tenant storage disk name.
     * Use {tenant_id} as tenant identifier in root and url options.
     *
     * Example (in your config/filesystems.php):
     * 'tenant' => [
     *     'driver' => 'local',
     *     'root'   => storage_path('tenant/{tenant_id}'),
     *     'url'    => '/storage/tenant/{tenant_id}'
     * ],
     */
    'tenant_disk_name'                         => 'tenant',

    /*
     * Use isolated resources storages for tenants.
     *
     * Related to 'resources_storage' of this config.
     */
    'isolate_resources_for_tenants' => true,

    /*
     * Resources storage config.
     * Replacement of storage config from config/cms.php for active tenant.
     * Use {tenant_id} as tenant identifier in path option.
     *
     * Related to 'isolate_resources_for_tenants' of this config.
     */
    'resources_storage'                        => [

        'uploads' => [
            'disk'            => 'tenant',
            'folder'          => 'uploads',
            'path'            => '/storage/tenant/{tenant_id}/uploads',
            'temporaryUrlTTL' => 3600,
        ],

        'media' => [
            'disk'   => 'tenant',
            'folder' => 'media',
            'path'   => '/storage/tenant/{tenant_id}/media',
        ],

        'resized' => [
            'disk'   => 'tenant',
            'folder' => 'resized',
            'path'   => '/storage/tenant/{tenant_id}/resized',
        ],
    ],
];
