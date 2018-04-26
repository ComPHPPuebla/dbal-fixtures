<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace ComPHPPuebla\Fixtures\Database;

use PHPUnit\Framework\TestCase;
use Doctrine\DBAL\Connection;
use Prophecy\Argument;

class InsertTest extends TestCase
{
    /** @var Connection */
    private $connection;

    /** @before */
    function configureConnection()
    {
        $this->connection = $this->prophesize(Connection::class);
        $this->connection->quoteIdentifier(Argument::any())->will(function ($value) {
            return "`$value[0]`";
        });
    }

    /** @test */
    function it_converts_to_sql_an_insert_with_null_values()
    {
        $insert = Insert::into('roles', new Row('id', 1, [
            'name' => 'admin',
            'parent_role' => null,
        ]));

        $sql = $insert->toSQL($this->connection->reveal());

        $this->assertEquals(
            'INSERT INTO roles (`name`, `parent_role`) VALUES (?, null)',
            $sql
        );
        $this->assertCount(1, $insert->parameters());
        $this->assertEquals('admin', $insert->parameters()[0]);
    }

    /** @test */
    function it_converts_to_sql_an_insert_with_a_function_call_value()
    {
        $insert = Insert::into('reviews', new Row('id', 1, [
            'content' => 'Excellent!',
            'date' => '`CURDATE()`',
        ]));

        $sql = $insert->toSQL($this->connection->reveal());

        $this->assertEquals(
            'INSERT INTO reviews (`content`, `date`) VALUES (?, CURDATE())',
            $sql
        );
        $this->assertCount(1, $insert->parameters());
        $this->assertEquals('Excellent!', $insert->parameters()[0]);
    }

    /** @test */
    function it_converts_to_sql_an_insert_with_numeric_values()
    {
        $insert = Insert::into('reviews', new Row('id', 1, [
            'content' => 'Excellent!',
            'date' => '`CURDATE()`',
            'rating' =>  5,
        ]));

        $sql = $insert->toSQL($this->connection->reveal());

        $this->assertEquals(
            'INSERT INTO reviews (`content`, `date`, `rating`) VALUES (?, CURDATE(), ?)',
            $sql
        );
        $this->assertCount(2, $insert->parameters());
        $this->assertEquals('Excellent!', $insert->parameters()[0]);
        $this->assertEquals(5, $insert->parameters()[1]);
    }
}
