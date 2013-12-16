<?php
namespace ComPHPPuebla\Doctrine\DBAL\Fixture\Loader;

interface Loader
{
    /**
     * @return array
     */
    public function load();
}
