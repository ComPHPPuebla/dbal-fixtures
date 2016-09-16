<?php
namespace ComPHPPuebla\DBAL\Fixture\Persister;

use \Xpmock\TestCase;
use ComPHPPuebla\DBAL\Fixture\Loader\YamlLoader;

class ConnectionPersisterIntegrationTest extends TestCase
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $gasStations;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @var \ComPHPPuebla\Doctrine\DBAL\Fixture\Persister\ForeignKeyParser
     */
    protected $parser;

    /**
     * @var int
     */
    protected $stationId;

    protected function setUp()
    {
        $this->path = __DIR__ . '/../../../../data/fixture.yml';
        $this->gasStations = [
            'stations' => [
                'station_1' => [
                    'name' => 'CASMEN GASOL',
                    'social_reason' => 'CASMEN SA CV',
                    'address_line_1' => '23 PTE NO 711',
                    'address_line_2' => 'EL CARMEN',
                    'location' => 'PUEBLA PUE',
                    'latitude' => 19.03817,
                    'longitude' => -98.20737,
                    'created_at' => '2013-10-06 00:00:00',
                    'last_updated_at' => '2013-10-06 00:00:00',
                ],
                'station_2' => [
                    'name' => 'COMBUSTIBLES JV',
                    'social_reason' => 'COMBUSTIBLES JV SA CV',
                    'address_line_1' => '24 SUR NO 507',
                    'address_line_2' => 'CENTRO',
                    'location' => 'PUEBLA PUE',
                    'latitude' => 19.03492,
                    'longitude' => -98.18554,
                    'created_at' => '2013-10-06 00:00:00',
                    'last_updated_at' => '2013-10-06 00:00:00',
                ],
            ],
            'reviews' => [
                'review_1' => [
                   'comment' => 'El servicio es excelente',
                   'stars' => 5,
                   'station_id' => '@station_1',
                ],
                'review_2' => [
                    'comment' => 'El servicio es pésimo',
                    'stars' => 1,
                    'station_id' => '@station_1',
                ],
            ],
        ];
        $this->stationId = 1;
        $this->connection = $this->mock('\Doctrine\DBAL\Connection');
        $this->parser = $this->mock('\ComPHPPuebla\DBAL\Fixture\Persister\ForeignKeyParser');
    }

    public function testCanPersistFixtures()
    {
        $this->expectsThatPersisterSavesTwoRecords();
        $this->expectsThatPersisterGetTheTwoRecordsInsertedIds();

        $loader = new YamlLoader($this->path);

        $rows = $loader->load();
        $this->assertEquals($this->gasStations, $rows);

        $persister = new ConnectionPersister($this->connection->new(), $this->parser->new());
        $persister->persist($rows);
    }

    protected function expectsThatPersisterSavesTwoRecords()
    {
        $station1 = $this->gasStations['stations']['station_1'];
        $station2 = $this->gasStations['stations']['station_2'];
        $review1 = $this->gasStations['reviews']['review_1'];
        $review1['station_id'] = $this->stationId;
        $review2 = $this->gasStations['reviews']['review_2'];
        $review2['station_id'] = $this->stationId;

        $this->connection->insert(['stations', $station1], null, $this->at(0))
                         ->insert(['stations', $station2], null, $this->at(2))
                         ->insert(['reviews', $review1], null, $this->at(4))
                         ->insert(['reviews', $review2], null, $this->at(6));
    }

    protected function expectsThatPersisterGetTheTwoRecordsInsertedIds()
    {
        $this->connection->lastInsertId([], $this->stationId, $this->at(1));
        $this->connection->lastInsertId([], 2, $this->at(3));
        $this->connection->lastInsertId([], 1, $this->at(5));
        $this->connection->lastInsertId([], 2, $this->at(7));
    }
}
