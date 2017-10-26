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
    weight VARCHAR(3) NOT NULL,
    day DATE NOT NULL,
    CONSTRAINT PK_weights PRIMARY KEY (id),
    CONSTRAINT FK_users_id FOREIGN KEY (id_users) REFERENCES users(id)
);
