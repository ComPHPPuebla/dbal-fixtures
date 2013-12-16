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