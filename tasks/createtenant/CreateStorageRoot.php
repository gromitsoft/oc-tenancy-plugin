<?php

namespace GromIT\Tenancy\Tasks\CreateTenant;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use GromIT\Tenancy\Concerns\UsesTenancyConfig;
use GromIT\Tenancy\Models\Tenant;
use GromIT\Tenancy\Tasks\CreateTenantTask;

class CreateStorageRoot implements CreateTenantTask
{
    use UsesTenancyConfig;

    public function handle(Tenant $tenant): void
    {
        $rootPath = config("filesystems.disks.{$this->getTenantDiskName()}.root");
        $rootPath = str_replace('{tenant_id}', $tenant->id, $rootPath);

        $local      = new Local('/');
        $fileSystem = new Filesystem($local);
        $fileSystem->createDir($rootPath);
    }
}
