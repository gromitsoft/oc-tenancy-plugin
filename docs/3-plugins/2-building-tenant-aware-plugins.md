# Building tenant aware plugins

Tenant aware plugins have some difference from usual plugins.

## Differences from usual plugins

### Attachments

**$attachOne** and **$attachMany** relations of your plugin must use **\GromIT\Tenancy\Models\File** model instead of
\System\Models\File. This will make uploaded files be stored in tenant disk.

```php
class Post extends \October\Rain\Database\Model
{
    public $attachOne = [
        'image' => \GromIT\Tenancy\Models\File::class
    ];
}
```

### Backend controllers

You need to add **\GromIT\Tenancy\Behaviors\TenantAwareController** behavior to your backend controllers. This behavior
will throw an exception on attempts to open pages from this controller if there is no current tenant.

```php
use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use GromIT\Tenancy\Behaviors\TenantAwareController;

class Posts extends \Backend\Classes\Controller
{
    public $implement = [
        ListController::class,
        FormController::class,
        TenantAwareController::class,    
    ];
}
```

### ImportModel

Use **\GromIT\Tenancy\Models\ImportModel** instead of \Backend\Models\ImportModel as base class for your import models.
This will make your import models use tenant aware version of attachment.

Other differences depending on the database strategy you are using.

## Multi database

### Make models tenant aware

For make models use tenant database you need to add **\GromIT\Tenancy\Concerns\UsesTenantConnection** trait to your
models.

### Revisionable trait

If your models using **\October\Rain\Database\Traits\Revisionable** trait then in your revision_history relation
definition you must use **\GromIT\Tenancy\Models\Revision** model instead of \System\Models\Revision. This will make
Revisionable trait to work with tenant database.

### Migrations

You can use default migrations, but in this way all your tables will also be created in the main database.

For preventing such behavior you can do this:

- add **\GromIT\Tenancy\Concerns\TenantAwareMigration** trait to your migrations and seeders
- run all schema changes or seeders inside execute closure

```php
use October\Rain\Database\Schema\Blueprint;
use GromIT\Tenancy\Concerns\TenantAwareMigration;
use October\Rain\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

class create_posts_table extends Migration
{
    use TenantAwareMigration;

    public function up()
    {
        $this->execute(function () {
            Schema::create('vendor_plugin_posts', function (Blueprint $table) {
                $table->increments('id');
                // ...            
            });
        });
    }
    
    public function down()
    {
        $this->execute(function () {
            Schema::dropIfExists('vendor_plugin_posts');
        });
    }
}
```

### DB facade

Be careful with DB facade. If you want to query tenant database, you must determine correct connection.

```php
use Illuminate\Support\Facades\DB;

DB::connection('tenant')->table('table_name')->get();
```

### Limitations

If you want to make relation to table in another database (for example: post author is admin which in a main database
and post is in a tenant's database), use index without foreign constraint.

## Single database

### Make models tenant aware

In single database setups for making model tenant aware, all you need to do is add
**\GromIT\Tenancy\Behaviors\TenantAwareModel** behavior to your models.

This behavior will:

- add polymorphic relation to \GromIT\Tenancy\Models\Tenantable model
- add global scope for filtering all queries by tenant_id from automatically joining gromit_tenancy_tenantables table

```php
use October\Rain\Database\Model;
use GromIT\Tenancy\Behaviors\TenantAwareModel;

class Post extends Model
{
    public $implement = [
        TenantAwareModel::class,    
    ];
}
```