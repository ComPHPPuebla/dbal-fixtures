# SQL functions

It is possible to use SQL functions in your fixtures.
A function call is delimited with backticks **`**.
As shown in the following example:

```yml
posts:
    post_1:
        title: "Post title"
        created_at: "`CURDATE()`"
```

This entry will produce the following insert statement

```sql
INSERT INTO posts (`title`, `created_at`) VALUES(?, CURDATE())
```

## Combining functions with fake data and ranges

Say you want to produce 3 entries with random latitude and longitude.
Suppose you're using MySQL's `POINT` data type.
And that you want the latitude and longitude to be randomly generated.
You would need to create an entry similar to the following one.

```yml
stations:
    stations_[1..3]:
        name: "${company}"
        location: "`PointFromText('POINT(${latitude} ${longitude})')`"
```

This will produce 3 inserts, similar to the following ones

```sql
INSERT INTO stations(`name`, `location`) VALUES (?, PointFromText('POINT(-38.253882 -51.337622)'))
INSERT INTO stations(`name`, `location`) VALUES (?, PointFromText('POINT(-36.253862 -58.337642)'))
INSERT INTO stations(`name`, `location`) VALUES (?, PointFromText('POINT(-28.253182 -71.337222)'))
```
