<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\DBAL\Fixture\Persister;

use ComPHPPuebla\DBAL\Fixture\Loader\YamlLoader;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use PHPUnit_Framework_TestCase as TestCase;

class ConnectionPersisterIntegrationTest extends TestCase
{
    /** @test */
    public function it_persists_fixtures_with_references()
    {
        $persister = new ConnectionPersister($this->connection, new ForeignKeyParser());

        $persister->persist((new YamlLoader())->load("$this->path/fixture.yml"));

        $station1 = $this->findStationNamed('CASMEN GASOL');
        $station2 = $this->findStationNamed('COMBUSTIBLES JV');
        $review1 = $this->findReviewRatedWith(5);
        $review2 = $this->findReviewRatedWith(1);

        // Stations have been persisted
        $this->assertGreaterThan(0, $station1['station_id']);
        $this->assertGreaterThan(0, $station2['station_id']);

        // Relationships match
        $this->assertEquals($station1['station_id'], $review1['station_id']);
        $this->assertEquals($station1['station_id'], $review2['station_id']);
    }

    /** @before */
    protected function configureFixtures(): void
    {
        $this->path = __DIR__ . '/../../../../data/';
        $this->gasStations = [
            'stations' => [
                'station_1' => [
                    '`name`' => 'CASMEN GASOL',
                    '`social_reason`' => 'CASMEN SA CV',
                    '`address_line_1`' => '23 PTE NO 711',
                    '`address_line_2`' => 'EL CARMEN',
                    '`location`' => 'PUEBLA PUE',
                    '`latitude`' => 19.03817,
                    '`longitude`' => -98.20737,
                    '`created_at`' => '2013-10-06 00:00:00',
                    '`last_updated_at`' => '2013-10-06 00:00:00',
                ],
                'station_2' => [
                    '`name`' => 'COMBUSTIBLES JV',
                    '`social_reason`' => 'COMBUSTIBLES JV SA CV',
                    '`address_line_1`' => '24 SUR NO 507',
                    '`address_line_2`' => 'CENTRO',
                    '`location`' => 'PUEBLA PUE',
                    '`latitude`' => 19.03492,
                    '`longitude`' => -98.18554,
                    '`created_at`' => '2013-10-06 00:00:00',
                    '`last_updated_at`' => '2013-10-06 00:00:00',
                ],
            ],
            'reviews' => [
                'review_1' => [
                    '`comment`' => 'El servicio es excelente',
                    '`stars`' => 5,
                    '`station_id`' => '@station_1',
                ],
                'review_2' => [
                    '`comment`' => 'El servicio es pésimo',
                    '`stars`' => 1,
                    '`station_id`' => '@station_1',
                ],
            ],
        ];
        $this->configureConnection();
    }

    private function configureConnection()
    {
        $databasePath = $this->path . '/../test_db.sq3';
        if (file_exists($databasePath)) {
            passthru("rm $databasePath");
            passthru("touch $databasePath");
        }
        passthru("sqlite3 $databasePath < $this->path/database.sql");
        $this->connection = DriverManager::getConnection(require
            __DIR__ . '/../../../../config/connection.config.php'
        );
    }

    private function findStationNamed(string $name): array
    {
        return $this->connection->executeQuery(
            'SELECT * FROM stations WHERE name = ?', [$name]
        )->fetch();
    }

    private function findReviewRatedWith(int $stars): array
    {
        return $this->connection->executeQuery(
            'SELECT * FROM reviews WHERE stars = ?', [$stars]
        )->fetch();
    }

    /** @var string */
    private $path;

    /** @var array */
    private $gasStations;

    /** @var Connection */
    private $connection;
}
