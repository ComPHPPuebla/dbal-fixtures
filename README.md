# Doctrine DBAL fixtures

Fixtures managament with Doctrine DBAL

```php
use \Doctrine\DBAL\DriverManager;

$params = [
    'path' => 'test.sq3',
    'user' => 'test_user',
    'password' => 't3st_us3!',
    'driver' => 'pdo_sqlite',
];
$connection = DriverManager::getConnection($params);

$loader = new YamlLoader('fixtures.yml');
$persister = new ConnectionPersister($connection);

$persister->persist($loader->load());
```

You can also load fixtures from the command line. There is a Symfony Console Application example in
the `bin` folder. In order to run the example you will need to create a SQLite database with the
following command.

```bash
$ sqlite3 test_db.sq3 < data/database.sql
```

Run the Symfony Console Command.

```bash
$ ./bin/cli dbal:fixture:load data/fixture.yml
```
