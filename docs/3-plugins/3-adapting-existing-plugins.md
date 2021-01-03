# Adapting existing plugins

With GromIT.Tenancy you can adapt existing plugins for working in multi tenancy environment.

All you need to do is adapt models and controllers of this plugin.

## Controllers

For prevent controllers to work without the current tenant you need to add **\GromIT\Tenancy\Behaviors\TenantAwareController** behavior to these controllers.

## Models

The way you can make models tenant aware depends on tenancy strategy you are using in your application.

In multi database setups you need to make models use tenant connection. For doing this you can use **\GromIT\Tenancy\Actions\TenantAwareness\MakeModelUseTenantConnection** action.

For single database setups use can use **\GromIT\Tenancy\Actions\TenantAwareness\MakeModelTenantAware** action.

Also, you must replace $attachOne and $attachMany relations of models for using the tenant aware version of File model.

## Example

For example, lets adapt RainLab.Blog. 

The only difference in multi database and single database setup is in adaptModels method 

```php
// /plugins/yourvendor/yourplugin/Plugin.php

use Backend\Classes\Controller;
use RainLab\Blog\Controllers\Categories;
use RainLab\Blog\Controllers\Posts;
use RainLab\Blog\Models\Category;
use RainLab\Blog\Models\Post;
use RainLab\Blog\Models\PostExport;
use RainLab\Blog\Models\PostImport;
use RainLab\Blog\Models\Settings;
use GromIT\Tenancy\Actions\TenantAwareness\MakeModelUseTenantConnection;
use GromIT\Tenancy\Actions\TenantAwareness\MakeModelTenantAware;
use GromIT\Tenancy\Behaviors\TenantAwareController;
use GromIT\Tenancy\Models\File;
use System\Classes\PluginBase;

/**
 * RainLabBlogAdapter Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = [
        'RainLab.Blog',
        'GromIT.Tenancy',
    ];

    public function pluginDetails(): array
    {
        return [
            'name'        => 'RainLabBlogTenancyAdapter',
            'description' => 'Plugin for make RainLab.Blog plugin tenant aware',
            'author'      => 'GromIT',
            'icon'        => 'icon-wrench'
        ];
    }

    public function boot(): void
    {
        $this->adaptModels();
        $this->adaptControllers();
    }

    /**
     * @throws \October\Rain\Exception\SystemException
     */
    protected function adaptModels(): void
    {
        $models = [
            Category::class,
            Post::class,
            PostImport::class,
            PostExport::class,
            Settings::class,
        ];

        foreach ($models as $model) {
            // MakeModelUseTenantConnection for multi database setups
            MakeModelUseTenantConnection::make()->execute($model);
            
            // MakeModelTenantAware for single database setups
            MakeModelTenantAware::make()->execute($model);
        }

        Post::extend(function (Post $model) {
            $model->attachMany['content_images']  = File::class;
            $model->attachMany['featured_images'] = [
                File::class,
                'order' => 'sort_order',
            ];
        });

        PostImport::extend(function (PostImport $model) {
            $model->attachOne['import_file'] = [
                File::class,
                'public' => false
            ];
        });
    }

    protected function adaptControllers(): void
    {
        $controllers = [
            Categories::class,
            Posts::class,
        ];

        foreach ($controllers as $controller) {
            $controller::extend(function (Controller $controller) {
                $controller->implement[] = TenantAwareController::class;
            });
        }
    }
}
```

## Limitations

We can't dynamically change connection for queries, executed by DB facade.

So if plugin use it with hardcoded table names, only thing we can do is change the plugin code.