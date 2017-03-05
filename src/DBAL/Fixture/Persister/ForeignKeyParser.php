<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\DBAL\Fixture\Persister;

class ForeignKeyParser
{
    /** @var array */
    protected $references;

    public function __construct()
    {
        $this->references = [];
    }

    public function addReference(string $key, int $id)
    {
        $this->references[$key] = $id;
    }

    public function parse(array $values): array
    {
        foreach ($values as $column => $value) {
            $values = $this->parseKeyIfNeeded($values, $value, $column);
        }
        return $values;
    }

    private function parseKeyIfNeeded(
        array $values,
        string $value,
        string $column
    ): array
    {
        if ($this->isAReference($value) && $this->referenceExistsFor($value)) {
            return $this->replaceReference($values, $value, $column);
        }
        return $values;
    }

    private function isAReference(string $value): bool
    {
        return '@' === $value[0];
    }

    private function referenceExistsFor(string $value): bool
    {
        return isset($this->references[substr($value, 1)]);
    }

    private function replaceReference(
        array $values,
        string $value,
        string $column
    ): array
    {
        $values[$column] = $this->references[substr($value, 1)];
        return $values;
    }
}
