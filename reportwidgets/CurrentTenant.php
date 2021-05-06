<?php

namespace GromIT\Tenancy\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Backend\Facades\BackendAuth;
use Backend\Widgets\Form;
use Exception;
use GromIT\Tenancy\Classes\CurrentTenantOverrider;
use GromIT\Tenancy\Classes\Permissions;
use GromIT\Tenancy\Classes\TenancyManager;
use GromIT\Tenancy\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use October\Rain\Database\Model;
use October\Rain\Exception\ValidationException;
use October\Rain\Support\Facades\Flash;

/**
 * CurrentTenant Report Widget
 */
class CurrentTenant extends ReportWidgetBase
{
    /**
     * @var string The default alias to use for this widget
     */
    protected $defaultAlias = 'CurrentTenantReportWidget';

    /**
     * @var \GromIT\Tenancy\Classes\TenancyManager|string
     */
    protected $tenancyManager;

    /**
     * @var string
     */
    protected $langKey = 'gromit.tenancy::lang.report_widgets.current_tenant';

    public function init(): void
    {
        $this->tenancyManager = TenancyManager::instance();

        /** @var \Backend\Models\User $user */
        $user = BackendAuth::getUser();

        if ($user->hasAccess(Permissions::OVERRIDE_CURRENT_TENANT) && request()->ajax()) {
            $this->bindChooseTenantForm();
        }
    }

    /**
     * Renders the widget's primary contents.
     *
     * @return string HTML markup supplied by this widget.
     * @throws \SystemException
     */
    public function render(): string
    {
        try {
            $this->prepareVars();
        } catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('current_tenant');
    }

    /**
     * Prepares the report widget view data
     */
    public function prepareVars(): void
    {
        /** @var \Backend\Models\User $user */
        $user = BackendAuth::getUser();

        $this->vars['allow']         = $user->hasAccess(Permissions::OVERRIDE_CURRENT_TENANT);
        $this->vars['currentTenant'] = $this->tenancyManager->getCurrent();
        $this->vars['title']         = __("{$this->langKey}.label");
    }

    /**
     * @throws \SystemException
     */
    public function onShowOverridePopup(): string
    {
        return $this->makePartial('choose_form', [
            'form' => $this->controller->widget->chooseTenantForm
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \October\Rain\Exception\ValidationException
     */
    public function onOverride(): RedirectResponse
    {
        $id = post('current_tenant_id');

        if (empty($id)) {
            throw new ValidationException([
                'current_tenant_id' => __("{$this->langKey}.choose_form.tenant_not_selected")
            ]);
        }

        /** @var Tenant $tenant */
        $tenant = Tenant::query()->find($id);

        if ($tenant === null) {
            Flash::error(__('gromit.tenancy::lang.exceptions.tenant_not_found'));
            return redirect()->refresh();
        }

        CurrentTenantOverrider::make()->setOverride($tenant);

        return redirect()->refresh();
    }

    public function onResetOverride(): RedirectResponse
    {
        CurrentTenantOverrider::make()->forgetOverride();

        return redirect()->refresh();
    }

    private function bindChooseTenantForm(): void
    {
        $currentTenant = $this->tenancyManager->getCurrent();

        $formModel = new Model();
        /** @noinspection PhpUndefinedFieldInspection */
        $formModel->current_tenant_id = $currentTenant->id ?? null;

        $this
            ->makeWidget(Form::class, [
                'alias'  => 'chooseTenantForm',
                'model'  => $formModel,
                'fields' => [
                    'current_tenant_id' => [
                        'label'       => "{$this->langKey}.choose_form.current_tenant_id.label",
                        'type'        => 'recordfinder',
                        'list'        => '~/plugins/gromit/tenancy/models/tenant/columns.yaml',
                        'title'       => "{$this->langKey}.choose_form.current_tenant_id.title",
                        'prompt'      => "{$this->langKey}.choose_form.current_tenant_id.prompt",
                        'keyFrom'     => 'id',
                        'nameFrom'    => 'name',
                        'searchMode'  => 'all',
                        'useRelation' => false,
                        'modelClass'  => Tenant::class,
                    ],
                ],
            ])
            ->bindToController();
    }
}
