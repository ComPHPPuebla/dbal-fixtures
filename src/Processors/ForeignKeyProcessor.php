<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Processors;

class ForeignKeyProcessor implements Processor
{
    /** @var array */
    protected $references;

    public function __construct()
    {
        $this->references = [];
    }

    public function process(array $row): array
    {
        $processedRows = [];
        foreach ($row as $column => $value) {
            if (null === $value) {
                $processedRows[$column] = $value;
                continue;
            }
            $processedRows[$column] = $this->parseKeyIfNeeded($value);
        }
        return $processedRows;
    }

    public function postProcessing(string $key, int $id): void
    {
        $this->addReference($key, $id);
    }

    private function addReference(string $key, int $id)
    {
        $this->references[$key] = $id;
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
        if (empty($value)) {
            return false;
        }

        return '@' === $value[0];
    }

    private function referenceExistsFor(string $value): bool
    {
        return isset($this->references[substr($value, 1)]);
    }
}
