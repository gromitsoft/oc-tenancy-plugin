<?php

namespace SergeyKasyanov\Tenancy\Concerns;

use SergeyKasyanov\Tenancy\Services\TenancyManager;

trait TenantedMigration
{
    protected function run(callable $migration): void
    {
        if (TenancyManager::instance()->getCurrent()) {
            $migration();
        }
    }
}
