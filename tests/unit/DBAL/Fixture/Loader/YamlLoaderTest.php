<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\DBAL\Fixture\Loader;

use Symfony\Component\Yaml\Parser;
use PHPUnit_Framework_TestCase as TestCase;

class YamlLoaderTest extends TestCase
{
    /** @var string */
    protected $path;

    /** @var array */
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

    /** @test */
    public function it_loads_fixtures_file()
    {
        $reader = $this->prophesize(Parser::class);
        $reader
            ->parse(file_get_contents($this->path))
            ->willReturn($this->gasStations)
        ;
        $loader = new YamlLoader($this->path, $reader->reveal());

        $this->assertEquals($this->gasStations, $loader->load());
    }
}
