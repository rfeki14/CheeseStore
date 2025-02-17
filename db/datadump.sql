
-- Insert users (customers & admin)
INSERT INTO users (email, password, type, firstname, lastname, contact_info, photo, status, activate_code, reset_code, created_on)
VALUES 
('admin@admin.com', '$2y$10$8wY63GX/y9Bq780GBMpxCesV9n1H6WyCqcA2hNy2uhC59hEnOpNaS', 1, 'Admin', 'admin', '', 'facebook-profile-image.jpeg', 1, '', '', '2018-05-01'),
( 'azizbenhassine270@gmail.com', '$2y$10$fE/5Xw5WXFKS.kWF3bpwx.Y7xNZmAe3gPrsLme1/dw1fmLAnhZaUG', 0, 'med', 'ben hassine', '51434055', '', 1, 'AOP9Ha8WReuw', '', '2025-02-05');

-- Insert categories
INSERT INTO category (name, cat_slug) 
VALUES 
('Milk', 'milk'), 
('Cheese', 'cheese'), 
('Yogurt', 'yogurt'), 
('Butter', 'butter'), 
('Ice Cream', 'ice-cream');

-- Insert products
INSERT INTO products (category_id, name, description, slug, qtty, photo, date_view, counter)
VALUES 
(1, 'Whole Milk', 'Fresh whole milk from grass-fed cows.', 'whole-milk', 50, 'whole-milk.jpg', NOW(), 5),
(1, 'Almond Milk', 'Dairy-free almond milk, unsweetened.', 'almond-milk', 40, 'almond-milk.jpg', NOW(), 3),
(2, 'Cheddar Cheese', 'Aged cheddar cheese, rich and creamy.', 'cheddar-cheese', 30, 'cheddar.jpg', NOW(), 8),
(3, 'Greek Yogurt', 'Thick and protein-rich Greek yogurt.', 'greek-yogurt', 25, 'greek-yogurt.jpg', NOW(), 7),
(4, 'Salted Butter', 'Creamy butter, perfect for cooking.', 'salted-butter', 20, 'butter.jpg', NOW(), 6),
(5, 'Vanilla Ice Cream', 'Classic vanilla ice cream.', 'vanilla-ice-cream', 35, 'vanilla-ice-cream.jpg', NOW(), 4);

-- Insert product editions (Small, Medium, Big, Family sizes)
INSERT INTO edition (name, product_id, weight, price)
VALUES 
('Small', 1, 0.5, 2.99), -- Whole Milk Small (500ml)
('Medium', 1, 1.0, 4.99), -- Whole Milk Medium (1L)
('Big', 3, 0.75, 3.49), -- Greek Yogurt Big (750g)
('Family', 2, 1.5, 5.99), -- Almond Milk Family (1.5L)
('Small', 4, 0.25, 2.99), -- Salted Butter Small (250g)
('Medium', 5, 0.5, 3.99), -- Vanilla Ice Cream Medium (500ml)
('Big', 5, 1.0, 6.99), -- Vanilla Ice Cream Big (1L)
('Family', 5, 2.0, 12.99); -- Vanilla Ice Cream Family (2L)

-- Insert addresses
INSERT INTO address (phone, street, city, state, zip_code, country)
VALUES 
(5551234, '123 Dairy Lane', 'Milktown', 'NY', '10001', 'USA'),
(5555678, '456 Cheese Street', 'Cheeseburg', 'WI', '53012', 'USA'),
(5558765, '789 Yogurt Ave', 'Yogurtville', 'CA', '90012', 'USA');

-- Link addresses to users
INSERT INTO user_addresses (user_id, address_id) VALUES 
(2, 1), -- Customer 1 with Dairy Lane
(3, 2); -- Customer 2 with Cheese Street

-- Insert sales/orders
INSERT INTO sales (user_id, total, sales_date, status, delivery_method, dp_address)
VALUES 
(2, 12.99, NOW(), 1, 'Delivery', 1), 
(3, 8.99, NOW(), 1, 'Pickup', 2);

-- Insert cart items (products in orders)
INSERT INTO cart (user_id, edition_id, quantity, price)
VALUES 
(2, 1, 2, 5.98), -- Customer 1 bought 2 Whole Milk (Small)
(3, 3, 1, 3.49); -- Customer 2 bought 1 Greek Yogurt (Big)

-- Insert store locations
INSERT INTO stores (address, name)
VALUES 
(1, 'Dairy Fresh Store - NY'), 
(2, 'Cheese Lovers Market - WI');
