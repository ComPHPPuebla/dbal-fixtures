<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Processors;

use Faker\Generator;
use PHPUnit_Framework_TestCase as TestCase;

class FakerProcessorTest extends TestCase
{
    /** @test */
    function it_process_a_row_with_a_formatter_with_no_arguments()
    {
        $generator = $this->prophesize(Generator::class);
        $processor = new FakerProcessor($generator->reveal());

        $processor->process(['first_name' => '${firstName}']);

        $generator->format('firstName')->shouldHaveBeenCalled();
    }

    /** @test */
    function it_process_a_row_with_a_formatter_with_one_argument()
    {
        $generator = $this->prophesize(Generator::class);
        $processor = new FakerProcessor($generator->reveal());

        $processor->process(['title' => '${title(\'female\')}']);

        $generator->format('title', ['female'])->shouldHaveBeenCalled();
    }

    /** @test */
    function it_process_a_row_with_a_formatter_with_several_arguments()
    {
        $generator = $this->prophesize(Generator::class);
        $processor = new FakerProcessor($generator->reveal());

        $processor->process(['image' => '${imageUrl(100, 200, \'dogs\')}']);

        $generator->format('imageUrl', [100, 200, 'dogs'])->shouldHaveBeenCalled();
    }

    /** @test */
    function it_process_several_rows_with_several_formatters_with_several_arguments()
    {
        $generator = $this->prophesize(Generator::class);
        $processor = new FakerProcessor($generator->reveal());

        $processor->process([
            'first_name' => '${firstName}',
            'title' => '${title(\'female\')}',
            'image' => '${imageUrl(100, 200, \'dogs\')}'
        ]);

        $generator->format('firstName')->shouldHaveBeenCalled();
        $generator->format('title', ['female'])->shouldHaveBeenCalled();
        $generator->format('imageUrl', [100, 200, 'dogs'])->shouldHaveBeenCalled();
    }
}
