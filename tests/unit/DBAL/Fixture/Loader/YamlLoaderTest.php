<?php
namespace ComPHPPuebla\DBAL\Fixture\Loader;

use \Xpmock\TestCase;

class YamlLoaderTest extends TestCase
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
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->path = __DIR__ . '/../../../../../data/fixture.yml';
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
                ]
            ]
        ];
    }

    public function testCanLoadFixturesFile()
    {
        $reader = $this->mock('\Symfony\Component\Yaml\Parser')
                       ->parse([file_get_contents($this->path)], $this->gasStations, $this->once())
                       ->new();

        $loader = new YamlLoader($this->path, $reader);

        $this->assertEquals($this->gasStations, $loader->load());
    }
}
