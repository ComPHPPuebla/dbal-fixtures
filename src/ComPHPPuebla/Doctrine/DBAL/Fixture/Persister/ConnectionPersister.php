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
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Perform insert statements
     *
     * @param array $rows
     */
    public function persist(array $rows)
    {
        foreach ($rows as $tableName => $row) {
            foreach ($row as $rowKey => $values) {
                $this->connection->insert($tableName, $values);
            }
        }
    }
}
