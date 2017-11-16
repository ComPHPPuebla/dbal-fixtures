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

class FakerProcessorTest extends TestCase
{
    /** @test */
    function it_process_a_row_with_a_formatter_with_no_arguments()
    {
        $generator = $this->prophesize(Generator::class);
        $processor = new FakerProcessor($generator->reveal());
        $row = new Row('', '', ['first_name' => '${firstName}']);

        $processor->beforeInsert($row);

        $generator->format('firstName')->shouldHaveBeenCalled();
    }

    /** @test */
    function it_process_a_row_with_a_formatter_with_one_argument()
    {
        $generator = $this->prophesize(Generator::class);
        $processor = new FakerProcessor($generator->reveal());
        $row = new Row('', '', ['title' => '${title(\'female\')}']);

        $processor->beforeInsert($row);

        $generator->format('title', ['female'])->shouldHaveBeenCalled();
    }

    /** @test */
    function it_process_a_row_with_a_formatter_with_several_arguments()
    {
        $generator = $this->prophesize(Generator::class);
        $processor = new FakerProcessor($generator->reveal());
        $row = new Row('', '', ['image' => '${imageUrl(100, 200, \'dogs\')}']);

        $processor->beforeInsert($row);

        $generator->format('imageUrl', [100, 200, 'dogs'])->shouldHaveBeenCalled();
    }

    /** @test */
    function it_process_several_rows_with_several_formatters_with_several_arguments()
    {
        $generator = $this->prophesize(Generator::class);
        $processor = new FakerProcessor($generator->reveal());
        $row = new Row('', '', [
            'first_name' => '${firstName}',
            'title' => '${title(\'female\')}',
            'image' => '${imageUrl(100, 200, \'dogs\')}'
        ]);

        $processor->beforeInsert($row);

        $generator->format('firstName')->shouldHaveBeenCalled();
        $generator->format('title', ['female'])->shouldHaveBeenCalled();
        $generator->format('imageUrl', [100, 200, 'dogs'])->shouldHaveBeenCalled();
    }
}
