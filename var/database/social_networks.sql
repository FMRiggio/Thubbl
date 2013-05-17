CREATE TABLE social_networks (
    id INT NOT NULL AUTO_INCREMENT,
    kind VARCHAR(15) NOT NULL,
    url VARCHAR(255) NOT NULL,
    ordinamento SMALLINT NOT NULL,
    id_user INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_user) REFERENCES user(id)
);

ALTER TABLE social_networks ADD CONSTRAINT FOREIGN KEY (id_user) REFERENCES user(id)
ALTER TABLE social_networks ADD COLUMN ordinamento SMALLINT;