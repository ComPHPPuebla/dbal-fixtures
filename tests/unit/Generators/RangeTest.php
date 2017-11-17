<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Generators;

use PHPUnit\Framework\TestCase;

class RangeTest extends TestCase
{
    /** @test */
    function it_recognizes_a_range_expression()
    {
        $this->assertTrue(Range::isRange('[1..10]'));
    }

    /** @test */
    function it_fails_to_create_an_invalid_range()
    {
        $this->expectException(InvalidRange::class);

        Range::from('[4..1]');
    }

    /** @test */
    function it_generates_the_amount_of_rows_defined_in_the_range()
    {
        $range = Range::from('[1..3]');
        $row = [
            '`name`' => 'CASMEN GASOL',
            '`address_line_1`' => '23 PTE NO 711',
            '`address_line_2`' => 'EL CARMEN',
            '`latitude`' => 19.03817,
            '`longitude`' => -98.20737,
        ];

        $generatedRows = $range->generate($row, 'station_[1..3]');

        $this->assertArrayHasKey('station_1', $generatedRows);
        $this->assertArrayHasKey('station_2', $generatedRows);
        $this->assertArrayHasKey('station_3', $generatedRows);
        $this->assertEquals($row, $generatedRows['station_1']);
        $this->assertEquals($row, $generatedRows['station_2']);
        $this->assertEquals($row, $generatedRows['station_3']);
    }
}
