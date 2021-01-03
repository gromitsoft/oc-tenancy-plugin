<?php

namespace GromIT\Tenancy\Behaviors;

use GromIT\Tenancy\Actions\TenantAwareness\AttachModelToCurrentTenant;
use GromIT\Tenancy\Classes\TenantAwareScope;
use GromIT\Tenancy\Models\Tenantable;
use October\Rain\Extension\ExtensionBase;

class TenantAwareModel extends ExtensionBase
{
    protected $model;

    /**
     * TenantAwareModel constructor.
     *
     * @param $model
     *
     * @throws \GromIT\Tenancy\Exceptions\CurrentTenantIsNotSet
     */
    public function __construct($model)
    {
        $this->model = $model;

        $this->boot();
    }

    /**
     * @throws \GromIT\Tenancy\Exceptions\CurrentTenantIsNotSet
     */
    protected function boot(): void
    {
        $this->model::addGlobalScope(new TenantAwareScope());

        $this->model->morphOne['tenantable'] = [
            Tenantable::class,
            'name' => 'tenantable',
        ];

        $this->model->bindEvent('model.afterCreate', function () {
            AttachModelToCurrentTenant::make()->execute($this->model);
        });

        $this->model->bindEvent('model.afterDelete', function () {
            Tenantable::query()->whereModel($this->model)->delete();
        });
    }
}
