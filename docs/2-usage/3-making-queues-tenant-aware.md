# Tenant aware queue jobs

For make job tenant aware you must add implementation of **\GromIT\Tenancy\Concerns\TenantAwareJob** to your job class.

```php
use GromIT\Tenancy\Concerns\TenantAwareJob;

class MakeSomething implements TenantAwareJob
{
    public function handle($job, $payload)
    {
        // do something        
    }
}
```

This will automatically make current tenant current when job will start processing.

If for some reason, tenant will cannot be determined, job will be deleted and **\GromIT\Tenancy\Exceptions\TenantNotFoundInTenantAwareJob** exception will be thrown.