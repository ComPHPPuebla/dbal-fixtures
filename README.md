# Doctrine DBAL fixtures


[![Build Status](https://travis-ci.org/ComPHPPuebla/dbal-fixtures.svg?branch=master)](https://travis-ci.org/ComPHPPuebla/dbal-fixtures)
[![Latest Stable Version](https://poser.pugx.org/comphppuebla/dbal-fixtures/v/stable.svg)](https://packagist.org/packages/comphppuebla/dbal-fixtures)
[![Latest Unstable Version](https://poser.pugx.org/comphppuebla/dbal-fixtures/v/unstable.svg)](https://packagist.org/packages/comphppuebla/dbal-fixtures)
[![License](https://poser.pugx.org/comphppuebla/dbal-fixtures/license.svg)](https://packagist.org/packages/comphppuebla/dbal-fixtures)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d876000d-611d-473f-b58c-64582903f7a9/mini.png)](https://insight.sensiolabs.com/projects/d876000d-611d-473f-b58c-64582903f7a9)


This package allows you to perform inserts to a database using a YAML file and
Doctrine DBAL. It has support for foreign keys using the `@` symbol.

The following is an example of a fixture file.

```yaml
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
use Doctrine\DBAL\DriverManager;
use ComPHPPuebla\DBAL\Fixture\Loader\YamlLoader;
use ComPHPPuebla\DBAL\Fixture\Persister\ConnectionPersister;

$connection = DriverManager::getConnection([
   'path' => 'test.sq3',
   'driver' => 'pdo_sqlite',
]);

$loader = new YamlLoader('fixtures.yml');
$persister = new ConnectionPersister($connection);

$persister->persist($loader->load());
```

You can also load fixtures from the command line. There is a Symfony Console
Application example in the `bin` folder. In order to run the example you will
need to create a SQLite database with the following command.

```bash
$ sqlite3 test_db.sq3 < data/database.sql
```

Run the Symfony Console Command.

```bash
$ ./bin/cli dbal:fixture:load data/fixture.yml
```

## Changelog

### 1.0.0 - 2014-10-08

* Replace `Zend/Config` package with `Symfony\Yaml` ([#1](https://github.com/ComPHPPuebla/dbal-fixtures/pull/1))
* Update Doctrine and Symfony packages to use `~2.4` version.
