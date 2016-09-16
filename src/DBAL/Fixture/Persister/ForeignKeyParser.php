<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\DBAL\Fixture\Persister;

class ForeignKeyParser
{
    /**
     * @var array
     */
    protected $references;

    /**
     * Initialize references
     */
    public function __construct()
    {
        $this->references = [];
    }

    /**
     * @param string $key
     * @param int $id
     */
    public function addReference($key, $id)
    {
        $this->references[$key] = $id;
    }

    /**
     * @param array $values
     * @return array
     */
    public function parse(array $values)
    {
        foreach ($values as $column => $value) {
            $values = $this->parseKeyIfNeeded($values, $value, $column);
        }
        return $values;
    }

    /**
     * @param array $values
     * @param string $value
     * @param string $column
     * @return array
     */
    private function parseKeyIfNeeded(array $values, $value, $column)
    {
        if ($this->isAReference($value) && $this->referenceExistsFor($value)) {
            return $this->replaceReference($values, $value, $column);
        }
        return $values;
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isAReference($value)
    {
        return '@' === $value[0];
    }

    /**
     * @param string $value
     * @return bool
     */
    private function referenceExistsFor($value)
    {
        return isset($this->references[substr($value, 1)]);
    }

    /**
     * @param array $values
     * @param string $value
     * @param string $column
     * @return array
     */
    private function replaceReference(array $values, $value, $column)
    {
        $values[$column] = $this->references[substr($value, 1)];
        return $values;
    }
}
