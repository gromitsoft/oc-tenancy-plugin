<?php

namespace GromIT\Tenancy\Tasks\CreateTenant;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Support\Facades\Schema;
use GromIT\Tenancy\Actions\CurrentTenant\RunAsTenant;
use GromIT\Tenancy\Classes\PluginsUpdater;
use GromIT\Tenancy\Classes\TenancyManager;
use GromIT\Tenancy\Concerns\UsesTenancyConfig;
use GromIT\Tenancy\Models\Tenant;
use GromIT\Tenancy\Tasks\CreateTenantTask;

class Migrate implements CreateTenantTask
{
    use UsesTenancyConfig;

    /**
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * @var \GromIT\Tenancy\Classes\TenancyManager
     */
    protected $tenancyManager;

    /**
     * @var string[]
     */
    protected $migrations;

    /**
     * Migrate constructor.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        $this->migrator = app()->make('migrator');

        $this->migrations = config('gromit.tenancy::database.tenant_db_default_migrations');

        $this->tenancyManager = TenancyManager::instance();
    }

    /**
     * @param \GromIT\Tenancy\Models\Tenant $tenant
     *
     * @throws \October\Rain\Exception\SystemException
     */
    public function handle(Tenant $tenant): void
    {
        RunAsTenant::make()->execute($tenant, function () use ($tenant) {
            $this->createMigrationsTable();

            $this->migrateBaseTables();

            $this->migratePlugins($tenant);
        });
    }

    protected function createMigrationsTable(): void
    {
        $migrationsTable = config('database.migrations');

        Schema::connection($this->getTenantConnectionName())->create(
            $migrationsTable,
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
            }
        );
    }

    protected function migrateBaseTables(): void
    {
        $defaultConnection = $this->getDefaultConnectionName();

        $this->migrator->setConnection($this->getTenantConnectionName());

        $this->migrator->run($this->migrations);

        $this->migrator->setConnection($defaultConnection);
    }

    /**
     * @param \GromIT\Tenancy\Models\Tenant $tenant
     *
     * @throws \October\Rain\Exception\SystemException
     */
    protected function migratePlugins(Tenant $tenant): void
    {
        PluginsUpdater::instance()->updatePluginsForTenants(collect([$tenant]));
    }
}
