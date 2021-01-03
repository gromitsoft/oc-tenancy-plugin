<?php

namespace GromIT\Tenancy;

use Backend\Classes\BackendController;
use Backend\Facades\Backend;
use Cms\Classes\CmsController;
use GromIT\Tenancy\Actions\MakeBaseModelsUseDefaultConnection;
use GromIT\Tenancy\Actions\TenantAwareness\MakeQueueTenantAware;
use GromIT\Tenancy\Classes\Permissions;
use GromIT\Tenancy\Components\CurrentTenant;
use GromIT\Tenancy\Concerns\UsesTenancyConfig;
use GromIT\Tenancy\Console\PluginRefresh;
use GromIT\Tenancy\Console\PluginUpdate;
use GromIT\Tenancy\Middleware\CurrentTenantMiddleware;
use System\Classes\PluginBase;

/**
 * Tenancy Plugin Information File
 */
class Plugin extends PluginBase
{
    use UsesTenancyConfig;

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'Tenancy',
            'description' => 'Multitenancy for OctoberCMS',
            'author'      => 'GromIT',
            'icon'        => 'icon-database'
        ];
    }

    public function register(): void
    {
        $this->registerConsoleCommand('tenancy:plugin-update', PluginUpdate::class);
        $this->registerConsoleCommand('tenancy:plugin-refresh', PluginRefresh::class);
    }

    public function registerReportWidgets(): array
    {
        return [
            ReportWidgets\CurrentTenant::class => [
                'label'   => 'gromit.tenancy::lang.report_widgets.current_tenant.label',
                'context' => 'dashboard',
            ],
        ];
    }

    public function boot(): void
    {
        MakeBaseModelsUseDefaultConnection::make()->execute();

        BackendController::extend(function (BackendController $controller) {
            $controller->middleware(CurrentTenantMiddleware::class);
        });

        CmsController::extend(function (CmsController $controller) {
            $controller->middleware(CurrentTenantMiddleware::class);
        });

        MakeQueueTenantAware::make()->execute();
    }

    public function registerPermissions(): array
    {
        return Permissions::lists();
    }

    public function registerComponents(): array
    {
        return [
            CurrentTenant::class => 'currentTenant',
        ];
    }

    public function registerNavigation(): array
    {
        return [
            'tenancy' => [
                'label'       => 'Арендаторы',
                'url'         => Backend::url('gromit/tenancy/tenants'),
                'icon'        => 'icon-database',
                'permissions' => [Permissions::MANAGE_TENANTS]
            ]
        ];
    }
}
