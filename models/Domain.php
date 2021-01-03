<?php

namespace GromIT\Tenancy\Models;

use October\Rain\Database\Model;
use October\Rain\Database\Relations\BelongsTo;
use October\Rain\Database\Traits\Validation;
use GromIT\Tenancy\QueryBuilders\DomainQueryBuilder;

/**
 * Domain Model
 *
 * @property int                                   $id
 * @property int                                   $tenant_id
 * @property bool                                  $is_active
 * @property string                                $url
 * @property \October\Rain\Argon\Argon             $created_at
 * @property \October\Rain\Argon\Argon             $updated_at
 *
 * @property \GromIT\Tenancy\Models\Tenant $tenant
 * @method BelongsTo                               tenant()
 *
 * @method static DomainQueryBuilder query()
 */
class Domain extends Model
{
    public $table = 'gromit_tenancy_domains';

    protected $fillable = [
        'tenant_id',
        'is_active',
        'url',
    ];

    protected $casts = [
        'id'        => 'int',
        'tenant_id' => 'int',
        'is_active' => 'bool',
    ];

    public $belongsTo = [
        'tenant' => Tenant::class,
    ];

    use Validation;

    public $rules = [
        'tenant_id' => 'required|exists:gromit_tenancy_tenants,id',
        'url'       => 'required|unique:gromit_tenancy_domains',
    ];

    public $customMessages = [
        'tenant_id.required' => 'gromit.tenancy::lang.models.domain.validation.tenant_id.required',
        'tenant_id.exists'   => 'gromit.tenancy::lang.models.domain.validation.tenant_id.exists',
        'url.required'       => 'gromit.tenancy::lang.models.domain.validation.url.required',
        'url.unique'         => 'gromit.tenancy::lang.models.domain.validation.url.unique',
    ];

    public function newEloquentBuilder($query): DomainQueryBuilder
    {
        return new DomainQueryBuilder($query);
    }
}
