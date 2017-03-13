<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\DBAL\DriverManager;
use ComPHPPuebla\Fixtures\Commands\LoadFixtureCommand;

$cli = new Application('DBAL Fixtures CLI', '3.0.0');
$cli->setCatchExceptions(true);

$connection = DriverManager::getConnection(require __DIR__ . '/../config/connection.config.php');

$helperSet = new HelperSet();
$helperSet->set(new ConnectionHelper($connection), 'db');
$cli->setHelperSet($helperSet);

$cli->addCommands([
    new LoadFixtureCommand(),
]);

$cli->run();
