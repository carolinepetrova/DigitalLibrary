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
    `rating` FLOAT DEFAULT 0, 
    `rating_sum` FLOAT DEFAULT 0,
    `votes_num` FLOAT DEFAULT 0,
    `filename` VARCHAR(150) NOT NULL, 
    `owner` INT NOT NULL, 
    PRIMARY KEY (`id`), 
    FOREIGN KEY (`owner`) REFERENCES users(`id`));

CREATE TABLE IF NOT EXISTS `loaned_documents` (
    `id` INT AUTO_INCREMENT,
    `doc_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `date_loaned` VARCHAR(20) NOT NULL,
    `expiration_date` VARCHAR(20) NOT NULL,
    `token` VARCHAR(500),
    PRIMARY KEY (`id`), 
    FOREIGN KEY (`doc_id`) REFERENCES documents(`id`),
    FOREIGN KEY (`user_id`) REFERENCES users(`id`));