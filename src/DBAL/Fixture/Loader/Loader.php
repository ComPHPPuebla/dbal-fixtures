<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\DBAL\Fixture\Loader;

/**
 * Reads a file and converts it into array so that it can be sent to a database
 */
interface Loader
{
    /**
     * @return array
     */
    public function load();
}
