<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Commands;

use ComPHPPuebla\Fixtures\Fixture;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputInterface, InputArgument};
use Symfony\Component\Console\Output\OutputInterface;
use ComPHPPuebla\Fixtures\Database\DBALConnection;
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
The <info>dbal:fixtures:create</info> command, loads a fixture in the configured database.
HELP
        );
    }

    /**
     * @throws \InvalidArgumentException If the file cannot be found
     * @throws \Symfony\Component\Console\Exception\LogicException If no helper
     * set can be found
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException If
     * the `db` helper is not present
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = new DBALConnection($this->getHelper('db')->getConnection());
        $filename = $this->getFilename($input->getArgument('file'));
        $output->writeln(sprintf(
            "Processing file '<info>%s</info>'... ",
            $filename
        ));
        (new Fixture($connection))->load($filename);
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
