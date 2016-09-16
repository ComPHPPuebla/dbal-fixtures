<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\DBAL\Fixture\Loader;

use Symfony\Component\Yaml\Parser;

class YamlLoader implements Loader
{
    /** @var string */
    protected $path;

    /** @var Parser */
    protected $reader;

    public function __construct($path, Parser $reader = null)
    {
        $this->path = $path;
        $this->reader = $reader;
    }

    public function load()
    {
        if (!$this->reader) {
            $this->reader = new Parser();
        }

        return $this->reader->parse(file_get_contents($this->path));
    }
}
