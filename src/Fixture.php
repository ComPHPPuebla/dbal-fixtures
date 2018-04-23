<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures;

use ComPHPPuebla\Fixtures\Database\Connection;
use ComPHPPuebla\Fixtures\Database\Row;
use ComPHPPuebla\Fixtures\Generators\Generator;
use ComPHPPuebla\Fixtures\Generators\RangeGenerator;
use ComPHPPuebla\Fixtures\Loaders\Loader;
use ComPHPPuebla\Fixtures\Loaders\YamlLoader;
use ComPHPPuebla\Fixtures\Processors\FakerProcessor;
use ComPHPPuebla\Fixtures\Processors\ForeignKeyProcessor;
use ComPHPPuebla\Fixtures\Processors\PostProcessor;
use ComPHPPuebla\Fixtures\Processors\PreProcessor;
use Faker\Factory;

class Fixture
{
    /** @var Loader */
    private $loader;

    /** @var Generator */
    private $generator;

    /** @var PreProcessor[] */
    private $preProcessors;

    /** @var PostProcessor[] */
    private $postProcessors;

    /** @var Connection */
    private $connection;

    /** @var array */
    private $rows = [];

    public function __construct(
        Connection $connection,
        Loader $loader = null,
        Generator $generator = null,
        array $preProcessors = [],
        array $postProcessors = []
    )
    {
        $this->connection = $connection;
        $this->loader = $loader ?? new YamlLoader();
        $this->generator = $generator ?? new RangeGenerator();
        $this->setProcessors($preProcessors, $postProcessors);
    }

    public function rows(): array
    {
        return $this->rows;
    }

    /**
     * Parse the fixture file and insert the rows in the configured database
     *
     * The process to parse a fixture file is as follows
     *
     * 1. It converts the content of the file to an associative array
     * 2. It process the rows for every table
     *   a. It generates the rows if the row identifier is a range definition
     *   b. It runs the pre-processors for each row
     *      * The foreign key processor replaces any reference `@reference` with a real database ID
     *      * The faker processor will replace any formatter `formatter(arg_1..arg_n)` with fake
     *        data
     *   c. It inserts the row in the database and assigns the auto generated ID to the row if any
     *   d. It runs the post-processors for each row
     *      * The foreign key processor will save the generated database ID for the rows that might
     *        use it as reference, in the pre-insert face
     */
    public function load(string $pathToFixturesFile): void
    {
        $tables = $this->loader->load($pathToFixturesFile);

        foreach ($tables as $table => $rows) {
            $this->processTableRows($table, $rows);
        }
    }

    private function processTableRows(string $table, array $rows): void
    {
        $primaryKey = $this->connection->primaryKeyOf($table);
        $generatedRows = $this->generator->generate($rows);
        foreach ($generatedRows as $identifier => $row) {
            $this->processRow($table, new Row($primaryKey, $identifier, $row));
        }
    }

    private function processRow(string $table, Row $row): void
    {
        foreach ($this->preProcessors as $processor) {
            $processor->beforeInsert($row);
        }

        $this->connection->insert($table, $row);
        $this->rows[$row->identifier()] = $row->values();

        foreach ($this->postProcessors as $processor) {
            $processor->afterInsert($row);
        }
    }

    /**
     * @param PreProcessor[] $preProcessors
     * @param PostProcessor[] $postProcessors
     */
    private function setProcessors(array $preProcessors, array $postProcessors): void
    {
        $foreignKeyProcessor = new ForeignKeyProcessor();
        $this->preProcessors = array_merge($preProcessors, [
            $foreignKeyProcessor,
            new FakerProcessor(Factory::create())
        ]);
        $this->postProcessors = array_merge($postProcessors, [
            $foreignKeyProcessor,
        ]);
    }
}
