-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2025 at 07:13 AM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 7.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `healz_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `content`, `created_at`) VALUES
(9, 0, 6, 'dsfs', '2025-02-05 01:17:13'),
(10, 13, 6, 'sss', '2025-02-05 01:17:17'),
(11, 15, 6, 'hjfhffh', '2025-02-06 14:05:53'),
(12, 15, 6, 'mhvhn', '2025-02-06 14:06:06'),
(13, 0, 6, 'dfd', '2025-02-07 13:07:26'),
(14, 19, 6, 'sss', '2025-02-07 13:07:30'),
(15, 20, 6, 'aaa', '2025-02-07 13:14:32'),
(16, 20, 6, 'sss', '2025-02-07 13:21:33'),
(17, 20, 6, 'sss', '2025-02-07 13:22:46'),
(18, 20, 6, 'sss', '2025-02-07 13:23:55'),
(19, 20, 6, 'ss', '2025-02-07 13:24:01'),
(20, 0, 6, 'ss', '2025-02-07 13:25:00'),
(21, 20, 6, 'ss', '2025-02-07 13:29:58'),
(22, 19, 6, 'ss', '2025-02-07 14:07:36'),
(23, 21, 6, 'eee', '2025-02-07 14:10:32'),
(24, 21, 6, 'eee', '2025-02-07 14:10:35'),
(25, 21, 6, 'sss', '2025-02-07 14:11:22'),
(26, 23, 6, 'dd', '2025-02-09 19:59:02'),
(27, 23, 6, 'dd', '2025-02-09 19:59:09'),
(28, 23, 6, 'dsd', '2025-02-09 19:59:21'),
(29, 22, 6, 'dsd', '2025-02-09 19:59:32'),
(30, 23, 6, 'ss', '2025-02-09 20:00:28'),
(31, 20, 6, 'sss', '2025-02-09 20:00:35'),
(32, 24, 6, 'alip', '2025-02-10 04:03:46'),
(33, 24, 6, 'ss', '2025-02-11 17:17:46'),
(34, 25, 6, 'ss', '2025-02-11 17:17:56'),
(35, 30, 6, 'dads', '2025-02-11 19:09:14'),
(36, 31, 6, 'ss', '2025-02-11 19:11:26'),
(37, 32, 8, 'Halo, selamat pagi! Saya tertarik dengan proyek Anda untuk mendesain tampilan web rumah sakit sederhana. Apakah Anda memiliki referensi desain atau fitur khusus yang ingin dimasukkan? Saya siap membantu dan dapat dihubungi lebih lanjut melalui chat atau WhatsApp. Terima kasih! ðŸ˜Š', '2025-02-11 21:02:22'),
(38, 32, 6, 'Baik Mas Boleh Silahkan langsung Hubungi Whatsapp Diatas', '2025-02-12 03:03:44');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(32, 32, 6, '2025-02-11 20:54:42'),
(33, 32, 8, '2025-02-11 21:01:30'),
(34, 32, 9, '2025-02-11 21:14:58'),
(35, 33, 9, '2025-02-11 21:15:24'),
(36, 32, 7, '2025-02-12 02:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender` varchar(50) DEFAULT NULL,
  `receiver` varchar(50) DEFAULT NULL,
  `message` text,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender`, `receiver`, `message`, `timestamp`, `created_at`) VALUES
(75, 'baros', 'david', 'Location: Jalan Sekeloa Utara, Sekeloa, Coblong, Bandung City, West Java, Java, 40133, Indonesia (Lat: -6.888423731315748, Lon: 107.61940284805306)\r\n        <div class="map-container" style="width: 100%; height: 200px;">\r\n            <div id="map--6.888423731315748-107.61940284805306" style="height: 100%;"></div>\r\n        </div>\r\n    ', '2025-02-12 02:25:52', NULL),
(76, 'baros', 'zaidan123', 'halo', '2025-02-12 03:04:27', NULL),
(77, 'david', 'zaidan123', 'Location: Universitas Komputer Indonesia, 12, Jalan Dipatiukur, Lebak Gede, Coblong, Bandung City, West Java, Java, 40132, Indonesia (Lat: -6.8873074627886295, Lon: 107.61524096075712)\r\n        <div class="map-container" style="width: 100%; height: 200px;">\r\n            <div id="map--6.8873074627886295-107.61524096075712" style="height: 100%;"></div>\r\n        </div>\r\n    ', '2025-02-12 03:05:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `image`, `created_at`) VALUES
(32, 6, 'Halo selamat pagi, saya mencari desainer untuk membuatkan tampilan web saya yang berupa web rumah sakit sederhana. Jika ada yang berminat untuk membuatnya bisa menghubungi melalui chat, komentar atau whatsapp ke wa.me/6289828909232', NULL, '2025-02-11 20:54:32'),
(33, 9, 'Morning Guys', NULL, '2025-02-11 21:15:19');

-- --------------------------------------------------------

--
-- Table structure for table `post_logs`
--

CREATE TABLE `post_logs` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product_posts`
--

CREATE TABLE `product_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `asset_type` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `license` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_posts`
--

INSERT INTO `product_posts` (`id`, `user_id`, `title`, `description`, `price`, `image`, `created_at`, `asset_type`, `category`, `license`) VALUES
(3, 6, 'Jasa Pembuatan Logo', 'Jasa Pembuatan Logo Start from 50K', '50000.00', '..\\assets\\images\\Jasa Pembuatan Logo1.jpg', '2025-02-06 14:37:27', 'Vectors', 'Branding', 'Paid'),
(4, 6, 'Jasa Pembuatan Logo', 'Jasa Pembuatan Logo Start from 200K', '200000.00', '..\\assets\\images\\Blur Band Logo.jpg', '2025-02-06 14:37:27', 'Vectors', 'Branding', 'Paid'),
(5, 6, 'Jasa Editing', 'Jasa Editing', '300000.00', '..\\assets\\images\\Illustrator.jpg', '2025-02-06 14:37:27', 'Vectors', 'Branding', 'Paid'),
(6, 6, 'Jasa Editing', 'Jasa Editing', '400000.00', '..\\assets\\images\\Illustrator2.jpg', '2025-02-06 14:37:27', 'Vectors', 'Branding', 'Paid'),
(11, 7, 'Jasa Edit Logo', 'Logo', '12121.00', '../assets/images/120058898_2773999276262860_1479075055984138500_n.jpg', '2025-02-06 21:08:18', 'Icons', 'Branding', 'Paid'),
(12, 7, 'Game Assets', 'Tiles', '40000.00', '../assets/images/tiles 1x1Asset 2.png', '2025-02-06 21:13:22', 'Vectors', 'Illustration', 'Paid'),
(13, 7, 'Branding Design', 'Branding Design', '2000000.00', '../assets/images/1.png', '2025-02-07 09:00:26', 'Vectors', 'Branding', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `profile_picture`, `latitude`, `longitude`) VALUES
(6, 'baros123', 'baros@bb.b', '$2y$10$ffm1c2DQ7Go6NJ4Oau5yzelx54s2GeGIEU1XSOqwNAZ9ucFey.ncK', '../assets/images/avatar04.png', -6.91744, 107.21612),
(7, 'david', 'david919@gmail.com', '$2y$10$mSDqYQ28nv7/THjFcz0//ezYPfFpQnuLbEO/EEcpmrtUzB06sboue', '../assets/images/48cf4-16839084706767-1920.webp', 40.7128, 74.006),
(8, 'didadanu', 'didadanuwijaya@mail.com', '$2y$10$xikGOju9Q.twPrhfUji3MO/7mxj5P6Mw87H5M7WOQlRD90D/.mNyG', NULL, NULL, NULL),
(9, 'daapinn', 'dapin@aaa.aaaa', '$2y$10$bhc13gwLHXgnHmWGU7.pGO6pxHT8/yFnmsJUUiccLJYMXPCsOrqOm', '../assets/images/WhatsApp Image 2024-10-30 at 17.34.13_5a84a5a4.jpg', NULL, NULL),
(10, 'zaidan123', 'zaidan@mail.com', '$2y$10$17cuxgXzqh30S.j98grVkOsyQcnjjUxj6whe2j7hgBui.TbZ8.13i', '../assets/images/Screenshot_20241029_185106_YouTube.jpg', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_logs`
--
ALTER TABLE `post_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product_posts`
--
ALTER TABLE `product_posts`
  ADD PRIMARY KEY (`id`),
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
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `post_logs`
--
ALTER TABLE `post_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `product_posts`
--
ALTER TABLE `product_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_logs`
--
ALTER TABLE `post_logs`
  ADD CONSTRAINT `post_logs_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `post_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `product_posts`
--
ALTER TABLE `product_posts`
  ADD CONSTRAINT `product_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
