# Foreign keys

You can **refer** to a previously inserted row by using the `@` symbol followed by the
row identifier. 

The following example will insert 2 rows:

* One in the table `stations` and 
* another one in the table `comments`, using the foreign key `station_id`. 

In this case the relationship is determined by using `@station_1` as the value for 
the column `station_id` in `comments`.

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
