# Fixture files

This library lets you load fake/testing data into your database using a YAML file. 

The following example will produce a new row in the table `reviews`
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
