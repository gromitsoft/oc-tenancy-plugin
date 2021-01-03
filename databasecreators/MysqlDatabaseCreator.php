<?php

namespace GromIT\Tenancy\DatabaseCreators;

use PDO;
use GromIT\Tenancy\Concerns\UsesTenancyConfig;

class MysqlDatabaseCreator implements DatabaseCreator
{
    use UsesTenancyConfig;

    /**
     * @var PDO|null
     */
    protected static $pdo;

    public function createDatabase(string $databaseName): void
    {
        $connectionName = $this->getTenantConnectionName();

        $charset   = config("database.connections.$connectionName.charset");
        $collation = config("database.connections.$connectionName.collation");

        $this
            ->getPDOConnection()
            ->exec("CREATE DATABASE IF NOT EXISTS {$databaseName} CHARACTER SET {$charset} COLLATE {$collation};");
    }

    public function dropDatabase(string $databaseName): void
    {
        $this
            ->getPDOConnection()
            ->exec("DROP DATABASE IF EXISTS {$databaseName}");
    }

    public function databaseExists(string $databaseName): bool
    {
        $result = $this
            ->getPDOConnection()
            ->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$databaseName}'");

        return $result->rowCount() > 0;
    }

    protected function getPDOConnection(): PDO
    {
        if (self::$pdo === null) {
            $connectionName = $this->getTenantConnectionName();

            $driver = config("database.connections.$connectionName.driver");
            $host   = config("database.connections.$connectionName.host");
            $port   = config("database.connections.$connectionName.port");

            $username = config("gromit.tenancy::database.create_database_auth.username")
                ?? config("database.connections.$connectionName.username");
            $password = config("gromit.tenancy::database.create_database_auth.password")
                ?? config("database.connections.$connectionName.password");

            $dsn = "{$driver}:host={$host};port={$port};";

            self::$pdo = new PDO($dsn, $username, $password);
        }

        return self::$pdo;
    }

    /**
     * @return string
     */
    protected function getConnectionName(): string
    {
        return config('database.default');
    }
}
