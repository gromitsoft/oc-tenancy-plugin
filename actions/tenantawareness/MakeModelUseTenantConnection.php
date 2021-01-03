<?php

namespace GromIT\Tenancy\Actions\TenantAwareness;

use October\Rain\Database\Model;
use October\Rain\Exception\SystemException;
use GromIT\Tenancy\Concerns\UsesTenancyConfig;

class MakeModelUseTenantConnection
{
    use UsesTenancyConfig;

    public static function make(): MakeModelUseTenantConnection
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
            $model->setConnection($this->getTenantConnectionName());
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
            throw new SystemException(__('gromit.tenancy::lang.exceptions.tenanted_model_only_for_models', [
                'className' => $modelClass
            ]));
        }
    }
}
