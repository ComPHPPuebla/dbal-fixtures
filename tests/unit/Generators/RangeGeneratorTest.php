<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Generators;

use PHPUnit_Framework_TestCase as TestCase;

class RangeGeneratorTest extends TestCase
{
    private $severalRows;

    /** @test */
    function it_ignores_rows_without_range_definitions()
    {
        $this->assertEquals($this->rows, $this->generator->generate($this->rows));
    }

    /** @test */
    function it_generates_rows_for_a_single_entry_with_a_range_definition()
    {
        $modifiedRows = $this->generator->generate($this->singleRow);

        $this->assertCount(5, $modifiedRows);
        $this->assertArrayHasKey('station_1', $modifiedRows);
        $this->assertArrayHasKey('station_2', $modifiedRows);
        $this->assertArrayHasKey('station_3', $modifiedRows);
        $this->assertArrayHasKey('station_4', $modifiedRows);
        $this->assertArrayHasKey('station_5', $modifiedRows);

        $this->assertEquals($modifiedRows['station_1'], $this->singleRow['station_[1..5]']);
        $this->assertEquals($modifiedRows['station_2'], $this->singleRow['station_[1..5]']);
        $this->assertEquals($modifiedRows['station_3'], $this->singleRow['station_[1..5]']);
        $this->assertEquals($modifiedRows['station_4'], $this->singleRow['station_[1..5]']);
        $this->assertEquals($modifiedRows['station_5'], $this->singleRow['station_[1..5]']);
    }

    /** @test */
    function it_generates_rows_for_several_entries_with_range_definitions()
    {
        $modifiedRows = $this->generator->generate($this->severalRows);

        $this->assertCount(5, $modifiedRows);
        $this->assertArrayHasKey('station_1', $modifiedRows);
        $this->assertArrayHasKey('station_2', $modifiedRows);
        $this->assertArrayHasKey('station_3', $modifiedRows);
        $this->assertArrayHasKey('station_4', $modifiedRows);
        $this->assertArrayHasKey('station_5', $modifiedRows);

        $this->assertEquals($modifiedRows['station_1'], $this->severalRows['station_[1..3]']);
        $this->assertEquals($modifiedRows['station_2'], $this->severalRows['station_[1..3]']);
        $this->assertEquals($modifiedRows['station_3'], $this->severalRows['station_[1..3]']);
        $this->assertEquals($modifiedRows['station_4'], $this->severalRows['station_[4..5]']);
        $this->assertEquals($modifiedRows['station_5'], $this->severalRows['station_[4..5]']);
    }

    /** @before */
    function configureGeneration()
    {
        $this->generator = new RangeGenerator();
        $this->rows = [
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
        $this->singleRow = [
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
        $this->severalRows = [
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
    }

    /** @var RangeGenerator */
    private $generator;

    /** @var array */
    private $rows;

    /** @var array */
    private $singleRow;
}
