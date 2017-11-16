<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures;

use ComPHPPuebla\Fixtures\Database\Row;
use ComPHPPuebla\Fixtures\Generators\RangeGenerator;
use ComPHPPuebla\Fixtures\Loaders\Loader;
use ComPHPPuebla\Fixtures\Database\Connection;
use ComPHPPuebla\Fixtures\Generators\Generator;
use ComPHPPuebla\Fixtures\Loaders\YamlLoader;
use ComPHPPuebla\Fixtures\Processors\FakerProcessor;
use ComPHPPuebla\Fixtures\Processors\ForeignKeyProcessor;
use ComPHPPuebla\Fixtures\Processors\Processor;
use Faker\Factory;

class Fixture
{
    /** @var Loader */
    private $loader;

    /** @var Generator */
    private $generator;

    /** @var Processor[] */
    private $processors;

    /** @var Connection */
    private $connection;

    /** @var array */
    private $rows = [];

    public function __construct(
        Connection $connection,
        Loader $loader = null,
        Generator $generator = null,
        array $processors = []
    )
    {
        $this->connection = $connection;
        $this->loader = $loader ?? new YamlLoader();
        $this->generator = $generator ?? new RangeGenerator();
        $this->setProcessors($processors);
    }

    public function rows(): array
    {
        return $this->rows;
    }

    public function load(string $pathToFixturesFile): void
    {
        $tables = $this->loader->load($pathToFixturesFile);

        foreach ($tables as $table => $rows) {
            $this->processTableRows($table, $rows);
        }
    }

    private function processTableRows(string $table, array $rows): void
    {
        $primaryKey = $this->connection->getPrimaryKeyOf($table);
        $generatedRows = $this->generator->generate($rows);
        foreach ($generatedRows as $identifier => $row) {
            $this->processRow($table, new Row($primaryKey, $identifier, $row));
        }
    }

    private function processRow(string $table, Row $row): void
    {
        foreach ($this->processors as $processor) {
            $processor->process($row);
        }

        $this->connection->insert($table, $row);
        $this->rows[$row->identifier()] = $row->values();

        foreach ($this->processors as $processor) {
            $processor->postProcessing($row);
        }
    }

    /**
     * @param Processor[] $processors
     */
    private function setProcessors(array $processors): void
    {
        $this->processors = empty($processors) ? [
            new ForeignKeyProcessor(),
            new FakerProcessor(Factory::create())
        ] : $processors;
    }
}
