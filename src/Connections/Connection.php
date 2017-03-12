<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Connections;

/**
 * Insert the given rows to a database
 */
interface Connection
{
    public function insert(array $rows): void;
}
