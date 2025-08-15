-- Database updates for vehicle availability management

-- Check if the vehicle_availability table exists, if not create it
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

-- Add images column to vehicles table if it doesn't exist
SET @exist := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
               WHERE TABLE_SCHEMA = DATABASE() 
               AND TABLE_NAME = 'vehicles' 
               AND COLUMN_NAME = 'images');

SET @query := IF(@exist = 0, 'ALTER TABLE `vehicles` ADD COLUMN `images` TEXT NULL AFTER `fuel_charge_per_km`', 'SELECT "Column already exists"');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add quantity column to vehicles table if it doesn't exist
SET @exist := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
               WHERE TABLE_SCHEMA = DATABASE() 
               AND TABLE_NAME = 'vehicles' 
               AND COLUMN_NAME = 'quantity');

SET @query := IF(@exist = 0, 'ALTER TABLE `vehicles` ADD COLUMN `quantity` INT(11) NOT NULL DEFAULT 1 AFTER `is_active`', 'SELECT "Column already exists"');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;