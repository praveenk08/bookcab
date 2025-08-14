/**
 * Notifications JavaScript functionality
 */

// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Mark notification as read via AJAX
    const markAsReadButtons = document.querySelectorAll('.mark-notification-read');
    if (markAsReadButtons) {
        markAsReadButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const notificationId = this.getAttribute('data-notification-id');
                const notificationItem = this.closest('.list-group-item');
                
                // Send AJAX request
                fetch(base_url + 'notification/mark_as_read_ajax/' + notificationId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI
                        notificationItem.classList.remove('unread');
                        const badge = notificationItem.querySelector('.badge.bg-primary');
                        if (badge) {
                            badge.remove();
                        }
                        this.remove(); // Remove the mark as read button
                        
                        // Update notification count in navbar
                        updateNotificationCount(data.unread_count);
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                });
            });
        });
    }
    
    // Function to update notification count in navbar
    function updateNotificationCount(count) {
        const notificationBadge = document.querySelector('.notification-badge .badge');
        if (notificationBadge) {
            if (count > 0) {
                notificationBadge.textContent = count > 99 ? '99+' : count;
                notificationBadge.style.display = 'inline-block';
            } else {
                notificationBadge.style.display = 'none';
            }
        }
    }
    
    // Delete notification via AJAX
    const deleteButtons = document.querySelectorAll('.delete-notification');
    if (deleteButtons) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (confirm('Are you sure you want to delete this notification?')) {
                    const notificationId = this.getAttribute('data-notification-id');
                    const notificationItem = this.closest('.list-group-item');
                    
                    // Send AJAX request
                    fetch(base_url + 'notification/delete_ajax/' + notificationId, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove notification from UI
                            notificationItem.remove();
                            
                            // Update notification count in navbar
                            updateNotificationCount(data.unread_count);
                            
                            // Check if there are no more notifications
                            const notificationList = document.querySelector('.notification-list');
                            if (notificationList && notificationList.children.length === 0) {
                                // Show empty state
                                const emptyState = `
                                    <div class="text-center py-5">
                                        <i class="fas fa-bell fa-4x text-muted mb-3"></i>
                                        <h4 class="text-muted">No Notifications</h4>
                                        <p class="text-muted">You don't have any notifications at the moment.</p>
                                    </div>
                                `;
                                notificationList.innerHTML = emptyState;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting notification:', error);
                    });
                }
            });
        });
    }
});