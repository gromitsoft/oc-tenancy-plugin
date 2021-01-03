<?php

namespace GromIT\Tenancy\Actions\TenantAwareness;

use October\Rain\Database\Model;
use October\Rain\Exception\SystemException;
use GromIT\Tenancy\Classes\TenancyManager;
use GromIT\Tenancy\Exceptions\CurrentTenantIsNotSet;

class MakeSettingsModelTenantAware
{
    public static function make(): MakeSettingsModelTenantAware
    {
        return new static();
    }

    /**
     * @param string $modelClass
     *
     * @throws \October\Rain\Exception\SystemException
     */
    public function execute(string $modelClass): void
    {
        $this->checkClass($modelClass);

        /** @noinspection PhpUndefinedMethodInspection */
        $modelClass::extend(function (Model $model) {
            $tenant = TenancyManager::instance()->getCurrent();

            if ($tenant === null) {
                throw new CurrentTenantIsNotSet();
            }

            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            $model->settingsCode .= '_tenant_' . TenancyManager::instance()->getCurrent()->id;
        });
    }

    /**
     * @param string $modelClass
     *
     * @throws \October\Rain\Exception\SystemException
     */
    protected function checkClass(string $modelClass): void
    {
        $model = new $modelClass;

        if (!($model instanceof Model)) {
            throw new SystemException(__('gromit.tenancy::lang.exceptions.tenanted_settings_only_for_models', [
                'className' => $modelClass
            ]));
        }

        if (!property_exists($model, 'settingsCode')) {
            $msg = __('gromit.tenancy::lang.exceptions.tenanted_settings_need_base_settings', [
                'modelClass' => $modelClass
            ]);

            throw new SystemException($msg);
        }
    }
}
