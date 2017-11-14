# Fixture classes

After loading a fixture you have access to the inserted rows through the `Fixture#rows` method,
this method returns the rows with the auto generated IDs created by the database. This is useful
because: 

1. Now you can save some queries in your tests, and 
2. You can create fixture classes with method names related to the domain of your application. 
   For instance, we could refer only the stations from Texas `$fixture->stationsInTexas()` or 
   get access to all the administrator users `$fixture->administrators()`.

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
    /** @var Fixture */
    private $fixture;
    
    /** @var array */
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
        // Filter all the rows that have identifiers prefixed with `station_`
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
        $this->authenticatedAs($this->fixture->adminUser()); // This method belongs to the trait `AuthenticateUsers` 
        
        // Query your database with your repository/mapper/table gateway, etc.
        // $stations = ...
        
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
