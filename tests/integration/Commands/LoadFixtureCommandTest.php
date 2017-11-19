<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures\Commands;

use ComPHPPuebla\Fixtures\ProvidesConnection;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class LoadFixtureCommandTest extends TestCase
{
    use ProvidesConnection;

    /** @test */
    function it_loads_a_given_fixture_file_into_the_configured_database()
    {
        $command = new LoadFixtureCommand();
        $helperSet = new HelperSet();
        $helperSet->set(new ConnectionHelper($this->connection), 'db');
        $command->setHelperSet($helperSet);
        $input = new ArrayInput([
            'file' => __DIR__ . '/../../../data/fixture-all.yml',
        ]);
        $output = new BufferedOutput();

        $statusCode = $command->run($input, $output);

        $this->assertEquals(0, $statusCode);
        $this->assertRegExp('/fixture\-all\.yml/', $output->fetch());
    }
}
