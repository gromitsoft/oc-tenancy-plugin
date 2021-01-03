<?php

return [
    /*
     * Tenant logging channel name.
     * Use {tenant_id} as tenant identifier in path option.
     *
     * Example (in your config/logging.php):
     * 'tenant' => [
     *     'driver' => 'single',
     *     'path'   => storage_path('logs/tenant/{tenant_id}/system.log'),
     *     'level'  => 'debug',
     *     'days'   => 14
     * ],
     */
    'tenant_logging_channel' => 'tenant',
];
