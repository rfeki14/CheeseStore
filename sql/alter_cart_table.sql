-- Créer la table si elle n'existe pas
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_per_unit DECIMAL(10,3) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Si la table existe déjà mais n'a pas la colonne price_per_unit
ALTER TABLE cart ADD COLUMN IF NOT EXISTS price_per_unit DECIMAL(10,3) NOT NULL DEFAULT 0.000;
