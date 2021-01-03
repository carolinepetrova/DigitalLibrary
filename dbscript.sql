create database digitallibrary;

use digitallibrary;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT  AUTO_INCREMENT,
    `name` VARCHAR(150),
    `email` VARCHAR(150) NOT NULL,
    `password` VARCHAR(150) NOT NULL,
    `rating` INT DEFAULT 0,
    PRIMARY KEY (`id`) );