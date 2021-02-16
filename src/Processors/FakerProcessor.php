<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Processors;

use ComPHPPuebla\Fixtures\Database\Row;
use Faker\Generator;

class FakerProcessor implements PreProcessor
{
    /** @var Generator */
    private $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * It will generate fake data for every column with a formatter definition
     * `${formatter(arg_1...arg_n)}`
     */
    public function beforeInsert(Row $row): void
    {
        foreach ($row->values() as $column => $value) {
            $this->generateFakeDataIfNeeded($row, $column, $value);
        }
    }

    private function generateFakeDataIfNeeded(Row $row, string $column, ?string $value): void
    {
        if(!is_scalar($value)) return;
        $formatted = $value;
        while (FormatterCall::matches($formatted)) {
            $formatted = FormatterCall::from($formatted)->run($this->generator);
        }

        $row->changeColumnValue($column, $formatted);
    }
}
