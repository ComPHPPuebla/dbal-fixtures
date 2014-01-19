<?php
/*
 * Load YAML fixtures in the configured database
 *
 * PHP version 5.4
 *
 * This source file is subject to the license that is bundled with this package in the
 * file LICENSE.
 *
 * @author     LMV <montealegreluis@gmail.com>
 */
namespace ComPHPPuebla\Doctrine\Console\Command;

use \Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Input\InputOption;
use \ComPHPPuebla\Doctrine\DBAL\Fixture\Persister\ConnectionPersister;
use \ComPHPPuebla\Doctrine\DBAL\Fixture\Loader\YamlLoader;
use \ComPHPPuebla\Doctrine\DBAL\Fixture\Persister\ForeignKeyParser;
use \InvalidArgumentException;

/**
 * Load YAML fixtures in the configured database
 *
 * @author     LMV <montealegreluis@gmail.com>
 */
class LoadFixtureCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('dbal:fixture:load')
             ->setDescription('Loads a fixture in the configured database.')
             ->setDefinition([
                 new InputArgument(
                     'file', InputArgument::REQUIRED, 'File path of YAML file to be loaded.'
                 ),
                 new InputOption(
                     'quote', null, InputOption::VALUE_NONE, 'If present, column names will be quoted on insert.'
                 )
             ])
             ->setHelp(<<<HELP
The <info>dbal:fixtures:create</info> loads a fixture in the configured database.
HELP
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $this->getHelper('db')->getConnection();

        $quote = $input->hasOption('quote');

        $persister = new ConnectionPersister($connection, new ForeignKeyParser(), $quote);

        $path = $input->getArgument('file');
        $fileName = realpath($path);

        if (!file_exists($fileName)) {
            throw new InvalidArgumentException(
                sprintf("YAML file '<info>%s</info>' does not exist.", $fileName)
            );
        } elseif (!is_readable($fileName)) {
            throw new InvalidArgumentException(
                sprintf("YAML file '<info>%s</info>' does not have read permissions.", $fileName)
            );
        }

        $loader = new YamlLoader($fileName);

        $output->write(sprintf("Processing file '<info>%s</info>'... ", $fileName));
        $persister->persist($loader->load());
    }
}
