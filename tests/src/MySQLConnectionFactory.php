<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class MySQLConnectionFactory implements ConnectionFactory
{
    /** @throws \Doctrine\DBAL\DBALException */
    function connect(): Connection
    {
        $connection = DriverManager::getConnection(require __DIR__ . '/../../config/mysql.config.php');
        $statement = $connection->prepare(file_get_contents(__DIR__ . '/../../data/mysql-database.sql'));
        $statement->execute();

        return $connection;
    }
}
