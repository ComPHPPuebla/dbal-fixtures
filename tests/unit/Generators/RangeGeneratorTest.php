<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Generators;

use PHPUnit\Framework\TestCase;

class RangeGeneratorTest extends TestCase
{
    /** @before */
    function newGenerator()
    {
        $this->generator = new RangeGenerator();
    }

    /** @test */
    function it_does_not_generate_rows_without_a_range_definition()
    {
        $rowsWithoutRanges = [
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
        ];

        $unmodifiedRows = $this->generator->generate($rowsWithoutRanges);

        $this->assertEquals($rowsWithoutRanges, $unmodifiedRows);
    }

    /** @test */
    function it_generates_rows_for_a_single_range_definition()
    {
        $rowWithOneRange = [
            'station_[1..5]' => [
                '`name`' => 'CASMEN GASOL',
                '`social_reason`' => 'CASMEN SA CV',
                '`address_line_1`' => '23 PTE NO 711',
                '`address_line_2`' => 'EL CARMEN',
                '`location`' => 'PUEBLA PUE',
                '`latitude`' => 19.03817,
                '`longitude`' => -98.20737,
                '`created_at`' => '2013-10-06 00:00:00',
                '`last_updated_at`' => '2013-10-06 00:00:00',
            ]
        ];

        $fiveIdenticalRows = $this->generator->generate($rowWithOneRange);

        $this->assertCount(5, $fiveIdenticalRows);

        // It generates a unique identifier for each row
        $this->assertArrayHasKey('station_1', $fiveIdenticalRows);
        $this->assertArrayHasKey('station_2', $fiveIdenticalRows);
        $this->assertArrayHasKey('station_3', $fiveIdenticalRows);
        $this->assertArrayHasKey('station_4', $fiveIdenticalRows);
        $this->assertArrayHasKey('station_5', $fiveIdenticalRows);

        // Every row is identical
        $this->assertEquals($fiveIdenticalRows['station_1'], $rowWithOneRange['station_[1..5]']);
        $this->assertEquals($fiveIdenticalRows['station_2'], $rowWithOneRange['station_[1..5]']);
        $this->assertEquals($fiveIdenticalRows['station_3'], $rowWithOneRange['station_[1..5]']);
        $this->assertEquals($fiveIdenticalRows['station_4'], $rowWithOneRange['station_[1..5]']);
        $this->assertEquals($fiveIdenticalRows['station_5'], $rowWithOneRange['station_[1..5]']);
    }

    /** @test */
    function it_generates_rows_for_several_range_definitions()
    {
        $rowsWith2Ranges = [
            'station_[1..3]' => [
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
            'station_[4..5]' => [
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
        ];

        $generatedRows = $this->generator->generate($rowsWith2Ranges);

        $this->assertCount(5, $generatedRows);

        $this->assertArrayHasKey('station_1', $generatedRows);
        $this->assertArrayHasKey('station_2', $generatedRows);
        $this->assertArrayHasKey('station_3', $generatedRows);
        $this->assertArrayHasKey('station_4', $generatedRows);
        $this->assertArrayHasKey('station_5', $generatedRows);

        // It generates 3 identical rows for the first range
        $this->assertEquals($generatedRows['station_1'], $rowsWith2Ranges['station_[1..3]']);
        $this->assertEquals($generatedRows['station_2'], $rowsWith2Ranges['station_[1..3]']);
        $this->assertEquals($generatedRows['station_3'], $rowsWith2Ranges['station_[1..3]']);

        // It generates 2 identical rows for the second range
        $this->assertEquals($generatedRows['station_4'], $rowsWith2Ranges['station_[4..5]']);
        $this->assertEquals($generatedRows['station_5'], $rowsWith2Ranges['station_[4..5]']);
    }

    /** @var RangeGenerator */
    private $generator;
}
