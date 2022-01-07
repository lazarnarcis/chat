SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `name` varchar(64) NOT NULL,
  `message` mediumtext NOT NULL,
  `admin` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `founder` int(11) NOT NULL,
  `action` int(11) NOT NULL,
  `file` longtext NOT NULL,
  `actiontext` varchar(10000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `text` text NOT NULL,
  `forTicket` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `text` varchar(200) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `text` mediumtext NOT NULL,
  `userid` int(11) NOT NULL,
  `closed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin` int(11) NOT NULL,
  `file` longtext NOT NULL,
  `bio` text NOT NULL,
  `founder` int(11) NOT NULL,
  `banned` int(11) NOT NULL,
  `logged` int(11) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `last_ip` varchar(64) NOT NULL,
  `verified` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `admin`, `file`, `bio`, `founder`, `banned`, `logged`, `ip`, `last_ip`) VALUES
(2, 'admbot', 'admin@gmail.com', '$2y$10$rIAA6BF.iHYZfcqHDaLhHO416pZXE3GAOIyjlr0DruiJsVj.UCKca', '2021-12-11 19:31:23', 0, 'images/bot.svg', 'Hi, if you have a problem you can make a ticket in the Contact section and an admin will answer you as soon as possible!', 0, 0, 1, '::1', '::1');

ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;