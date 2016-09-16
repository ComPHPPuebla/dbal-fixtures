<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\DBAL\Fixture\Loader;

use Symfony\Component\Yaml\Parser;

/**
 * Reads a .yml file and converts it to an associative array
 */
class YamlLoader implements Loader
{
    /** @var string */
    protected $path;

    /** @var Parser */
    protected $parser;

    /**
     * @param string $path
     * @param Parser|null $parser
     */
    public function __construct($path, Parser $parser = null)
    {
        $this->path = $path;
        $this->parser = $parser ?: new Parser();
    }

    /**
     * @return array
     */
    public function load()
    {
        return $this->parser->parse(file_get_contents($this->path));
    }
}
