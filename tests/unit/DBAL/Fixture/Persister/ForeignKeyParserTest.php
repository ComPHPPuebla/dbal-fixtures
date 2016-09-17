<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\DBAL\Fixture\Persister;

use PHPUnit_Framework_TestCase as TestCase;

class ForeignKeyParserTest extends TestCase
{
    /** @test */
    public function it_parses_a_foreign_key()
    {
        $parser = new ForeignKeyParser();
        $parser->addReference('station_1', 1);

        $parsedValues = $parser->parse([
            'comment' => 'El servicio es excelente',
            'stars' => 5,
            'station_id' => '@station_1',
        ]);

        $this->assertEquals(1, $parsedValues['station_id']);
    }

    /** @test */
    public function it_replaces_several_times_the_same_key()
    {
        $parser = new ForeignKeyParser();
        $parser->addReference('station_1', 1);

        $firstComment = $parser->parse([
            'comment' => 'El servicio es excelente',
            'stars' => 5,
            'station_id' => '@station_1',
        ]);
        $secondComment = $parser->parse([
            'comment' => 'El servicio es pésimo',
            'stars' => 0,
            'station_id' => '@station_1',
        ]);
        $thirdComment = $parser->parse([
            'comment' => 'El servicio es regular',
            'stars' => 2,
            'station_id' => '@station_1',
        ]);
        $this->assertEquals(1, $firstComment['station_id']);
        $this->assertEquals(1, $secondComment['station_id']);
        $this->assertEquals(1, $thirdComment['station_id']);
    }

    /** @test */
    public function it_replaces_several_keys()
    {
        $parser = new ForeignKeyParser();
        $parser->addReference('station_1', 1);
        $parser->addReference('station_2', 2);

        $firstComment = $parser->parse([
            'comment' => 'El servicio es excelente',
            'stars' => 5,
            'station_id' => '@station_1',
        ]);
        $secondComment = $parser->parse([
            'comment' => 'El servicio es pésimo',
            'stars' => 0,
            'station_id' => '@station_2',
        ]);
        $thirdComment = $parser->parse([
            'comment' => 'El servicio es regular',
            'stars' => 2,
            'station_id' => '@station_1',
        ]);
        $this->assertEquals(1, $firstComment['station_id']);
        $this->assertEquals(2, $secondComment['station_id']);
        $this->assertEquals(1, $thirdComment['station_id']);
    }

    /** @test */
    public function it_ignores_entries_without_foreign_keys()
    {
        $originalValue = [
            'comment' => 'El servicio es excelente',
            'stars' => 5,
        ];
        $parsedValues = (new ForeignKeyParser())->parse($originalValue);

        $this->assertEquals($parsedValues, $originalValue);
    }
}
