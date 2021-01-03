# Single database 

For using this plugin in single database way you need to do this steps:

First, install a plugin into your OctoberCMS installation.

Then copy **/plugins/gromit/config/tenancy.php** config file in **/config/gromit/tenancy/tenancy.php** of your OctoberCMS installation.

Comment out or remove

- **CreateDatabase** and **Migrate** from **create_tenant_tasks**
- **DeleteDatabase** from **delete_tenant_tasks**
- **ChangeTenantDatabaseConnection** from **switch_tenant_tasks**

## Example:

```php
// /config/gromit/tenancy/tenancy.php

return [

    // ...

    /*
     * Tasks performing after creating tenant.
     * All tasks must implement \GromIT\Tenancy\Tasks\CreateTenantTask interface.
     */
    'create_tenant_tasks'          => [
//        \GromIT\Tenancy\Tasks\CreateTenant\CreateDatabase::class,
//        \GromIT\Tenancy\Tasks\CreateTenant\Migrate::class,
        \GromIT\Tenancy\Tasks\CreateTenant\CreateStorageRoot::class,
    ],

    /*
     * Tasks performing after deleting tenant.
     * All tasks must implement \GromIT\Tenancy\Tasks\DeleteTenantTask interface.
     */
    'delete_tenant_tasks'          => [
//        \GromIT\Tenancy\Tasks\DeleteTenant\DeleteDatabase::class,
        \GromIT\Tenancy\Tasks\DeleteTenant\DeleteStorageRoot::class,
    ],

    /*
     * Tasks performing on switching tenants.
     * All tasks must implement \GromIT\Tenancy\Tasks\SwitchTenantTask interface.
     */
    'switch_tenant_tasks'          => [
//        \GromIT\Tenancy\Tasks\SwitchTenant\ChangeTenantDatabaseConnection::class,
        \GromIT\Tenancy\Tasks\SwitchTenant\ChangeCachePrefix::class,
        \GromIT\Tenancy\Tasks\SwitchTenant\ChangeStorageDisk::class,
        \GromIT\Tenancy\Tasks\SwitchTenant\ChangeResourcesStorage::class,
        \GromIT\Tenancy\Tasks\SwitchTenant\ChangeTenantLoggingChannel::class,
    ],
];

```