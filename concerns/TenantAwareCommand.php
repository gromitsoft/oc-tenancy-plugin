<?php

namespace GromIT\Tenancy\Concerns;

use GromIT\Tenancy\Actions\CurrentTenant\RunAsTenant;
use GromIT\Tenancy\Exceptions\TenantNotFoundInTenantAwareCommand;
use GromIT\Tenancy\Models\Tenant;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

trait TenantAwareCommand
{
    protected $tenantOption = 'tenant';

    protected function specifyParameters(): void
    {
        parent::specifyParameters();

        $this->addOption(
            $this->tenantOption,
            null,
            InputOption::VALUE_REQUIRED,
            'Tenant ID or "all" for all tenants.'
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return mixed|void
     * @throws \October\Rain\Exception\SystemException
     * @noinspection DisconnectedForeachInstructionInspection
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->getTenants($input->getOption($this->tenantOption)) as $tenant) {
            $this->line('');
            $this->info("Running command for tenant `{$tenant->name}` (id: {$tenant->getKey()})");
            $this->line("---");

            RunAsTenant::make()->execute($tenant, function () {
                $this->laravel->call([$this, 'handle']);
            });
        }
    }

    /**
     * @param $tenantId
     *
     * @return \GromIT\Tenancy\QueryBuilders\TenantQueryBuilder[]|\Illuminate\Database\Eloquent\Collection
     * @throws \GromIT\Tenancy\Exceptions\TenantNotFoundInTenantAwareCommand
     */
    protected function getTenants($tenantId)
    {
        if (empty($tenantId)) {
            $this->error('Tenant option is required');
            exit();
        }

        if ($tenantId === 'all') {
            $tenants = Tenant::query()->get();
        } else {
            $tenants = Tenant::query()->where('id', $tenantId)->get($tenantId);
        }

        if ($tenants->isEmpty()) {
            throw TenantNotFoundInTenantAwareCommand::forCommand($this->getName());
        }

        return $tenants;
    }
}
