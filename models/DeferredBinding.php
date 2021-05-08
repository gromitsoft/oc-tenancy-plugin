<?php

namespace GromIT\Tenancy\Models;

use GromIT\Tenancy\Concerns\UsesTenantConnection;

class DeferredBinding extends \October\Rain\Database\Models\DeferredBinding
{
    use UsesTenantConnection;
}
