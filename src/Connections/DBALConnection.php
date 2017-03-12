<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Connections;

use ComPHPPuebla\DBAL\Fixture\Persister\ForeignKeyParser;
use Doctrine\DBAL\Connection as DbConnection;

class DBALConnection implements Connection
{
    /** @var DbConnection */
    protected $connection;

    /** @var ForeignKeyParser */
    protected $parser;

    public function __construct(
        DbConnection $connection,
        ForeignKeyParser $parser = null
    ) {
        $this->connection = $connection;
        $this->parser = $parser ?: new ForeignKeyParser();
    }

    public function insert(array $fixtures): void
    {
        foreach ($fixtures as $tableName => $rows) {
            $this->insertTableRows($tableName, $this->quoteIdentifiers($rows));
        }
    }

    protected function quoteIdentifiers(array $rows): array
    {
        $quotedRows = array_map(function (array $row) {
            $quoted = [];
            foreach ($row as $identifier => $value) {
                $quoted[$this->connection->quoteIdentifier($identifier)] = $value;
            }

            return $quoted;
        }, $rows);

        return $quotedRows;
    }

    protected function insertTableRows(string $tableName, array $rows): void
    {
        foreach ($rows as $rowKey => $values) {
            $this->connection->insert($tableName, $this->parser->parse($values));
            $this->parser->addReference($rowKey, $this->connection->lastInsertId());
        }
    }
}
