<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace ComPHPPuebla\Fixtures\Database;

use Doctrine\DBAL\Connection;

class Insert
{
    /** @var string */
    private $table;

    /** @var Row */
    private $row;

    public static function into(string $table, Row $row): Insert
    {
        return new Insert($table, $row);
    }

    public function toSQL(Connection $connection): string
    {
        return sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            implode(', ', $this->quoteIdentifiers($connection)),
            implode(', ', $this->row->placeholders())
        );
    }

    public function parameters(): array
    {
        $parameters = [];
        foreach ($this->row->values() as $value) {
            if(is_array($value)) {
                $parameters[] = json_encode($value);
            } elseif (is_numeric($value) || trim($value, '`') === $value) {
                $parameters[] = $value;
            }
        }
        return $parameters;
    }

    private function quoteIdentifiers(Connection $connection): array
    {
        return array_map(function (string $column) use ($connection) {
            return $connection->quoteIdentifier($column);
        }, $this->row->columns());
    }

    private function __construct(string $table, Row $row)
    {
        $this->table = $table;
        $this->row = $row;
    }
}
