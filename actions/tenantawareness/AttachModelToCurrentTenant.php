<?php

namespace GromIT\Tenancy\Actions\TenantAwareness;

use October\Rain\Database\Model;
use GromIT\Tenancy\Classes\TenancyManager;
use GromIT\Tenancy\Exceptions\CurrentTenantIsNotSet;
use GromIT\Tenancy\Models\Tenantable;

class AttachModelToCurrentTenant
{
    public static function make(): AttachModelToCurrentTenant
    {
        return new static();
    }

    /**
     * @param \October\Rain\Database\Model $model
     *
     * @return \GromIT\Tenancy\Models\Tenantable
     * @throws \GromIT\Tenancy\Exceptions\CurrentTenantIsNotSet
     */
    public function execute(Model $model): Tenantable
    {
        $currentTenant = TenancyManager::instance()->getCurrent();

        if ($currentTenant === null) {
            throw new CurrentTenantIsNotSet();
        }

        $tenantable                  = new Tenantable();
        $tenantable->tenant_id       = $currentTenant->id;
        $tenantable->tenantable_type = get_class($model);
        $tenantable->tenantable_id   = $model->getKey();
        $tenantable->save();

        return $tenantable;
    }
}
