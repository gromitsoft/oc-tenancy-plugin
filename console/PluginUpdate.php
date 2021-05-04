<?php

namespace GromIT\Tenancy\Console;

use GromIT\Tenancy\Classes\Helpers;
use GromIT\Tenancy\Classes\PluginsUpdater;
use GromIT\Tenancy\Classes\TenancyManager;
use GromIT\Tenancy\Concerns\TenantAwareCommand;
use GromIT\Tenancy\Events\UpdatingPluginForTenant;
use GromIT\Tenancy\Exceptions\TenantAwarePluginUpdateException;
use GromIT\Tenancy\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Symfony\Component\Console\Input\InputArgument;
use System\Classes\PluginBase;
use System\Classes\PluginManager;

class PluginUpdate extends Command
{
    use TenantAwareCommand;

    /**
     * @var string The console command name.
     */
    protected $name = 'tenancy:plugin-update';

    /**
     * @var string The console command description.
     */
    protected $description = 'Update plugins in tenants databases';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \GromIT\Tenancy\Exceptions\TenantAwarePluginUpdateException
     * @throws \October\Rain\Exception\SystemException
     */
    public function handle(): void
    {
        $plugins = $this->getPlugins();

        $this->subscribeToUpdatingPluginEvent();

        $this->updatePlugins(
            TenancyManager::instance()->getCurrent(),
            $plugins
        );

        $this->output->success('Done.');
    }

    protected function subscribeToUpdatingPluginEvent(): void
    {
        Event::listen(UpdatingPluginForTenant::class, function (UpdatingPluginForTenant $event) {
            $this->output->writeln($this->getPluginUpdateMessage($event));
        });
    }

    /**
     * @param \GromIT\Tenancy\Models\Tenant  $tenant
     * @param \Illuminate\Support\Collection $plugins
     *
     * @throws \October\Rain\Exception\SystemException
     */
    protected function updatePlugins(Tenant $tenant, Collection $plugins): void
    {
        PluginsUpdater::instance()->updatePluginsForTenants($tenant, $plugins);
    }

    protected function getPluginUpdateMessage(UpdatingPluginForTenant $event): string
    {
        $pluginNameParts = explode('\\', get_class($event->getPlugin()));
        $pluginName      = $pluginNameParts[0] . '.' . $pluginNameParts[1];

        return "Updating {$pluginName}...";
    }

    /**
     * @return \Illuminate\Support\Collection|array<string, PluginBase>
     * @throws \GromIT\Tenancy\Exceptions\TenantAwarePluginUpdateException
     * @noinspection PhpUnusedParameterInspection
     */
    protected function getPlugins(): Collection
    {
        $pluginManager = PluginManager::instance();

        $pluginCode = $this->argument('plugin_code');

        if ($pluginCode) {
            $pluginCode = $pluginManager->normalizeIdentifier($pluginCode);

            $pluginObj = $pluginManager->findByIdentifier($pluginCode);

            if (!$pluginObj) {
                throw TenantAwarePluginUpdateException::pluginNotFound($pluginCode);
            }

            if (!Helpers::isPluginTenantAware($pluginCode)) {
                throw TenantAwarePluginUpdateException::pluginIsNotTenantAware($pluginCode);
            }

            return collect([
                $pluginCode => $pluginManager->findByIdentifier($pluginCode),
            ]);
        }

        return collect($pluginManager->getPlugins())
            ->filter(function (PluginBase $pluginObj, string $pluginCode) {
                return Helpers::isPluginTenantAware($pluginCode);
            });
    }

    protected function getArguments(): array
    {
        $pluginCode = __('gromit.tenancy::lang.commands.plugin_update.arguments.plugin_code');

        return [
            ['plugin_code', InputArgument::OPTIONAL, $pluginCode, null],
        ];
    }
}
