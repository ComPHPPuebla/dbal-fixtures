SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE IF NOT EXISTS stations (
    station_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    social_reason VARCHAR(100) NOT NULL,
    address_line_1 VARCHAR(150) NOT NULL,
    address_line_2 VARCHAR(80) NOT NULL,
    location VARCHAR(100) NOT NULL,
    latitude FLOAT NOT NULL,
    longitude FLOAT NOT NULL,
    created_at DATETIME NOT NULL,
    last_updated_at DATETIME NOT NULL,
    PRIMARY KEY(station_id)
);

TRUNCATE TABLE stations;

CREATE TABLE IF NOT EXISTS reviews (
    review_id INT NOT NULL AUTO_INCREMENT,
    comment VARCHAR(500) NOT NULL,
    stars INT NOT NULL,
    station_id INT NOT NULL,
    PRIMARY KEY(review_id),
    FOREIGN KEY(station_id) REFERENCES station(station_id)
);

TRUNCATE TABLE reviews;

CREATE TABLE IF NOT EXISTS states (
    url VARCHAR(500) NOT NULL,
    name VARCHAR(500) NOT NULL,
    PRIMARY KEY(url)
);

TRUNCATE TABLE states;

CREATE TABLE IF NOT EXISTS roles (
    role_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(500) NOT NULL,
    parent_role VARCHAR(500),
    PRIMARY KEY(role_id)
);

TRUNCATE TABLE roles;

SET FOREIGN_KEY_CHECKS=0;
