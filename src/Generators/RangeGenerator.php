<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Generators;

class RangeGenerator implements Generator
{
    /**
     * If any of the rows contain a range definition (`[start..end]`) it will generate the amount
     * of rows defined by the range
     *
     * It will ignore rows without range definitions
     *
     * @throws InvalidRange
     */
    public function generate(array $rows): array
    {
        $generatedRows = [];
        foreach ($rows as $identifier => $row) {
            $generatedRows = $this->generateRowsIfNeeded($identifier, $row, $generatedRows);
        }
        return $generatedRows;
    }

    /** @throws InvalidRange */
    private function generateRowsIfNeeded(string $identifier, array $row, array $rows): array
    {
        if (Range::isRange($identifier)) {
            $rows += Range::from($identifier)->generate($row, $identifier);
        } else {
            $rows[$identifier] = $row;
        }
        return $rows;
    }
}
