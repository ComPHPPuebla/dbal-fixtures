<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Processors;

use Faker\Generator;
use PHPUnit\Framework\TestCase;

class FormatterCallTest extends TestCase
{
    /** @before */
    function createFormatter()
    {
        $this->generator = $this->prophesize(Generator::class);
    }

    /** @test */
    function it_does_not_match_a_null_value()
    {
        $isAFormatter = FormatterCall::matches(null);

        $this->assertFalse($isAFormatter);
    }

    /** @test */
    function it_matches_a_formatter_without_arguments()
    {
        $isAFormatter = FormatterCall::matches('${firstName}');

        $this->assertTrue($isAFormatter);
    }

    /** @test */
    function it_matches_a_formatter_with_one_argument()
    {
        $isAFormatter = FormatterCall::matches('${title(\'female\')}');

        $this->assertTrue($isAFormatter);
    }

    /** @test */
    function it_matches_a_formatter_with_several_arguments()
    {
        $isAFormatter = FormatterCall::matches('${imageUrl(100, 200, \'dogs\')}');

        $this->assertTrue($isAFormatter);
    }

    /** @test */
    function it_calls_a_faker_formatter_without_arguments()
    {
        $call = FormatterCall::from('${firstName}');

        $call->run($this->generator->reveal());

        $this->generator->format('firstName', [])->shouldHaveBeenCalled();
    }

    /** @test */
    function it_calls_a_faker_formatter_with_one_argument()
    {
        $call = FormatterCall::from('${title(\'female\')}');

        $call->run($this->generator->reveal());

        $this->generator->format('title', ['female'])->shouldHaveBeenCalled();
    }

    /** @test */
    function it_calls_a_faker_formatter_with_several_arguments()
    {
        $call = FormatterCall::from('${imageUrl(100, 200, \'dogs\')}');

        $call->run($this->generator->reveal());

        $this->generator->format('imageUrl', [100, 200, 'dogs'])->shouldHaveBeenCalled();
    }

    /** @test */
    function it_calls_2_faker_formatters()
    {
        $value = '`PointFromText(\'POINT(${latitude} ${longitude})\')`';

        $this->generator->format('latitude', [])->willReturn(51.8939035);
        $this->generator->format('longitude', [])->willReturn(4.5231352);

        $call = FormatterCall::from($value);
        $value = $call->run($this->generator->reveal());

        $call = FormatterCall::from($value);
        $value = $call->run($this->generator->reveal());

        $this->assertEquals('`PointFromText(\'POINT(51.8939035 4.5231352)\')`', $value);
    }

    /** @var Generator */
    private $generator;
}
