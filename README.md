# Doctrine DBAL fixtures

[![Build Status](https://travis-ci.org/ComPHPPuebla/dbal-fixtures.svg?branch=master)](https://travis-ci.org/ComPHPPuebla/dbal-fixtures)
[![Latest Stable Version](https://poser.pugx.org/comphppuebla/dbal-fixtures/v/stable.svg)](https://packagist.org/packages/comphppuebla/dbal-fixtures)
[![Latest Unstable Version](https://poser.pugx.org/comphppuebla/dbal-fixtures/v/unstable.svg)](https://packagist.org/packages/comphppuebla/dbal-fixtures)
[![License](https://poser.pugx.org/comphppuebla/dbal-fixtures/license.svg)](https://packagist.org/packages/comphppuebla/dbal-fixtures)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d876000d-611d-473f-b58c-64582903f7a9/mini.png)](https://insight.sensiolabs.com/projects/d876000d-611d-473f-b58c-64582903f7a9)

Fixtures management using YAML files. It supports: foreign key references, and
generation of fake data.

1. [Installation](#installation)
1. [Example](#example)
1. [Complete Reference](#complete-reference)
    1. [YAML](#yaml)
    1. [References](#references)
    1. [Rows Generation](#rows-generation)
    1. [Fake Data Generation](#fake-data-generation)
    1. [Fixture Classes](#fixture-classes)
1. [Console Application Example](#console-application-example)
1. [Tests](#tests)
1. [CHANGELOG](CHANGELOG.md)
1. [License](#license)

## Installation

Install this package using composer

```bash
$ composer require comphppuebla/dbal-fixtures
```

## Example

The following is an example of a fixture file.

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

Use the following code to load the file.

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

## Complete Reference

### YAML

This library turns a YAML file into associative arrays, in order to insert them
into a database. The following example will produce a new row in the table `reviews`
with the values `El servicio es muy malo`, `2`, and `1` for the columns `comment`,
`stars` and `station_id` respectively. Notice that every row has an **identifier**,
in this example it is `review_1`.

```yaml
reviews:
    review_1:
        comment: "El servicio es muy malo"
        stars: 2
        station_id: 1
```

### References

You can reference a previously inserted row by using the `@` symbol followed by the
row identifier. The following example will insert 2 rows, one in the table `stations`
and another one in the table `comments`. The comment will have a reference to the station
through the column `station_id`. In this case the relationship is determined by using
`@station_1` as the value for that column.

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

### Rows Generation

It is possible to generate more than one row in a definition by using the range
notation `[first..last]`. The following example will insert six identical rows in
the table `reviews` associated to the row identified as `station_1`.

```yaml
reviews:
    review_[1..6]:
        comment: "El servicio es muy malo"
        stars: 2
        station_id: "@station_1"
```

### Fake Data Generation

This library uses [Faker](https://github.com/fzaninotto/Faker) to generate fake
data for tests, or to seed a database for development. The syntax to call a Faker
formatter from your fixture file is as follows `${formatter(argument_1, argument_2,..,argument_n)}`.
The following example will insert three rows into the table `stations`. It will
generate fake data for all the columns. It is possible to pass 1 or more
parameters as shown in the columns `created_at` and `updated_at`.

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
```

### Fixture classes

After loading a fixture you have access to the inserted rows through the `Fixture#rows` method,
this method returns the rows with the auto generated IDs created by the database. This is useful
because: 1) now you can save some queries and 2) you can create fixture classes with method names
related to the domain of your application. For instance, we could refer only the stations from Texas
`$fixture->stationsInTexas()` or get access to all the administrator users `$fixture->administrators()`.

Let's create an example. Suppose you have the following fixture file.

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
users:
    user_1:
        username: "montealegreluis@gmail.com"
        password: "iL0ve_myJob"
        state: "active"
        roleName: "admin"
```

You could create a custom fixture class for your integration tests like the following.

```php
class StationsFixture
{
    private $fixture;
    private $rows;
    public static function fromURL(string $url): StationsFixture
    {
        return new StationsFixture($url);
    }
    private function __construct(string $url)
    {
        $connection = new DBALConnection(DriverManager::getConnection(['url' => $url]));
        $this->fixture = new Fixture($connection);
        $this->rows = $this->fixture->rows();
    }
    public function adminUser(): array
    {
        return $this->rows['user_1'];
    }
    public function stations(): array
    {
        return array_filter($this->rows, function (array $key) {
            return strpos($key, 'station_') === 0;
        }, ARRAY_FILTER_USE_KEY);
    }
}
```

Then you could use it in your tests as follows.

```php
class StationsControllerTest extends TestCase
{
    use AuthenticatesUsers; // This could be a trait specific to your framework/application
    
    private $fixture;
    
    /** @test */
    function it_shows_all_the_gas_stations_after_login()
    {
        $this->authenticateAs($this->fixture->adminUser()); // This method belongs to AuthenticateUsers 
        
        // Query your database with your repository/mapper/table gateway, etc.
        
        // Compare the result with the rows inserted by your fixture
        $expectedStations = $this->fixture->stations();
        
        $this->assertEquals($expectedStations, $stations); 
    }
    
    /** @before */
    function configure(): void
    {
        $this->fixture = StationsFixture::fromURL('sqlite:///test.sq3');
    }
```

## Console Application Example

You can also load fixtures from the command line. Take a look at the Symfony
Console Application in the `bin` folder. In order to run the example application
you will need to create a SQLite database with the following command.

```bash
$ sqlite3 test_db.sq3 < data/database.sql
```

Run the Symfony Console Command.

```bash
$ ./bin/cli dbal:fixture:load data/fixture.yml
```

## Tests

Run the test suite using PHPUnit:

```bash
$ bin/phpunit --testdox
```

## License

Released under the [MIT License](LICENSE).
