# Notification System Documentation

## Overview

The notification system allows for real-time notifications to users, vendors, and administrators within the Car Booking Platform. It provides a centralized way to inform users about important events such as booking confirmations, payment receipts, and system announcements.

## Features

- User notifications for bookings, payments, and system messages
- Vendor notifications for new bookings and updates
- Admin notifications for system-wide events
- Notification badge with unread count in the navigation bar
- Mark notifications as read individually or all at once
- Delete notifications individually or all at once
- AJAX support for real-time updates without page refresh

## Database Structure

The notification system uses a `notifications` table with the following structure:

```sql
CREATE TABLE `notifications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `type` enum('booking','payment','review','system','vendor') DEFAULT 'system',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `reference_id` int(11) unsigned DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_read` (`is_read`),
  CONSTRAINT `fk_notifications_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

## Components

### 1. Notification Model

The `Notification_model.php` handles all database operations related to notifications:

- `get_user_notifications($user_id, $limit = NULL)` - Get all notifications for a user
- `get_unread_notifications($user_id, $limit = NULL)` - Get unread notifications for a user
- `get_unread_count($user_id)` - Get count of unread notifications
- `mark_as_read($notification_id, $user_id)` - Mark a notification as read
- `mark_all_as_read($user_id)` - Mark all notifications as read
- `delete_notification($notification_id, $user_id)` - Delete a notification
- `delete_all_notifications($user_id)` - Delete all notifications
- `create_notification($data)` - Create a new notification
- `create_multiple_notifications($user_ids, $type, $title, $message, $reference_id)` - Create notifications for multiple users

### 2. Notification Controller

The `Notification.php` controller handles all notification-related actions:

- `index()` - Display all notifications
- `mark_as_read($notification_id)` - Mark a notification as read
- `mark_as_read_ajax($notification_id)` - Mark a notification as read via AJAX
- `mark_all_as_read()` - Mark all notifications as read
- `mark_all_as_read_ajax()` - Mark all notifications as read via AJAX
- `delete($notification_id)` - Delete a notification
- `delete_ajax($notification_id)` - Delete a notification via AJAX
- `delete_all()` - Delete all notifications
- `delete_all_ajax()` - Delete all notifications via AJAX
- `get_unread_count()` - Get unread notification count

### 3. Notification Helper

The `notification_helper.php` provides helper functions to create notifications from any controller:

- `create_notification($user_id, $type, $title, $message, $reference_id = NULL)` - Create a notification
- `create_multiple_notifications($user_ids, $type, $title, $message, $reference_id = NULL)` - Create notifications for multiple users
- `get_unread_notification_count($user_id)` - Get unread notification count

### 4. Notification Views

- `notification/list.php` - Main view to display all notifications
- `templates/notification_badge.php` - Badge component for the navigation bar

### 5. Assets

- `assets/css/notifications.css` - CSS styles for notifications
- `assets/js/notifications.js` - JavaScript for AJAX functionality

## How to Use

### Creating Notifications

To create a notification from any controller, use the helper functions:

```php
// Create a notification for a single user
create_notification(
    $user_id,
    'booking',  // Type: booking, payment, review, system, vendor
    'Booking Confirmed',  // Title
    'Your booking #123 has been confirmed.',  // Message
    123  // Reference ID (optional)
);

// Create notifications for multiple users
create_multiple_notifications(
    [1, 2, 3],  // Array of user IDs
    'system',  // Type
    'System Maintenance',  // Title
    'The system will be down for maintenance on Saturday.',  // Message
    null  // Reference ID (optional)
);
```

### Displaying Notifications

The notification badge is automatically included in the navigation bar. To display the full list of notifications, use:

```php
redirect('notification');
```

### Customizing Notification Types

To add a new notification type:

1. Update the `type` enum in the `notifications` table
2. Add styling for the new type in `notifications.css`
3. Update the badge class in the `list.php` view

## Implementation Examples

### Booking Creation

When a booking is created, notifications are sent to the user, vendor, and admin:

```php
// Create notification for user
create_notification(
    $user_id,
    'booking',
    'Booking Created',
    'Your booking #' . $booking_id . ' has been created successfully.',
    $booking_id
);

// Create notification for vendor
create_notification(
    $vendor_user_id,
    'booking',
    'New Booking Received',
    'You have received a new booking #' . $booking_id . '.',
    $booking_id
);

// Create notification for admin
create_notification(
    $admin_id,
    'booking',
    'New Booking Created',
    'A new booking #' . $booking_id . ' has been created.',
    $booking_id
);
```

### Payment Confirmation

When a payment is made, notifications are sent to the user and vendor:

```php
// Create notification for user
create_notification(
    $user_id,
    'payment',
    'Payment Confirmed',
    'Your payment for booking #' . $booking_id . ' has been confirmed.',
    $booking_id
);

// Create notification for vendor
create_notification(
    $vendor_user_id,
    'payment',
    'Payment Received',
    'Payment for booking #' . $booking_id . ' has been received.',
    $booking_id
);
```

## Conclusion

The notification system provides a comprehensive solution for real-time notifications within the Car Booking Platform. It can be easily extended to support additional notification types and features as needed.