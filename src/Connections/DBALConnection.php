<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Connections;

use Doctrine\DBAL\Connection as DbConnection;

class DBALConnection implements Connection
{
    /** @var DbConnection */
    protected $connection;

    public function __construct(DbConnection $connection)
    {
        $this->connection = $connection;
    }

    public function insert(string $table, Row $row): void
    {
        $this->connection->insert($table, $this->quoteIdentifiers($row->values()));
        $row->assignId($this->connection->lastInsertId());
    }

    private function quoteIdentifiers(array $row): array
    {
        $quoted = [];
        foreach ($row as $column => $value) {
            $quoted[$this->connection->quoteIdentifier($column)] = $value;
        }
        return $quoted;
    }

    public function getPrimaryKeyOf(string $table): string
    {
        $schema = $this->connection->getSchemaManager();
        return $schema->listTableDetails($table)->getPrimaryKeyColumns()[0];
    }

    public function registerPlatformType(string $platformType, string $dbalType)
    {
        $schema = $this->connection->getSchemaManager();
        $schema->getDatabasePlatform()->registerDoctrineTypeMapping($platformType, $dbalType);
    }
}
