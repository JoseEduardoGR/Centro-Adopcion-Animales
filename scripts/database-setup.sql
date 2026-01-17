-- Create database
CREATE DATABASE IF NOT EXISTS animal_adoption_center;
USE animal_adoption_center;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Animals table
CREATE TABLE IF NOT EXISTS animals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    species VARCHAR(50) NOT NULL,
    breed VARCHAR(100),
    age INT,
    gender ENUM('Male', 'Female') NOT NULL,
    size ENUM('Small', 'Medium', 'Large') NOT NULL,
    description TEXT,
    health_status VARCHAR(100),
    adoption_status ENUM('Available', 'Adopted', 'Pending') DEFAULT 'Available',
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Adoptions table
CREATE TABLE IF NOT EXISTS adoptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    animal_id INT,
    adoption_date DATE,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
);

-- Insert sample data
INSERT INTO users (username, email, password, full_name, phone, address) VALUES
('admin', 'admin@adoption.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', '555-0001', '123 Admin St'),
('john_doe', 'john@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', '555-0002', '456 User Ave');

INSERT INTO animals (name, species, breed, age, gender, size, description, health_status, image_url) VALUES
('Buddy', 'Dog', 'Golden Retriever', 3, 'Male', 'Large', 'Friendly and energetic dog, great with kids', 'Healthy', '/placeholder.svg?height=300&width=300'),
('Whiskers', 'Cat', 'Persian', 2, 'Female', 'Medium', 'Calm and affectionate cat, loves to cuddle', 'Healthy', '/placeholder.svg?height=300&width=300'),
('Max', 'Dog', 'German Shepherd', 5, 'Male', 'Large', 'Loyal and protective, needs experienced owner', 'Healthy', '/placeholder.svg?height=300&width=300'),
('Luna', 'Cat', 'Siamese', 1, 'Female', 'Small', 'Playful kitten, very social and active', 'Healthy', '/placeholder.svg?height=300&width=300');
