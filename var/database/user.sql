CREATE TABLE user (
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(128) NOT NULL,
    password VARCHAR(32) NOT NULL,
    salt VARCHAR(32) NOT NULL,
    displayed_name VARCHAR(64) NOT NULL,
    claim VARCHAR(255),
    privacy BOOLEAN NOT NULL,
    date_created TIMESTAMP NOT NULL,
    active BOOLEAN NOT NULL,
    permalink VARCHAR(32)
    PRIMARY KEY (id)
);