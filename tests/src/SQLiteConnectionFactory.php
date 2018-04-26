<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class SQLiteConnectionFactory implements ConnectionFactory
{
    /** @throws \Doctrine\DBAL\DBALException */
    public function connect(): Connection
    {
        $path = __DIR__ . '/../../data/';
        $databasePath = $path . '/../test_db.sq3';
        if (file_exists($databasePath)) {
            passthru("rm $databasePath");
            passthru("touch $databasePath");
        }
        passthru("sqlite3 $databasePath < $path/database.sql");

        return DriverManager::getConnection(require __DIR__ . '/../../config/connection.config.php');
    }
}
