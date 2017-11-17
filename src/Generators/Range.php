<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Generators;

class Range
{
    private const RANGE_PATTERN = '/\[(\d*)\.\.(\d+)\]/i';

    /** @var string */
    private $definition;

    /** @var int */
    private $start;

    /** @var int */
    private $end;

    public static function isRange(string $definition): bool
    {
        return 1 === preg_match(self::RANGE_PATTERN, $definition);
    }

    /**
     * Create a range from a definition with the form "[start..end]"
     *
     * @throws InvalidRange If the final value is not greater than the initial value
     */
    public static function from(string $text): Range
    {
        $matches = [];
        preg_match(self::RANGE_PATTERN, $text, $matches);
        [$definition, $start, $end] = $matches;

        return new Range($definition, $start, $end);
    }

    public function generate(array $row, string $rangeIdentifier): array
    {
        $generatedRows = [];
        foreach (range($this->start, $this->end) as $i) {
            $generatedRows[$this->buildRowIdentifier($rangeIdentifier, $i)] = $row;
        }
        return $generatedRows;
    }

    private function __construct(string $expression, int $start, int $end)
    {
        $this->setRange($start, $end);
        $this->definition = $expression;
    }

    /**
     * @throws InvalidRange
     */
    private function setRange(int $start, int $end): void
    {
        if ($start > $end) throw InvalidRange::withValues($start, $end);

        $this->start = $start;
        $this->end = $end;
    }

    private function buildRowIdentifier(string $identifier, int $i): string
    {
        return str_replace($this->definition, $i, $identifier);
    }
}
