# Tenant aware artisan commands

For make console command tenant aware you must add \GromIT\Tenancy\Concerns\TenantAwareCommand trait to command's class.

```php
use Illuminate\Console\Command;
use GromIT\Tenancy\Concerns\TenantAwareCommand;

class TenancyArtisan extends Command
{
    use TenantAwareCommand;
    
    public function handle()
    {
        // do something
    }
}
```

This will add required tenant option to your command. Accepts tenant id or 'all'.

On 'all' command will be executed for every tenant.