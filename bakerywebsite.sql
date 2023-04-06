-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2023 at 07:58 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bakerywebsite`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `create_by` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `admin_panel`
--

CREATE TABLE `admin_panel` (
  `id` int(11) NOT NULL,
  `img_path` varchar(255) NOT NULL,
  `img_name` varchar(255) NOT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `create_by` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_by` varchar(255) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `admin_restaurant`
--

CREATE TABLE `admin_restaurant` (
  `id` int(14) NOT NULL,
  `manager` varchar(255) NOT NULL,
  `manager_contact` varchar(255) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_contact` varchar(255) NOT NULL,
  `res_address` text NOT NULL,
  `res_iframe` text NOT NULL,
  `cuisine` varchar(255) NOT NULL,
  `start_time` varchar(255) NOT NULL,
  `end_time` varchar(255) NOT NULL,
  `rest_day` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `img_logo` varchar(255) NOT NULL,
  `img_cover` varchar(255) DEFAULT NULL,
  `max_seat` varchar(14) NOT NULL,
  `status` enum('public','hide') NOT NULL,
  `create_by` int(14) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_by` int(14) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `admin_res_gallery`
--

CREATE TABLE `admin_res_gallery` (
  `id` int(14) NOT NULL,
  `res_id` int(14) NOT NULL,
  `gallery_name` varchar(255) NOT NULL,
  `create_by` int(14) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_by` int(14) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `admin_res_seats`
--

CREATE TABLE `admin_res_seats` (
  `id` int(14) NOT NULL,
  `res_id` int(255) NOT NULL,
  `seat_1` int(255) DEFAULT NULL,
  `seat_2` int(255) DEFAULT NULL,
  `seat_3` int(255) DEFAULT NULL,
  `seat_4` int(255) DEFAULT NULL,
  `seat_5` int(255) DEFAULT NULL,
  `seat_6` int(255) DEFAULT NULL,
  `create_by` int(14) NOT NULL,
  `create_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `admin_res_tag`
--

CREATE TABLE `admin_res_tag` (
  `id` int(14) NOT NULL,
  `res_id` int(14) NOT NULL,
  `tag_1` enum('vegetarian','non-vegetarian') NOT NULL,
  `tag_2` enum('halal','non-halal') NOT NULL,
  `tag_3` enum('yes','no') DEFAULT NULL,
  `tag_4` varchar(255) DEFAULT NULL,
  `tag_5` varchar(255) DEFAULT NULL,
  `create_by` int(14) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_by` int(14) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `img_path` varchar(255) NOT NULL,
  `img_name` varchar(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `pid` int(255) NOT NULL,
  `sid` int(255) NOT NULL,
  `status` enum('pending','paid','checkout') NOT NULL,
  `order_id` varchar(10) DEFAULT NULL,
  `add_by` varchar(255) NOT NULL,
  `add_time` datetime NOT NULL,
  `update_by` varchar(255) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `product_name`, `img_path`, `img_name`, `quantity`, `price`, `pid`, `sid`, `status`, `order_id`, `add_by`, `add_time`, `update_by`, `update_time`) VALUES
(2, 'Pie', 'img/sellerGallery/', 'gallery6.jpg', 1, '10', 3, 2, 'paid', '61d0a860b1', '1', '2023-03-23 09:58:26', '1', '2023-03-23 10:01:42'),
(3, 'Waffle', 'img/sellerGallery/', 'product-5.jpg', 1, '12.50', 4, 2, 'paid', '61d0a860b1', '1', '2023-03-23 10:09:58', '1', '2023-03-27 03:14:32'),
(6, 'Pie', 'img/sellerGallery/', 'gallery6.jpg', 1, '10', 3, 2, 'paid', '61d0a860b1', '1', '2023-03-27 05:39:18', '1', '2023-03-27 05:43:14'),
(9, 'Waffle', 'img/sellerGallery/', 'product-5.jpg', 1, '12.50', 4, 2, 'checkout', NULL, '1', '2023-03-28 04:45:36', '1', '2023-03-29 07:30:40');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_path` varchar(255) NOT NULL,
  `category_file` varchar(255) NOT NULL,
  `create_by` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_by` int(255) NOT NULL,
  `update_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `category_path`, `category_file`, `create_by`, `create_time`, `update_by`, `update_time`) VALUES
(1, 'Pastry', 'images/', 'gallery1.jpg', '', '2023-03-23 04:22:30', 0, '2023-03-23 04:22:30'),
(2, 'Cake', 'images/', 'gallery.jpg', '', '2023-03-23 04:22:30', 0, '2023-03-23 04:22:30');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(10) NOT NULL,
  `purchase_qty` int(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `order_by` varchar(255) NOT NULL,
  `order_time` datetime NOT NULL,
  `update_by` varchar(255) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `purchase_qty`, `price`, `address`, `order_by`, `order_time`, `update_by`, `update_time`) VALUES
(0, '61d0a860b1', 1, '10.50', 'NO 2, LALUAN BATU GAJAH PERDANA 29,', '1', '2023-03-23 10:01:42', NULL, NULL),
(0, 'b247219eee', 1, '13.13', 'NO 2, LALUAN BATU GAJAH PERDANA 29,', '1', '2023-03-27 03:14:32', NULL, NULL),
(0, 'e276f02026', 1, '10.50', 'NO 2, LALUAN BATU GAJAH PERDANA 29,', '1', '2023-03-27 05:43:14', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `img_path` varchar(255) NOT NULL,
  `img_name` varchar(255) NOT NULL,
  `product_price` varchar(255) NOT NULL,
  `product_quantity` int(255) NOT NULL,
  `product_description` varchar(255) DEFAULT NULL,
  `category_type` varchar(255) NOT NULL,
  `status` enum('public','hide') NOT NULL,
  `sold` int(255) DEFAULT NULL,
  `create_by` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_by` varchar(255) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `img_path`, `img_name`, `product_price`, `product_quantity`, `product_description`, `category_type`, `status`, `sold`, `create_by`, `create_time`, `update_by`, `update_time`) VALUES
(1, 'Croissant', 'images/', 'gallery3.jpg', '3', 3, NULL, 'Pastry', 'public', NULL, '1', '2023-02-01 03:38:28', NULL, NULL),
(2, 'Cake', 'images/', 'gallery2.jpg', '3.5', 5, NULL, 'Cake', 'public', NULL, '', '2023-03-22 04:14:43', NULL, '2023-03-22 04:14:43'),
(3, 'Pie', 'img/sellerGallery/', 'gallery6.jpg', '10', 3, NULL, 'Pastry', 'public', NULL, '2', '2023-03-23 04:27:08', NULL, NULL),
(4, 'Waffle', 'img/sellerGallery/', 'product-5.jpg', '12.50', 9, NULL, 'Cake', 'public', NULL, '2', '2023-03-23 10:09:27', NULL, NULL),
(5, 'Bread', 'img/sellerGallery/', 'about.png', '10', 12, NULL, 'Pastry', 'public', NULL, '2', '2023-03-27 11:10:27', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_gallery`
--

CREATE TABLE `product_gallery` (
  `id` int(11) NOT NULL,
  `img_path` varchar(255) NOT NULL,
  `img_name` varchar(255) NOT NULL,
  `product_id` int(255) NOT NULL,
  `create_by` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_by` varchar(255) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `res_booking`
--

CREATE TABLE `res_booking` (
  `id` int(14) NOT NULL,
  `uid` int(14) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `book_date` varchar(255) NOT NULL,
  `book_time` varchar(255) NOT NULL,
  `guest` varchar(255) NOT NULL,
  `seat` varchar(255) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `book_number` varchar(10) NOT NULL,
  `res_id` int(14) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_contact` varchar(255) NOT NULL,
  `res_address` varchar(255) NOT NULL,
  `create_by` int(14) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_by` int(14) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

CREATE TABLE `sellers` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_num` varchar(30) NOT NULL,
  `address` varchar(255) NOT NULL,
  `bg_path` varchar(255) DEFAULT NULL,
  `bg_name` varchar(255) DEFAULT NULL,
  `create_by` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_by` varchar(255) NOT NULL,
  `update_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`id`, `shop_name`, `user_id`, `email`, `phone_num`, `address`, `bg_path`, `bg_name`, `create_by`, `create_time`, `update_by`, `update_time`) VALUES
(2, 'tiha', 1, 'fbistamam@gmail.com', '0135811028', 'no 2, bgp perak', '../img/sellerbg/', 'passport_img.jpg', 'tihaahmad', '2023-03-23 02:40:43', 'tihaahmad', '2023-03-23 09:58:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `user_role` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `phone_num` varchar(30) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `img_path` varchar(255) DEFAULT NULL,
  `img_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_identifier` text DEFAULT NULL,
  `remember_token` text DEFAULT NULL,
  `create_by` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_by` varchar(255) NOT NULL,
  `update_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `user_role`, `email`, `full_name`, `phone_num`, `address`, `img_path`, `img_name`, `password`, `remember_identifier`, `remember_token`, `create_by`, `create_time`, `update_by`, `update_time`) VALUES
(1, 'tihaahmad', '2', 'fbistamam@gmail.com', 'NORFATIHAH BINTI AHMAD BISTAMAM', '0135811028', 'NO 2, LALUAN BATU GAJAH PERDANA 29,', NULL, NULL, '$2y$10$KPYXDGxx2jmvTAStEoSvIOje8NPM8L1RZ7yzPd4ZVFaMJtOwPq/F6', '', '', 'tihaahmad', '2023-03-21 08:44:12', 'tihaahmad', '2023-03-23 10:01:15'),
(2, 'tiha', '1', 'f@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$10$1lWqBtog/Q5xWkHR4Yhwa.Unxt72GXQFq5f5fE8CXaN5JHe.LH6bK', NULL, NULL, 'tiha', '2023-03-22 02:09:05', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_path` varchar(255) NOT NULL,
  `product_img` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `pid` int(255) NOT NULL,
  `add_by` int(11) NOT NULL,
  `add_time` datetime NOT NULL,
  `update_by` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `product_name`, `product_path`, `product_img`, `price`, `pid`, `add_by`, `add_time`, `update_by`, `update_time`) VALUES
(0, 'Cake', 'images/', 'gallery2.jpg', '3.5', 2, 1, '2023-03-23 04:06:39', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_action`
--

CREATE TABLE `wishlist_action` (
  `id` int(11) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `action` enum('like','unlike') NOT NULL,
  `create_by` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wishlist_action`
--

INSERT INTO `wishlist_action` (`id`, `product_id`, `action`, `create_by`, `create_time`) VALUES
(0, '1', 'like', '1', '2023-03-23 04:06:36'),
(0, '2', 'like', '1', '2023-03-23 04:06:39'),
(0, '1', 'unlike', '1', '2023-03-23 04:06:46'),
(0, '1', 'unlike', '1', '2023-03-23 07:23:08'),
(0, '1', 'unlike', '1', '2023-03-23 08:03:52'),
(0, '1', 'unlike', '1', '2023-03-23 08:04:56'),
(0, '1', 'unlike', '1', '2023-03-23 08:10:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sellers`
--
ALTER TABLE `sellers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sellers`
--
ALTER TABLE `sellers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
