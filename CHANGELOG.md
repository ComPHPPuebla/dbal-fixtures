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
