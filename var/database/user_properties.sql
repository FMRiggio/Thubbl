CREATE TABLE user_properties (
    id INT NOT NULL AUTO_INCREMENT,
    id_user INT NOT NULL,
    property_name VARCHAR(16) NOT NULL,
    property_value VARCHAR(32) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_user) REFERENCES user(id)
)