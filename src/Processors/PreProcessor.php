<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Processors;

use ComPHPPuebla\Fixtures\Database\Row;

interface PreProcessor
{
    public function beforeInsert(Row $row): void;
}
