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
     * @param int    $id
     */
    public function addReference($key, $id)
    {
        $this->references[$key] = $id;
    }

    /**
     * @param  array $values
     * @return array
     */
    public function parse(array $values)
    {
        foreach ($values as $column => $value) {
            if ('@' === $value[0] && isset($this->references[substr($value, 1)])) {
                $values[$column] = $this->references[substr($value, 1)];
            }
        }

        return $values;
    }
}
