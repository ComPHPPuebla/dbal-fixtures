<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures;

use Doctrine\DBAL\Connection;

class TestDatabase
{
    /** @var Connection */
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /** @throws \Doctrine\DBAL\DBALException*/
    public function findStationNamed(string $name): array
    {
        return $this->connection->executeQuery('SELECT * FROM stations WHERE name = ?', [$name])->fetch();
    }

    /** @throws \Doctrine\DBAL\DBALException*/
    public function findReviewRatedWith(int $stars): array
    {
        return $this->connection->executeQuery('SELECT * FROM reviews WHERE stars = ?', [$stars])->fetch();
    }

    /** @throws \Doctrine\DBAL\DBALException*/
    public function findLocationWithId(int $id): array
    {
        return $this->connection->executeQuery('SELECT * FROM locations WHERE id = ?', [$id])->fetch();
    }

    /** @throws \Doctrine\DBAL\DBALException*/
    public function findAllReviews(): array
    {
        return $this->connection->executeQuery('SELECT * FROM reviews')->fetchAll();
    }

    /** @throws \Doctrine\DBAL\DBALException*/
    public function findAllStations(): array
    {
        return $this->connection->executeQuery('SELECT * FROM stations')->fetchAll();
    }
}
