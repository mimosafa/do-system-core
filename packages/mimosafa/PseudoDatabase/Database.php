<?php

namespace PseudoDatabase;

class Database
{
    /**
     * Tables
     *
     * @var Table[]
     */
    private static $tables = [];

    /**
     * Check existence of table
     *
     * @param string $tableName
     * @return bool
     */
    public static function exists(string $tableName): bool
    {
        return isset(self::$tables[$tableName]);
    }

    /**
     * Create table
     *
     * @param string $tableName
     * @param array $definitions
     * @return void
     * @throws \Exception
     */
    public static function create(string $tableName, array $definitions = []): void
    {
        if (isset(self::$tables[$tableName])) {
            throw new \Exception('Table named `' . $tableName . '` is already exists.');
        }
        self::$tables[$tableName] = new Table($tableName, $definitions);
    }

    /**
     * Get table (singleton)
     *
     * @param string $tableName
     * @return Table|null
     */
    public static function table(string $tableName): ?Table
    {
        return self::$tables[$tableName] ?? null;
    }
}
