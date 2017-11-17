<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Processors;

use ComPHPPuebla\Fixtures\Database\Row;

/**
 * Keeps a map of database IDs and identifiers in order to replace references `@identifier` with
 * real database IDs
 *
 * - It saves all ke-value pairs `identifiers -> IDs` in the `postInsert` method
 * - It replaces al references to identifiers `@identifiers` with the real IDs in the `preInsert`
 *   method
 */
class ForeignKeyProcessor implements PreProcessor, PostProcessor
{
    /** @var array */
    protected $references;

    public function __construct()
    {
        $this->references = [];
    }

    /**
     * If one of the column values is a reference `@identifier` it replaces it with the real
     * database ID
     *
     * It ignores any column without a reference
     */
    public function beforeInsert(Row $row): void
    {
        foreach ($row->values() as $column => $value) {
            if (null !== $value) {
                $row->changeColumnValue($column, $this->parseKeyIfNeeded($value));
            }
        }
    }

    /**
     * It saves the key-value pair `@identfier -> ID` for this row
     *
     * @see ForeignKeyProcessor#addReference
     */
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
