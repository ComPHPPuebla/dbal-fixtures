<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Connections;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class DBALConnectionTest extends TestCase
{
    /** @test */
    function it_inserts_a_row_into_a_given_table()
    {
        $dbalConnection = $this->prophesize(Connection::class);
        $dbalConnection->lastInsertId()->willReturn(1);
        $dbalConnection->quoteIdentifier(Argument::type('string'))->will(function ($args, $connection) {
            $quotedColumn = "\"{$args[0]}\"";
            $connection->quoteIdentifier($args[0])->willReturn($quotedColumn);
            return $quotedColumn;
        });
        $dbalConnection->insert('stations', Argument::type('array'))->shouldBeCalled();
        $connection = new DBALConnection($dbalConnection->reveal());

        $newId = $connection->insert('stations', [
            'comment' => 'El servicio es excelente',
            'stars' => 5,
            'station_id' => '1',
        ]);

        $this->assertEquals(1, $newId);
    }
}
