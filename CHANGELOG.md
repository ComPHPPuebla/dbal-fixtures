# Changelog

## 1.0.0 - 2014-10-08

* Replace `Zend/Config` package with `Symfony\Yaml` ([#1](https://github.com/ComPHPPuebla/dbal-fixtures/pull/1))
* Update Doctrine and Symfony packages to use `~2.4` version.

## 2.0.0 - 2016-09-17

* Switched to PSR-4 autoloading.
* Removed namespace Doctrine.
* Updated minimum PHP version to 5.6.
* Updated all the dependencies.
* Replaced XPMock with Prophecy in tests.
* Identifiers are quoted now by default.
* Make the the ForeignKeyParser a default value in YamlLoader constructor.
* Keep only the integration tests for the Persister class.

## 3.0.0 - 2017-03-13

* PSR-4 root namespace is now `ComPHPPuebla\Fixtures`.
* Updated minimum PHP version to 7.1.
* Update package dependencies.
* Add support to generate fake data using Faker.
* Added facade class `Fixture`, now you only need to call `load` on this object.

## 3.1.0 - 2017-06-20

* Added support to retrieve the rows inserted through the `Fixture#rows` method.
* Fixed issue: Non `AUTO_INCREMENT` primary keys are not overwritten anymore.
* Added ability to register specific platform types like `enum` in MySQL.
* Added ability to specify `null` values in the `yml` file.

## 3.1.1 - 2017-11-13

* Fixed issue with `ForeingKeyProcessor#isReference`. It failed to identify references
  in empty entries. See [#3](https://github.com/ComPHPPuebla/dbal-fixtures/issues/3)

## 4.0.0 - 2018-01-23

* Update Symfony components to v4
