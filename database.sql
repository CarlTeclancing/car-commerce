-- Car Store Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS car_store;
USE car_store;

-- Cars Table
CREATE TABLE IF NOT EXISTS cars (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    mileage INT,
    fuel_type VARCHAR(20),
    transmission VARCHAR(20),
    color VARCHAR(30),
    description LONGTEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contacts/Orders Table
CREATE TABLE IF NOT EXISTS contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    car_id INT NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    message LONGTEXT,
    status ENUM('pending', 'contacted', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
);

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO admin (username, password, email) VALUES 
('admin', '$2y$10$N0W52cAA.JJ0SJ0VqJ7CXe6QU7e.HB0DL8l8J7y8KWh5x8Q5m7KXi', 'admin@carstore.com');

-- Sample car data (optional)
INSERT INTO cars (name, brand, model, year, price, mileage, fuel_type, transmission, color, description, image) VALUES
('Civic Sedan', 'Honda', 'Civic', 2022, 22000, 15000, 'Petrol', 'Automatic', 'Silver', 'Reliable and fuel-efficient sedan perfect for daily commuting.', 'civic.jpg'),
('Accord Executive', 'Honda', 'Accord', 2021, 28000, 25000, 'Petrol', 'Automatic', 'Black', 'Premium sedan with advanced safety features.', 'accord.jpg'),
('CR-V SUV', 'Honda', 'CR-V', 2020, 32000, 35000, 'Petrol', 'Automatic', 'White', 'Spacious SUV ideal for families.', 'crv.jpg'),
('Toyota Corolla', 'Toyota', 'Corolla', 2023, 20000, 8000, 'Petrol', 'Manual', 'Blue', 'Best-selling compact sedan worldwide.', 'corolla.jpg'),
('Fortuner SUV', 'Toyota', 'Fortuner', 2019, 35000, 45000, 'Diesel', 'Automatic', 'Red', 'Rugged SUV for off-road adventures.', 'fortuner.jpg');
