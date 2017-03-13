<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla;

use ComPHPPuebla\Connections\DBALConnection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use PHPUnit_Framework_TestCase as TestCase;

class FixturesIntegrationTest extends TestCase
{
    /** @test */
    public function it_persists_fixtures_with_references()
    {
        $fixtures = new Fixtures(new DBALConnection($this->connection));

        $fixtures->load("$this->path/fixture.yml");

        $station1 = $this->findStationNamed('CASMEN GASOL');
        $station2 = $this->findStationNamed('COMBUSTIBLES JV');
        $review1 = $this->findReviewRatedWith(5);
        $review2 = $this->findReviewRatedWith(1);

        // Stations have been saved
        $this->assertGreaterThan(0, $station1['station_id']);
        $this->assertGreaterThan(0, $station2['station_id']);

        // Relationships match
        $this->assertEquals($station1['station_id'], $review1['station_id']);
        $this->assertEquals($station1['station_id'], $review2['station_id']);
    }

    /** @test */
    public function it_persists_fixtures_with_references_and_fake_data()
    {
        $fixtures = new Fixtures(new DBALConnection($this->connection));

        $fixtures->load("$this->path/fixture-faker.yml");

        $station = $this->findStationNamed('CASMEN GASOL');
        $reviews = $this->findAllReviews();

        // Station has been saved
        $this->assertGreaterThan(0, $station['station_id']);

        // Relationships match
        $this->assertEquals($station['station_id'], $reviews[0]['station_id']);
        $this->assertEquals($station['station_id'], $reviews[1]['station_id']);

        // Faker calls have been replaced
        $this->assertNotEquals('${company}', $station['social_reason']);
        $this->assertNotEquals('${address}', $station['address_line_1']);
        $this->assertNotEquals('${date(\'Y-m-d H:i:s\')}', $station['created_at']);
        $this->assertNotEquals('${numberBetween(1, 5)}', $reviews[0]['stars']);
        $this->assertNotEquals('${numberBetween(1, 5)}', $reviews[1]['stars']);
    }

    /** @test */
    public function it_persists_fixtures_with_generated_rows_references_and_fake_data()
    {
        $fixtures = new Fixtures(new DBALConnection($this->connection));

        $fixtures->load("$this->path/fixture-all.yml");

        $stations = $this->findAllStations();
        $reviews = $this->findAllReviews();

        // Station has been saved
        $this->assertCount(3, $stations);
        $this->assertGreaterThan(0, $stations[0]['station_id']);
        $this->assertGreaterThan(0, $stations[1]['station_id']);
        $this->assertGreaterThan(0, $stations[2]['station_id']);

        // Relationships match, 2 reviews for station 1, 3 for station 2
        $this->assertEquals($stations[0]['station_id'], $reviews[0]['station_id']);
        $this->assertEquals($stations[0]['station_id'], $reviews[1]['station_id']);
        $this->assertEquals($stations[1]['station_id'], $reviews[2]['station_id']);
        $this->assertEquals($stations[1]['station_id'], $reviews[3]['station_id']);
        $this->assertEquals($stations[1]['station_id'], $reviews[4]['station_id']);

        // Faker calls have been replaced
        $this->assertNotEquals('${company}', $stations[0]['social_reason']);
        $this->assertNotEquals('${address}', $stations[0]['address_line_1']);
        $this->assertNotEquals('${date(\'Y-m-d H:i:s\')}', $stations[0]['created_at']);
        $this->assertNotEquals('${company}', $stations[1]['social_reason']);
        $this->assertNotEquals('${address}', $stations[1]['address_line_1']);
        $this->assertNotEquals('${date(\'Y-m-d H:i:s\')}', $stations[1]['created_at']);
        $this->assertNotEquals('${company}', $stations[2]['social_reason']);
        $this->assertNotEquals('${address}', $stations[2]['address_line_1']);
        $this->assertNotEquals('${date(\'Y-m-d H:i:s\')}', $stations[2]['created_at']);
        $this->assertNotEquals('${numberBetween(1, 5)}', $reviews[0]['stars']);
        $this->assertNotEquals('${numberBetween(1, 5)}', $reviews[1]['stars']);
        $this->assertNotEquals('${numberBetween(1, 5)}', $reviews[2]['stars']);
        $this->assertNotEquals('${numberBetween(1, 5)}', $reviews[3]['stars']);
        $this->assertNotEquals('${numberBetween(1, 5)}', $reviews[4]['stars']);
    }

    /** @before */
    protected function configureFixtures(): void
    {
        $this->path = __DIR__ . '/../../data/';
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
            __DIR__ . '/../../config/connection.config.php'
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

    private function findAllReviews()
    {
        return $this->connection->executeQuery('SELECT * FROM reviews')->fetchAll();
    }

    private function findAllStations()
    {
        return $this->connection->executeQuery('SELECT * FROM stations')->fetchAll();
    }

    /** @var string */
    private $path;

    /** @var array */
    private $gasStations;

    /** @var Connection */
    private $connection;
}
