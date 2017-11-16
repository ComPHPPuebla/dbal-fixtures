<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Connections;

/**
 * Insert the given row to a database
 */
interface Connection
{
    public function insert(string $table, Row $row): void;

    public function getPrimaryKeyOf(string $table): string;
}
