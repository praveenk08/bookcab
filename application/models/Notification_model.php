<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Get all notifications for a specific user
     * 
     * @param int $user_id User ID
     * @param int $limit Optional limit for number of notifications
     * @return array Array of notification objects
     */
    public function get_user_notifications($user_id, $limit = NULL) {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        
        if ($limit !== NULL) {
            $this->db->limit($limit);
        }
        
        $query = $this->db->get('notifications');
        return $query->result();
    }
    
    /**
     * Get unread notifications for a specific user
     * 
     * @param int $user_id User ID
     * @param int $limit Optional limit for number of notifications
     * @return array Array of notification objects
     */
    public function get_unread_notifications($user_id, $limit = NULL) {
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        $this->db->order_by('created_at', 'DESC');
        
        if ($limit !== NULL) {
            $this->db->limit($limit);
        }
        
        $query = $this->db->get('notifications');
        return $query->result();
    }
    
    /**
     * Get count of unread notifications for a user
     * 
     * @param int $user_id User ID
     * @return int Count of unread notifications
     */
    public function get_unread_count($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        return $this->db->count_all_results('notifications');
    }
    
    /**
     * Mark a notification as read
     * 
     * @param int $notification_id Notification ID
     * @param int $user_id User ID (for security check)
     * @return bool Success or failure
     */
    public function mark_as_read($notification_id, $user_id) {
        $this->db->where('id', $notification_id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('notifications', ['is_read' => 1]);
    }
    
    /**
     * Mark all notifications as read for a user
     * 
     * @param int $user_id User ID
     * @return bool Success or failure
     */
    public function mark_all_as_read($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->update('notifications', ['is_read' => 1]);
    }
    
    /**
     * Delete a notification
     * 
     * @param int $notification_id Notification ID
     * @param int $user_id User ID (for security check)
     * @return bool Success or failure
     */
    public function delete_notification($notification_id, $user_id) {
        $this->db->where('id', $notification_id);
        $this->db->where('user_id', $user_id);
        return $this->db->delete('notifications');
    }
    
    /**
     * Delete all notifications for a user
     * 
     * @param int $user_id User ID
     * @return bool Success or failure
     */
    public function delete_all_notifications($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->delete('notifications');
    }
    
    /**
     * Create a new notification
     * 
     * @param array $data Notification data
     * @return int|bool Inserted ID on success, false on failure
     */
    public function create_notification($data) {
        $this->db->insert('notifications', $data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
    }
    
    /**
     * Create multiple notifications (for multiple users)
     * 
     * @param array $user_ids Array of user IDs
     * @param string $type Notification type
     * @param string $title Notification title
     * @param string $message Notification message
     * @param int $reference_id Reference ID (optional)
     * @return bool Success or failure
     */
    public function create_multiple_notifications($user_ids, $type, $title, $message, $reference_id = NULL) {
        $data = [];
        $now = date('Y-m-d H:i:s');
        
        foreach ($user_ids as $user_id) {
            $data[] = [
                'user_id' => $user_id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'reference_id' => $reference_id,
                'is_read' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        
        if (!empty($data)) {
            return $this->db->insert_batch('notifications', $data);
        }
        
        return FALSE;
    }
}