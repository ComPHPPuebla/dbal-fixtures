# Fake Data

This library uses [Faker](https://github.com/fzaninotto/Faker) to generate fake
data for tests, or to seed a database for development. 

The syntax to call a Faker formatter from your fixture file is as follows 
`${formatter(argument_1, argument_2,..,argument_n)}`.

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
