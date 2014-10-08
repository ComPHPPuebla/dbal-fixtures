<?php
namespace ComPHPPuebla\Doctrine\DBAL\Fixture\Loader;

use Symfony\Component\Yaml\Parser;

class YamlLoader implements Loader
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var \Symfony\Component\Yaml\Parser
     */
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
