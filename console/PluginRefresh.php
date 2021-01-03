<?php

namespace GromIT\Tenancy\Console;

use GromIT\Tenancy\Events\UpdatingPluginForTenant;
use GromIT\Tenancy\Models\Tenant;
use Illuminate\Support\Collection;

class PluginRefresh extends PluginUpdate
{
    /**
     * @var string The console command name.
     */
    protected $name = 'tenancy:plugin-refresh';

    /**
     * @var string The console command description.
     */
    protected $description = 'Refresh plugins in tenants databases';

    protected function updatePlugins(Tenant $tenant, Collection $plugins): void
    {
        $this->pluginUpdater->updatePluginsForTenants($tenant, $plugins, true);
    }

    protected function getPluginUpdateMessage(UpdatingPluginForTenant $event): string
    {
        $message = parent::getPluginUpdateMessage($event);

        return str_replace('Updating', 'Refreshing', $message);
    }
}
