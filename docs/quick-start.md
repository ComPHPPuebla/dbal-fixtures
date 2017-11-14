# Quick start guide

If you want to have a glance on how to use this library, follow this steps:

1. Create the sample SQLite database

```bash
$ sqlite3 test_db.sq3 < data/database.sql
```

2. Save the following fixture in a file name `stations.yml`.

```yaml
stations:
    station_[1..3]:
        name: "${company}"
        social_reason: "${bs}"
        address_line_1: "${streetAddress}"
        address_line_2: "${secondaryAddress}}"
        location: "${city}"
        latitude: "${latitude}"
        longitude: "${longitude}"
        created_at: "${date('Y-m-d H:i:s', 'yesterday')}"
        last_updated_at: "${date('Y-m-d H:i:s')}"
reviews:
    review_[1..2]:
        comment: "${realText}"
        stars: "${numberBetween(1, 2)}"
        station_id: "@station_1"
    review_[3..5]:
        comment: "${realText}"
        stars: "${numberBetween(3, 5)}"
        station_id: "@station_2"
    review_6:
        comment: "El servicio es excelente"
        stars: 5
        station_id: "@station_3"
```

3. Use the following code to load the fixture in a file named `loader.php`.

```php
use Doctrine\DBAL\DriverManager;
use ComPHPPuebla\Fixtures\Connections\DBALConnection;
use ComPHPPuebla\Fixtures\Fixture;

$connection = DriverManager::getConnection([
   'path' => 'test.sq3',
   'driver' => 'pdo_sqlite',
]);

$fixture = new Fixture(new DBALConnection($connection));
$fixture->load('fixture.yml');
```

4. Run the PHP file

```bash
$ php loader.php
```

5. Verify that you have 3 stations, the first one with 2 reviews, the second 
one with 3 reviews, and the third one with only one review. Columns starting
with a `$` should have random fake data.
