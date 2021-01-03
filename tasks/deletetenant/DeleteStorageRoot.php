<?php

namespace GromIT\Tenancy\Tasks\DeleteTenant;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use GromIT\Tenancy\Concerns\UsesTenancyConfig;
use GromIT\Tenancy\Models\Tenant;
use GromIT\Tenancy\Tasks\DeleteTenantTask;

class DeleteStorageRoot implements DeleteTenantTask
{
    use UsesTenancyConfig;

    public function handle(Tenant $tenant): void
    {
        $rootPath = config("filesystems.disks.{$this->getTenantDiskName()}.root");
        $rootPath = str_replace('{tenant_id}', $tenant->id, $rootPath);

        $local      = new Local('/');
        $fileSystem = new Filesystem($local);
        $fileSystem->deleteDir($rootPath);
    }
}
