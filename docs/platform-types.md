# Platform types

There are some platform specific types that are not supported by default by DBAL.
If you want, for instance, use MySQL's `ENUM` or `POINT` you'll need to register them.

In some cases like `ENUM` we can simply alias the type to an existing one like `string`

```php
<?php
$connection = new DBALConnection(DriverManager::getConnection(['url' => $dsn]));
$connection->registerPlatformType('enum', 'string');
$fixture = new Fixture($connection);
```

In some other cases we will need to process the value before using it in our tests.
A good example is MySQL's `POINT`.
You can register it and use `string` for it.
But when queried the result will be unreadable.
And you will need some processing before being able to use it.
For the `POINT` type we can use PHP's `unpack` function.

```php
$connection = DriverManager::getConnection(['url' => $dsn]);
$location = $connection->executeQuery('SELECT * FROM locations WHERE id = ?', [$id])->fetch();
$coordinates = unpack('x/x/x/x/corder/Ltype/dlat/dlon', $location['coordinates']);

echo "The latitude is {$coordinates['lat']}\n";
echo "The longitude is {$coordinates['lon']}\n";
```
