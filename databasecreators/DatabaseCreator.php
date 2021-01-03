<?php

namespace GromIT\Tenancy\DatabaseCreators;

interface DatabaseCreator
{
    public function createDatabase(string $databaseName): void;

    public function dropDatabase(string $databaseName): void;

    public function databaseExists(string $databaseName): bool;
}
