-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2017 at 11:51 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `estate_agency`
--

-- --------------------------------------------------------

--
-- Table structure for table `enquiries`
--

CREATE TABLE `enquiries` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(500) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IP` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enquiries`
--

INSERT INTO `enquiries` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `IP`) VALUES
(1, 'paulius', 'test@test.com', 'subject', 'messagte', '2017-12-19 11:54:19', ''),
(2, 'asdf', 'asdfa@asdfasd.com', 'asdf', 'asdf', '2017-12-19 13:36:33', '::1'),
(3, 'asdf', 'asdfa@asdfasd.com', 'sdfas', 'asdf', '2017-12-19 14:00:47', '::1'),
(4, 'asdf', 'asdfa@asdfasd.com', 'asdf', 'asdf', '2017-12-19 14:01:54', '::1'),
(5, 'asdf', 'asdfa@asdfasd.com', 'asdf', 'asdfadfa', '2017-12-19 14:05:38', '::1'),
(6, 'asdf', 'asdfa@asdfasd.com', 'asdf', 'asdf', '2017-12-19 14:07:54', '::1'),
(7, 'asdf', 'asdfa@asdfasd.com', 'asdf', 'asdf', '2017-12-19 14:08:46', '::1'),
(8, 'asdf', 'asdfa@asdfasd.com', 'asdf', 'asdf', '2017-12-19 14:08:52', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `propertyType` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rentalProperty` tinyint(1) NOT NULL,
  `noOfBedrooms` int(11) NOT NULL,
  `price` double(10,2) NOT NULL,
  `postcode` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `addressline1` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `addressline2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `title`, `propertyType`, `status`, `area`, `description`, `rentalProperty`, `noOfBedrooms`, `price`, `postcode`, `addressline1`, `addressline2`, `images`, `created_at`, `lat`, `lng`, `updated_at`) VALUES
(77, 'asfa', 'houses', 'underOffer', 'greenwich', 'asdfddxx ee', 0, 1, 3232.00, 'N19LL', '', NULL, '5a3b01215658d,5a3b62f7c2715', '2017-12-21 00:41:55', 51.532574, -0.109044, '2017-12-21 08:29:59'),
(79, 'default', 'flats', 'underOffer', 'kingscross', 'asdfasd', 0, 3, 333.00, 'N19LL', '', NULL, '5a3b70ba84b23', '2017-12-21 08:58:33', 51.532574, -0.109044, '2017-12-21 09:28:42');

-- --------------------------------------------------------

--
-- Table structure for table `sellingrequests`
--

CREATE TABLE `sellingrequests` (
  `id` int(10) UNSIGNED NOT NULL,
  `requestType` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `propertyType` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `noOfbedrooms` int(11) NOT NULL,
  `price` double(10,2) NOT NULL,
  `postcode` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `IP` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sellingrequests`
--

INSERT INTO `sellingrequests` (`id`, `requestType`, `name`, `email`, `propertyType`, `description`, `noOfbedrooms`, `price`, `postcode`, `created_at`, `IP`) VALUES
(64, 'sell', 'asdf', 'asdfa@asdfasd.com', 'houses', 'asdf asdf asdf', 3, 0.00, 'asdf', NULL, ''),
(65, 'rent', 'asdf', 'asdfa@asdfasd.com', 'bungalows', 'asdf xxx', 2, 0.00, 'adsf', '2017-12-19 13:33:42', '::1'),
(66, 'sell', 'asdf', 'asdfa@asdfasd.com', 'flats', 'asdfasdf', 3, 32.00, 'asd', '2017-12-19 14:09:13', '::1'),
(67, 'sell', 'asdf', 'asdfa@asdfasd.com', 'flats', 'asdf', 2, 12.00, 'asfd', '2017-12-19 14:10:04', '::1'),
(68, 'sell', 'asdf', 'asdfa@asdfasd.com', 'bungalows', 'asdfasdf', 3, 333.00, 'asdf', '2017-12-19 15:43:40', '::1'),
(69, 'rent', 'asdf', 'asdfa@asdfasd.com', 'houses', 'asdfasdf', 3, 32.00, 'sadf', '2017-12-19 15:44:13', '::1'),
(70, 'sell', 'asdf', 'asdfa@asdfasd.com', 'flats', 'asdfadf', 3, 23.00, 'dsaf', '2017-12-19 15:47:50', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `firstname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Paulius', 'Gaida', 'paulius', 'paulius@mail.com', 'xxxx', '2017-11-18 13:27:41', '2017-11-18 13:27:41'),
(2, 'bbbb', 'fff', 'www', 'test@mail.com', 'fadfa', NULL, NULL),
(4, 'ddd', 'aaa', 'asdfasdf', 'tea@asdf.com', '$2y$10$uokXuEc0ojArbyoft/vrWOLR6OlYVjQ6q88tad8vhK1Fsi8I.MG0W', '2017-12-05 23:23:36', NULL),
(16, 'paulius', 'paulius', 'paulius2', 'test@test2.com', '$2y$10$eXeA8KPsKgrw3dJayhrmpON7ljp4RcJ3OfUXwO0VxWdSCXMWdKMDi', '2017-12-17 16:03:19', NULL),
(17, 'test', 'test', 'test', 'test@test.com', '$2y$10$3BPGhNEp3I4za3XuB0wcduYVRolqx/87.IjvZhAf79NHzZukiNo/m', '2017-12-17 16:07:41', NULL),
(18, 'asdf', 'sadf', 'asdf', 'asdf@asdf.com', '$2y$10$vcqmRMnEtnAZddmwZ4m7KuNpJ9GZmwD89h.HNfTgGJWWWU116EHia', '2017-12-20 16:19:27', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `enquiries`
--
ALTER TABLE `enquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sellingrequests`
--
ALTER TABLE `sellingrequests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `enquiries`
--
ALTER TABLE `enquiries`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT for table `sellingrequests`
--
ALTER TABLE `sellingrequests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
