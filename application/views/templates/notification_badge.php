<?php
// This component displays a notification badge with unread count
// It should be included in the header or navigation bar

// Get the current user ID
$user_id = $this->session->userdata('user_id');

// Only proceed if user is logged in
if ($user_id) {
    // Load the notification model if not already loaded
    if (!isset($this->notification_model)) {
        $CI =& get_instance();
        $CI->load->model('notification_model');
        $unread_count = $CI->notification_model->get_unread_count($user_id);
    } else {
        $unread_count = $this->notification_model->get_unread_count($user_id);
    }
}
?>

<?php if (isset($user_id) && $user_id): ?>
<a href="<?php echo base_url('notification'); ?>" class="nav-link position-relative notification-badge">
    <i class="fas fa-bell"></i>
    <?php if (isset($unread_count) && $unread_count > 0): ?>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?php echo ($unread_count > 99) ? '99+' : $unread_count; ?>
        <span class="visually-hidden">unread notifications</span>
    </span>
    <?php endif; ?>
</a>
<?php endif; ?>