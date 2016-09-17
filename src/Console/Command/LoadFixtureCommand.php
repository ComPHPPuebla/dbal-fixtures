<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use ComPHPPuebla\DBAL\Fixture\Persister\ConnectionPersister;
use ComPHPPuebla\DBAL\Fixture\Loader\YamlLoader;
use InvalidArgumentException;

/**
 * Load YAML fixtures to the configured database
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
                     'file',
                     InputArgument::REQUIRED,
                     'File path of YAML file to be loaded.'
                 ),
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
        $persister = new ConnectionPersister($this->getHelper('db')->getConnection());
        $loader = new YamlLoader($this->getFilename($input->getArgument('file')));

        $output->writeln(sprintf(
            "Processing file '<info>%s</info>'... ",
            $this->getFilename($input->getArgument('file'))
        ));
        $persister->persist($loader->load());
    }

    /**
     * @param string $path
     * @return string
     */
    private function getFilename($path)
    {
        $fileName = realpath($path);
        if (!file_exists($fileName)) {
            throw new InvalidArgumentException(sprintf(
                "YAML file '<info>%s</info>' does not exist.",
                $fileName
            ));
        }
        if (!is_readable($fileName)) {
            throw new InvalidArgumentException(sprintf(
                "YAML file '<info>%s</info>' does not have read permissions.",
                $fileName
            ));
        }
        return $fileName;
    }
}
