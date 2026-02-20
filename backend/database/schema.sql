CREATE DATABASE IF NOT EXISTS scandiweb_store;
USE scandiweb_store;

CREATE TABLE categories (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE products (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    in_stock BOOLEAN DEFAULT TRUE,
    description TEXT,
    category_id VARCHAR(50),
    brand VARCHAR(100),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE prices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(50),
    amount DECIMAL(10,2),
    currency_label VARCHAR(10),
    currency_symbol VARCHAR(5),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(50),
    image_url TEXT,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE attributes (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(255),
    type ENUM('text', 'swatch')
);

CREATE TABLE product_attributes (
    product_id VARCHAR(50),
    attribute_id VARCHAR(50),
    PRIMARY KEY (product_id, attribute_id)
);

CREATE TABLE attribute_items (
    id VARCHAR(50) PRIMARY KEY,
    attribute_id VARCHAR(50),
    display_value VARCHAR(255),
    value VARCHAR(255),
    FOREIGN KEY (attribute_id) REFERENCES attributes(id)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id VARCHAR(50),
    quantity INT,
    selected_attributes JSON,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);
