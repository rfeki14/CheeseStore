
-- Indexes for table `address`

ALTER TABLE `address`
    ADD PRIMARY KEY (`id`);

-- Indexes for table `cart`
ALTER TABLE `cart`
    ADD PRIMARY KEY (`id`);

-- Indexes for table `category`
ALTER TABLE `category`
    ADD PRIMARY KEY (`id`);

-- Indexes for table `details`
ALTER TABLE `details`
    ADD PRIMARY KEY (`id`);

-- Indexes for table `products`
ALTER TABLE `products`
    ADD PRIMARY KEY (`id`);

-- Indexes for table `sales`
ALTER TABLE `sales`
    ADD PRIMARY KEY (`id`);

-- Indexes for table `users`
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_address` (`address`),

-- Indexes for table `user_addresses`
ALTER TABLE `user_addresses`
    ADD PRIMARY KEY (`user_id`, `address_id`),
    ADD KEY `address_id` (`address_id`);

-- Indexes for table `edition`
ALTER TABLE `edition`
    ADD PRIMARY KEY (`id`),
    ADD KEY `product_id` (`product_id`);

-- AUTO_INCREMENT for table `address`
ALTER TABLE `address`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

-- AUTO_INCREMENT for table `cart`
ALTER TABLE `cart`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

-- AUTO_INCREMENT for table `category`
ALTER TABLE `category`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- AUTO_INCREMENT for table `details`
ALTER TABLE `details`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

-- AUTO_INCREMENT for table `products`
ALTER TABLE `products`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

-- AUTO_INCREMENT for table `sales`
ALTER TABLE `sales`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

-- AUTO_INCREMENT for table `users`
ALTER TABLE `users`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

-- AUTO_INCREMENT for table `edition`
ALTER TABLE `edition`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- Constraints for table `user_addresses`
ALTER TABLE `user_addresses`
    ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `user_addresses_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE CASCADE;


-- Constraints for table `users`
ALTER TABLE `users`
    ADD CONSTRAINT `fk_address` FOREIGN KEY (`address`) REFERENCES `address` (`id`) ON DELETE SET NULL,

-- Constraints for table `edition`
ALTER TABLE `edition`
    ADD CONSTRAINT `edition_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
