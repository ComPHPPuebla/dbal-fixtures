<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures;

trait ProvidesConnections
{
    function databaseConnections(): array
    {
        return [
            'SQLite' => [new SQLiteConnectionFactory()],
            'MySQL' => [new MySQLConnectionFactory()],
        ];
    }
}
