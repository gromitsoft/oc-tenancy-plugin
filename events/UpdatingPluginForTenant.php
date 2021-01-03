<?php

namespace GromIT\Tenancy\Events;

use Illuminate\Queue\SerializesModels;
use GromIT\Tenancy\Models\Tenant;
use System\Classes\PluginBase;

class UpdatingPluginForTenant
{
    use SerializesModels;

    /**
     * @var \GromIT\Tenancy\Models\Tenant
     */
    private $tenant;

    /**
     * @var \System\Classes\PluginBase
     */
    private $plugin;

    public function __construct(Tenant $tenant, PluginBase $plugin)
    {
        $this->tenant = $tenant;
        $this->plugin = $plugin;
    }

    /**
     * @return \GromIT\Tenancy\Models\Tenant
     */
    public function getTenant(): Tenant
    {
        return $this->tenant;
    }

    /**
     * @return \System\Classes\PluginBase
     */
    public function getPlugin(): PluginBase
    {
        return $this->plugin;
    }
}
