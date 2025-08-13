<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    private $settings_table = 'settings';

    public function __construct() {
        parent::__construct();
        $this->load->database();
        
        // Check if settings table exists, if not create it
        if (!$this->db->table_exists($this->settings_table)) {
            $this->create_settings_table();
        }
    }
    
    /**
     * Create settings table if it doesn't exist
     */
    private function create_settings_table() {
        $this->load->dbforge();
        
        $fields = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'setting_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => TRUE
            ),
            'setting_value' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP'
            ),
            'updated_at' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE
            )
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->settings_table, TRUE);
        
        // Add default settings
        $this->add_default_settings();
    }
    
    /**
     * Add default settings to the table
     */
    private function add_default_settings() {
        $default_settings = array(
            array('setting_key' => 'site_name', 'setting_value' => 'Car Booking System'),
            array('setting_key' => 'site_tagline', 'setting_value' => 'Book your ride with ease'),
            array('setting_key' => 'admin_email', 'setting_value' => 'admin@carbooking.com'),
            array('setting_key' => 'contact_phone', 'setting_value' => '+1234567890'),
            array('setting_key' => 'site_description', 'setting_value' => 'Car Booking System - Book your ride with ease'),
            array('setting_key' => 'currency', 'setting_value' => 'USD'),
            array('setting_key' => 'timezone', 'setting_value' => 'UTC'),
            array('setting_key' => 'maintenance_mode', 'setting_value' => '0'),
            array('setting_key' => 'booking_advance_days', 'setting_value' => '30'),
            array('setting_key' => 'booking_min_hours', 'setting_value' => '4'),
            array('setting_key' => 'enable_guest_booking', 'setting_value' => '0'),
            array('setting_key' => 'auto_approve_booking', 'setting_value' => '0'),
            array('setting_key' => 'cancellation_hours', 'setting_value' => '24'),
            array('setting_key' => 'cancellation_fee_percent', 'setting_value' => '10'),
            array('setting_key' => 'service_fee_percent', 'setting_value' => '5'),
            array('setting_key' => 'tax_percent', 'setting_value' => '7'),
            array('setting_key' => 'payment_methods', 'setting_value' => 'cash,paypal'),
            array('setting_key' => 'mail_protocol', 'setting_value' => 'smtp'),
            array('setting_key' => 'smtp_host', 'setting_value' => 'smtp.example.com'),
            array('setting_key' => 'smtp_port', 'setting_value' => '587'),
            array('setting_key' => 'smtp_user', 'setting_value' => 'user@example.com'),
            array('setting_key' => 'smtp_pass', 'setting_value' => 'password'),
            array('setting_key' => 'smtp_crypto', 'setting_value' => 'tls'),
            array('setting_key' => 'mail_from_email', 'setting_value' => 'noreply@carbooking.com'),
            array('setting_key' => 'mail_from_name', 'setting_value' => 'Car Booking System'),
            array('setting_key' => 'email_template_booking', 'setting_value' => 'Dear {customer_name},\n\nYour booking (ID: {booking_id}) has been confirmed for {booking_date}.\n\nTotal Amount: {total_amount}\nVehicle Details: {vehicle_details}\n\nThank you for choosing our service.'),
            array('setting_key' => 'email_template_cancellation', 'setting_value' => 'Dear {customer_name},\n\nYour booking (ID: {booking_id}) for {booking_date} has been cancelled.\n\nReason: {cancellation_reason}\n\nThank you for choosing our service.')
        );
        
        foreach ($default_settings as $setting) {
            $this->db->insert($this->settings_table, $setting);
        }
    }
    
    /**
     * Get all settings as key-value pairs
     * 
     * @return array Settings as key-value pairs
     */
    public function get_all_settings() {
        $query = $this->db->get($this->settings_table);
        $settings = array();
        
        foreach ($query->result() as $row) {
            $settings[$row->setting_key] = $row->setting_value;
        }
        
        return $settings;
    }
    
    /**
     * Get a specific setting by key
     * 
     * @param string $key Setting key
     * @param mixed $default Default value if setting not found
     * @return mixed Setting value or default
     */
    public function get_setting($key, $default = NULL) {
        $this->db->where('setting_key', $key);
        $query = $this->db->get($this->settings_table);
        
        if ($query->num_rows() > 0) {
            return $query->row()->setting_value;
        }
        
        return $default;
    }
    
    /**
     * Update a single setting
     * 
     * @param string $key Setting key
     * @param mixed $value Setting value
     * @return bool TRUE on success, FALSE on failure
     */
    public function update_setting($key, $value) {
        $this->db->where('setting_key', $key);
        $query = $this->db->get($this->settings_table);
        
        $data = array(
            'setting_value' => $value,
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        if ($query->num_rows() > 0) {
            $this->db->where('setting_key', $key);
            return $this->db->update($this->settings_table, $data);
        } else {
            $data['setting_key'] = $key;
            return $this->db->insert($this->settings_table, $data);
        }
    }
    
    /**
     * Update multiple settings at once
     * 
     * @param array $settings Array of settings as key-value pairs
     * @return bool TRUE on success, FALSE on failure
     */
    public function update_settings($settings) {
        $this->db->trans_start();
        
        foreach ($settings as $key => $value) {
            // Skip non-setting fields like submit buttons, CSRF tokens, etc.
            if (in_array($key, array('submit', 'csrf_token'))) {
                continue;
            }
            
            $this->update_setting($key, $value);
        }
        
        $this->db->trans_complete();
        
        return ($this->db->trans_status() === TRUE);
    }
    
    /**
     * Delete a setting
     * 
     * @param string $key Setting key
     * @return bool TRUE on success, FALSE on failure
     */
    public function delete_setting($key) {
        $this->db->where('setting_key', $key);
        return $this->db->delete($this->settings_table);
    }
}