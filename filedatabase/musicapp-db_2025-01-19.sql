-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 19, 2025 at 03:54 AM
-- Server version: 8.2.0
-- PHP Version: 8.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `musicapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id_activity` int NOT NULL,
  `id_user` int NOT NULL,
  `id_music` int DEFAULT NULL,
  `settime` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_actions`
--

CREATE TABLE `admin_actions` (
  `id_action` int NOT NULL,
  `id_user` int NOT NULL,
  `id_music` int NOT NULL,
  `action` enum('POST','DROP') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feeds`
--

CREATE TABLE `feeds` (
  `id_feed` int NOT NULL,
  `id_user` int NOT NULL,
  `option_feed` enum('FeedBack','Report') NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `feeds`
--

INSERT INTO `feeds` (`id_feed`, `id_user`, `option_feed`, `content`) VALUES
(1, 1, 'FeedBack', 'dsadsadsad'),
(2, 1, 'Report', 'ada bug disini'),
(3, 1, 'FeedBack', 'aji kia maling mangga'),
(4, 1, 'Report', 'aji kia maling lagu saya\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE `follow` (
  `id_user_follower` int NOT NULL,
  `id_user_followed` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id_music` int NOT NULL,
  `id_user` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id_music`, `id_user`, `created_at`) VALUES
(18, 1, '2025-01-17 14:14:59'),
(19, 1, '2025-01-17 07:52:00');

-- --------------------------------------------------------

--
-- Table structure for table `music`
--

CREATE TABLE `music` (
  `id_music` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `upload_date` datetime DEFAULT NULL,
  `license` text,
  `description` text,
  `file_name` varchar(255) DEFAULT NULL,
  `caption` text,
  `artist_name` varchar(100) DEFAULT NULL,
  `like_count` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `music`
--

INSERT INTO `music` (`id_music`, `id_user`, `image`, `title`, `genre`, `release_date`, `upload_date`, `license`, `description`, `file_name`, `caption`, `artist_name`, `like_count`) VALUES
(18, 1, '8c092e66416f562d34aa5dabda4581aa.jpg', 'dsadsad', 'ddsadsad', '2024-12-09', '2024-12-09 21:06:04', 'Standard License', 'dsadsad', '『夢を追いかける』.mp3', 'dsadas', 'Misaki Zuijin', 1),
(19, 6, 'A solitary figu ae654137-f109-4d10-99ad-fbe10700b68e.png', 'dsadgdafxd', 'pop, j-pop', '2024-12-09', '2024-12-09 21:42:42', 'Standard License', 'dsadsad', 'qwerty.mp3', 'ddsadasd', 'LCV3R', 1),
(20, 6, 'bd49335b-90df-49ac-b243-17ae3e9a7446.png', 'dsadsadsa', 'pop, j-pop', '2024-12-09', '2024-12-09 22:05:18', 'Standard License', 'sdadsadsad', '『届かない愛』.mp3', 'dsadsad', 'reygasss', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id_notification` int NOT NULL,
  `id_user` int NOT NULL,
  `id_music` int DEFAULT NULL,
  `id_reply` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id_notification`, `id_user`, `id_music`, `id_reply`) VALUES
(5, 1, 18, NULL),
(6, 6, 19, NULL),
(7, 6, 20, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `playlists`
--

CREATE TABLE `playlists` (
  `id_playlist` int NOT NULL,
  `id_user` int NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `playlists`
--

INSERT INTO `playlists` (`id_playlist`, `id_user`, `name`) VALUES
(1, 1, 'Musik saya'),
(2, 1, 'Musik saya'),
(3, 1, 'Petani'),
(4, 1, 'Petani'),
(5, 1, 'dsadsad');

-- --------------------------------------------------------

--
-- Table structure for table `playlist_details`
--

CREATE TABLE `playlist_details` (
  `id_playlist` int NOT NULL,
  `id_music` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `id_reply` int NOT NULL,
  `id_feed` int NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `artist_name` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `artist_name`, `password`, `email`, `phone_number`, `gender`, `region`, `date_of_birth`, `photo`, `role`) VALUES
(1, 'user-1', 'Misaki Zuijin', '$2y$10$zdhOF/z0Yo4sazu7GtckM.utiffVOaOjr.RI12PTg/IB6vDGtaw1a', 'misakizuijin@gmail.com', '087837867127', 'Other', 'Indonesia', '2024-12-09', '8c092e66416f562d34aa5dabda4581aa.jpg', 'user'),
(3, 'user-21', NULL, '$2y$10$FbxYZnw/d0FBEhRZJgfNV.9SDas/JWFuw3TJQ27kob1DKh/.LPSmi', 'user@gmail.com', NULL, NULL, NULL, NULL, NULL, 'user'),
(4, 'userasd', NULL, '$2y$10$TDlcC4rP0L9sxUW9FPoNUuHzOYJdPLhBjmF.NXx8dPjHQDvKWAhvu', 'userasd@gmail.com', NULL, NULL, NULL, NULL, NULL, 'user'),
(5, 'wqe', NULL, '$2y$10$tUvC2DpL1hf2ryJZV3CGduz2Ub7hM4AJZMMw79/87DRh5fuFQNU5u', 'qwe@gmail.com', NULL, NULL, NULL, NULL, NULL, 'user'),
(6, 'reyga', 'reygasss', '$2y$10$2aBVjg/Sbso8p8x79/7dk.kMMJWHsqrb04fgC0YV0t37pipmVfIfu', 'reyga@gmail.com', '12341123321', 'Male', 'Indonesia', '2024-12-09', '60111.jpg', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id_activity`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_music` (`id_music`);

--
-- Indexes for table `admin_actions`
--
ALTER TABLE `admin_actions`
  ADD PRIMARY KEY (`id_action`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_music` (`id_music`);

--
-- Indexes for table `feeds`
--
ALTER TABLE `feeds`
  ADD PRIMARY KEY (`id_feed`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`id_user_follower`,`id_user_followed`),
  ADD KEY `fk_user_followed` (`id_user_followed`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id_music`,`id_user`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `music`
--
ALTER TABLE `music`
  ADD PRIMARY KEY (`id_music`),
  ADD KEY `fk_music_user` (`id_user`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id_notification`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_music` (`id_music`),
  ADD KEY `id_reply` (`id_reply`);

--
-- Indexes for table `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`id_playlist`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `playlist_details`
--
ALTER TABLE `playlist_details`
  ADD PRIMARY KEY (`id_playlist`,`id_music`),
  ADD KEY `id_music` (`id_music`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id_reply`),
  ADD KEY `id_feed` (`id_feed`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id_activity` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_actions`
--
ALTER TABLE `admin_actions`
  MODIFY `id_action` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feeds`
--
ALTER TABLE `feeds`
  MODIFY `id_feed` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `music`
--
ALTER TABLE `music`
  MODIFY `id_music` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id_notification` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `playlists`
--
ALTER TABLE `playlists`
  MODIFY `id_playlist` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `id_reply` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `activities_ibfk_2` FOREIGN KEY (`id_music`) REFERENCES `music` (`id_music`);

--
-- Constraints for table `admin_actions`
--
ALTER TABLE `admin_actions`
  ADD CONSTRAINT `admin_actions_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `admin_actions_ibfk_2` FOREIGN KEY (`id_music`) REFERENCES `music` (`id_music`);

--
-- Constraints for table `feeds`
--
ALTER TABLE `feeds`
  ADD CONSTRAINT `feeds_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `follow`
--
ALTER TABLE `follow`
  ADD CONSTRAINT `fk_user_followed` FOREIGN KEY (`id_user_followed`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_follower` FOREIGN KEY (`id_user_follower`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`id_music`) REFERENCES `music` (`id_music`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `music`
--
ALTER TABLE `music`
  ADD CONSTRAINT `fk_music_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`id_music`) REFERENCES `music` (`id_music`),
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`id_reply`) REFERENCES `replies` (`id_reply`);

--
-- Constraints for table `playlists`
--
ALTER TABLE `playlists`
  ADD CONSTRAINT `playlists_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `playlist_details`
--
ALTER TABLE `playlist_details`
  ADD CONSTRAINT `playlist_details_ibfk_1` FOREIGN KEY (`id_playlist`) REFERENCES `playlists` (`id_playlist`) ON DELETE CASCADE,
  ADD CONSTRAINT `playlist_details_ibfk_2` FOREIGN KEY (`id_music`) REFERENCES `music` (`id_music`) ON DELETE CASCADE;

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`id_feed`) REFERENCES `feeds` (`id_feed`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
