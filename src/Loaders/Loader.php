<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Loaders;

/**
 * Reads a file and converts it into array so that it can be sent to a database
 */
interface Loader
{
    public function load(string $path): array;
}
