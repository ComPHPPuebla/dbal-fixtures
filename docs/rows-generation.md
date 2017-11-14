# Rows Generation

It is possible to generate more than one row in a definition by using the **range**
notation `[first..last]`. 

The following example will insert six identical rows in the table `reviews`. 
It will relate each review to the station with the identifier `@station_1`.

```yaml
reviews:
    review_[1..6]:
        comment: "El servicio es muy malo"
        stars: 2
        station_id: "@station_1"
```
