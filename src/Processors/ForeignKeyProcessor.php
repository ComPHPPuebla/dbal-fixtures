<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Processors;

use ComPHPPuebla\Fixtures\Database\Row;

class ForeignKeyProcessor implements PreProcessor, PostProcessor
{
    /** @var array */
    protected $references;

    public function __construct()
    {
        $this->references = [];
    }

    public function beforeInsert(Row $row): void
    {
        foreach ($row->values() as $column => $value) {
            if (null !== $value) {
                $row->changeColumnValue($column, $this->parseKeyIfNeeded($value));
            }
        }
    }

    public function afterInsert(Row $row): void
    {
        $this->addReference($row);
    }

    public function addReference(Row $row)
    {
        $this->references[$row->identifier()] = $row->id();
    }

    /**
     * @return mixed
     */
    private function parseKeyIfNeeded(string $value)
    {
        if ($this->isAReference($value) && $this->referenceExistsFor($value)) {
            return $this->references[substr($value, 1)];
        }
        return $value;
    }

    private function isAReference(string $value): bool
    {
        return !empty($value) && '@' === $value[0];
    }

    private function referenceExistsFor(string $value): bool
    {
        return isset($this->references[substr($value, 1)]);
    }
}
