<?php
namespace ComPHPPuebla\Doctrine\DBAL\Fixture\Persister;

use \Doctrine\DBAL\Connection;

class ConnectionPersister implements Persister
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @var ForeignKeyParser
     */
    protected $parser;

    /**
     * @param Connection $connection
     * @param ForeignKeyParser $parser
     */
    public function __construct(Connection $connection, ForeignKeyParser $parser)
    {
        $this->connection = $connection;
        $this->parser = $parser;
    }

    /**
     * Perform insert statements
     *
     * @param array $fixtures
     */
    public function persist(array $fixtures)
    {
        foreach ($fixtures as $tableName => $rows) {
            $this->insertTableRows($tableName, $rows);
        }
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
