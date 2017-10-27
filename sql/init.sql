CREATE DATABASE weighty;
USE weighty;

CREATE TABLE users (
    id INT AUTO_INCREMENT,
    username VARCHAR(25) NOT NULL,
    email VARCHAR(254) NOT NULL,
    password VARCHAR(60) NOT NULL,
    admin BOOLEAN NOT NULL DEFAULT false,
    CONSTRAINT PK_users PRIMARY KEY (id)
);

CREATE TABLE weights (
    id INT AUTO_INCREMENT,
    id_users INT NOT NULL,
    weight INT NOT NULL,
    day DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT PK_weights PRIMARY KEY (id),
    CONSTRAINT FK_users_id FOREIGN KEY (id_users) REFERENCES users(id)
);
