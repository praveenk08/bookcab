<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper(['form', 'url', 'security', 'string']);
        $this->load->library(['form_validation', 'session', 'email']);
    }

    public function index() {
        // If already logged in, redirect to appropriate dashboard
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_based_on_role();
        }
        
        // Show login page
        $this->load->view('templates/header');
        $this->load->view('auth/login');
        $this->load->view('templates/footer');
    }

    public function register() {
        // If already logged in, redirect to appropriate dashboard
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_based_on_role();
        }

        // Form validation rules
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');

        if ($this->form_validation->run() === FALSE) {
            // Load registration form
            $this->load->view('templates/header');
            $this->load->view('auth/register');
            $this->load->view('templates/footer');
        } else {
            // Hash password
            $password_hash = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
            
            // Prepare user data
            $user_data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'password_hash' => $password_hash,
                'role' => 'user',
                'is_verified' => 1 // Auto-verify for now, can be changed to 0 for email verification
            ];
            
            // Register user
            $user_id = $this->user_model->register($user_data);
            
            if ($user_id) {
                $this->session->set_flashdata('success', 'Registration successful! You can now login.');
                redirect('auth');
            } else {
                $this->session->set_flashdata('error', 'Registration failed. Please try again.');
                redirect('auth/register');
            }
        }
    }

    public function login() {
        // If already logged in, redirect to appropriate dashboard
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_based_on_role();
        }

        // Form validation rules
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            // Load login form
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            
            // Verify credentials
            $user = $this->user_model->get_user_by_email($email);
            
            if ($user && password_verify($password, $user->password_hash)) {
                // Set session data
                $session_data = [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'logged_in' => TRUE
                ];
                
                $this->session->set_userdata($session_data);
                
                // Redirect based on role
                $this->_redirect_based_on_role();
            } else {
                $this->session->set_flashdata('error', 'Invalid email or password');
                redirect('auth');
            }
        }
    }

    public function logout() {
        // Unset user session data
        $this->session->unset_userdata(['user_id', 'name', 'email', 'role', 'logged_in']);
        $this->session->set_flashdata('success', 'You have been logged out successfully');
        redirect('auth');
    }
    
    public function change_password() {
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Form validation rules
        $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[new_password]');
        
        if ($this->form_validation->run() === FALSE) {
            // Load change password form
            $this->load->view('templates/header');
            $this->load->view('auth/change_password');
            $this->load->view('templates/footer');
        } else {
            // Get user data
            $user_id = $this->session->userdata('user_id');
            $user = $this->user_model->get_user_by_id($user_id);
            
            // Verify current password
            if (!password_verify($this->input->post('current_password'), $user->password_hash)) {
                $this->session->set_flashdata('error', 'Current password is incorrect.');
                redirect('auth/change_password');
            }
            
            // Hash new password
            $password_hash = password_hash($this->input->post('new_password'), PASSWORD_BCRYPT);
            
            // Update password
            $result = $this->user_model->update_user($user_id, ['password_hash' => $password_hash]);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Password changed successfully.');
                
                // Redirect based on user role
                $this->_redirect_based_on_role();
            } else {
                $this->session->set_flashdata('error', 'Failed to change password. Please try again.');
                redirect('auth/change_password');
            }
        }
    }

    private function _redirect_based_on_role() {
        switch ($this->session->userdata('role')) {
            case 'admin':
                redirect('admin/dashboard');
                break;
            case 'vendor':
                redirect('vendor/dashboard');
                break;
            default: // 'user'
                redirect('user/dashboard');
                break;
        }
    }
    
    public function forgot_password() {
        // If already logged in, redirect to appropriate dashboard
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_based_on_role();
        }
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        
        if ($this->form_validation->run() === FALSE) {
            // Load forgot password form
            $this->load->view('templates/header');
            $this->load->view('auth/forgot_password');
            $this->load->view('templates/footer');
        } else {
            $email = $this->input->post('email');
            $user = $this->user_model->get_user_by_email($email);
            
            if ($user) {
                // Generate reset token
                $token = random_string('alnum', 32);
                $expiry = date('Y-m-d H:i:s', strtotime('+1 day'));
                
                // Save token to database
                $this->user_model->save_reset_token($user->id, $token, $expiry);
                
                // Send reset email
                $reset_link = base_url('auth/reset_password/' . $token);
                
                $this->email->from('noreply@carbooking.com', 'Car Booking System');
                $this->email->to($email);
                $this->email->subject('Reset Your Password');
                $this->email->message('Click the link below to reset your password: ' . $reset_link);
                
                if ($this->email->send()) {
                    $this->session->set_flashdata('success', 'Password reset instructions have been sent to your email');
                } else {
                    $this->session->set_flashdata('error', 'Failed to send reset email. Please try again later.');
                }
            } else {
                // Don't reveal that the email doesn't exist for security
                $this->session->set_flashdata('success', 'If your email exists in our system, you will receive reset instructions');
            }
            
            redirect('auth/forgot_password');
        }
    }
    
    public function reset_password($token = NULL) {
        // If already logged in, redirect to appropriate dashboard
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_based_on_role();
        }
        
        if (!$token) {
            $this->session->set_flashdata('error', 'Invalid reset token');
            redirect('auth/forgot_password');
        }
        
        $user = $this->user_model->get_user_by_reset_token($token);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'Invalid or expired reset token');
            redirect('auth/forgot_password');
        }
        
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
        
        if ($this->form_validation->run() === FALSE) {
            // Load reset password form
            $data['token'] = $token;
            $this->load->view('templates/header');
            $this->load->view('auth/reset_password', $data);
            $this->load->view('templates/footer');
        } else {
            // Hash new password
            $password_hash = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
            
            // Update user password
            $this->user_model->update_password($user->id, $password_hash);
            
            // Clear reset token
            $this->user_model->clear_reset_token($user->id);
            
            $this->session->set_flashdata('success', 'Your password has been reset successfully. You can now log in with your new password.');
            redirect('auth/login');
        }
    }
    
    public function verify_email($token = NULL) {
        if (!$token) {
            $data['success'] = FALSE;
            $data['message'] = 'Invalid verification token';
        } else {
            $user = $this->user_model->get_user_by_verification_token($token);
            
            if ($user) {
                // Mark user as verified
                $this->user_model->verify_user($user->id);
                
                $data['success'] = TRUE;
            } else {
                $data['success'] = FALSE;
                $data['message'] = 'Invalid or expired verification token';
            }
        }
        
        $this->load->view('templates/header');
        $this->load->view('auth/verify_email', $data);
        $this->load->view('templates/footer');
    }
    
    public function resend_verification() {
        // If already logged in, redirect to appropriate dashboard
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_based_on_role();
        }
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        
        if ($this->form_validation->run() === FALSE) {
            // Load resend verification form
            $this->load->view('templates/header');
            $this->load->view('auth/resend_verification');
            $this->load->view('templates/footer');
        } else {
            $email = $this->input->post('email');
            $user = $this->user_model->get_user_by_email($email);
            
            if ($user && !$user->is_verified) {
                // Generate verification token
                $token = random_string('alnum', 32);
                
                // Save token to database
                $this->user_model->save_verification_token($user->id, $token);
                
                // Send verification email
                $verification_link = base_url('auth/verify_email/' . $token);
                
                $this->email->from('noreply@carbooking.com', 'Car Booking System');
                $this->email->to($email);
                $this->email->subject('Verify Your Email');
                $this->email->message('Click the link below to verify your email: ' . $verification_link);
                
                if ($this->email->send()) {
                    $this->session->set_flashdata('success', 'Verification email has been sent to your email address');
                } else {
                    $this->session->set_flashdata('error', 'Failed to send verification email. Please try again later.');
                }
            } else {
                // Don't reveal that the email doesn't exist or is already verified for security
                $this->session->set_flashdata('success', 'If your email exists and is not verified, you will receive verification instructions');
            }
            
            redirect('auth/login');
        }
    }
}