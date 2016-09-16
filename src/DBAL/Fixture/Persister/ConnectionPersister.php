<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\DBAL\Fixture\Persister;

use Doctrine\DBAL\Connection;

class ConnectionPersister implements Persister
{
    /** @var Connection */
    protected $connection;

    /** @var ForeignKeyParser */
    protected $parser;

    /**
     * @param Connection $connection
     * @param ForeignKeyParser $parser
     */
    public function __construct(
        Connection $connection,
        ForeignKeyParser $parser = null
    ) {
        $this->connection = $connection;
        $this->parser = $parser ?: new ForeignKeyParser();
    }

    /**
     * Perform insert statements
     *
     * @param array $fixtures
     */
    public function persist(array $fixtures)
    {
        foreach ($fixtures as $tableName => $rows) {
            $this->insertTableRows($tableName, $this->quoteIdentifiers($rows));
        }
    }

    /**
     * @param  array $rows
     * @return array
     */
    protected function quoteIdentifiers(array $rows)
    {
        $quotedRows = array_map(function ($row) {
            $quoted = [];
            foreach ($row as $identifier => $value) {
                $quoted[$this->connection->quoteIdentifier($identifier)] = $value;
            }

            return $quoted;
        }, $rows);

        return $quotedRows;
    }

    /**
     * @param string $tableName
     * @param array $rows
     */
    protected function insertTableRows($tableName, array $rows)
    {
        foreach ($rows as $rowKey => $values) {
            $this->connection->insert($tableName, $this->parser->parse($values));
            $this->parser->addReference($rowKey, $this->connection->lastInsertId());
        }
    }
}
