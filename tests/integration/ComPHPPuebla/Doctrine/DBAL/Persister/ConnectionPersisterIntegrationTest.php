<?php
namespace ComPHPPuebla\Doctrine\DBAL\Fixture\Persister;

use \Xpmock\TestCase;
use ComPHPPuebla\Doctrine\DBAL\Fixture\Loader\YamlLoader;

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

    protected function setUp()
    {
        $this->path = __DIR__ . '/../../../../../../data/fixture.yml';
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
                'station_3' => [
                    'name' => 'GAS LA POBLANITA',
                    'social_reason' => 'GASOLINERA LA POBLANITA SA CV',
                    'address_line_1' => '2 PTE NO 1902',
                    'address_line_2' => 'SAN ALEJANDRO',
                    'location' => 'PUEBLA PUE',
                    'latitude' => 19.05226,
                    'longitude' => -98.21158,
                    'created_at' => '2013-10-06 00:00:00',
                    'last_updated_at' => '2013-10-06 00:00:00',
                ],
                'station_4' => [
                    'name' => 'GASOL ECOLOGIC POBLANO',
                    'social_reason' => 'GASOL ECOLOGICO POBLANO SA CV',
                    'address_line_1' => 'C 26 SUR NO  709',
                    'address_line_2' => 'AZCARATE',
                    'location' => 'PUEBLA PUE',
                    'latitude' => 19.03348,
                    'longitude' => -98.18496,
                    'created_at' => '2013-10-06 00:00:00',
                    'last_updated_at' => '2013-10-06 00:00:00',
                ],
                'station_5' => [
                    'name' => 'GASOL LA CAMIONERA',
                    'social_reason' => 'SERV LA CAMIONERA PUEBLA SA CV',
                    'address_line_1' => 'BLVD NTE NO 4210',
                    'address_line_2' => 'LAS CUARTILLAS',
                    'location' => 'PUEBLA PUE',
                    'latitude' => 19.07248,
                    'longitude' => -98.2044,
                    'created_at' => '2013-10-06 00:00:00',
                    'last_updated_at' => '2013-10-06 00:00:00',
                ],
                'station_6' => [
                    'name' => 'GASOL LOS ANGELES',
                    'social_reason' => 'GASOLINERA LOS ANGELES SA CV',
                    'address_line_1' => 'AV 16 DE SEPTIEMBRE NO 4322',
                    'address_line_2' => 'HUEXOTITLA',
                    'location' => 'PUEBLA PUE',
                    'latitude' => 19.0265,
                    'longitude' => -98.20896,
                    'created_at' => '2013-10-06 00:00:00',
                    'last_updated_at' => '2013-10-06 00:00:00',
                ],
            ],
        ];
        $this->connection = $this->mock('\Doctrine\DBAL\Connection');
    }

    public function testCanPersistFixtures()
    {
        $this->expectsThatPersisterSavesTwoRecords();

        $loader = new YamlLoader($this->path);

        $rows = $loader->load();
        $this->assertEquals($this->gasStations, $rows);

        $persister = new ConnectionPersister($this->connection->new());
        $persister->persist($rows);
    }

    protected function expectsThatPersisterSavesTwoRecords()
    {
        $station1 = $this->gasStations['stations']['station_1'];
        $station2 = $this->gasStations['stations']['station_2'];

        $this->connection->insert('stations', $station1, null, $this->at(0))
                         ->insert('stations', $station2, null, $this->at(1));
    }
}
