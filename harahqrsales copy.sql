-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2025 at 06:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `harahqrsales`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_disabled` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `created_at`, `is_disabled`) VALUES
(1, 'Main Dishes', 'Hearty and filling main course options', '2025-03-13 00:35:34', 0),
(2, 'Appetizers', 'Light bites to start your meal', '2025-03-13 00:35:34', 0),
(3, 'Beverages', 'Refreshing drinks and beverages', '2025-03-13 00:35:34', 0),
(4, 'Desserts', 'Sweet treats to end your meal', '2025-03-13 00:35:34', 0),
(5, 'Rice Meals', 'Complete meals served with rice', '2025-03-13 00:35:34', 0),
(6, 'Soups', 'Warm and comforting soup selections', '2025-03-13 00:35:34', 0),
(7, 'Salads', 'Fresh and healthy salad options', '2025-03-13 00:35:34', 0),
(8, 'Main Dishes', 'Hearty and filling main course options', '2025-03-13 00:35:49', 0),
(9, 'Appetizers', 'Light bites to start your meal', '2025-03-13 00:35:49', 0),
(10, 'Beverages', 'Refreshing drinks and beverages', '2025-03-13 00:35:49', 0),
(11, 'Desserts', 'Sweet treats to end your meal', '2025-03-13 00:35:49', 0),
(12, 'Rice Meals', 'Complete meals served with rice', '2025-03-13 00:35:49', 0),
(13, 'Soups', 'Warm and comforting soup selections', '2025-03-13 00:35:49', 0),
(14, 'Salads', 'Fresh and healthy salad options', '2025-03-13 00:35:49', 0),
(15, 'Main Dishes', 'Hearty and filling main course options', '2025-03-13 00:35:53', 0),
(16, 'Appetizers', 'Light bites to start your meal', '2025-03-13 00:35:53', 0),
(17, 'Beverages', 'Refreshing drinks and beverages', '2025-03-13 00:35:53', 0),
(18, 'Desserts', 'Sweet treats to end your meal', '2025-03-13 00:35:53', 0),
(19, 'Rice Meals', 'Complete meals served with rice', '2025-03-13 00:35:53', 0),
(21, 'Salads', 'Fresh and healthy salad options', '2025-03-13 00:35:53', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `type` enum('ORDER_READY','TABLE_STATUS','PAYMENT','SOUND_ALERT') NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `order_id`, `message`, `type`, `is_read`, `created_at`) VALUES
(1, 1, 'New order received', 'ORDER_READY', 0, '2025-03-13 00:47:18'),
(2, 1, 'New paid order ready for preparation', '', 0, '2025-03-13 00:57:40'),
(3, 1, 'Order #1 is ready for service', 'ORDER_READY', 0, '2025-03-13 01:01:58'),
(4, 1, 'New paid order ready for preparation', '', 0, '2025-03-13 01:18:26'),
(5, 1, 'Table 1 - Order #1 is ready for service', 'ORDER_READY', 0, '2025-03-13 01:28:53'),
(6, 1, 'Table 1 - Order #1 is ready for service', 'ORDER_READY', 0, '2025-03-13 01:28:53'),
(7, 2, 'New order received from Table #2', 'ORDER_READY', 0, '2025-03-13 01:38:53'),
(8, 2, 'New paid order ready for preparation', '', 0, '2025-03-13 01:39:49'),
(9, 2, 'Table 2 - Order #2 is ready for service', 'ORDER_READY', 0, '2025-03-13 01:40:23'),
(10, 2, 'Table 2 - Order #2 is ready for service', 'ORDER_READY', 0, '2025-03-13 01:40:23'),
(11, 3, 'New order received from Table #3', 'ORDER_READY', 0, '2025-03-13 02:03:51'),
(12, 3, 'New paid order ready for preparation', '', 0, '2025-03-13 02:05:35'),
(13, 4, 'New order received from Table #', 'ORDER_READY', 0, '2025-03-13 02:14:39'),
(14, 5, 'New order received from Table #', 'ORDER_READY', 0, '2025-03-13 02:14:55'),
(15, 6, 'New order received from Table #1', 'ORDER_READY', 0, '2025-03-13 03:16:14'),
(16, 7, 'New order received from Table #6', 'ORDER_READY', 0, '2025-03-13 03:22:58'),
(17, 8, 'New order received from Table #1', 'ORDER_READY', 0, '2025-03-13 03:25:52'),
(18, 9, 'New order received from Table #1', 'ORDER_READY', 0, '2025-03-13 03:26:42'),
(19, 10, 'New order received from Table #1', 'ORDER_READY', 0, '2025-03-13 03:31:37'),
(20, 11, 'New order received from Table #1', 'ORDER_READY', 0, '2025-03-13 03:33:29'),
(21, 12, 'New order received from Table #1', 'ORDER_READY', 0, '2025-03-13 03:34:49'),
(22, 12, 'New paid order ready for preparation', '', 0, '2025-03-13 03:36:16'),
(23, 11, 'New paid order ready for preparation', '', 0, '2025-03-13 03:38:16'),
(24, 4, 'Table 3 - Order #4 is being prepared', '', 0, '2025-03-13 03:38:59'),
(25, 5, 'Table 3 - Order #5 is being prepared', '', 0, '2025-03-13 03:39:02'),
(26, 6, 'Table 1 - Order #6 is being prepared', '', 0, '2025-03-13 03:39:08'),
(27, 7, 'Table 6 - Order #7 is being prepared', '', 0, '2025-03-13 03:39:09'),
(28, 8, 'Table 1 - Order #8 is being prepared', '', 0, '2025-03-13 03:39:10'),
(29, 9, 'Table 1 - Order #9 is being prepared', '', 0, '2025-03-13 03:39:11'),
(30, 10, 'Table 1 - Order #10 is being prepared', '', 0, '2025-03-13 03:39:12'),
(31, 3, 'Table 3 - Order #3 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:39:52'),
(32, 3, 'Table 3 - Order #3 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:39:52'),
(33, 5, 'Table 3 - Order #5 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:39:54'),
(34, 5, 'Table 3 - Order #5 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:39:54'),
(35, 4, 'Table 3 - Order #4 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:39:56'),
(36, 4, 'Table 3 - Order #4 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:39:56'),
(37, 11, 'Table 1 - Order #11 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:40:00'),
(38, 11, 'Table 1 - Order #11 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:40:00'),
(39, 6, 'Table 1 - Order #6 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:40:01'),
(40, 6, 'Table 1 - Order #6 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:40:01'),
(41, 7, 'Table 6 - Order #7 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:40:06'),
(42, 7, 'Table 6 - Order #7 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:40:06'),
(43, 9, 'Table 1 - Order #9 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:40:08'),
(44, 9, 'Table 1 - Order #9 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:40:08'),
(45, 10, 'Table 1 - Order #10 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:40:09'),
(46, 10, 'Table 1 - Order #10 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:40:09'),
(47, 8, 'Table 1 - Order #8 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:40:09'),
(48, 8, 'Table 1 - Order #8 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:40:09'),
(49, 12, 'Table 1 - Order #12 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:40:10'),
(50, 12, 'Table 1 - Order #12 is ready for service', 'ORDER_READY', 0, '2025-03-13 03:40:10'),
(51, 10, 'New paid order ready for preparation', '', 0, '2025-03-13 03:45:53'),
(52, 13, 'New order received from Table #', 'ORDER_READY', 0, '2025-03-13 03:56:16'),
(53, 14, 'New order received from Table #', 'ORDER_READY', 0, '2025-03-13 03:56:31'),
(54, 15, 'New order received from Table #', 'ORDER_READY', 0, '2025-03-13 03:56:35'),
(55, 16, 'New order received from Table #', 'ORDER_READY', 0, '2025-03-13 03:56:37'),
(56, 17, 'New order received from Table #', 'ORDER_READY', 0, '2025-03-13 03:56:38'),
(57, 18, 'New order received from Table #', 'ORDER_READY', 0, '2025-03-13 03:59:21'),
(58, 19, 'New order received from Table #1', 'ORDER_READY', 0, '2025-03-13 03:59:33'),
(59, 20, 'New order received from Table #7', 'ORDER_READY', 0, '2025-03-13 04:02:29'),
(60, 19, 'New paid order ready for preparation', '', 0, '2025-03-13 04:03:20'),
(61, 20, 'New paid order ready for preparation', '', 0, '2025-03-13 04:03:35'),
(62, 10, 'Table 1 - Order #10 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:09'),
(63, 10, 'Table 1 - Order #10 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:09'),
(64, 13, 'Table 1 - Order #13 is being prepared', '', 0, '2025-03-13 04:04:11'),
(65, 14, 'Table 1 - Order #14 is being prepared', '', 0, '2025-03-13 04:04:12'),
(66, 15, 'Table 1 - Order #15 is being prepared', '', 0, '2025-03-13 04:04:12'),
(67, 16, 'Table 1 - Order #16 is being prepared', '', 0, '2025-03-13 04:04:14'),
(68, 17, 'Table 1 - Order #17 is being prepared', '', 0, '2025-03-13 04:04:15'),
(69, 18, 'Table 1 - Order #18 is being prepared', '', 0, '2025-03-13 04:04:16'),
(70, 19, 'Table 1 - Order #19 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:17'),
(71, 19, 'Table 1 - Order #19 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:17'),
(72, 18, 'Table 1 - Order #18 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:18'),
(73, 18, 'Table 1 - Order #18 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:18'),
(74, 13, 'Table 1 - Order #13 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:19'),
(75, 13, 'Table 1 - Order #13 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:19'),
(76, 15, 'Table 1 - Order #15 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:20'),
(77, 15, 'Table 1 - Order #15 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:20'),
(78, 17, 'Table 1 - Order #17 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:21'),
(79, 17, 'Table 1 - Order #17 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:21'),
(80, 16, 'Table 1 - Order #16 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:22'),
(81, 16, 'Table 1 - Order #16 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:22'),
(82, 14, 'Table 1 - Order #14 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:23'),
(83, 14, 'Table 1 - Order #14 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:23'),
(84, 20, 'Table 7 - Order #20 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:24'),
(85, 20, 'Table 7 - Order #20 is ready for service', 'ORDER_READY', 0, '2025-03-13 04:04:24'),
(86, 18, 'New paid order ready for preparation', '', 0, '2025-03-13 04:09:06'),
(87, 17, 'New paid order ready for preparation', '', 0, '2025-03-13 04:20:06'),
(88, 16, 'New paid order ready for preparation', '', 0, '2025-03-13 04:26:50'),
(89, 15, 'New paid order ready for preparation', '', 0, '2025-03-13 04:35:55'),
(90, 14, 'New paid order ready for preparation', '', 0, '2025-03-13 04:40:16'),
(91, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:10'),
(92, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:12'),
(93, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:14'),
(94, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:16'),
(95, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:18'),
(96, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:21'),
(97, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:23'),
(98, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:25'),
(99, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:27'),
(100, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:28'),
(101, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:30'),
(102, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:32'),
(103, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:35'),
(104, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:37'),
(105, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:39'),
(106, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:41'),
(107, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:43'),
(108, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:45'),
(109, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:47'),
(110, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:49'),
(111, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:51'),
(112, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:53'),
(113, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:55'),
(114, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:57'),
(115, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 04:59:59'),
(116, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:01'),
(117, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:03'),
(118, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:05'),
(119, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:07'),
(120, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:09'),
(121, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:11'),
(122, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:13'),
(123, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:15'),
(124, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:17'),
(125, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:19'),
(126, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:21'),
(127, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:23'),
(128, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:25'),
(129, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:27'),
(130, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:29'),
(131, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:00:31'),
(132, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:01:16'),
(133, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:02:16'),
(134, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:02:52'),
(135, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:02:52'),
(136, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:02:55'),
(137, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:02:57'),
(138, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:02:59'),
(139, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:01'),
(140, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:03'),
(141, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:05'),
(142, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:07'),
(143, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:09'),
(144, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:11'),
(145, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:13'),
(146, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:15'),
(147, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:17'),
(148, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:19'),
(149, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:21'),
(150, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:23'),
(151, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:25'),
(152, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:27'),
(153, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:29'),
(154, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:31'),
(155, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:33'),
(156, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:35'),
(157, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:37'),
(158, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:39'),
(159, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:41'),
(160, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:43'),
(161, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:45'),
(162, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:47'),
(163, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:49'),
(164, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:51'),
(165, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:03:53'),
(166, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:04:16'),
(167, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:05:16'),
(168, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:06:16'),
(169, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:07:16'),
(170, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:08:16'),
(171, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:09:16'),
(172, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:10:16'),
(173, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:10:35'),
(174, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:10:36'),
(175, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:10:38'),
(176, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:10:40'),
(177, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:10:42'),
(178, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:10:46'),
(179, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:10:47'),
(180, 21, 'New order received from Table #', 'ORDER_READY', 0, '2025-03-13 05:10:48'),
(181, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:10:49'),
(182, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:10:51'),
(183, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:10:53'),
(184, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:10:55'),
(185, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:10:57'),
(186, 22, 'New order received from Table #7', 'ORDER_READY', 0, '2025-03-13 05:10:58'),
(187, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:10:59'),
(188, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:01'),
(189, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:02'),
(190, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:14'),
(191, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:16'),
(192, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:18'),
(193, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:20'),
(194, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:22'),
(195, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:25'),
(196, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:27'),
(197, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:29'),
(198, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:31'),
(199, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:33'),
(200, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:35'),
(201, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:37'),
(202, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:39'),
(203, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:41'),
(204, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:43'),
(205, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:45'),
(206, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:47'),
(207, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:49'),
(208, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:51'),
(209, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:53'),
(210, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:55'),
(211, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:57'),
(212, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:11:59'),
(213, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:12:01'),
(214, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:12:03'),
(215, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:12:04'),
(216, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:13:38'),
(217, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:13:40'),
(218, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:13:42'),
(219, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:13:44'),
(220, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:13:46'),
(221, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:13:48'),
(222, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:13:50'),
(223, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:13:52'),
(224, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:13:54'),
(225, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:13:56'),
(226, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:13:58'),
(227, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:00'),
(228, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:02'),
(229, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:05'),
(230, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:07'),
(231, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:09'),
(232, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:11'),
(233, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:13'),
(234, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:15'),
(235, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:17'),
(236, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:19'),
(237, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:21'),
(238, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:23'),
(239, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:25'),
(240, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:27'),
(241, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:29'),
(242, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:31'),
(243, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:33'),
(244, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:35'),
(245, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:37'),
(246, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:39'),
(247, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:41'),
(248, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:43'),
(249, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:45'),
(250, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:47'),
(251, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:49'),
(252, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:51'),
(253, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:53'),
(254, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:55'),
(255, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:57'),
(256, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:14:59'),
(257, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:15:01'),
(258, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:15:03'),
(259, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:15:08'),
(260, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:15:10'),
(261, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:15:12'),
(262, 21, 'New paid order ready for preparation', '', 0, '2025-03-13 05:17:10'),
(263, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:24'),
(264, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:26'),
(265, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:28'),
(266, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:30'),
(267, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:33'),
(268, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:35'),
(269, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:37'),
(270, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:39'),
(271, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:41'),
(272, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:43'),
(273, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:44'),
(274, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:46'),
(275, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:48'),
(276, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:50'),
(277, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:52'),
(278, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:54'),
(279, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:56'),
(280, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:18:58'),
(281, NULL, 'New order alert from cashier', 'SOUND_ALERT', 0, '2025-03-13 05:19:00'),
(282, 23, 'New order received from Table #', 'ORDER_READY', 0, '2025-03-13 05:19:19'),
(283, 24, 'New order received from Table #7', 'ORDER_READY', 0, '2025-03-13 05:19:26');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `table_id` int(11) DEFAULT NULL,
  `order_type` enum('QR','WALK_IN') NOT NULL,
  `status` enum('PENDING','PAID','PREPARING','COMPLETED','DELIVERED') DEFAULT 'PENDING',
  `payment_method` enum('CASH','GCASH') DEFAULT NULL,
  `payment_status` enum('PENDING','PAID') DEFAULT 'PENDING',
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `table_id`, `order_type`, `status`, `payment_method`, `payment_status`, `total_amount`, `created_at`, `updated_at`) VALUES
(1, 1, 'QR', '', 'CASH', 'PAID', 637.00, '2025-03-13 00:47:18', '2025-03-13 01:28:52'),
(2, 2, 'QR', '', 'CASH', 'PAID', 606.00, '2025-03-13 01:38:53', '2025-03-13 01:40:23'),
(3, 3, 'QR', '', '', 'PAID', 1015.00, '2025-03-13 02:03:51', '2025-03-13 03:39:52'),
(4, 3, 'QR', '', NULL, 'PENDING', 537.00, '2025-03-13 02:14:39', '2025-03-13 03:39:56'),
(5, 3, 'QR', '', NULL, 'PENDING', 606.00, '2025-03-13 02:14:55', '2025-03-13 03:39:54'),
(6, 1, 'QR', '', NULL, 'PENDING', 598.00, '2025-03-13 03:16:14', '2025-03-13 03:40:01'),
(7, 9, 'QR', '', NULL, 'PENDING', 537.00, '2025-03-13 03:22:58', '2025-03-13 03:40:06'),
(8, 1, 'QR', '', NULL, 'PENDING', 836.00, '2025-03-13 03:25:52', '2025-03-13 03:40:09'),
(9, 1, 'QR', '', NULL, 'PENDING', 836.00, '2025-03-13 03:26:42', '2025-03-13 03:40:08'),
(10, 1, 'QR', '', 'CASH', 'PAID', 537.00, '2025-03-13 03:31:37', '2025-03-13 04:04:09'),
(11, 1, 'QR', '', 'CASH', 'PAID', 2548.00, '2025-03-13 03:33:29', '2025-03-13 03:40:00'),
(12, 1, 'QR', '', 'GCASH', 'PAID', 656.00, '2025-03-13 03:34:49', '2025-03-13 03:40:10'),
(13, 1, 'QR', '', NULL, 'PENDING', 338.00, '2025-03-13 03:56:16', '2025-03-13 04:04:19'),
(14, 1, 'QR', 'PREPARING', 'CASH', 'PAID', 936.00, '2025-03-13 03:56:31', '2025-03-13 04:40:16'),
(15, 1, 'QR', 'PREPARING', 'CASH', 'PAID', 936.00, '2025-03-13 03:56:35', '2025-03-13 04:35:55'),
(16, 1, 'QR', 'PREPARING', 'CASH', 'PAID', 936.00, '2025-03-13 03:56:37', '2025-03-13 04:26:50'),
(17, 1, 'QR', 'PREPARING', 'CASH', 'PAID', 936.00, '2025-03-13 03:56:38', '2025-03-13 04:20:06'),
(18, 1, 'QR', 'PREPARING', 'CASH', 'PAID', 1534.00, '2025-03-13 03:59:21', '2025-03-13 04:09:06'),
(19, 1, 'QR', '', 'CASH', 'PAID', 1125.00, '2025-03-13 03:59:33', '2025-03-13 04:04:17'),
(20, 10, 'QR', '', 'CASH', 'PAID', 1323.00, '2025-03-13 04:02:28', '2025-03-13 04:04:24'),
(21, 10, 'QR', 'PREPARING', 'CASH', 'PAID', 598.00, '2025-03-13 05:10:48', '2025-03-13 05:17:10'),
(22, 10, 'QR', 'PENDING', NULL, 'PENDING', 517.00, '2025-03-13 05:10:58', '2025-03-13 05:10:58'),
(23, 10, 'QR', 'PENDING', NULL, 'PENDING', 766.00, '2025-03-13 05:19:19', '2025-03-13 05:19:19'),
(24, 10, 'QR', 'PENDING', NULL, 'PENDING', 398.00, '2025-03-13 05:19:26', '2025-03-13 05:19:26');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `created_at`) VALUES
(1, 1, 85, 1, 299.00, 299.00, '2025-03-13 00:47:18'),
(2, 1, 102, 2, 169.00, 338.00, '2025-03-13 00:47:18'),
(3, 2, 87, 1, 229.00, 229.00, '2025-03-13 01:38:53'),
(4, 2, 98, 1, 99.00, 99.00, '2025-03-13 01:38:53'),
(5, 2, 112, 2, 139.00, 278.00, '2025-03-13 01:38:53'),
(6, 3, 89, 1, 189.00, 189.00, '2025-03-13 02:03:51'),
(7, 3, 90, 1, 199.00, 199.00, '2025-03-13 02:03:51'),
(8, 3, 103, 1, 149.00, 149.00, '2025-03-13 02:03:51'),
(9, 3, 106, 1, 299.00, 299.00, '2025-03-13 02:03:51'),
(10, 3, 109, 1, 179.00, 179.00, '2025-03-13 02:03:51'),
(11, 4, 89, 1, 189.00, 189.00, '2025-03-13 02:14:39'),
(12, 4, 102, 1, 169.00, 169.00, '2025-03-13 02:14:39'),
(13, 4, 109, 1, 179.00, 179.00, '2025-03-13 02:14:39'),
(14, 5, 89, 1, 189.00, 189.00, '2025-03-13 02:14:55'),
(15, 5, 94, 1, 69.00, 69.00, '2025-03-13 02:14:55'),
(16, 5, 102, 1, 169.00, 169.00, '2025-03-13 02:14:55'),
(17, 5, 109, 1, 179.00, 179.00, '2025-03-13 02:14:55'),
(18, 6, 85, 2, 299.00, 598.00, '2025-03-13 03:16:14'),
(19, 7, 91, 3, 179.00, 537.00, '2025-03-13 03:22:58'),
(20, 8, 85, 1, 299.00, 299.00, '2025-03-13 03:25:52'),
(21, 8, 90, 1, 199.00, 199.00, '2025-03-13 03:25:52'),
(22, 8, 102, 2, 169.00, 338.00, '2025-03-13 03:25:52'),
(23, 9, 85, 1, 299.00, 299.00, '2025-03-13 03:26:42'),
(24, 9, 90, 1, 199.00, 199.00, '2025-03-13 03:26:42'),
(25, 9, 102, 2, 169.00, 338.00, '2025-03-13 03:26:42'),
(26, 10, 90, 1, 199.00, 199.00, '2025-03-13 03:31:37'),
(27, 10, 102, 2, 169.00, 338.00, '2025-03-13 03:31:37'),
(28, 11, 85, 4, 299.00, 1196.00, '2025-03-13 03:33:29'),
(29, 11, 102, 8, 169.00, 1352.00, '2025-03-13 03:33:29'),
(30, 12, 88, 2, 259.00, 518.00, '2025-03-13 03:34:49'),
(31, 12, 94, 2, 69.00, 138.00, '2025-03-13 03:34:49'),
(32, 13, 102, 2, 169.00, 338.00, '2025-03-13 03:56:16'),
(33, 14, 85, 2, 299.00, 598.00, '2025-03-13 03:56:31'),
(34, 14, 102, 2, 169.00, 338.00, '2025-03-13 03:56:31'),
(35, 15, 85, 2, 299.00, 598.00, '2025-03-13 03:56:35'),
(36, 15, 102, 2, 169.00, 338.00, '2025-03-13 03:56:35'),
(37, 16, 85, 2, 299.00, 598.00, '2025-03-13 03:56:37'),
(38, 16, 102, 2, 169.00, 338.00, '2025-03-13 03:56:37'),
(39, 17, 85, 2, 299.00, 598.00, '2025-03-13 03:56:38'),
(40, 17, 102, 2, 169.00, 338.00, '2025-03-13 03:56:38'),
(41, 18, 85, 4, 299.00, 1196.00, '2025-03-13 03:59:21'),
(42, 18, 102, 2, 169.00, 338.00, '2025-03-13 03:59:21'),
(43, 19, 85, 1, 299.00, 299.00, '2025-03-13 03:59:33'),
(44, 19, 102, 1, 169.00, 169.00, '2025-03-13 03:59:33'),
(45, 19, 106, 1, 299.00, 299.00, '2025-03-13 03:59:33'),
(46, 19, 109, 2, 179.00, 358.00, '2025-03-13 03:59:33'),
(47, 20, 89, 1, 189.00, 189.00, '2025-03-13 04:02:28'),
(48, 20, 90, 1, 199.00, 199.00, '2025-03-13 04:02:28'),
(49, 20, 97, 1, 129.00, 129.00, '2025-03-13 04:02:28'),
(50, 20, 101, 1, 159.00, 159.00, '2025-03-13 04:02:28'),
(51, 20, 102, 1, 169.00, 169.00, '2025-03-13 04:02:28'),
(52, 20, 106, 1, 299.00, 299.00, '2025-03-13 04:02:29'),
(53, 20, 109, 1, 179.00, 179.00, '2025-03-13 04:02:29'),
(54, 21, 85, 2, 299.00, 598.00, '2025-03-13 05:10:48'),
(55, 22, 89, 1, 189.00, 189.00, '2025-03-13 05:10:58'),
(56, 22, 90, 1, 199.00, 199.00, '2025-03-13 05:10:58'),
(57, 22, 97, 1, 129.00, 129.00, '2025-03-13 05:10:58'),
(58, 23, 90, 3, 199.00, 597.00, '2025-03-13 05:19:19'),
(59, 23, 102, 1, 169.00, 169.00, '2025-03-13 05:19:19'),
(60, 24, 90, 2, 199.00, 398.00, '2025-03-13 05:19:26');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_disabled` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `name`, `description`, `price`, `image_url`, `is_available`, `created_at`, `is_disabled`) VALUES
(85, 1, 'Beef Steak', 'Tender beef steak served with mashed potatoes and vegetables', 299.00, NULL, 1, '2025-03-13 00:35:53', 0),
(86, 1, 'Grilled Chicken', 'Herb-marinated chicken with roasted vegetables', 249.00, NULL, 1, '2025-03-13 00:35:53', 0),
(87, 1, 'Fish Fillet', 'Pan-seared fish fillet with lemon butter sauce', 229.00, NULL, 1, '2025-03-13 00:35:53', 0),
(88, 1, 'Pork Chop', 'Grilled pork chop with apple sauce and sides', 259.00, NULL, 1, '2025-03-13 00:35:53', 0),
(89, 2, 'Calamari Rings', 'Crispy fried calamari served with tartar sauce', 189.00, NULL, 1, '2025-03-13 00:35:53', 0),
(90, 2, 'Buffalo Wings', 'Spicy chicken wings with blue cheese dip', 199.00, NULL, 1, '2025-03-13 00:35:53', 0),
(91, 2, 'Nachos Supreme', 'Loaded nachos with cheese, meat, and vegetables', 179.00, NULL, 1, '2025-03-13 00:35:53', 0),
(92, 2, 'Cheese Sticks', 'Mozzarella cheese sticks with marinara sauce', 159.00, NULL, 1, '2025-03-13 00:35:53', 0),
(93, 3, 'Iced Tea', 'House-made fresh iced tea', 59.00, NULL, 1, '2025-03-13 00:35:53', 0),
(94, 3, 'Lemonade', 'Fresh squeezed lemonade', 69.00, NULL, 1, '2025-03-13 00:35:53', 0),
(95, 3, 'Soda', 'Various soda flavors', 49.00, NULL, 1, '2025-03-13 00:35:53', 0),
(96, 3, 'Fresh Fruit Shake', 'Choice of mango, strawberry, or banana', 89.00, NULL, 1, '2025-03-13 00:35:53', 0),
(97, 4, 'Chocolate Cake', 'Rich chocolate cake with ganache', 129.00, NULL, 1, '2025-03-13 00:35:53', 0),
(98, 4, 'Ice Cream', 'Three scoops of assorted flavors', 99.00, NULL, 1, '2025-03-13 00:35:53', 0),
(99, 4, 'Fruit Salad', 'Fresh fruit medley with cream', 119.00, NULL, 1, '2025-03-13 00:35:53', 0),
(100, 4, 'Leche Flan', 'Classic Filipino caramel custard', 89.00, NULL, 1, '2025-03-13 00:35:53', 0),
(101, 5, 'Chicken Adobo Rice', 'Classic adobo with garlic rice and egg', 159.00, NULL, 1, '2025-03-13 00:35:53', 0),
(102, 5, 'Beef Tapa', 'Beef tapa with garlic rice and fried egg', 169.00, NULL, 1, '2025-03-13 00:35:53', 0),
(103, 5, 'Sisig Rice', 'Sizzling sisig on top of garlic rice', 149.00, NULL, 1, '2025-03-13 00:35:53', 0),
(104, 5, 'Tocino Rice', 'Sweet pork tocino with rice and egg', 149.00, NULL, 1, '2025-03-13 00:35:53', 0),
(105, 6, 'Sinigang', 'Tamarind-based soup with pork and vegetables', 189.00, NULL, 1, '2025-03-13 00:35:53', 0),
(106, 6, 'Bulalo', 'Beef bone marrow soup with vegetables', 299.00, NULL, 1, '2025-03-13 00:35:53', 0),
(107, 6, 'Cream of Mushroom', 'Rich and creamy mushroom soup', 129.00, NULL, 1, '2025-03-13 00:35:53', 0),
(108, 6, 'Corn Soup', 'Creamy corn soup with chicken', 119.00, NULL, 1, '2025-03-13 00:35:53', 0),
(109, 7, 'Caesar Salad', 'Classic caesar salad with grilled chicken', 179.00, NULL, 1, '2025-03-13 00:35:53', 0),
(110, 7, 'Greek Salad', 'Fresh vegetables with feta cheese and olives', 169.00, NULL, 1, '2025-03-13 00:35:53', 0),
(111, 7, 'Mango Salad', 'Fresh green mango salad with tomatoes', 149.00, NULL, 1, '2025-03-13 00:35:53', 0),
(112, 7, 'Garden Salad', 'Mixed greens with house dressing', 139.00, NULL, 1, '2025-03-13 00:35:53', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `table_id` int(11) NOT NULL,
  `table_number` varchar(10) NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `status` enum('AVAILABLE','OCCUPIED','READY','CLEANING') DEFAULT 'AVAILABLE',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`table_id`, `table_number`, `qr_code`, `status`, `created_at`) VALUES
(1, '1', 'table_1_67d2129f777f6', 'OCCUPIED', '2025-03-12 23:02:55'),
(2, '2', 'table_2_67d2129f8ae5d', 'READY', '2025-03-12 23:02:55'),
(3, '3', 'table_3_67d2129f8b46a', 'READY', '2025-03-12 23:02:55'),
(4, '4', 'table_4_67d2129f8b9ae', 'READY', '2025-03-12 23:02:55'),
(5, '5', 'table_5_67d2129f8d04d', 'READY', '2025-03-12 23:02:55'),
(9, '6', 'table_6_67d242762013e', 'READY', '2025-03-13 02:27:02'),
(10, '7', 'table_7_67d2563a67715', 'OCCUPIED', '2025-03-13 03:51:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('ADMIN','CASHIER','KITCHEN','WAITER') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN', '2025-03-12 22:54:17'),
(2, 'cashier', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'CASHIER', '2025-03-12 22:54:17'),
(3, 'kitchen', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'KITCHEN', '2025-03-12 22:54:17'),
(4, 'waiter', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'WAITER', '2025-03-12 22:54:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`table_id`),
  ADD UNIQUE KEY `table_number` (`table_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=284;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `table_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `tables` (`table_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
