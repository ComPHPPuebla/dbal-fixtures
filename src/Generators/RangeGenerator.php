<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Generators;

class RangeGenerator implements Generator
{
    private $rangeRegExp = '/\[(\d*)\.\.(\d+)\]/i';

    public function generate(array $rows): array
    {
        $modifiedRows = [];
        foreach ($rows as $key => $row) {
            if ($this->isRange($key)) {
                [$range, $start, $end] = $this->getRangeFrom($key);
                $generatedRows = $this->generateRows($range, $start, $end, $key, $row);
                $modifiedRows += $generatedRows;
                continue;
            }
            $modifiedRows[$key] = $rows[$key];
        }

        return $modifiedRows;
    }

    private function isRange(string $key): bool
    {
        return 1 === preg_match($this->rangeRegExp, $key);
    }

    private function getRangeFrom($key)
    {
        $matches = [];
        preg_match($this->rangeRegExp, $key, $matches);

        return $matches;
    }

    private function generateRows(string $range, int $start, int $end, string $key, array $row)
    {
        $generatedRows = [];

        foreach (range($start, $end) as $current) {
            $generatedRows[str_replace($range, $current, $key)] = $row;
        }

        return $generatedRows;
    }
}
