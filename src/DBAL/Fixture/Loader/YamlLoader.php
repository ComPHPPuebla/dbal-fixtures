<?php
/**
 * PHP version 7.1
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
    /** @var Parser */
    protected $parser;

    public function __construct(Parser $parser = null)
    {
        $this->parser = $parser ?: new Parser();
    }

    /**
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public function load(string $path): array
    {
        return $this->parser->parse(file_get_contents($path));
    }
}
