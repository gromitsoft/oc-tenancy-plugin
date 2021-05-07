<?php

namespace GromIT\Tenancy\Models;

use October\Rain\Database\Model;
use GromIT\Tenancy\QueryBuilders\TenantableQueryBuilder;

/**
 * Class Tenantable
 *
 * @property int    $id
 * @property int    $tenant_id
 * @property string $tenantable_type
 * @property int    $tenantable_id
 *
 * @property Tenant $tenant
 *
 * @method static TenantableQueryBuilder query()
 */
class Tenantable extends Model
{
    public $table = 'gromit_tenancy_tenantables';

    public $timestamps = false;

    protected $casts = [
        'tenant_id' => 'int',
    ];

    public $belongsTo = [
        'tenant' => Tenant::class,
    ];

    public $morphTo = [
        'tenantable' => [],
    ];

    public function newEloquentBuilder($query): TenantableQueryBuilder
    {
        return new TenantableQueryBuilder($query);
    }
}
