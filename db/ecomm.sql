-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2025 at 10:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecomm`
--

-- --------------------------------------------------------

--
--
-- Table structure for table `address`
--
--

DROP DATABASE ecomm; 

Create database ecomm;
use ecomm;

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `phone` int(20) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL
);

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `phone`, `street`, `city`, `state`, `zip_code`, `country`) VALUES
(1, 5551234, '123 Dairy Lane', 'Milktown', 'NY', '10001', 'USA'),
(2, 5555678, '456 Cheese Street', 'Cheeseburg', 'WI', '53012', 'USA'),
(3, 5558765, '789 Yogurt Ave', 'Yogurtville', 'CA', '90012', 'USA'),
(4, 92582637, 'Belli Gare', 'Turki', 'Nabeul', '8084', 'Tunisia'),
(5, 92582637, 'Belli Gare', 'Grombalia', 'Nabeul', '8030', 'Tunisia');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `edition_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL
);

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `edition_id`, `quantity`, `price`) VALUES
(1, 2, 1, 2, 5.98),
(2, 3, 3, 1, 3.49),
(7, 6, 2, 2, 9.98),
(8, 6, 8, 2, 25.98);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cat_slug` varchar(150) NOT NULL
);

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `cat_slug`) VALUES
(1, 'Lait / Lben', 'milk'),
(2, 'Fromage', 'cheese'),
(4, 'Beurre', 'butter');

-- --------------------------------------------------------

--
-- Table structure for table `details`
--

CREATE TABLE `details` (
  `id` int(11) NOT NULL,
  `sales_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
);

--
-- Dumping data for table `details`
--

INSERT INTO `details` (`id`, `sales_id`, `product_id`, `quantity`) VALUES
(1, 3, 3, 1),
(2, 3, 4, 1),
(3, 4, 3, 1),
(4, 5, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `edition`
--

CREATE TABLE `edition` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `product_id` int(11) NOT NULL,
  `weight` float NOT NULL,
  `price` double NOT NULL DEFAULT 0
);

--
-- Dumping data for table `edition`
--

INSERT INTO `edition` (`id`, `name`, `product_id`, `weight`, `price`) VALUES
(1, 'Small', 1, 0.5, 2.99),
(2, 'Medium', 1, 1, 4.99),
(3, 'Big', 3, 0.75, 3.49),
(4, 'Family', 2, 1.5, 5.99),
(5, 'Small', 4, 0.25, 2.99),
(6, 'Medium', 5, 0.5, 3.99),
(7, 'Big', 5, 1, 6.99),
(8, 'Family', 5, 2, 12.99),
(9, 'testedition', 8, 150, 100);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(200) NOT NULL,
  `qtty` int(11) NOT NULL DEFAULT 10,
  `photo` varchar(200) DEFAULT NULL,
  `date_view` datetime NOT NULL DEFAULT current_timestamp(),
  `counter` int(11) NOT NULL DEFAULT 0
);

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `slug`, `qtty`, `photo`, `date_view`, `counter`) VALUES
(1, 1, 'Whole Milk', '<p>Fresh whole milk from grass-fed cows.</p>\r\n', 'whole-milk', 50, '754000000025700000000063113150.jpg', '2025-02-21', 3),
(2, 1, 'Almond Milk', '<p>Dairy-free almond milk, unsweetened.</p>\r\n', 'almond-milk', 40, 'homemade-almond-milk-2.jpg', '2025-02-21', 30),
(3, 2, 'Cheddar Cheese', '<p>Aged cheddar cheese, rich and creamy.</p>\r\n', 'cheddar-cheese', 28, 'How-to-Make-Cheddar-Cheese-17.jpg', '2025-02-21', 10),
(4, 3, 'Greek Yogurt', '<p>Thick and protein-rich Greek yogurt.</p>\r\n', 'greek-yogurt', 23, 'images.jpg', '2025-02-21', 3),
(5, 4, 'Salted Butter', '<p>Creamy butter, perfect for cooking.</p>\r\n', 'salted-butter', 20, 'salted-vs-unsalted-butter-2.jpg', '2025-02-21', 2),
(6, 5, 'Vanilla Ice Cream', '<p>Classic vanilla ice cream.</p>\r\n', 'vanilla-ice-cream', 35, 'images (1).jpg', '2025-02-21', 2),
(8, 2, 'TestPROD', 'test add', 'testprod', 35, 'testprod.jpg', '2025-02-21', 3);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `sales_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) DEFAULT 0,
  `delivery_method` varchar(10) NOT NULL,
  `dp_address` int(11) DEFAULT NULL
);

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `total`, `sales_date`, `status`, `delivery_method`, `dp_address`) VALUES
(1, 2, 12.99, '2025-02-18', 1, 'Delivery', 1),
(2, 3, 8.99, '2025-02-18', 1, 'Pickup', 2),
(3, 6, 16.48, '2025-02-19', 0, 'delivery', 4),
(4, 6, 10.49, '2025-02-21', 0, 'delivery', 5),
(5, 6, 5.99, '2025-02-21', 0, 'pickup', 2);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `address` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL
);

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `address`, `name`) VALUES
(1, 1, 'Dairy Fresh Store - NY'),
(2, 2, 'Cheese Lovers Market - WI');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(60) NOT NULL,
  `type` int(1) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `contact_info` varchar(100) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `status` int(1) NOT NULL,
  `activate_code` varchar(15) NOT NULL,
  `reset_code` varchar(15) NOT NULL,
  `created_on` date NOT NULL
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `type`, `firstname`, `lastname`, `contact_info`, `photo`, `status`, `activate_code`, `reset_code`, `created_on`) VALUES
(1, 'admin@dairyshop.com', 'adminpass', 1, 'Admin', 'Dairy', '123-456-7890', '', 1, '', '', '2025-02-18'),
(2, 'customer1@gmail.com', 'custpass1', 0, 'John', 'Doe', '555-1234', '', 0, '', '', '2025-02-18'),
(3, 'customer2@gmail.com', 'custpass2', 0, 'Jane', 'Smith', '555-5678', '', 1, '', '', '2025-02-18'),
(4, 'admin@admin.com', '$2y$10$8wY63GX/y9Bq780GBMpxCesV9n1H6WyCqcA2hNy2uhC59hEnOpNaS', 1, 'Admin', 'admin', '', 'facebook-profile-image.jpeg', 1, '', '', '2018-05-01'),
(5, 'azizbenhassine270@gmail.com', '$2y$10$fE/5Xw5WXFKS.kWF3bpwx.Y7xNZmAe3gPrsLme1/dw1fmLAnhZaUG', 0, 'med', 'ben hassine', '51434055', '', 1, 'AOP9Ha8WReuw', '', '2025-02-05'),
(6, 'test@test', '$2y$10$xac/KiF/K1VNXpxWWBsda.iW6iwGAIEo6jQwA7uJV/0ZFGEWoQ6Q.', 0, 'Riadh', 'Feki', '92582637', '', 1, '', '', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `user_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL
) ;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`user_id`, `address_id`) VALUES
(2, 1),
(3, 2),
(6, 4),
(6, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `details`
--
ALTER TABLE `details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edition`
--
ALTER TABLE `edition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `delivery_address` (`dp_address`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `address_id` (`address`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`user_id`,`address_id`),
  ADD KEY `address_id` (`address_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `details`
--
ALTER TABLE `details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `edition`
--
ALTER TABLE `edition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `edition`
--
ALTER TABLE `edition`
  ADD CONSTRAINT `edition_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_delivery_fk` FOREIGN KEY (`dp_address`) REFERENCES `address` (`id`),
  ADD CONSTRAINT `sales_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `stores`
--
ALTER TABLE `stores`
  ADD CONSTRAINT `stores_ibfk_1` FOREIGN KEY (`address`) REFERENCES `address` (`id`);

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_addresses_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
