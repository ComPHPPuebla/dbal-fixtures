<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Processors;

use ComPHPPuebla\Fixtures\Connections\Row;

class ForeignKeyProcessor implements Processor
{
    /** @var array */
    protected $references;

    public function __construct()
    {
        $this->references = [];
    }

    public function process(Row $row): void
    {
        foreach ($row->values() as $column => $value) {
            if (null !== $value) {
                $row->changeColumnValue($column, $this->parseKeyIfNeeded($value));
            }
        }
    }

    public function postProcessing(Row $row): void
    {
        $this->addReference($row->identifier(), $row->id());
    }

    private function addReference(string $identifier, $id)
    {
        $this->references[$identifier] = $id;
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
