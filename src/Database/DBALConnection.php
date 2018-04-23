<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Database;

use Doctrine\DBAL\Connection as DbConnection;

class DBALConnection implements Connection
{
    /** @var DbConnection */
    private $connection;

    public function __construct(DbConnection $connection)
    {
        $this->connection = $connection;
    }

    public function insert(string $table, Row $row): void
    {
        $this->connection->insert($table, $this->quoteIdentifiers($row->values()));
        $row->assignId($this->connection->lastInsertId());
    }

    /**
     * This method is needed in order to keep track of references to other rows in the fixtures
     *
     * @see \ComPHPPuebla\Fixtures\Fixture::processTableRows
     * @throws \Doctrine\DBAL\DBALException
     */
    public function primaryKeyOf(string $table): string
    {
        $schema = $this->connection->getSchemaManager();
        return $schema->listTableDetails($table)->getPrimaryKeyColumns()[0];
    }

    /**
     * Use this method for types not supported by default by DBAL, like MySQL enums. For instance:
     *
     * `$connection->registerPlatformType('enum', 'string');`
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function registerPlatformType(string $platformType, string $dbalType): void
    {
        $schema = $this->connection->getSchemaManager();
        $schema->getDatabasePlatform()->registerDoctrineTypeMapping($platformType, $dbalType);
    }

    private function quoteIdentifiers(array $row): array
    {
        $quoted = [];
        foreach ($row as $column => $value) {
            $quoted[$this->connection->quoteIdentifier($column)] = $value;
        }
        return $quoted;
    }
}
