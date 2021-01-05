create database digitallibrary;

use digitallibrary;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT  AUTO_INCREMENT,
    `name` VARCHAR(150),
    `email` VARCHAR(150) NOT NULL,
    `password` VARCHAR(150) NOT NULL,
    `rating` INT DEFAULT 0,
    PRIMARY KEY (`id`) );

CREATE TABLE IF NOT EXISTS `documents` ( 
    `id` INT AUTO_INCREMENT, 
    `name` VARCHAR(50) NOT NULL, 
    `description` VARCHAR(300) NOT NULL, 
    `keywords` VARCHAR(150) NOT NULL, 
    `format` ENUM('pdf','html') NOT NULL,
    `rating` INT DEFAULT 0, 
    `filename` VARCHAR(150) NOT NULL, 
    `owner` INT NOT NULL, 
    PRIMARY KEY (`id`), 
    FOREIGN KEY (`owner`) REFERENCES users(`id`));