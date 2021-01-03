<?php

namespace SergeyKasyanov\Tenancy\Services;

use PDO;

class MysqlDatabaseManager
{
    /**
     * @var PDO|null
     */
    private static $pdo;

    public static function make(): MysqlDatabaseManager
    {
        return new self();
    }

    public function createDatabase(string $databaseName): void
    {
        $connectionName = $this->getConnectionName();

        $this
            ->getPDOConnection()
            ->exec(sprintf(
                'CREATE DATABASE IF NOT EXISTS %s CHARACTER SET %s COLLATE %s;',
                $databaseName,
                config("database.connections.$connectionName.charset"),
                config("database.connections.$connectionName.collation")
            ));
    }

    public function dropDatabase(string $databaseName): void
    {
        $this
            ->getPDOConnection()
            ->exec(sprintf(
                'DROP DATABASE IF EXISTS %s',
                $databaseName
            ));
    }

    private function getPDOConnection(): PDO
    {
        if (self::$pdo === null) {
            $connectionName = $this->getConnectionName();

            $dsn = sprintf(
                "%s:host=%s;port=%d;",
                config("database.connections.$connectionName.driver"),
                config("database.connections.$connectionName.host"),
                config("database.connections.$connectionName.port")
            );

            self::$pdo = new PDO(
                $dsn,
                config("database.connections.$connectionName.username"),
                config("database.connections.$connectionName.password")
            );
        }

        return self::$pdo;
    }

    /**
     * @return string
     */
    private function getConnectionName(): string
    {
        return config('database.default');
    }
}
