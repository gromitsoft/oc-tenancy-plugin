<?php

namespace GromIT\Tenancy\Concerns;

use GromIT\Tenancy\Classes\TenancyManager;

trait TenantAwareMigration
{
    protected function execute(callable $migration): void
    {
        if (TenancyManager::instance()->getCurrent()) {
            $migration();
        }
    }
}
