# Seeders

You can also load fixtures from the command line using the `LoadFixtureCommand` command.
You can use this command to seed your database in your development environment.

Suppose you have the following file saved in `bin/cli.php`.

```php
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\DBAL\DriverManager;
use ComPHPPuebla\Fixtures\Commands\LoadFixtureCommand;

$cli = new Application('DBAL Fixtures CLI', '3.0.0');
$cli->setCatchExceptions(true);

$connection = DriverManager::getConnection([
  'path' => 'test.sq3', // path to your database file
  'driver' => 'pdo_sqlite',
]);
$helperSet = new HelperSet();
$helperSet->set(new ConnectionHelper($connection), 'db');
$cli->setHelperSet($helperSet);

$cli->addCommands([
    new LoadFixtureCommand(),
]);
$cli->run();
```

Now you can run your seeder as a Symfony Console Command.

```bash
$ ./bin/cli dbal:fixture:load data/seeder.yml
```
