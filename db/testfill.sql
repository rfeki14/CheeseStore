

-- Dumping data for table `address`
INSERT INTO `address` (`id`, `street`, `city`, `state`, `zip_code`, `country`) VALUES
(1, '123 Cheese St', 'Cheesetown', 'Cheesestate', '12345', 'USA'),
(2, '456 Dairy Rd', 'Milktown', 'Dairystate', '67890', 'USA');

-- Dumping data for table `products`
INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `slug`, `price`, `qtty`, `photo`, `date_view`, `counter`) VALUES
(1, 1, 'Cheddar Cheese', '<p>Aged cheddar cheese with a sharp, tangy flavor.</p>', 'cheddar-cheese', 5.99, 100, 'cheddar-cheese.jpg', '2025-01-24', 10),
(2, 1, 'Gouda Cheese', '<p>Rich and creamy Gouda cheese, perfect for sandwiches.</p>', 'gouda-cheese', 6.99, 80, 'gouda-cheese.jpg', '2025-02-03', 8),
(3, 1, 'Brie Cheese', '<p>Soft and creamy Brie cheese with a buttery flavor.</p>', 'brie-cheese', 7.99, 50, 'brie-cheese.jpg', '2025-01-26', 5),
(4, 1, 'Blue Cheese', '<p>Strong and tangy blue cheese with a distinctive flavor.</p>', 'blue-cheese', 8.99, 60, 'blue-cheese.jpg', '2025-01-26', 3),
(5, 2, 'Whole Milk', '<p>Fresh whole milk from grass-fed cows.</p>', 'whole-milk', 2.99, 200, 'whole-milk.jpg', '2025-01-26', 2),
(6, 2, 'Skim Milk', '<p>Low-fat skim milk, perfect for a healthy diet.</p>', 'skim-milk', 2.49, 150, 'skim-milk.jpg', '2025-01-26', 1);

-- Dumping data for table `cart`
INSERT INTO `cart` (`id`, `user_id`, `edition_id`, `quantity`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 1),
(3, 2, 3, 3);

-- Dumping data for table `category`
INSERT INTO `category` (`id`, `name`, `cat_slug`) VALUES
(1, 'Cheese', 'cheese'),
(2, 'Milk', 'milk');

-- Dumping data for table `details`
INSERT INTO `details` (`id`, `sales_id`, `product_id`, `quantity`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 1),
(3, 2, 3, 3);

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `email`, `password`, `type`, `firstname`, `lastname`, `contact_info`, `photo`, `status`, `activate_code`, `reset_code`, `created_on`) VALUES
(5, 'admin@admin.com', '$2y$10$8wY63GX/y9Bq780GBMpxCesV9n1H6WyCqcA2hNy2uhC59hEnOpNaS', 1, 'Admin', 'admin', '', 'facebook-profile-image.jpeg', 1, '', '', '2018-05-01'),
(1, 'cheese_lover@example.com', '$2y$10$8wY63GX/y9Bq780GBMpxCesV9n1H6WyCqcA2hNy2uhC59hEnOpNaS', 0, 'Cheese', 'Lover', '1234567890', 'cheese_lover.jpg', 1, '', '', '2025-01-01'),
(2, 'milk_fan@example.com', '$2y$10$dvV7onY2bPSb9GBENwR57OixbBy3veerLtRt/FqnpoeyzV1h8x48K', 0, 'Milk', 'Fan', '0987654321', 'milk_fan.jpg', 1, '', '', '2025-01-02'),
(11, 'test@gmail.com', '$2y$10$dvV7onY2bPSb9GBENwR57OixbBy3veerLtRt/FqnpoeyzV1h8x48K', 0, 'test', 'test', 'test', '', 1, '', '', '2018-05-11');

-- Dumping data for table `user_addresses`
INSERT INTO `user_addresses` (`user_id`, `address_id`) VALUES
(1, 1),
(2, 2);

-- Dumping data for table `edition`
INSERT INTO `edition` (`id`, `product_id`, `weight`, `price`) VALUES
(1, 1, 500, 5.99),
(2, 2, 500, 6.99),
(3, 3, 500, 7.99),
(4, 4, 500, 8.99),
(5, 5, 1000, 2.99),
(6, 6, 1000, 2.49);

