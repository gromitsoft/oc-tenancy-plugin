<?php

namespace GromIT\Tenancy\Models;

use GromIT\Tenancy\Classes\TenancyManager;
use GromIT\Tenancy\Exceptions\CurrentTenantIsNotSet;

/**
 * Revision model for using with revision history relation in models of tenant aware plugins.
 *
 * "use UsesTenantConnection" is not enough here,
 * because of using Db::table($revisionModel->getTable()) in Revisionable trait.
 */
class Revision extends \System\Models\Revision
{
    /**
     * @return string
     * @throws \GromIT\Tenancy\Exceptions\CurrentTenantIsNotSet
     */
    public function getTable(): string
    {
        $tenant = TenancyManager::instance()->getCurrent();

        if ($tenant === null) {
            throw new CurrentTenantIsNotSet();
        }

        $dbName = $tenant->database_name;

        return $dbName . '.' . parent::getTable();
    }
}
