# Doctrine DBAL fixtures

This package allows you to perform inserts to a database using a YAML file and Doctrine DBAL. It has
support for foreign keys using the `@` symbol.

The following is an example of a fixture file.

```
stations:
    station_1:
        name: "CASMEN GASOL"
        social_reason: "CASMEN SA CV"
        address_line_1: "23 PTE NO 711"
        address_line_2: "EL CARMEN"
        location: "PUEBLA PUE"
        latitude: 19.03817
        longitude: -98.20737
reviews:
    review_1:
        comment: "El servicio es excelente"
        stars: 5
        station_id: "@station_1"
```

Use the following code to load the file.

```php
use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Doctrine\DBAL\Fixture\Loader\YamlLoader;
use \ComPHPPuebla\Doctrine\DBAL\Fixture\Persister\ForeignKeyParser;

$params = [
    'path' => 'test.sq3',
    'driver' => 'pdo_sqlite',
];
$connection = DriverManager::getConnection($params);

$loader = new YamlLoader('fixtures.yml');
$parser = new ForeignKeyParser();
$persister = new ConnectionPersister($connection, $parser);

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
If you need to quote the column identifiers add the `--quote` option to the command as follows:

```bash
$ ./bin/cli dbal:fixture:load --quote data/fixture.yml
```
