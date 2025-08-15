# Car Booking System

## Overview
A comprehensive car booking system built with CodeIgniter that allows users to book vehicles with or without drivers. The system supports multiple user roles including admin, vendor, and customer.

## Features
- User authentication and role-based access control
- Vehicle management with search and filter functionality
- Driver management with search and filter functionality
- Booking management system
- Payment processing
- Vendor registration and management
- Admin dashboard for system oversight
- Customer interface for booking vehicles

## Project Structure

### Core Directories
- **application/** - Contains the CodeIgniter application code
  - **config/** - Configuration files including routes, database settings
  - **controllers/** - Controller classes that handle requests
  - **models/** - Database models for data manipulation
  - **views/** - View templates for rendering HTML
  - **helpers/** - Helper functions including notification helpers
  - **libraries/** - Custom libraries and third-party integrations
- **assets/** - Static assets like CSS, JavaScript, and images
  - **css/** - Stylesheet files
  - **js/** - JavaScript files
  - **images/** - Image assets including placeholder images
- **uploads/** - Directory for uploaded files
  - **drivers/** - Driver photos and license documents
  - **vehicles/** - Vehicle images
  - **vendor_docs/** - Vendor documentation
- **system/** - CodeIgniter core system files (not to be modified)

### Key Files
- **index.php** - Entry point for the application
- **database.sql** - Database schema for setting up the database
- **.htaccess** - Apache configuration for URL rewriting

## Controllers
- **Auth.php** - Handles user authentication
- **Admin.php** - Admin dashboard and management functions
- **Booking.php** - Booking creation and management
- **Driver.php** - Driver management and search
- **Vehicle.php** - Vehicle management and search
- **Vendor.php** - Vendor registration and management
- **User.php** - User profile and bookings
- **Notification.php** - Notification system

## Models
- **User_model.php** - User data operations
- **Vehicle_model.php** - Vehicle data operations
- **Driver_model.php** - Driver data operations
- **Booking_model.php** - Booking data operations
- **Vendor_model.php** - Vendor data operations
- **Notification_model.php** - Notification data operations

## Views
- **templates/** - Header, footer, and other shared templates
- **auth/** - Login, registration, and profile views
- **admin/** - Admin dashboard views
- **booking/** - Booking creation and management views
- **driver/** - Driver management views
- **vehicle/** - Vehicle management views
- **vendor/** - Vendor management views
- **user/** - User dashboard views

## Installation
1. Clone the repository
2. Import the database.sql file to your MySQL server
3. Configure the database connection in application/config/database.php
4. Set the base URL in application/config/config.php
5. Make sure the upload directories have write permissions
6. Access the application through your web server

## Error Handling
- The system includes proper error handling for missing images and undefined variables
- Placeholder images are displayed when actual images are not available
- Form validation prevents invalid data submission

## Search and Filter Functionality
- The system includes search and filter capabilities for vehicles and drivers
- Users can search by name, license number, status, etc.
- Results are displayed in a responsive table format

## Requirements
- PHP 7.2 or higher
- MySQL 5.7 or higher
- Apache web server with mod_rewrite enabled
- GD library for image processing

## License
This project is licensed under the MIT License - see the LICENSE file for details.