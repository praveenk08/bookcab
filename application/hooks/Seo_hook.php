<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seo_hook {
    protected $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
    }
    
    /**
     * Add SEO meta tags and perform basic tasks before page rendering
     */
    public function add_seo_tags() {
        // Skip for AJAX requests
        if ($this->CI->input->is_ajax_request()) {
            return;
        }
        
        // Load URL helper if not already loaded
        if (!function_exists('base_url')) {
            $this->CI->load->helper('url');
        }
        
        // Default meta tags
        $meta = [
            'title' => 'Car Booking System',
            'description' => 'Book cars, vehicles and drivers for your travel needs',
            'keywords' => 'car booking, vehicle rental, driver hire, travel',
            'author' => 'Car Booking System',
            'robots' => 'index, follow',
            'canonical' => current_url(),
            'og:title' => 'Car Booking System',
            'og:description' => 'Book cars, vehicles and drivers for your travel needs',
            'og:type' => 'website',
            'og:url' => current_url(),
            'og:image' => base_url('assets/images/logo.png'),
            'twitter:card' => 'summary_large_image',
            'twitter:title' => 'Car Booking System',
            'twitter:description' => 'Book cars, vehicles and drivers for your travel needs',
            'twitter:image' => base_url('assets/images/logo.png')
        ];
        
        // Customize meta tags based on current page
        $this->customize_meta_tags($meta);
        
        // Add meta tags to output
        $this->add_meta_tags_to_output($meta);
        
        // Perform other basic tasks
        $this->perform_basic_tasks();
    }
    
    /**
     * Customize meta tags based on current page
     * 
     * @param array &$meta Meta tags array (passed by reference)
     */
    private function customize_meta_tags(&$meta) {
        // Get current controller and method
        $router = $this->CI->router;
        $controller = $router->fetch_class();
        $method = $router->fetch_method();
        
        // Customize based on controller/method
        switch ($controller) {
            case 'home':
                if ($method === 'index') {
                    $meta['title'] = 'Car Booking System - Home';
                    $meta['description'] = 'Welcome to Car Booking System. Book cars, vehicles and drivers for your travel needs.';
                }
                break;
                
            case 'vehicle':
                if ($method === 'details' && $this->CI->uri->segment(3)) {
                    // Try to get vehicle details
                    $this->CI->load->model('vehicle_model');
                    $vehicle_id = $this->CI->uri->segment(3);
                    $vehicle = $this->CI->vehicle_model->get_vehicle_by_id($vehicle_id);
                    
                    if ($vehicle) {
                        $meta['title'] = htmlspecialchars($vehicle->title) . ' - Car Booking System';
                        $meta['description'] = 'Book ' . htmlspecialchars($vehicle->title) . ' (' . htmlspecialchars($vehicle->type) . ') for your travel needs.';
                        $meta['keywords'] = htmlspecialchars($vehicle->title) . ', ' . htmlspecialchars($vehicle->type) . ', car booking, vehicle rental';
                        
                        // Set OpenGraph and Twitter tags
                        $meta['og:title'] = htmlspecialchars($vehicle->title) . ' - Car Booking System';
                        $meta['og:description'] = 'Book ' . htmlspecialchars($vehicle->title) . ' (' . htmlspecialchars($vehicle->type) . ') for your travel needs.';
                        $meta['twitter:title'] = htmlspecialchars($vehicle->title) . ' - Car Booking System';
                        $meta['twitter:description'] = 'Book ' . htmlspecialchars($vehicle->title) . ' (' . htmlspecialchars($vehicle->type) . ') for your travel needs.';
                        
                        // Set image if available
                        $images = isset($vehicle->images) ? json_decode($vehicle->images, true) : null;
                        if (!empty($images) && is_array($images) && isset($images[0])) {
                            $image_url = base_url('uploads/vehicles/' . $images[0]);
                            $meta['og:image'] = $image_url;
                            $meta['twitter:image'] = $image_url;
                        }
                    }
                } elseif ($method === 'search') {
                    $meta['title'] = 'Search Vehicles - Car Booking System';
                    $meta['description'] = 'Search for available vehicles for your travel needs.';
                    $meta['keywords'] = 'search vehicles, car booking, vehicle rental, driver hire';
                }
                break;
                
            case 'vendor':
                $meta['title'] = 'Vendor Dashboard - Car Booking System';
                $meta['description'] = 'Manage your vehicles, drivers, and bookings as a vendor.';
                $meta['keywords'] = 'vendor dashboard, manage vehicles, manage drivers, car booking';
                break;
                
            case 'admin':
                $meta['title'] = 'Admin Dashboard - Car Booking System';
                $meta['description'] = 'Manage the Car Booking System as an administrator.';
                $meta['keywords'] = 'admin dashboard, manage users, manage vendors, car booking';
                break;
                
            case 'user':
                $meta['title'] = 'User Dashboard - Car Booking System';
                $meta['description'] = 'Manage your bookings and profile as a user.';
                $meta['keywords'] = 'user dashboard, manage bookings, user profile, car booking';
                break;
                
            case 'booking':
                if ($method === 'view' && $this->CI->uri->segment(3)) {
                    $meta['title'] = 'Booking Details - Car Booking System';
                    $meta['description'] = 'View details of your booking.';
                    $meta['keywords'] = 'booking details, view booking, car booking';
                } elseif ($method === 'create') {
                    $meta['title'] = 'Create Booking - Car Booking System';
                    $meta['description'] = 'Create a new booking for a vehicle.';
                    $meta['keywords'] = 'create booking, book vehicle, car booking';
                }
                break;
        }
        
        // Set canonical URL
        $meta['canonical'] = current_url();
        $meta['og:url'] = current_url();
    }
    
    /**
     * Add meta tags to output
     * 
     * @param array $meta Meta tags array
     */
    private function add_meta_tags_to_output($meta) {
        // Set page title
        if (isset($meta['title'])) {
            $this->CI->output->set_header('X-Page-Title: ' . $meta['title']);
            $this->CI->output->append_output('<script>document.title = "' . addslashes($meta['title']) . '";</script>');
        }
        
        // Build meta tags HTML
        $meta_html = '';
        
        // Standard meta tags
        if (isset($meta['description'])) {
            $meta_html .= '<meta name="description" content="' . htmlspecialchars($meta['description']) . '">'."\n";
        }
        
        if (isset($meta['keywords'])) {
            $meta_html .= '<meta name="keywords" content="' . htmlspecialchars($meta['keywords']) . '">'."\n";
        }
        
        if (isset($meta['author'])) {
            $meta_html .= '<meta name="author" content="' . htmlspecialchars($meta['author']) . '">'."\n";
        }
        
        if (isset($meta['robots'])) {
            $meta_html .= '<meta name="robots" content="' . htmlspecialchars($meta['robots']) . '">'."\n";
        }
        
        if (isset($meta['canonical'])) {
            $meta_html .= '<link rel="canonical" href="' . htmlspecialchars($meta['canonical']) . '">'."\n";
        }
        
        // OpenGraph meta tags
        foreach ($meta as $key => $value) {
            if (strpos($key, 'og:') === 0) {
                $meta_html .= '<meta property="' . htmlspecialchars($key) . '" content="' . htmlspecialchars($value) . '">'."\n";
            }
        }
        
        // Twitter meta tags
        foreach ($meta as $key => $value) {
            if (strpos($key, 'twitter:') === 0) {
                $meta_html .= '<meta name="' . htmlspecialchars($key) . '" content="' . htmlspecialchars($value) . '">'."\n";
            }
        }
        
        // Add meta tags to output
        if (!empty($meta_html)) {
            $this->CI->output->append_output('<script>document.addEventListener("DOMContentLoaded", function() {
                var head = document.querySelector("head");
                head.insertAdjacentHTML("beforeend", ' . json_encode($meta_html) . ');
            });</script>');
        }
    }
    
    /**
     * Perform basic tasks before page rendering
     */
    private function perform_basic_tasks() {
        // Check for empty arrays and values to prevent errors
        $this->handle_empty_values();
        
        // Add security headers
        $this->add_security_headers();
        
        // Add performance optimizations
        $this->add_performance_optimizations();
    }
    
    /**
     * Handle empty arrays and values to prevent errors
     */
    private function handle_empty_values() {
        // Add error handling for common PHP notices
        $this->CI->output->append_output('<script>
        // Global error handler for JavaScript
        window.onerror = function(message, source, lineno, colno, error) {
            console.error("Error: " + message);
            return true; // Prevent default error handling
        };
        
        // Handle null or undefined values in JavaScript
        function safeGet(obj, path, defaultValue) {
            if (!obj) return defaultValue;
            const keys = path.split(".");
            let current = obj;
            
            for (let i = 0; i < keys.length; i++) {
                if (current === null || current === undefined) {
                    return defaultValue;
                }
                current = current[keys[i]];
            }
            
            return current === null || current === undefined ? defaultValue : current;
        }
        </script>');
    }
    
    /**
     * Add security headers to response
     */
    private function add_security_headers() {
        // Add Content Security Policy header
        $this->CI->output->set_header('X-Content-Type-Options: nosniff');
        $this->CI->output->set_header('X-Frame-Options: SAMEORIGIN');
        $this->CI->output->set_header('X-XSS-Protection: 1; mode=block');
    }
    
    /**
     * Add performance optimizations
     */
    private function add_performance_optimizations() {
        // Add browser caching headers for static assets
        if (preg_match('/\.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$/', $_SERVER['REQUEST_URI'])) {
            $this->CI->output->set_header('Cache-Control: public, max-age=31536000');
            $this->CI->output->set_header('Pragma: public');
            $this->CI->output->set_header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        }
    }
}