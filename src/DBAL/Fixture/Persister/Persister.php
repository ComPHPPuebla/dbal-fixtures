<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\DBAL\Fixture\Persister;

/**
 * Insert the given rows to a database
 */
interface Persister
{
    /**
     * @param array $rows
     */
    public function persist(array $rows);
}
