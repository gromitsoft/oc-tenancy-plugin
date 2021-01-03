<?php namespace SergeyKasyanov\Tenancy\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Behaviors\RelationController;
use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;
use October\Rain\Database\Relations\HasMany;
use SergeyKasyanov\Tenancy\QueryBuilders\TenantQueryBuilder;

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

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('SergeyKasyanov.Tenancy', 'tenancy', 'tenants');
    }

    /**
     * @param \SergeyKasyanov\Tenancy\QueryBuilders\TenantQueryBuilder $query
     */
    public function listExtendQuery(TenantQueryBuilder $query): void
    {
        $query->with([
            'domains' => function (HasMany $domainQuery) {
                /** @var HasMany|\SergeyKasyanov\Tenancy\QueryBuilders\DomainQueryBuilder $domainQuery */
                $domainQuery->orderByActivity();
            }
        ]);
    }
}
