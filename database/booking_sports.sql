CREATE DATABASE IF NOT EXISTS booking_sports;
USE booking_sports;
CREATE TABLE IF NOT EXISTS users (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
email VARCHAR(150) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
role ENUM('admin', 'user') DEFAULT 'user',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS fields (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
sport_type ENUM('football', 'basket', 'tennis') NOT NULL,
location VARCHAR(255),
price DECIMAL(10,2),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS reservations (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
field_id INT NOT NULL,
date DATE NOT NULL,
start_time TIME NOT NULL,
end_time TIME NOT NULL,
status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (field_id) REFERENCES fields(id) ON DELETE CASCADE
);


USE booking_sports;
INSERT INTO fields (name, sport_type, location, price)
VALUES
('Terrain Foot 1', 'football', 'Centre Sportif A', 50.00),
('Terrain Basket 1', 'basket', 'Centre Sportif B', 30.00),
('Terrain Tennis 1', 'tennis', 'Club Tennis C', 25.00);