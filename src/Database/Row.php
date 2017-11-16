<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
 namespace ComPHPPuebla\Fixtures\Database;

class Row
{
    /** @var string */
    private $primaryKeyColumn;

    /** @var string */
    private $identifier;

    /** @var array $values */
    private $values;

    public function __construct(string $primaryKeyColumn, string $identifier, array $values)
    {
        $this->primaryKeyColumn = $primaryKeyColumn;
        $this->identifier = $identifier;
        $this->values = $values;
    }

    public function assignId(int $id): void
    {
        if (isset($this->values[$this->primaryKeyColumn])) {
            return; // This is not an auto-generated key
        }

        $this->values[$this->primaryKeyColumn] = $id;
    }

    /**
     * @return mixed Most common types int (auto_increment) and string (uuid)
     */
    public function id()
    {
        return $this->values[$this->primaryKeyColumn];
    }

    public function values(): array
    {
        return $this->values;
    }

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function changeColumnValue(string $column, $value): void
    {
        $this->values[$column] = $value;
    }

    public function valueOf($column)
    {
        return $this->values[$column] ?? null;
    }
}