<?php
namespace ComPHPPuebla\Doctrine\DBAL\Fixture\Persister;

use \Xpmock\TestCase;

class ConnectionPersisterTest extends TestCase
{
    /**
     * @var array
     */
    protected $gasStations;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    protected function setUp()
    {
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
                ]
            ]
        ];
        $this->connection = $this->mock('\Doctrine\DBAL\Connection');
    }

    public function testCanPersistFixtures()
    {
        $this->expectsThatPersisterSavesTwoRecords();

        $persister = new ConnectionPersister($this->connection->new());
        $persister->persist($this->gasStations);
    }

    protected function expectsThatPersisterSavesTwoRecords()
    {
        $station1 = $this->gasStations['stations']['station_1'];
        $station2 = $this->gasStations['stations']['station_2'];

        $this->connection->insert(['stations', $station1], null, $this->at(0))
                         ->insert(['stations', $station2], null, $this->at(1));
    }
}
