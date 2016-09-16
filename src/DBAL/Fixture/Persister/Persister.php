<?php
namespace ComPHPPuebla\DBAL\Fixture\Persister;

interface Persister
{
    /**
     * @param array $rows
     */
    public function persist(array $rows);
}
