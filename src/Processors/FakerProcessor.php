<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Processors;

use Faker\Generator;

class FakerProcessor
{
    private $formatterRegExp = '/\$\{(\w+)(?:\(([^\)]+)\))?\}/i';

    /** @var Generator */
    private $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function process(array $row): void
    {
        foreach ($row as $column => $value) {
            if ($this->isFakerFormatter($value)) {
                $row[$column] = $this->callFormatter($value);
            }
        }
    }

    private function isFakerFormatter(string $value): bool
    {
        return 1 === preg_match($this->formatterRegExp, $value);
    }

    /**
     * @return mixed
     */
    private function callFormatter(string $value)
    {
        $methodCall = [];
        preg_match($this->formatterRegExp, $value, $methodCall);

        if (count($methodCall) === 2) {
            return $this->callWithNoParameters($methodCall[1]);
        }

        return $this->call($methodCall);
    }

    /**
     * @return mixed
     */
    private function call(array $callDefinition)
    {
        [$_, $formatter, $arguments] = $callDefinition;

        return $this->generator->format(
            $formatter,
            $this->parseArguments($arguments)
        );
    }

    /**
     * @return mixed
     */
    private function callWithNoParameters(string $formatter)
    {
        return $this->generator->format($formatter);
    }

    private function parseArguments($arguments): array
    {
        return array_map(function ($argument) {
                return str_replace(['\'', '"'], '', $argument);
            },
            array_map('trim', explode(',', $arguments))
        );
    }
}
