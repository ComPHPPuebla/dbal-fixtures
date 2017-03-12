<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputInterface, InputArgument};
use Symfony\Component\Console\Output\OutputInterface;
use ComPHPPuebla\Connections\DBALConnection;
use ComPHPPuebla\Loader\YamlLoader;
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
        $connection = new DBALConnection($this->getHelper('db')->getConnection());
        $loader = new YamlLoader($this->getFilename($input->getArgument('file')));

        $filename = $this->getFilename($input->getArgument('file'));
        $output->writeln(sprintf(
            "Processing file '<info>%s</info>'... ",
            $filename
        ));
        $connection->insert($loader->load($filename));
    }

    /**
     * @throws \InvalidArgumentException If file does not exist, or can't be read
     */
    private function getFilename(string $path): string
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
