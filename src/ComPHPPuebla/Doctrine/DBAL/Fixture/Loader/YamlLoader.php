<?php
namespace ComPHPPuebla\Doctrine\DBAL\Fixture\Loader;

use \Zend\Config\Reader\Yaml as YamlReader;

class YamlLoader implements Loader
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var \Zend\Config\Reader\Yaml
     */
    protected $reader;

    public function __construct($path, YamlReader $reader = null)
    {
        $this->path = $path;
        $this->reader = $reader;
    }

    public function load()
    {
        if (!$this->reader) {
            $this->reader = new YamlReader(['Spyc','YAMLLoadString']);
        }

        return $this->reader->fromFile($this->path);
    }
}
