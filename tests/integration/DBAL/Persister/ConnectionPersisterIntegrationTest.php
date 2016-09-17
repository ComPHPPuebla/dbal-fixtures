<?php
/**
 * PHP version 5.6
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
    /** @var string */
    protected $path;

    /** @var array */
    protected $gasStations;

    /** @var Connection */
    protected $connection;

    protected function setUp()
    {
        $this->path = realpath(__DIR__ . '/../../../../data/');
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

    /** @test */
    public function it_persists_fixtures_with_references()
    {
        $persister = new ConnectionPersister(
            $this->connection, new ForeignKeyParser()
        );

        $persister->persist((new YamlLoader("$this->path/fixture.yml"))->load());

        $station1 = $this->stationNamed('CASMEN GASOL');
        $station2 = $this->stationNamed('COMBUSTIBLES JV');
        $review1 = $this->reviewRated(5);
        $review2 = $this->reviewRated(1);

        $this->assertGreaterThan(0, $station1['station_id']);
        $this->assertGreaterThan(0, $station2['station_id']);
        $this->assertEquals($station1['station_id'], $review1['station_id']);
        $this->assertEquals($station1['station_id'], $review2['station_id']);
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

    /**
     * @param string $name
     * @return array
     */
    private function stationNamed($name)
    {
        return $this->connection->executeQuery(
            'SELECT * FROM stations WHERE name = ?', [$name]
        )->fetch();
    }

    /**
     * @param int $stars
     * @return array
     */
    private function reviewRated($stars)
    {
        return $this->connection->executeQuery(
            'SELECT * FROM reviews WHERE stars = ?', [$stars]
        )->fetch();
    }
}
