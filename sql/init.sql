-- Create new database named "weighty", no case sensitive, charset UFT-8
DROP DATABASE IF EXISTS weighty;
CREATE DATABASE IF NOT EXISTS weighty CHARSET utf8 COLLATE utf8_unicode_ci;
USE weighty;

-- Create a table named users, it contains all users with login informations
CREATE TABLE users (
    id INT AUTO_INCREMENT,
    username VARCHAR(25) NOT NULL,
    email VARCHAR(254) NOT NULL,
    password VARCHAR(60) NOT NULL,
    admin BOOLEAN NOT NULL DEFAULT false,
    CONSTRAINT PK_users PRIMARY KEY (id),
    CONSTRAINT UX_username UNIQUE (username),
    CONSTRAINT UX_email UNIQUE (email)
);

-- Create a table named weights, it contains the weights and the date, it refers to the user id in table "users"
CREATE TABLE weights (
    id INT AUTO_INCREMENT,
    id_users INT NOT NULL,
    weight INT NOT NULL,
    day DATE NOT NULL,
    CONSTRAINT PK_weights PRIMARY KEY (id),
    CONSTRAINT FK_users_id FOREIGN KEY (id_users) REFERENCES users(id)
);
