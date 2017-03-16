PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE stations (
    station_id INTEGER NOT NULL,
    name VARCHAR(100) NOT NULL,
    social_reason VARCHAR(100) NOT NULL,
    address_line_1 VARCHAR(150) NOT NULL,
    address_line_2 VARCHAR(80) NOT NULL,
    location VARCHAR(100) NOT NULL,
    latitude DOUBLE PRECISION NOT NULL,
    longitude DOUBLE PRECISION NOT NULL,
    created_at DATETIME NOT NULL,
    last_updated_at DATETIME NOT NULL,
    PRIMARY KEY(station_id)
);

CREATE TABLE reviews (
    review_id INTEGER NOT NULL,
    comment VARCHAR(500) NOT NULL,
    stars INTEGER NOT NULL,
    station_id INTEGER NOT NULL,
    PRIMARY KEY(review_id),
    FOREIGN KEY(station_id) REFERENCES station(station_id)
);

CREATE TABLE states (
    url VARCHAR(500) NOT NULL,
    name VARCHAR(500) NOT NULL,
    PRIMARY KEY(url)
);

COMMIT;
