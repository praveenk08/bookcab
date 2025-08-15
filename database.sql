-- Car Booking System Database Schema

-- Settings Table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','vendor','customer') NOT NULL DEFAULT 'customer',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `verification_token` varchar(100) DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create vendors table
CREATE TABLE IF NOT EXISTS `vendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `business_name` varchar(100) DEFAULT NULL,
  `owner_name` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `business_license` varchar(50) DEFAULT NULL,
  `tax_id` varchar(50) DEFAULT NULL,
  `doc_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `vendors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create vehicles table
CREATE TABLE IF NOT EXISTS `vehicles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `type` enum('car','suv','luxury','van') NOT NULL,
  `make` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `registration_number` varchar(20) DEFAULT NULL,
  `mileage` int(11) DEFAULT NULL,
  `fuel_type` enum('petrol','diesel','electric','hybrid') DEFAULT NULL,
  `transmission` enum('manual','automatic') DEFAULT NULL,
  `seats` int(2) NOT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `fuel_charge_per_km` decimal(10,2) DEFAULT NULL,
  `description` text,
  `features` text,
  `images` JSON NOT NULL DEFAULT (JSON_ARRAY()),
  `is_air_conditioned` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `rating` decimal(3,2) DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `vendor_id` (`vendor_id`),
  CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create vehicle_images table
CREATE TABLE IF NOT EXISTS `vehicle_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `vehicle_id` (`vehicle_id`),
  CONSTRAINT `vehicle_images_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create drivers table
CREATE TABLE IF NOT EXISTS `drivers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `license_number` varchar(50) NOT NULL,
  `experience_years` int(11) NOT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `address` text DEFAULT NULL,
  `license_document` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `vendor_id` (`vendor_id`),
  CONSTRAINT `drivers_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create vehicle_availability table
CREATE TABLE IF NOT EXISTS `vehicle_availability` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicle_date` (`vehicle_id`,`date`),
  CONSTRAINT `vehicle_availability_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create bookings table
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `payment_status` enum('pending','paid','refunded') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create booking_items table
CREATE TABLE IF NOT EXISTS `booking_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days` int(11) NOT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `driver_price_per_day` decimal(10,2) DEFAULT NULL,
  `fuel_charge_per_km` decimal(10,2) DEFAULT NULL,
  `distance` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `vehicle_id` (`vehicle_id`),
  KEY `vendor_id` (`vendor_id`),
  KEY `driver_id` (`driver_id`),
  CONSTRAINT `booking_items_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_items_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_items_ibfk_3` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_items_ibfk_4` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create payments table
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('credit_card','debit_card','paypal','bank_transfer','cash') NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create reviews table
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `user_id` (`user_id`),
  KEY `vendor_id` (`vendor_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create notifications table
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('booking','payment','review','system','vendor') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create audit_logs table
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `entity_type` varchar(50) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `old_value` text,
  `new_value` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data for testing

-- Insert admin user
INSERT INTO `users` (`name`, `email`, `phone`, `password_hash`, `role`, `is_verified`, `status`) VALUES
('Admin User', 'admin@example.com', '1234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, 'active');

-- Insert customers
INSERT INTO `users` (`name`, `email`, `phone`, `password_hash`, `role`, `is_verified`, `status`) VALUES
('John Doe', 'john@example.com', '9876543210', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 1, 'active'),
('Jane Smith', 'jane@example.com', '8765432109', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 1, 'active'),
('Robert Johnson', 'robert@example.com', '7654321098', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 0, 'inactive');

-- Insert vendor users
INSERT INTO `users` (`name`, `email`, `phone`, `password_hash`, `role`, `is_verified`, `status`) VALUES
('Vendor One', 'vendor1@example.com', '6543210987', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vendor', 1, 'active'),
('Vendor Two', 'vendor2@example.com', '5432109876', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vendor', 1, 'active');

-- Insert vendors
INSERT INTO `vendors` (`user_id`, `business_name`, `owner_name`, `address`, `city`, `state`, `postal_code`, `country`, `business_license`, `tax_id`, `doc_path`, `status`) VALUES
(4, 'Premium Cars', 'Vendor One', '123 Main St', 'New York', 'NY', '10001', 'USA', 'LIC123456', 'TAX123456', 'uploads/vendors/doc1.pdf', 'approved'),
(5, 'Luxury Rides', 'Vendor Two', '456 Oak St', 'Los Angeles', 'CA', '90001', 'USA', 'LIC789012', 'TAX789012', 'uploads/vendors/doc2.pdf', 'pending');

-- Insert vehicles
INSERT INTO `vehicles` (`vendor_id`, `title`, `type`, `make`, `model`, `year`, `registration_number`, `mileage`, `fuel_type`, `transmission`, `seats`, `price_per_day`, `fuel_charge_per_km`, `description`, `features`, `is_air_conditioned`, `is_active`, `rating`, `images`) VALUES
(1, 'Comfortable Sedan', 'car', 'Toyota', 'Camry', 2020, 'ABC123', 15000, 'petrol', 'automatic', 5, 50.00, 2.50, 'A comfortable sedan for your daily needs.', 'Bluetooth, GPS, Backup Camera', 1, 1, 4.5, '["car1_1.jpg","car1_2.jpg"]'),
(1, 'Spacious SUV', 'suv', 'Honda', 'CR-V', 2021, 'DEF456', 10000, 'petrol', 'automatic', 7, 75.00, 3.00, 'A spacious SUV for family trips.', 'Bluetooth, GPS, Backup Camera, Sunroof', 1, 1, 4.2, '["suv1_1.jpg","suv1_2.jpg"]'),
(2, 'Luxury Sedan', 'luxury', 'Mercedes', 'E-Class', 2022, 'GHI789', 5000, 'petrol', 'automatic', 5, 150.00, 5.00, 'Experience luxury with this premium sedan.', 'Leather Seats, Premium Sound System, Navigation', 1, 1, 4.8, '["luxury1_1.jpg","luxury1_2.jpg"]');

-- Insert drivers
INSERT INTO `drivers` (`vendor_id`, `name`, `phone`, `license_number`, `experience_years`, `price_per_day`, `is_active`, `license_document`, `photo`) VALUES
(1, 'Driver One', '1122334455', 'DL123456', 5, 25.00, 1, 'license_doc1.pdf', 'driver1.jpg'),
(1, 'Driver Two', '2233445566', 'DL789012', 3, 20.00, 1, 'license_doc2.pdf', 'driver2.jpg'),
(2, 'Driver Three', '3344556677', 'DL345678', 7, 30.00, 1, 'license_doc3.pdf', 'driver3.jpg');

-- Insert bookings
INSERT INTO `bookings` (`user_id`, `booking_date`, `total_amount`, `status`, `payment_status`) VALUES
(2, '2023-06-01 10:00:00', 225.00, 'completed', 'paid'),
(3, '2023-06-05 14:30:00', 450.00, 'confirmed', 'paid'),
(2, '2023-06-20 09:15:00', 540.00, 'pending', 'pending');

-- Insert booking items
INSERT INTO `booking_items` (`booking_id`, `vehicle_id`, `vendor_id`, `driver_id`, `start_date`, `end_date`, `days`, `price_per_day`, `driver_price_per_day`, `fuel_charge_per_km`, `distance`, `total_price`, `status`) VALUES
(1, 1, 1, 1, '2023-06-01', '2023-06-03', 3, 50.00, 25.00, 2.50, 100, 225.00, 'completed'),
(2, 2, 1, NULL, '2023-06-10', '2023-06-15', 6, 75.00, NULL, 3.00, 50, 450.00, 'confirmed'),
(3, 3, 2, 3, '2023-06-20', '2023-06-22', 3, 150.00, 30.00, 5.00, 75, 540.00, 'pending');

-- Insert payments
INSERT INTO `payments` (`booking_id`, `amount`, `payment_method`, `transaction_id`, `payment_date`, `status`) VALUES
(1, 225.00, 'credit_card', 'TXN123456', '2023-06-01 10:05:00', 'completed'),
(2, 450.00, 'paypal', 'TXN789012', '2023-06-05 14:35:00', 'completed');

-- Insert reviews
INSERT INTO `reviews` (`booking_id`, `user_id`, `vendor_id`, `rating`, `comment`) VALUES
(1, 2, 1, 5, 'Excellent service and very clean car.'),
(2, 3, 1, 4, 'Good experience overall. Would recommend.');

-- Insert notifications
INSERT INTO `notifications` (`user_id`, `type`, `title`, `message`, `reference_id`, `is_read`) VALUES
(2, 'booking', 'Booking Confirmed', 'Your booking #1 has been confirmed.', 1, 1),
(2, 'payment', 'Payment Received', 'Your payment for booking #1 has been received.', 1, 1),
(3, 'booking', 'Booking Confirmed', 'Your booking #2 has been confirmed.', 2, 0),
(4, 'vendor', 'Vendor Application Approved', 'Your vendor application has been approved.', 1, 1),
(5, 'vendor', 'Vendor Application Pending', 'Your vendor application is pending review.', 2, 0);

-- Insert vehicle_images
INSERT INTO `vehicle_images` (`vehicle_id`, `image_path`, `is_primary`, `sort_order`) VALUES
(1, 'car1_1.jpg', 1, 1),
(1, 'car1_2.jpg', 0, 2),
(2, 'suv1_1.jpg', 1, 1),
(2, 'suv1_2.jpg', 0, 2),
(3, 'luxury1_1.jpg', 1, 1),
(3, 'luxury1_2.jpg', 0, 2);

-- Insert vehicle_availability (for the next 7 days from current date)
INSERT INTO `vehicle_availability` (`vehicle_id`, `date`, `quantity`) VALUES
(1, CURDATE(), 1),
(1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), 1),
(1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), 1),
(1, DATE_ADD(CURDATE(), INTERVAL 3 DAY), 1),
(1, DATE_ADD(CURDATE(), INTERVAL 4 DAY), 1),
(1, DATE_ADD(CURDATE(), INTERVAL 5 DAY), 1),
(1, DATE_ADD(CURDATE(), INTERVAL 6 DAY), 1),
(2, CURDATE(), 1),
(2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), 1),
(2, DATE_ADD(CURDATE(), INTERVAL 2 DAY), 1),
(2, DATE_ADD(CURDATE(), INTERVAL 3 DAY), 1),
(2, DATE_ADD(CURDATE(), INTERVAL 4 DAY), 1),
(2, DATE_ADD(CURDATE(), INTERVAL 5 DAY), 1),
(2, DATE_ADD(CURDATE(), INTERVAL 6 DAY), 1),
(3, CURDATE(), 1),
(3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), 1),
(3, DATE_ADD(CURDATE(), INTERVAL 2 DAY), 1),
(3, DATE_ADD(CURDATE(), INTERVAL 3 DAY), 1),
(3, DATE_ADD(CURDATE(), INTERVAL 4 DAY), 1),
(3, DATE_ADD(CURDATE(), INTERVAL 5 DAY), 1),
(3, DATE_ADD(CURDATE(), INTERVAL 6 DAY), 1);