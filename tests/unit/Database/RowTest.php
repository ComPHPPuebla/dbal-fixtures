<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Database;

use PHPUnit\Framework\TestCase;

class RowTest extends TestCase
{
    /** @test */
    function it_gets_assigned_an_id()
    {
        $row = new Row('id', '', [] );
        $id = 1;

        $row->assignId($id);

        $this->assertEquals($id, $row->id());
    }

    /** @test */
    function it_does_not_override_its_id()
    {
        $existingId = 10;
        $ignoredId = 1;
        $row = new Row('id', '', ['id' => $existingId] );

        $row->assignId($ignoredId);

        $this->assertEquals($existingId, $row->id());
    }

    /** @test */
    function it_has_access_to_the_column_values()
    {
        $originalValues = [
            'column_name_1' => 'value_1',
            'column_name_2' => 'value_2',
            'column_name_3' => 'value_3',
        ];
        $row = new Row('', '', $originalValues);

        $rowValues = $row->values();

        $this->assertEquals($originalValues, $rowValues);
    }

    /** @test */
    function it_knows_its_identifier()
    {
        $identifier = 'station_1';
        $row = new Row('', $identifier, [] );

        $rowIdentifier = $row->identifier();

        $this->assertEquals($identifier, $rowIdentifier);
    }

    /** @test */
    function it_changes_the_value_of_a_column()
    {
        $row = new Row('', '', [
            'column_name' => 'old_column_name',
        ] );

        $row->changeColumnValue('column_name', 'new_column_value');

        $this->assertEquals('new_column_value', $row->values()['column_name']);
    }

    /** @test */
    function it_gets_the_value_of_a_specific_column()
    {
        $value = 'specific_value';
        $row = new Row('', '', [
            'specific_column' => $value,
        ] );

        $rowValue = $row->valueOf('specific_column');

        $this->assertEquals($value, $rowValue);
    }
}
