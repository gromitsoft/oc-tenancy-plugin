<?php

namespace SergeyKasyanov\Tenancy\Tasks\CreateTenant;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Support\Facades\Schema;
use SergeyKasyanov\Tenancy\Actions\MakeTenantCurrent;
use SergeyKasyanov\Tenancy\Models\Tenant;
use SergeyKasyanov\Tenancy\Services\PluginsUpdater;
use SergeyKasyanov\Tenancy\Tasks\CreateTenantTask;

class Migrate implements CreateTenantTask
{
    /**
     * @var \Illuminate\Database\Migrations\Migrator
     */
    private $migrator;

    /**
     * @var string[]
     */
    private $migrations;

    /**
     * @var string
     */
    private $connection;

    /**
     * Migrate constructor.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        $this->migrator = app()->make('migrator');

        $this->migrations = config('sergeykasyanov.tenancy::tenant_db_default_migrations');
        $this->connection = config('sergeykasyanov.tenancy::tenant_connection_name');
    }

    public function handle(Tenant $tenant): void
    {
        MakeTenantCurrent::make()->execute($tenant);

        $this->createMigrationsTable();

        $this->migrateBaseTables();

        $this->migratePlugins($tenant);
    }

    private function createMigrationsTable(): void
    {
        Schema::connection($this->connection)->create('migrations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('migration');
            $table->integer('batch');
        });
    }

    private function migrateBaseTables(): void
    {
        $this->migrator->setConnection($this->connection);

        $this->migrator->run($this->migrations);
    }

    private function migratePlugins(Tenant $tenant): void
    {
        PluginsUpdater::instance()->updatePluginsForTenant($tenant);
    }
}
