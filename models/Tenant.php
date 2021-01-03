<?php namespace SergeyKasyanov\Tenancy\Models;

use October\Rain\Database\Model;
use October\Rain\Database\Relations\HasMany;
use October\Rain\Database\Traits\Validation;
use SergeyKasyanov\Tenancy\Actions\GenerateUniqueDbName;
use SergeyKasyanov\Tenancy\Actions\PerformAfterCreateTenantTasks;
use SergeyKasyanov\Tenancy\Actions\PerformAfterDeleteTenantTasks;
use SergeyKasyanov\Tenancy\QueryBuilders\TenantQueryBuilder;

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
 * @property \October\Rain\Database\Collection|\SergeyKasyanov\Tenancy\Models\Domain[] $domains
 * @method HasMany                                                                     domains()
 *
 * @method static TenantQueryBuilder query()
 */
class Tenant extends Model
{
    public $table = 'sergeykasyanov_tenancy_tenants';

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
        'name.required' => 'sergeykasyanov.tenancy::lang.models.tenant.validation.name.required',
    ];

    public function newEloquentBuilder($query): TenantQueryBuilder
    {
        return new TenantQueryBuilder($query);
    }

    protected function beforeCreate(): void
    {
        $this->database_name = GenerateUniqueDbName::make()->execute();
    }

    protected function afterCreate(): void
    {
        PerformAfterCreateTenantTasks::make()->execute($this);
    }

    protected function afterDelete(): void
    {
        PerformAfterDeleteTenantTasks::make()->execute($this);
    }
}
