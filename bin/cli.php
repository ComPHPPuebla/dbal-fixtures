<?php
/**
 * DBAL Fixtures CLI
 *
 * PHP version 5.4
 *
 * This source file is subject to the license that is bundled with this package in the
 * file LICENSE.
 *
 * @author     LMV <montealegreluis@gmail.com>
 */
require 'vendor/autoload.php';

use \Symfony\Component\Console\Application;
use \Symfony\Component\Console\Helper\HelperSet;
use \Symfony\Component\Console\Helper\DialogHelper;
use \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Doctrine\Console\Command\LoadFixtureCommand;

/**
 * DBAL Fixtures CLI
 *
 * @author     LMV <montealegreluis@gmail.com>
 */
$cli = new Application('DBAL Fixtures CLI', '0.1.1');
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
