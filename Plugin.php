<?php namespace SergeyKasyanov\Tenancy;

use Backend\Facades\Backend;
use Illuminate\Support\Facades\App;
use SergeyKasyanov\Tenancy\Actions\SetCurrentTenantByDomain;
use SergeyKasyanov\Tenancy\Console\PluginsUpdate;
use SergeyKasyanov\Tenancy\Support\Permissions;
use System\Classes\PluginBase;

/**
 * Tenancy Plugin Information File
 */
class Plugin extends PluginBase
{
    public $elevated = true;

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'Tenancy',
            'description' => 'Multi database tenancy',
            'author'      => 'SergeyKasyanov',
            'icon'        => 'icon-database'
        ];
    }

    public function register(): void
    {
        $this->registerConsoleCommand('tenancy:plugins-update', PluginsUpdate::class);
    }

    public function boot(): void
    {
        if (!App::runningInConsole()) {
            $this->setCurrentTenantByDomain();
        }
    }

    private function setCurrentTenantByDomain(): void
    {
        $domain = request()->getHost();

        SetCurrentTenantByDomain::make()->execute($domain);
    }

    public function registerPermissions(): array
    {
        return Permissions::lists();
    }

    public function registerNavigation(): array
    {
        return [
            'tenancy' => [
                'label'       => 'Арендаторы',
                'url'         => Backend::url('sergeykasyanov/tenancy/tenants'),
                'icon'        => 'icon-database',
                'permissions' => [Permissions::MANAGE_TENANTS]
            ]
        ];
    }
}
