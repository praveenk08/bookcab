<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('notification_model');
        $this->load->helper(['form', 'url', 'security']);
        $this->load->library(['form_validation', 'session']);
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * Display all notifications for the current user
     */
    public function index() {
        $user_id = $this->session->userdata('user_id');
        $data['notifications'] = $this->notification_model->get_user_notifications($user_id);
        
        $this->load->view('templates/header');
        $this->load->view('notification/list', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Mark a notification as read
     * 
     * @param int $id Notification ID
     */
    public function mark_as_read($id) {
        $user_id = $this->session->userdata('user_id');
        $success = $this->notification_model->mark_as_read($id, $user_id);
        
        if ($success) {
            $this->session->set_flashdata('success', 'Notification marked as read.');
        } else {
            $this->session->set_flashdata('error', 'Failed to mark notification as read.');
        }
        
        redirect('notification');
    }
    
    /**
     * Mark a notification as read via AJAX
     * 
     * @param int $id Notification ID
     */
    public function mark_as_read_ajax($id) {
        $user_id = $this->session->userdata('user_id');
        $result = $this->notification_model->mark_as_read($id, $user_id);
        $unread_count = $this->notification_model->get_unread_count($user_id);
        
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode([
            'success' => $result,
            'unread_count' => $unread_count
        ]));
    }
    
    /**
     * Mark all notifications as read for the current user
     */
    public function mark_all_as_read() {
        $user_id = $this->session->userdata('user_id');
        $success = $this->notification_model->mark_all_as_read($user_id);
        
        if ($success) {
            $this->session->set_flashdata('success', 'All notifications marked as read.');
        } else {
            $this->session->set_flashdata('error', 'Failed to mark notifications as read.');
        }
        
        redirect('notification');
    }
    
    /**
     * Mark all notifications as read via AJAX
     */
    public function mark_all_as_read_ajax() {
        $user_id = $this->session->userdata('user_id');
        $result = $this->notification_model->mark_all_as_read($user_id);
        
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode([
            'success' => $result,
            'unread_count' => 0
        ]));
    }
    
    /**
     * Delete a notification
     * 
     * @param int $id Notification ID
     */
    public function delete($id) {
        $user_id = $this->session->userdata('user_id');
        $success = $this->notification_model->delete_notification($id, $user_id);
        
        if ($success) {
            $this->session->set_flashdata('success', 'Notification deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete notification.');
        }
        
        redirect('notification');
    }
    
    /**
     * Delete a notification via AJAX
     * 
     * @param int $id Notification ID
     */
    public function delete_ajax($id) {
        $user_id = $this->session->userdata('user_id');
        $result = $this->notification_model->delete_notification($id, $user_id);
        $unread_count = $this->notification_model->get_unread_count($user_id);
        
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode([
            'success' => $result,
            'unread_count' => $unread_count
        ]));
    }
    
    /**
     * Delete all notifications for the current user
     */
    public function delete_all() {
        $user_id = $this->session->userdata('user_id');
        $success = $this->notification_model->delete_all_notifications($user_id);
        
        if ($success) {
            $this->session->set_flashdata('success', 'All notifications deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete notifications.');
        }
        
        redirect('notification');
    }
    
    /**
     * Delete all notifications via AJAX
     */
    public function delete_all_ajax() {
        $user_id = $this->session->userdata('user_id');
        $result = $this->notification_model->delete_all_notifications($user_id);
        
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode([
            'success' => $result,
            'unread_count' => 0
        ]));
    }
    
    /**
     * Get unread notification count for the current user
     * Used for AJAX requests
     */
    public function get_unread_count() {
        $user_id = $this->session->userdata('user_id');
        $count = $this->notification_model->get_unread_count($user_id);
        
        // Return as JSON
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(['count' => $count]));
    }
}