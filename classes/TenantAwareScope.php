<?php

namespace GromIT\Tenancy\Classes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Query\JoinClause;
use GromIT\Tenancy\Exceptions\CurrentTenantIsNotSet;
use GromIT\Tenancy\Models\Tenantable;

class TenantAwareScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $model
     *
     * @return void
     * @throws \GromIT\Tenancy\Exceptions\CurrentTenantIsNotSet
     */
    public function apply(Builder $builder, Model $model): void
    {
        $tenant = TenancyManager::instance()->getCurrent();

        if ($tenant === null) {
            throw new CurrentTenantIsNotSet();
        }

        $tenantableTable = Tenantable::make()->getTable();

        $builder
            ->selectRaw("{$model->getTable()}.*")
            ->join($tenantableTable, function (JoinClause $join) use ($model, $tenantableTable) {
                $join->on("{$tenantableTable}.tenantable_id", '=', "{$model->getTable()}.{$model->getKeyName()}");
                $join->where("{$tenantableTable}.tenantable_type", '=', get_class($model));
            })
            ->where("$tenantableTable.tenant_id", $tenant->id);
    }
}
