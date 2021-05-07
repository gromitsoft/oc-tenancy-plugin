<?php

namespace GromIT\Tenancy\DatabaseCreators;

class SqliteDatabaseCreator implements DatabaseCreator
{
    public function createDatabase(string $databaseName): void
    {
        if (!$this->databaseExists($databaseName)) {
            file_put_contents($databaseName, '');
        }
    }

    public function dropDatabase(string $databaseName): void
    {
        unlink($databaseName);
    }

    public function databaseExists(string $databaseName): bool
    {
        return file_exists($databaseName);
    }
}
