<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Processors;

use PHPUnit\Framework\TestCase;

class ForeignKeyProcessorTest extends TestCase
{
    /** @test */
    public function it_parses_a_foreign_key()
    {
        $processor = new ForeignKeyProcessor();
        $processor->postProcessing('station_1', 1);


        $processedRow = $processor->process([
            'comment' => 'El servicio es excelente',
            'stars' => 5,
            'station_id' => '@station_1',
        ]);

        $this->assertEquals(1, $processedRow['station_id']);
    }

    /** @test */
    public function it_replaces_several_times_the_same_key()
    {
        $processor = new ForeignKeyProcessor();
        $processor->postProcessing('station_1', 1);


        $firstComment = $processor->process([
            'comment' => 'El servicio es excelente',
            'stars' => 5,
            'station_id' => '@station_1',
        ]);
        $secondComment = $processor->process([
            'comment' => 'El servicio es pésimo',
            'stars' => 0,
            'station_id' => '@station_1',
        ]);
        $thirdComment = $processor->process([
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
        $processor = new ForeignKeyProcessor();
        $processor->postProcessing('station_1', 1);
        $processor->postProcessing('station_2', 2);

        $firstComment = $processor->process([
            'comment' => 'El servicio es excelente',
            'stars' => 5,
            'station_id' => '@station_1',
        ]);
        $secondComment = $processor->process([
            'comment' => 'El servicio es pésimo',
            'stars' => 0,
            'station_id' => '@station_2',
        ]);
        $thirdComment = $processor->process([
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
        $originalRow = (new ForeignKeyProcessor())->process([
            'comment' => 'El servicio es excelente',
            'stars' => 5,
        ]);

        $this->assertEquals('El servicio es excelente', $originalRow['comment']);
        $this->assertEquals(5, $originalRow['stars']);
    }

    /** @test */
    public function it_ignores_null_values()
    {
        $originalRow = (new ForeignKeyProcessor())->process([
            'name' => 'admin',
            'parent_role' => null,
        ]);

        $this->assertEquals('admin', $originalRow['name']);
        $this->assertNull($originalRow['parent_role']);
    }

    /** @test */
    public function it_ignores_entries_with_empty_strings()
    {
        $originalRow = (new ForeignKeyProcessor())->process([
            'comment' => '',
            'stars' => 5,
        ]);

        $this->assertEquals('', $originalRow['comment']);
        $this->assertEquals(5, $originalRow['stars']);
    }
}
