<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Generators;

use Exception;

class InvalidRange extends Exception
{
    public static function withValues($start, $end): InvalidRange
    {
        return new InvalidRange("$start should be a value greater than $end");
    }
}
