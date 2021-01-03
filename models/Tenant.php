<?php

namespace GromIT\Tenancy\Models;

use October\Rain\Database\Model;
use October\Rain\Database\Relations\HasMany;
use October\Rain\Database\Traits\Validation;
use October\Rain\Exception\SystemException;
use GromIT\Tenancy\Actions\Tasks\PerformAfterCreateTenantTasks;
use GromIT\Tenancy\Actions\Tasks\PerformAfterDeleteTenantTasks;
use GromIT\Tenancy\Classes\TenancyManager;
use GromIT\Tenancy\QueryBuilders\TenantQueryBuilder;

/**
 * Tenant Model
 *
 * @property int                                                                       $id
 * @property string                                                                    $name
 * @property bool                                                                      $is_active
 * @property string                                                                    $database_name
 * @property \October\Rain\Argon\Argon                                                 $created_at
 * @property \October\Rain\Argon\Argon                                                 $updated_at
 *
 * @property \October\Rain\Database\Collection|\GromIT\Tenancy\Models\Domain[] $domains
 * @method HasMany                                                                     domains()
 *
 * @method static TenantQueryBuilder query()
 */
class Tenant extends Model
{
    public $table = 'gromit_tenancy_tenants';

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'id'        => 'int',
        'is_active' => 'bool',
    ];

    public $hasMany = [
        'domains' => Domain::class,
    ];

    use Validation;

    public $rules = [
        'name' => 'required',
    ];

    public $customMessages = [
        'name.required' => 'gromit.tenancy::lang.models.tenant.validation.name.required',
    ];

    public function newEloquentBuilder($query): TenantQueryBuilder
    {
        return new TenantQueryBuilder($query);
    }

    protected function afterCreate(): void
    {
        PerformAfterCreateTenantTasks::make()->execute($this);
    }

    /**
     * @throws \October\Rain\Exception\SystemException
     */
    protected function beforeDelete(): void
    {
        $currentTenant = TenancyManager::instance()->getCurrent();

        if ($currentTenant && $currentTenant->id === $this->id) {
            throw new SystemException(
                __('gromit.tenancy::lang.exceptions.tenant_cant_delete_self')
            );
        }
    }

    protected function afterDelete(): void
    {
        PerformAfterDeleteTenantTasks::make()->execute($this);
    }
}
