<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Processors;

use ComPHPPuebla\Fixtures\Database\Row;
use PHPUnit\Framework\TestCase;

class ForeignKeyProcessorTest extends TestCase
{
    /** @before */
    function createProcessor()
    {
        $this->processor = new ForeignKeyProcessor();
    }

    /** @test */
    public function it_replaces_a_reference_with_the_real_foreign_key_value()
    {
        $station = new Row('id', 'station_1', ['id' => 1]);
        $this->processor->addReference($station);

        $row = new Row('', '', [
            'comment' => 'El servicio es excelente',
            'stars' => 5,
            'station_id' => '@station_1',
        ]);

        $this->processor->beforeInsert($row);

        $this->assertEquals($station->id(), $row->valueOf('station_id'));
    }

    /** @test */
    public function it_replaces_several_times_the_same_key()
    {
        $station = new Row('id', 'station_1', ['id' => 1]);
        $this->processor->addReference($station);

        $firstComment = new Row('', '', [
            'comment' => 'El servicio es excelente',
            'stars' => 5,
            'station_id' => '@station_1',
        ]);
        $secondComment = new Row('', '', [
            'comment' => 'El servicio es pésimo',
            'stars' => 0,
            'station_id' => '@station_1',
        ]);
        $thirdComment = new Row('', '', [
            'comment' => 'El servicio es regular',
            'stars' => 2,
            'station_id' => '@station_1',
        ]);

        $this->processor->beforeInsert($firstComment);
        $this->processor->beforeInsert($secondComment);
        $this->processor->beforeInsert($thirdComment);

        $this->assertEquals($station->id(), $firstComment->valueOf('station_id'));
        $this->assertEquals($station->id(), $secondComment->valueOf('station_id'));
        $this->assertEquals($station->id(), $thirdComment->valueOf('station_id'));
    }

    /** @test */
    public function it_replaces_several_keys_several_times()
    {
        $firstStation = new Row('id', 'station_1', ['id' => 1]);
        $secondStation = new Row('id', 'station_2', ['id' => 2]);
        $this->processor->addReference($firstStation);
        $this->processor->addReference($secondStation);

        $firstComment = new Row('', '', [
            'comment' => 'El servicio es excelente',
            'stars' => 5,
            'station_id' => '@station_1',
        ]);
        $secondComment = new Row('', '', [
            'comment' => 'El servicio es pésimo',
            'stars' => 0,
            'station_id' => '@station_2',
        ]);
        $thirdComment = new Row('', '', [
            'comment' => 'El servicio es regular',
            'stars' => 2,
            'station_id' => '@station_1',
        ]);

        $this->processor->beforeInsert($firstComment);
        $this->processor->beforeInsert($secondComment);
        $this->processor->beforeInsert($thirdComment);

        $this->assertEquals($firstStation->id(), $firstComment->valueOf('station_id'));
        $this->assertEquals($secondStation->id(), $secondComment->valueOf('station_id'));
        $this->assertEquals($firstStation->id(), $thirdComment->valueOf('station_id'));
    }

    /** @test */
    public function it_ignores_columns_without_foreign_keys()
    {
        $originalRow = new Row('', '', [
            'comment' => 'Excelente servicio',
            'stars' => 5,
        ]);

        $this->processor->beforeInsert($originalRow);

        $this->assertEquals('Excelente servicio', $originalRow->valueOf('comment'));
        $this->assertEquals(5, $originalRow->valueOf('stars'));
    }

    /** @test */
    public function it_ignores_columns_with_null_values()
    {
        $originalRow = new Row('', '', [
            'name' => 'admin',
            'parent_role' => null,
        ]);

        $this->processor->beforeInsert($originalRow);

        $this->assertEquals('admin', $originalRow->valueOf('name'));
        $this->assertNull($originalRow->valueOf('parent_role'));
    }

    /** @test */
    public function it_ignores_columns_with_empty_strings()
    {
        $originalRow = new Row('', '', [
            'comment' => '',
            'stars' => 5,
        ]);

        $this->processor->beforeInsert($originalRow);

        $this->assertEquals('', $originalRow->valueOf('comment'));
        $this->assertEquals(5, $originalRow->valueOf('stars'));
    }

    /** @var ForeignKeyProcessor */
    private $processor;
}
