<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Processors;

use ComPHPPuebla\Fixtures\Database\Row;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class FakerProcessorTest extends TestCase
{
    /** @before */
    function createProcessor()
    {
        $this->generator = $this->prophesize(Generator::class);
        $this->processor = new FakerProcessor($this->generator->reveal());
    }

    /** @test */
    function it_does_not_process_a_row_without_faker_formatter_definitions()
    {
        $row = new Row('', '', ['first_name' => 'No formatter here']);

        $this->processor->beforeInsert($row);

        $this->generator->format(Argument::any(), Argument::any())->shouldNotHaveBeenCalled();
    }

    /** @test */
    function it_process_a_row_with_a_single_faker_formatter()
    {
        $row = new Row('', '', ['first_name' => '${firstName}']);

        $this->processor->beforeInsert($row);

        $this->generator->format('firstName', [])->shouldHaveBeenCalled();
    }

    /** @test */
    function it_process_a_row_with_2_formatters_within_a_function()
    {
        $row = new Row('', '', [
            'coordinates' => '`PointFromText(\'POINT(${latitude} ${longitude})\')`'
        ]);
        $this->generator->format('latitude', [])->willReturn(51.8939035);
        $this->generator->format('longitude', [])->willReturn(4.5231352);

        $this->processor->beforeInsert($row);

        $this->assertEquals('`PointFromText(\'POINT(51.8939035 4.5231352)\')`', $row->valueOf('coordinates'));
    }

    /** @test */
    function it_process_a_row_with_several_faker_formatters()
    {
        $row = new Row('', '', [
            'first_name' => '${firstName}',
            'title' => '${title(\'female\')}',
            'image' => '${imageUrl(100, 200, \'dogs\')}'
        ]);

        $this->processor->beforeInsert($row);

        $this->generator->format('firstName', [])->shouldHaveBeenCalled();
        $this->generator->format('title', ['female'])->shouldHaveBeenCalled();
        $this->generator->format('imageUrl', [100, 200, 'dogs'])->shouldHaveBeenCalled();
    }

    /** @var FakerProcessor */
    private $processor;

    /** @var Generator */
    private $generator;
}
