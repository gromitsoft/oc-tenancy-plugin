# Current tenant

GromIT.Tenancy provides TenancyManager and set of actions for work with current tenant.

## TenancyManager

**\GromIT\Tenancy\Classes\TenancyManager** has these methods:

Option                               | Description
-------------------------------------|-------------
**getCurrent(): Tenant**             | Return the current tenant
**hasCurrent(): bool**               | Check if there is current tenant
**forgetCurrent(): void**            | Forget the current tenant
**setCurrent(Tenant $tenant): void** | Set tenant as current

```php
use GromIT\Tenancy\Classes\TenancyManager;$tenancyManager = TenancyManager::instance();

$hasCurrent = $tenancyManager->hasCurrent();
$currentTenant = $tenancyManager->getCurrent();
$tenancyManager->forgetCurrent();
$tenancyManager->setCurrent($tenant);
```

## Actions

Plugin also provides several actions: MakeTenantCurrent, ForgetCurrentTenant, RunAsTenant

### MakeTenantCurrent

Sets given tenant as current and runs **makeCurrent** method of every task from **gromit.tenancy::tenancy.switch_tenant_tasks**.

By default, this will change several things in config:
- change "tenant" database connection to use database of current tenant
- change "tenant" storage disk root to tenant's related path
- prefix cache
- change "tenant "logging channel to tenant's related path

### ForgetCurrentTenant

Sets the current tenant to null and runs **forgetCurrent** method of every task from **gromit.tenancy::tenancy.switch_tenant_tasks**.

By default, it will return all config values changed by **MakeTenantCurrent** to its default values.

### RunAsTenant

Runs given closure in context of given tenant.

Basically it calls **MakeTenantCurrent** and executes the given closure. After doing that, it calls **ForgetCurrentTenant**, or **MakeTenantCurrent** for previous tenant that was current.

```php
use GromIT\Tenancy\Actions\CurrentTenant\MakeTenantCurrent;
use GromIT\Tenancy\Actions\CurrentTenant\ForgetCurrentTenant;
use GromIT\Tenancy\Actions\CurrentTenant\RunAsTenant;
use GromIT\Tenancy\Models\Tenant;

// no tenant is set as current at this moment

$tenant1 = Tenant::find(1);
$tenant2 = Tenant::find(2);

MakeTenantCurrent::make()->execute($tenant1);

// now current tenant is $tenant1.
// config('database.connections.tenant.database') is now returns database name of $tenant1
 
RunAsTenant::make()->execute($tenant2, function () {
    // for this closure current tenant is $tenant2
    // database connection, cache, storage, logging channel now configured for $tenant2
});

// $tenant1 is current again

ForgetCurrentTenant::make()->execute();

// now there is no current tenant
// config('database.connections.tenant.database') now return null

```