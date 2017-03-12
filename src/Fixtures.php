<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla;

use ComPHPPuebla\Generators\RangeGenerator;
use ComPHPPuebla\Loader\Loader;
use ComPHPPuebla\Connections\Connection;
use ComPHPPuebla\Generators\Generator;
use ComPHPPuebla\Loader\YamlLoader;
use ComPHPPuebla\Processors\FakerProcessor;
use ComPHPPuebla\Processors\ForeignKeyProcessor;
use ComPHPPuebla\Processors\Processor;
use Faker\Factory;

class Fixtures
{
    /** @var Loader */
    private $loader;

    /** @var Generator */
    private $generator;

    /** @var Processor[] */
    private $processors;

    /** @var Connection */
    private $connection;

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

    public function load($pathToFixturesFile): void
    {
        $tables = $this->loader->load($pathToFixturesFile);

        foreach ($tables as $table => $rows) {
            $generatedRows = $this->generator->generate($rows);
            foreach ($generatedRows as $key => $row) {
                foreach ($this->processors as $processor) {
                    $row = $processor->process($row);
                }
                $id = $this->connection->insert($table, $row);
                foreach ($this->processors as $processor) {
                    $processor->postProcessing($key, $id);
                }
            }
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
