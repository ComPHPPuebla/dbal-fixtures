<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Database;

use ComPHPPuebla\Fixtures\ProvidesConnection;
use PHPUnit\Framework\TestCase;

class DBALConnectionTest extends TestCase
{
    use ProvidesConnection;

    /** @test */
    function it_inserts_a_row_into_a_given_table()
    {
        $connection = new DBALConnection($this->connection);
        $values = [
            'name' => 'CASMEN GASOL',
            'social_reason' => 'CASMEN SA CV',
            'address_line_1' => '23 PTE NO 711',
            'address_line_2' => 'EL CARMEN',
            'location' => 'PUEBLA PUE',
            'latitude' => 19.03817,
            'longitude' => -98.20737,
            'created_at' => '2013-10-06 00:00:00',
            'last_updated_at' => '2013-10-06 00:00:00',
        ];
        $station = new Row('id', 'station_1', $values);

        $connection->insert('stations', $station);

        $this->assertArrayHasKey('id', $station->values());
        $this->assertInternalType('numeric', $station->id());
        $this->assertArraySubset($values, $station->values());
    }

    /** @test */
    function it_gets_the_primary_key_column_of_a_given_table()
    {
        $connection = new DBALConnection($this->connection);

        $primaryKeyColumn = $connection->primaryKeyOf('states');

        $this->assertEquals('url', $primaryKeyColumn);
    }
}
