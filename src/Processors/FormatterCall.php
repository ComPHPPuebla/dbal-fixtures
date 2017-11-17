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

    /** @var string */
    private $formatter;

    /** @var string */
    private $arguments;

    public function run(Generator $generator)
    {
        return $generator->format($this->formatter, $this->parseArguments());
    }

    public static function matches(?string $value): bool
    {
        return $value !== null && 1 === preg_match(self::FORMATTER_PATTERN, $value);
    }

    public static function from(string $definition): FormatterCall
    {
        return new FormatterCall($definition);
    }

    public function __construct(string $definition)
    {
        [$this->formatter, $this->arguments] = $this->parseDefinition($definition);
    }

    private function parseDefinition(string $definition): array
    {
        $callDefinition = [];
        preg_match(self::FORMATTER_PATTERN, $definition, $callDefinition);

        array_shift($callDefinition);

        if (\count($callDefinition) === 1) {
            $callDefinition[1] = '';
        }

        return $callDefinition;
    }

    private function parseArguments(): array
    {
        $arguments = explode(',', $this->arguments);
        $excludeEmptyStrings = array_filter($arguments);
        $trimmedArguments = array_map('trim', $excludeEmptyStrings);

        return array_map(
            function ($argument) {
                $argumentWithoutQuotes = str_replace(['\'', '"'], '', $argument);
                return $argumentWithoutQuotes;
            },
            $trimmedArguments
        );
    }
}
