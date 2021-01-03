# Determining current tenant

The current tenant is determined by current tenant finder.

Default Tenant finder is **\GromIT\Tenancy\TenantFinders\DomainTenantFinder**

For use another tenant finder you can do this:

1. Then copy **/plugins/gromit/config/tenancy.php** config file in **/config/gromit/tenancy/tenancy.php** of your
   OctoberCMS installation.
2. Change **tenant_finder** option.

GromIT.Tenancy provides only DomainTenantFinder. If you want to use another one, you must create it by yourself. Your
custom tenant finder must implement the **\GromIT\Tenancy\TenantFinders\TenantFinder** interface.

## Example:

```php
// yourvendor\yourplugin\tenantfinders\CustomTenantFinder.php

use GromIT\Tenancy\TenantFinders\TenantFinder;
use Illuminate\Http\Request;
use GromIT\Tenancy\Models\Tenant;

class CustomTenantFinder implements TenantFinder
{
    public function findForRequest(Request $request) : ?Tenant
    {
        // find tenant by session variable
        // or by current user id
        // or in another way
    }
}

```

```php
// /config/gromit/tenancy/tenancy.php

return [

    /*
     * Tenant finder for request.
     * Must implement \GromIT\Tenancy\TenantFinder\TenantFinder.
     */
    'tenant_finder' => \YourVendor\YourPlugin\TenantFinders\CustomTenantFinder::class,
    
    // ...
];

```
