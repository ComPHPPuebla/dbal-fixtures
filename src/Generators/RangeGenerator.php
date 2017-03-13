<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Generators;

class RangeGenerator implements Generator
{
    public function generate(array $rows): array
    {
        $modifiedRows = [];
        foreach ($rows as $key => $row) {
            if (Range::isRange($key)) {
                $generatedRows = $this->generateRows(Range::from($key), $key, $row);
                $modifiedRows += $generatedRows;
                continue;
            }
            $modifiedRows[$key] = $rows[$key];
        }

        return $modifiedRows;
    }

    private function generateRows(Range $range, string $key, array $row)
    {
        $generatedRows = [];
        foreach ($range->generate() as $current) {
            $generatedKey = str_replace($range->expression(), $current, $key);
            $generatedRows[$generatedKey] = $row;
        }
        return $generatedRows;
    }
}
