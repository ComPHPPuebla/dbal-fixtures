<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Processors;

use Faker\Generator;

class FormatterCall
{
    private const FORMATTER_PATTERN = '/\$\{(\w+)(?:\(([^\)]+)\))?\}/i';

    /** @var string Original value */
    private $definition;

    /** @var string Original patter ${formatter(a1, a2, ... , an)} */
    private $call;

    /** @var string */
    private $formatter;

    /** @var string */
    private $arguments;

    /**
     * It calls the formatter, on the given generator, with the arguments defined in this call object
     */
    public function run(Generator $generator): string
    {
        return str_replace(
            $this->call,
            $generator->format($this->formatter, $this->parseArguments()),
            $this->definition
        );
    }

    /**
     * A valid formatter definition matches the pattern `${formatter(arg_1..arg_n)}`
     */
    public static function matches(?string $value): bool
    {
        return $value !== null && 1 === preg_match(self::FORMATTER_PATTERN, $value);
    }

    public static function from(string $definition): FormatterCall
    {
        return new FormatterCall($definition);
    }

    private function __construct(string $definition)
    {
        $this->definition = $definition;
        [$this->call, $this->formatter, $this->arguments] = $this->parseDefinition($definition);
    }

    private function parseDefinition(string $definition): array
    {
        $callDefinition = [];
        preg_match(self::FORMATTER_PATTERN, $definition, $callDefinition);

        if (\count($callDefinition) === 2) {
            $callDefinition[2] = '';
        }

        return $callDefinition;
    }

    /**
     * The parsing arguments process can be described as follows
     *
     * - Split the list of arguments `arg_1,...,arg_n` using the comma as delimiter
     * - Trim the individual argument values
     * - Remove the quotes from the arguments' values if present
     */
    private function parseArguments(): array
    {
        if (empty($this->arguments)) {
            return [];
        }

        $arguments = explode(',', $this->arguments);
        $trimmedArguments = array_map('trim', $arguments);
        return array_map(
            function ($argument) {
                $argumentWithoutQuotes = str_replace(['\'', '"'], '', $argument);
                return $argumentWithoutQuotes;
            },
            $trimmedArguments
        );
    }
}
