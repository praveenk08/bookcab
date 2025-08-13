<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Register a new user
     * 
     * @param array $data User data
     * @return int|bool User ID on success, FALSE on failure
     */
    public function register($data) {
        $this->db->insert('users', $data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
    }

    /**
     * Get user by email
     * 
     * @param string $email User email
     * @return object|bool User object on success, FALSE if not found
     */
    public function get_user_by_email($email) {
        $query = $this->db->get_where('users', ['email' => $email]);
        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }

    /**
     * Get user by ID
     * 
     * @param int $id User ID
     * @return object|bool User object on success, FALSE if not found
     */
    public function get_user_by_id($id) {
        $query = $this->db->get_where('users', ['id' => $id]);
        $user = ($query->num_rows() > 0) ? $query->row() : FALSE;
        
        // Ensure status property exists
        if ($user && !isset($user->status)) {
            $user->status = 'active'; // Default status
        }
        
        return $user;
    }

    /**
     * Update user profile
     * 
     * @param int $id User ID
     * @param array $data User data to update
     * @return bool TRUE on success, FALSE on failure
     */
    public function update_user($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('users', $data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Change user password
     * 
     * @param int $id User ID
     * @param string $new_password_hash New password hash
     * @return bool TRUE on success, FALSE on failure
     */
    public function change_password($id, $new_password_hash) {
        $this->db->where('id', $id);
        $this->db->update('users', ['password_hash' => $new_password_hash]);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Get all users with optional filtering
     * 
     * @param array $filters Optional filters
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of user objects
     */
    public function get_users($filters = [], $limit = NULL, $offset = NULL) {
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        if ($limit !== NULL && $offset !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get('users');
        return $query->result();
    }

    /**
     * Count users with optional filtering
     * 
     * @param array $filters Optional filters
     * @return int Number of users
     */
    public function count_users($filters = []) {
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        return $this->db->count_all_results('users');
    }
    
    /**
     * Save password reset token
     * 
     * @param int $user_id User ID
     * @param string $token Reset token
     * @param string $expiry Token expiry datetime
     * @return bool TRUE on success, FALSE on failure
     */
    public function save_reset_token($user_id, $token, $expiry) {
        $this->db->where('id', $user_id);
        $this->db->update('users', [
            'reset_token' => $token,
            'reset_token_expiry' => $expiry
        ]);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    
    /**
     * Get user by reset token
     * 
     * @param string $token Reset token
     * @return object|bool User object on success, FALSE if not found or expired
     */
    public function get_user_by_reset_token($token) {
        $this->db->where('reset_token', $token);
        $this->db->where('reset_token_expiry >', date('Y-m-d H:i:s'));
        $query = $this->db->get('users');
        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }
    
    /**
     * Update user password
     * 
     * @param int $user_id User ID
     * @param string $password_hash New password hash
     * @return bool TRUE on success, FALSE on failure
     */
    public function update_password($user_id, $password_hash) {
        $this->db->where('id', $user_id);
        $this->db->update('users', ['password_hash' => $password_hash]);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    
    /**
     * Clear reset token
     * 
     * @param int $user_id User ID
     * @return bool TRUE on success, FALSE on failure
     */
    public function clear_reset_token($user_id) {
        $this->db->where('id', $user_id);
        $this->db->update('users', [
            'reset_token' => NULL,
            'reset_token_expiry' => NULL
        ]);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    
    /**
     * Save email verification token
     * 
     * @param int $user_id User ID
     * @param string $token Verification token
     * @return bool TRUE on success, FALSE on failure
     */
    public function save_verification_token($user_id, $token) {
        $this->db->where('id', $user_id);
        $this->db->update('users', ['verification_token' => $token]);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    
    /**
     * Get user by verification token
     * 
     * @param string $token Verification token
     * @return object|bool User object on success, FALSE if not found
     */
    public function get_user_by_verification_token($token) {
        $query = $this->db->get_where('users', ['verification_token' => $token]);
        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }
    
    /**
     * Mark user as verified
     * 
     * @param int $user_id User ID
     * @return bool TRUE on success, FALSE on failure
     */
    public function verify_user($user_id) {
        $this->db->where('id', $user_id);
        $this->db->update('users', [
            'is_verified' => 1,
            'verification_token' => NULL
        ]);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
}