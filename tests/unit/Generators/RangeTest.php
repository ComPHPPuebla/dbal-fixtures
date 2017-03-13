<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Generators;

use PHPUnit_Framework_TestCase as TestCase;

class RangeTest extends TestCase
{
    /** @test */
    function it_recognizes_a_range_expression()
    {
        $this->assertTrue(Range::isRange('[1..10]'));
    }

    /** @test */
    function it_creates_a_range_from_an_expression()
    {
        $range = Range::from('[1..10]');

        $this->assertInstanceOf(Range::class, $range);
        $this->assertCount(10, $range->generate());
        $this->assertEquals('[1..10]', $range->expression());
    }

    /** @test */
    function it_fails_to_create_an_invalid_range()
    {
        $this->expectException(InvalidRange::class);

        Range::from('[4..1]');
    }
}
