-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 25, 2021 at 09:24 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digitallibrary`
--

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `keywords` varchar(150) NOT NULL,
  `format` enum('pdf','html') NOT NULL,
  `rating` float DEFAULT 0,
  `rating_sum` float NOT NULL DEFAULT 0,
  `votes_num` float NOT NULL DEFAULT 0,
  `filename` varchar(150) NOT NULL,
  `owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `name`, `description`, `keywords`, `format`, `rating`, `rating_sum`, `votes_num`, `filename`, `owner`) VALUES
(1, 'HTML мета-тагове и SEO', 'SEO e съкращение на „search engine optimization”, което накратко означава процес по подобряването на даден сайт откъм неговата видимост относно търсене, свързано с темата/функционалността на сайта. SEO засяга само резултати от търсене, които са неплатени.', 'seo, serp, robots, indexing, ranking, best practices, title, meta, tag, description, canonical', 'html', 5, 5, 1, '/DigitalLibrary/storage/81661/81661_referat.html', 1),
(10, 'HTTPS и HTTP - сигурност в PHP', 'Рефератът има за цел да въведе в основите на PHP сигурността, като представи протокола HTTP в детайли, както и неговата защитена версия HTTPS и да направи кратко сравнение между тях. Друга важна точка е да обясни SSL/TLS сертификатите, които се използват в HTTPS. Също така показва основните концепции при изграждането на сигурни системи в PHP, илюстрирайки редица добри практики при кодирането на сървърната част.', 'HTTP, HTTPS, protocol, request, response, client-server, SSL, TLS, security, PHP, XSS, cross-site scripting, SQL injection, validation, encoding, ', 'html', 5, 5, 1, '1_1611492769_81747/81747_referat.html', 4),
(11, 'Протокол HTTP версия 2.0+', 'Рефератът има за цел да представи HTTP протокола. Прави се сравнение между различните стандарти на протокола,\nобсъждат се предимствата и недостатъците на различните версии, както и необходимостта от бъдещето развитие на протокола. Въведените концепции се унагледяват с илюстрации.', 'HTTP, HTTP/1.1, HTTP/1.x, HTTP/2, HTTP/3, QUIC, SPDY, domain sharding, server push, head-of-line-blocking', 'html', 0, 0, 0, '1_1611493285_81653/81653_referat.html', 6),
(12, 'CSS - оформление, box модел', 'Важно е да разберем разликата между двата основни типа елементи - тези на ниво блок - block-level, и тези на ниво ред - inline-level. Първият вид се използва за по-големи и значими обекти, като заглавия и други подобни. Тези елементи винаги започват на нов ред и заемат цялата свободна широчина, без значение от това колко всъщност им трябва. Елементите от втория тип са по-малки парчета от съдържанието, които заемат толкова място, колкото им трябва, и могат да започват на същия ред като предходните. Най-често този тип се използва за отделни думи от абзац, които трябва да се уголемят, подчертаят, зададат с различен шрифт и големина и така нататък.', 'css, box, модел', 'html', 0, 0, 0, '1_1611493628_80963/80963_referat.html', 5),
(13, 'Motion UI - създаване на CSS анимации', 'Рефератът има за цел да ни запознае с един нов начин за създаване на анимации чрез CSS, показани са примери, посочени са причини за използването на анимациите както и основно запознаване с класовете и ефектите, които предоставя Motion UI.', 'CSS, анимации, Transition Classes, Animation Classes,Modifier Classes, движения, преходи, тенденции', 'html', 0, 0, 0, '1_1611494423_81658/81658_referat.html', 7),
(15, 'AngularJS', 'Рефератът цели да запознае аудиторията с рамката AngularJS и да ги подтикне към търсене на възможности за нейното приложение.', 'директиви,обвързване на данни,компилатор,контролер,валидация,услуги,предимства и недостатъци,факти,проложения, javascript', 'html', 0, 0, 0, '1_1611507369_81724/81724_referat.html', 7),
(16, 'Споделяне на ресурси от различни източници (CORS).', 'Кратък преглед на обменянето на ресурси в WEB и http протокола. Какви хедъри се съдържат в HTTP Request и HTTP Response. Примери и обяснение за Simple Request, Preflighted request, Request with credentials.', 'HTTP, Header, Request, Response, Simple Request, Preflighted request, Request with credentials, CORS, Body, Same-origin', 'html', 3, 3, 1, '1_1611507620_81287/81287_referat.html', 5),
(18, 'Обектно ориентирано програмиране с JavaScript', 'Документът има за цел да запознае читателят с Обектно ориентирано програмиране на езика JavaScript.', 'ооп, javascript', 'pdf', 0, 0, 0, '1_1611508012_dokumentite.com-obektno-orientirano-programirane-s-javascript.pdf', 5),
(19, 'Полезни разширения за уеб с Visual Code Editor', 'Рефератът представя полезни разширения за разработка на уеб с Visual Studio Code.', 'Visual Studio Code, extensions, web development, javascript. html, css, code snippets, intellisense, code formatting', 'html', 0, 0, 0, '1_1611589526_81587/81587_referat.html', 7);

-- --------------------------------------------------------

--
-- Table structure for table `loaned_documents`
--

CREATE TABLE `loaned_documents` (
  `id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_loaned` varchar(20) NOT NULL,
  `expiration_date` varchar(20) NOT NULL,
  `token` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loaned_documents`
--

INSERT INTO `loaned_documents` (`id`, `doc_id`, `user_id`, `date_loaned`, `expiration_date`, `token`) VALUES
(23, 1, 1, '1610396817', '1611001617', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTAzOTY4MTcsImV4cCI6MTYxMTAwMTYxNywiaXNzIjoiaHR0cDpcL1wvbG9jYWxob3N0XC9EaWdpdGFsTGlicmFyeVwvIiwiZGF0YSI6eyJ1c2VyX2lkIjoiMSIsImRvY19pZCI6IjEiLCJkYXRlX2xvYW5lZCI6IjE2MTAzOTY4MTcifX0.yDF4VotJeTkZ3cEOA7ASI5plSvqtumJ3f42Kgg31TdE'),
(26, 10, 1, '1611512816', '1611944816', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTE1MTI4MTYsImV4cCI6MTYxMTk0NDgxNiwiaXNzIjoiaHR0cDpcL1wvbG9jYWxob3N0XC9EaWdpdGFsTGlicmFyeVwvIiwiZGF0YSI6eyJ1c2VyX2lkIjoiMSIsImRvY19pZCI6IjEwIiwiZGF0ZV9sb2FuZWQiOiIxNjExNTEyODE2In19.1Q-125GmgxHqR8HWufgBRBdh1MTnnvvrrBaH22HRtKs'),
(27, 11, 1, '1611512864', '1612117664', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTE1MTI4NjQsImV4cCI6MTYxMjExNzY2NCwiaXNzIjoiaHR0cDpcL1wvbG9jYWxob3N0XC9EaWdpdGFsTGlicmFyeVwvIiwiZGF0YSI6eyJ1c2VyX2lkIjoiMSIsImRvY19pZCI6IjExIiwiZGF0ZV9sb2FuZWQiOiIxNjExNTEyODY0In19.I5vn87iNxszgdynqvZJvQcXsTMI6d8C6kbtAFtwOocY'),
(28, 12, 1, '1611513030', '1612722630', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTE1MTMwMzAsImV4cCI6MTYxMjcyMjYzMCwiaXNzIjoiaHR0cDpcL1wvbG9jYWxob3N0XC9EaWdpdGFsTGlicmFyeVwvIiwiZGF0YSI6eyJ1c2VyX2lkIjoiMSIsImRvY19pZCI6IjEyIiwiZGF0ZV9sb2FuZWQiOiIxNjExNTEzMDMwIn19.Eh53-bkTh0tPDut0jsysIsamne84NssJRr5OjOca2Hg'),
(34, 16, 1, '1611606096', '1612038096', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTE2MDYwOTYsImV4cCI6MTYxMjAzODA5NiwiaXNzIjoiaHR0cDpcL1wvbG9jYWxob3N0XC9EaWdpdGFsTGlicmFyeVwvIiwiZGF0YSI6eyJ1c2VyX2lkIjoiMSIsImRvY19pZCI6IjE2IiwiZGF0ZV9sb2FuZWQiOiIxNjExNjA2MDk2In19.aTS5MRkv9x5dVzgSY-IEGtJ2fxdUHm9O9fXOCUn8RsM');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `rating` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `rating`) VALUES
(1, 'Каролина Колева', 'karolinapetrovawork@gmail.com', '$2y$10$DrcChIxiZUKeUTEg4fWmvewpis1GckAfWlIqz8dNB.RbVONXugWni', 11),
(4, 'Диян Михайлов', 'test1@test.com', '$2y$10$uAL4wm2qG22oxUWd41GVYe9OZAy5ru2qBCW/4lzVIuKaho8rceDhy', 40),
(5, 'Момчил Сулов', 'test2@test.com', '$2y$10$7ooSlcqF5p/8u9iUd9i71ePqP6KrX5XZ4/GK0zAkPiVLiYE3BNiaK', 8),
(6, 'Християн Христов', 'test3@test.com', '$2y$10$4yoXzNHlndIyh/mzHhmMuuOUuPXAmd5oCa1QTHWD1R.xEtHK/.2lG', 0),
(7, 'Александра Симеонова', 'karolinapetrova98@gmail.com', '$2y$10$8IUfXj4ixkfiPf0S5QM6Nul1ql6F9VBCOGrsX7E1DUZJ/./vWz6L2', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner` (`owner`);

--
-- Indexes for table `loaned_documents`
--
ALTER TABLE `loaned_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doc_id` (`doc_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `loaned_documents`
--
ALTER TABLE `loaned_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`id`);

--
-- Constraints for table `loaned_documents`
--
ALTER TABLE `loaned_documents`
  ADD CONSTRAINT `loaned_documents_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `documents` (`id`),
  ADD CONSTRAINT `loaned_documents_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
