<?php

namespace SergeyKasyanov\Tenancy\Actions;

use October\Rain\Support\Str;
use SergeyKasyanov\Tenancy\Models\Tenant;

class GenerateUniqueDbName
{
    public static function make(): GenerateUniqueDbName
    {
        return new static();
    }

    public function execute(): string
    {
        $defaultConnection = config('database.default');
        $mainDbName        = config("database.connections.$defaultConnection.database");

        return $this->createUniqueDbName($mainDbName);
    }

    protected function createUniqueDbName(string $mainDbName): string
    {
        $candidate = $mainDbName . '_' . Str::random();

        if ($this->alreadyExists($candidate)) {
            return $this->createUniqueDbName($mainDbName);
        }

        return $candidate;
    }

    protected function alreadyExists(string $candidate): bool
    {
        return Tenant::query()
            ->whereDatabaseName($candidate)
            ->exists();
    }
}
