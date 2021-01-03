<?php

namespace SergeyKasyanov\Tenancy\QueryBuilders;

use October\Rain\Database\Builder;

class TenantQueryBuilder extends Builder
{
    public function whereDatabaseName(string $databaseName): TenantQueryBuilder
    {
        return $this->where('database_name', $databaseName);
    }

    public function onlyActive(): TenantQueryBuilder
    {
        return $this->where('is_active', true);
    }

    public function whereDomainUrl(string $url, bool $onlyActive = true): TenantQueryBuilder
    {
        return $this->whereHas('domains', function (DomainQueryBuilder $domainsQuery) use ($url, $onlyActive) {
            return $domainsQuery
                ->when($onlyActive, function (DomainQueryBuilder $domainsQuery) {
                    $domainsQuery->onlyActive();
                })
                ->whereUrl($url);
        });
    }
}
