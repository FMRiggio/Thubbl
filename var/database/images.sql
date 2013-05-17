CREATE TABLE images (
    id INT NOT NULL AUTO_INCREMENT,
    filename VARCHAR(32) NOT NULL,
    kind VARCHAR(16) NOT NULL,
    id_user INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_user) REFERENCES user(id)
);