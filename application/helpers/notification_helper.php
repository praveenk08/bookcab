<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Notification Helper
 *
 * This helper provides functions to create notifications from any controller
 */

// ------------------------------------------------------------------------

if (!function_exists('create_notification')) {
    /**
     * Create a notification for a user
     *
     * @param int $user_id User ID
     * @param string $type Notification type (booking, payment, review, system, vendor)
     * @param string $title Notification title
     * @param string $message Notification message
     * @param int $reference_id Reference ID (optional)
     * @return bool Success or failure
     */
    function create_notification($user_id, $type, $title, $message, $reference_id = NULL) {
        $CI =& get_instance();
        $CI->load->model('notification_model');
        
        $data = [
            'user_id' => $user_id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'reference_id' => $reference_id,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $CI->notification_model->create_notification($data);
    }
}

// ------------------------------------------------------------------------

if (!function_exists('create_multiple_notifications')) {
    /**
     * Create notifications for multiple users
     *
     * @param array $user_ids Array of user IDs
     * @param string $type Notification type (booking, payment, review, system, vendor)
     * @param string $title Notification title
     * @param string $message Notification message
     * @param int $reference_id Reference ID (optional)
     * @return bool Success or failure
     */
    function create_multiple_notifications($user_ids, $type, $title, $message, $reference_id = NULL) {
        $CI =& get_instance();
        $CI->load->model('notification_model');
        
        return $CI->notification_model->create_multiple_notifications($user_ids, $type, $title, $message, $reference_id);
    }
}

// ------------------------------------------------------------------------

if (!function_exists('get_unread_notification_count')) {
    /**
     * Get unread notification count for a user
     *
     * @param int $user_id User ID
     * @return int Count of unread notifications
     */
    function get_unread_notification_count($user_id) {
        $CI =& get_instance();
        $CI->load->model('notification_model');
        
        return $CI->notification_model->get_unread_count($user_id);
    }
}