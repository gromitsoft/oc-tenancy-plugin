# Multi database

The default installation of this plugin is already configured for work with multiple databases. All you need to do is
make **tenant** database connection.

Go to your **/config/database.php** and make a copy of mysql connection. Set a name of new connection to **tenant** and
database option to **null**.

## Example:

```php
// /config/database.php

return [

    // ...

    'connections' => [
    
        // ...
        
        'mysql' => [
            'driver'     => 'mysql',
            'engine'     => 'InnoDB',
            'host'       => env('DB_HOST', 'localhost'),
            'port'       => env('DB_PORT', 3306),
            'database'   => env('DB_DATABASE', ''),
            'username'   => env('DB_USERNAME', ''),
            'password'   => env('DB_PASSWORD', ''),
            'charset'    => 'utf8mb4',
            'collation'  => 'utf8mb4_unicode_ci',
            'prefix'     => '',
            'varcharmax' => 191,
        ],

        'tenant' => [ // <--
            'driver'     => 'mysql',
            'engine'     => 'InnoDB',
            'host'       => env('TENANT_HOST', 'localhost'),
            'port'       => env('TENANT_PORT', 3306),
            'database'   => null, // <--
            'username'   => env('TENANT_USERNAME', ''),
            'password'   => env('TENANT_PASSWORD', ''),
            'charset'    => 'utf8mb4',
            'collation'  => 'utf8mb4_unicode_ci',
            'prefix'     => '',
            'varcharmax' => 191,
        ],
        
        // ...
    ]

    // ...

];
```