<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Processors;

interface Processor
{
    public function process(array $row): array;

    public function postProcessing(string $key, int $id): void;
}