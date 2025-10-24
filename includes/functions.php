<?php
// Common functions for AquaCampus

// Sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Generate unique order number
function generate_order_number() {
    return 'ORD' . date('Ymd') . rand(1000, 9999);
}

// Format currency
function format_currency($amount) {
    return '$' . number_format($amount, 2);
}

// Format date
function format_date($date) {
    return date('M d, Y', strtotime($date));
}

// Format datetime
function format_datetime($datetime) {
    return date('M d, Y g:i A', strtotime($datetime));
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Redirect function
function redirect($url) {
    header("Location: $url");
    exit();
}

// Flash message functions
function set_flash_message($message, $type = 'info') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

function get_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}

// Validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate phone number
function validate_phone($phone) {
    return preg_match('/^[\+]?[1-9][\d]{0,15}$/', $phone);
}

// Hash password
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify password
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Get delivery status badge class
function get_status_badge_class($status) {
    $classes = [
        'pending' => 'bg-warning',
        'confirmed' => 'bg-info',
        'preparing' => 'bg-primary',
        'out_for_delivery' => 'bg-secondary',
        'delivered' => 'bg-success',
        'cancelled' => 'bg-danger'
    ];
    return $classes[$status] ?? 'bg-secondary';
}

// Get payment status badge class
function get_payment_status_badge_class($status) {
    $classes = [
        'pending' => 'bg-warning',
        'paid' => 'bg-success',
        'failed' => 'bg-danger',
        'refunded' => 'bg-info'
    ];
    return $classes[$status] ?? 'bg-secondary';
}

// Calculate order total
function calculate_order_total($items, $delivery_fee = 0) {
    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += $item['total_price'];
    }
    return $subtotal + $delivery_fee;
}

// Get available delivery dates (next 7 days)
function get_available_delivery_dates() {
    $dates = [];
    for ($i = 1; $i <= 7; $i++) {
        $dates[] = date('Y-m-d', strtotime("+$i days"));
    }
    return $dates;
}

// Check if delivery date is valid
function is_valid_delivery_date($date) {
    $available_dates = get_available_delivery_dates();
    return in_array($date, $available_dates);
}

// Get delivery time slots
function get_delivery_time_slots() {
    return [
        '09:00-11:00' => 'Morning (9:00 AM - 11:00 AM)',
        '11:00-13:00' => 'Late Morning (11:00 AM - 1:00 PM)',
        '13:00-15:00' => 'Afternoon (1:00 PM - 3:00 PM)',
        '15:00-17:00' => 'Late Afternoon (3:00 PM - 5:00 PM)',
        '17:00-19:00' => 'Evening (5:00 PM - 7:00 PM)',
        '19:00-21:00' => 'Night (7:00 PM - 9:00 PM)'
    ];
}

// Log activity
function log_activity($user_id, $action, $details = '') {
    // This could be expanded to log to a database table
    $log_entry = date('Y-m-d H:i:s') . " - User $user_id: $action - $details\n";
    error_log($log_entry, 3, 'logs/activity.log');
}

// Send email notification (placeholder)
function send_email_notification($to, $subject, $message) {
    // This is a placeholder - in a real application, you would use PHPMailer or similar
    return mail($to, $subject, $message);
}

// Get system setting
function get_system_setting($key, $default = null) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    } catch (PDOException $e) {
        return $default;
    }
}

// Update system setting
function update_system_setting($key, $value) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE system_settings SET setting_value = ? WHERE setting_key = ?");
        return $stmt->execute([$value, $key]);
    } catch (PDOException $e) {
        return false;
    }
}
?>
