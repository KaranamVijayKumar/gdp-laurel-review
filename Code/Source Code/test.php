<?php

phpinfo();
?>

-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2020 at 10:04 AM
-- Server version: 5.5.62
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laurelre_site-production`
--

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT '1',
  `inventory` int(10) UNSIGNED DEFAULT NULL,
  `created` int(10) UNSIGNED DEFAULT NULL,
  `modified` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `issue_content`
--

CREATE TABLE `issue_content` (
  `id` int(10) UNSIGNED NOT NULL,
  `issue_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` mediumtext,
  `content_text` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `issue_files`
--

CREATE TABLE `issue_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `issueable_id` int(10) UNSIGNED DEFAULT NULL,
  `issueable_type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `storage_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `access_key` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `preview_key` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `mime` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `meta` text COLLATE utf8_bin,
  `status` tinyint(1) DEFAULT NULL,
  `created` int(10) UNSIGNED DEFAULT NULL,
  `modified` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `issue_toc`
--

CREATE TABLE `issue_toc` (
  `id` int(10) UNSIGNED NOT NULL,
  `issue_id` int(10) UNSIGNED DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `is_header` tinyint(1) DEFAULT NULL,
  `content` text,
  `content_text` text,
  `created` int(10) UNSIGNED DEFAULT NULL,
  `modified` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `issue_toc_contents`
--

CREATE TABLE `issue_toc_contents` (
  `id` int(10) UNSIGNED NOT NULL,
  `issue_id` int(10) UNSIGNED DEFAULT NULL,
  `issue_toc_title_id` int(10) UNSIGNED DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `highlight` tinyint(1) DEFAULT NULL,
  `content` text,
  `content_text` text,
  `created` int(10) UNSIGNED DEFAULT NULL,
  `modified` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `issue_toc_titles`
--

CREATE TABLE `issue_toc_titles` (
  `id` int(10) UNSIGNED NOT NULL,
  `issue_toc_id` int(10) UNSIGNED DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `content` text,
  `content_text` text,
  `created` int(10) UNSIGNED DEFAULT NULL,
  `modified` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slug` (`slug`),
  ADD KEY `title` (`title`),
  ADD KEY `status` (`status`),
  ADD KEY `inventory` (`inventory`);

--
-- Indexes for table `issue_content`
--
ALTER TABLE `issue_content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issue_id` (`issue_id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `issue_files`
--
ALTER TABLE `issue_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issueable_id` (`issueable_id`),
  ADD KEY `issueable_type` (`issueable_type`),
  ADD KEY `name` (`name`),
  ADD KEY `access_key` (`access_key`),
  ADD KEY `preview_key` (`preview_key`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `issue_toc`
--
ALTER TABLE `issue_toc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issue_id` (`issue_id`),
  ADD KEY `order` (`order`),
  ADD KEY `is_header` (`is_header`);

--
-- Indexes for table `issue_toc_contents`
--
ALTER TABLE `issue_toc_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issue_id` (`issue_id`),
  ADD KEY `issue_toc_title_id` (`issue_toc_title_id`),
  ADD KEY `slug` (`slug`),
  ADD KEY `status` (`status`),
  ADD KEY `highlight` (`highlight`);

--
-- Indexes for table `issue_toc_titles`
--
ALTER TABLE `issue_toc_titles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issue_toc_id` (`issue_toc_id`),
  ADD KEY `order` (`order`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `issue_content`
--
ALTER TABLE `issue_content`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `issue_files`
--
ALTER TABLE `issue_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `issue_toc`
--
ALTER TABLE `issue_toc`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1741;

--
-- AUTO_INCREMENT for table `issue_toc_contents`
--
ALTER TABLE `issue_toc_contents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=555;

--
-- AUTO_INCREMENT for table `issue_toc_titles`
--
ALTER TABLE `issue_toc_titles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2472;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `issue_content`
--
ALTER TABLE `issue_content`
  ADD CONSTRAINT `issue_content_ibfk_1` FOREIGN KEY (`issue_id`) REFERENCES `issues` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `issue_toc`
--
ALTER TABLE `issue_toc`
  ADD CONSTRAINT `issue_toc_ibfk_1` FOREIGN KEY (`issue_id`) REFERENCES `issues` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `issue_toc_contents`
--
ALTER TABLE `issue_toc_contents`
  ADD CONSTRAINT `issue_toc_contents_ibfk_1` FOREIGN KEY (`issue_id`) REFERENCES `issues` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `issue_toc_contents_ibfk_2` FOREIGN KEY (`issue_toc_title_id`) REFERENCES `issue_toc_titles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `issue_toc_titles`
--
ALTER TABLE `issue_toc_titles`
  ADD CONSTRAINT `issue_toc_titles_ibfk_1` FOREIGN KEY (`issue_toc_id`) REFERENCES `issue_toc` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

