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
    private $expression;

    /** @var int */
    private $start;

    /** @var int */
    private $end;

    /**
     * Create a range from an expression with the form "[start..end]"
     *
     * @throws InvalidRange If the final value is not greater than the initial value
     */
    public static function from(string $text): Range
    {
        $matches = [];
        preg_match(self::RANGE_PATTERN, $text, $matches);
        [$expression, $start, $end] = $matches;

        return new Range($expression, $start, $end);
    }

    /**
     * The pattern used to create this range "[start..end]"
     *
     * This text representation is used to build the identifier for the row
     *
     * @see RangeGenerator#generateRows
     */
    public function expression(): string
    {
        return $this->expression;
    }

    public function generate(): array
    {
        return range($this->start, $this->end);
    }

    public static function isRange(string $expression): bool
    {
        return 1 === preg_match(self::RANGE_PATTERN, $expression);
    }

    private function __construct(string $expression, int $start, int $end)
    {
        $this->setRange($start, $end);
        $this->expression = $expression;
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
}
