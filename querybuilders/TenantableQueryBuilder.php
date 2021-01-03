<?php

namespace GromIT\Tenancy\QueryBuilders;

use October\Rain\Database\Builder;
use October\Rain\Database\Model;

class TenantableQueryBuilder extends Builder
{
    public function whereModel(Model $model): TenantableQueryBuilder
    {
        return $this
            ->where('tenantable_type', get_class($model))
            ->where('tenantable_id', $model->getKey());
    }
}
