<?php

namespace GromIT\Tenancy\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Behaviors\RelationController;
use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;
use GromIT\Tenancy\Classes\Permissions;
use Illuminate\Http\RedirectResponse;
use October\Rain\Database\Relations\HasMany;
use October\Rain\Support\Facades\Flash;
use GromIT\Tenancy\Models\Tenant;
use GromIT\Tenancy\QueryBuilders\TenantQueryBuilder;

class Tenants extends Controller
{
    public $implement = [
        ListController::class,
        FormController::class,
        RelationController::class,
    ];

    public $listConfig = 'config/list.yaml';
    public $formConfig = 'config/form.yaml';
    public $relationConfig = 'config/relation.yaml';

    protected $requiredPermissions = [Permissions::MANAGE_TENANTS];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('GromIT.Tenancy', 'tenancy', 'tenants');
    }

    /**
     * @param \GromIT\Tenancy\QueryBuilders\TenantQueryBuilder $query
     */
    public function listExtendQuery(TenantQueryBuilder $query): void
    {
        $query->with([
            'domains' => function (HasMany $domainQuery) {
                /** @var HasMany|\GromIT\Tenancy\QueryBuilders\DomainQueryBuilder $domainQuery */
                $domainQuery->orderByActivity();
            }
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create_onSave(): RedirectResponse
    {
        // Reason for overriding create_onSave is database transaction.
        // \GromIT\Tenancy\Tasks\CreateTenant\Migrate is juggling database connections
        // so transaction performing in FormController::create_onSave not finishing and record not saving.

        $tenant = Tenant::create(post('Tenant'));

        $savedMsg = __('gromit.tenancy::lang.controllers.tenants.config.form.create.flashSaved');

        Flash::success($savedMsg);

        return redirect($this->actionUrl('update', $tenant->id));
    }
}
