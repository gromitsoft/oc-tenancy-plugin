# Working with storage and logs

Storage and Log facades work as usual.

The only difference is if you want to work with files in 'tenant' disk you need to specify this on every facade call.

```php
use Illuminate\Support\Facades\Storage;

// this will place file in /storage/app/my_file.txt
Storage::put('my_file.txt', 'hello world'); 

// this will place file in /storage/tenant/{tenant_id}/my_file.txt (configured in disks section of /config/filesystem.php)
Storage::disk('tenant')->put('my_file.txt', 'hello world'); 
```

Same with logging channels.

```php
use Illuminate\Support\Facades\Log;

// this will write in /storage/logs/system.log
Log::info('hello world');

// this will write in /storage/logs/tenant/{tenant_id}/system.log (configured in channels section of /config/logging.php)
Log::channel('tenant')->info('hello world');
```