<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\DialogHelper;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\DBAL\DriverManager;
use ComPHPPuebla\Console\Command\LoadFixtureCommand;

$cli = new Application('DBAL Fixtures CLI', '2.0.0');
$cli->setCatchExceptions(true);

$connection = DriverManager::getConnection(require 'config/connection.config.php');

$helperSet = new HelperSet();
$helperSet->set(new DialogHelper(), 'dialog');
$helperSet->set(new ConnectionHelper($connection), 'db');

$cli->setHelperSet($helperSet);

$cli->addCommands([
    new LoadFixtureCommand(),
]);

$cli->run();
