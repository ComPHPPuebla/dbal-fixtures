<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Generators;

class Range
{
    private const RANGE_REGEXP = '/\[(\d*)\.\.(\d+)\]/i';

    /** @var string */
    private $expression;

    /** @var int */
    private $start;

    /** @var int */
    private $end;

    public function expression(): string
    {
        return $this->expression;
    }

    public function generate(): array
    {
        return range($this->start, $this->end);
    }

    public static function from($text): Range
    {
        $matches = [];
        preg_match(self::RANGE_REGEXP, $text, $matches);
        [$expression, $start, $end] = $matches;

        return new Range($expression, $start, $end);
    }

    public static function isRange(string $expression): bool
    {
        return 1 === preg_match(self::RANGE_REGEXP, $expression);
    }

    private function __construct(string $expression, int $start, int $end)
    {
        $this->expression = $expression;
        $this->setRange($start, $end);
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
