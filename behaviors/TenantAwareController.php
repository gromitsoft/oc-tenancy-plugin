<?php

namespace GromIT\Tenancy\Behaviors;

use Backend\Classes\ControllerBehavior;
use GromIT\Tenancy\Classes\TenancyManager;
use GromIT\Tenancy\Exceptions\CurrentTenantIsNotSet;
use GromIT\Tenancy\Models\Tenant;

class TenantAwareController extends ControllerBehavior
{
    /**
     * @var \GromIT\Tenancy\Classes\TenancyManager
     */
    protected $tenancyManager;

    /**
     * TenantAwareController constructor.
     *
     * @param $controller
     *
     * @throws \GromIT\Tenancy\Exceptions\CurrentTenantIsNotSet
     */
    public function __construct($controller)
    {
        parent::__construct($controller);

        $this->viewPath = $this->guessViewPath();

        $this->tenancyManager = TenancyManager::instance();

        if (!$this->tenancyManager->hasCurrent()) {
            throw new CurrentTenantIsNotSet();
        }
    }

    public function getCurrentTenant(): ?Tenant
    {
        return $this->tenancyManager->getCurrent();
    }
}
