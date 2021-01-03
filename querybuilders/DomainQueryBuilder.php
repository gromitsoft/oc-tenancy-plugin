<?php

namespace GromIT\Tenancy\QueryBuilders;

use October\Rain\Database\Builder;

class DomainQueryBuilder extends Builder
{
    public function whereUrl(string $url): DomainQueryBuilder
    {
        return $this->where('url', $url);
    }

    public function onlyActive(): DomainQueryBuilder
    {
        return $this->where('is_active', true);
    }

    public function orderByActivity($direction = 'desc'): DomainQueryBuilder
    {
        return $this->orderBy('is_active', $direction);
    }
}
