<?php
// utils.php - Utility functions for the application

/**
 * Hash password using secure method
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password against hash
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Generate verification token
 */
function generateVerificationToken() {
    return bin2hex(random_bytes(32));
}

/**
 * Generate secure random string
 */
function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Validate phone number
 */
function isValidPhone($phone) {
    if (empty($phone)) {
        return true; // Phone is optional
    }
    
    // Remove all non-digit characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Check if phone number is between 10-15 digits
    return strlen($phone) >= 10 && strlen($phone) <= 15;
}

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate CSRF token
 */
function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Send verification email
 */
function sendVerificationEmail($email, $token, $name) {
    try {
        // Email configuration
        $to = $email;
        $subject = "Verify your InfluConnect Brand Account";
        $verification_link = "https://" . $_SERVER['HTTP_HOST'] . "/verify.php?token=" . $token;
        
        // Email body
        $message = "
        <html>
        <head>
            <title>Verify Your Account</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .button { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; }
                .footer { margin-top: 30px; font-size: 12px; color: #666; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Welcome to InfluConnect!</h1>
                </div>
                <div class='content'>
                    <h2>Hello " . htmlspecialchars($name) . ",</h2>
                    <p>Thank you for registering your brand with InfluConnect. To complete your account setup, please verify your email address by clicking the button below:</p>
                    
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='" . $verification_link . "' class='button'>Verify Email Address</a>
                    </p>
                    
                    <p>If the button doesn't work, you can also copy and paste this link into your browser:</p>
                    <p style='word-break: break-all; background: #fff; padding: 15px; border-radius: 5px; font-family: monospace;'>" . $verification_link . "</p>
                    
                    <p>This verification link will expire in 24 hours for security reasons.</p>
                    
                    <p>If you didn't create this account, please ignore this email.</p>
                    
                    <p>Best regards,<br>The InfluConnect Team</p>
                </div>
                <div class='footer'>
                    <p>&copy; 2024 InfluConnect. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Email headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: InfluConnect <noreply@influconnect.com>" . "\r\n";
        $headers .= "Reply-To: support@influconnect.com" . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Send email
        if (mail($to, $subject, $message, $headers)) {
            return [
                'success' => true,
                'message' => 'Verification email sent successfully'
            ];
        } else {
            throw new Exception('Failed to send email');
        }
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

/**
 * Log activity
 */
function logActivity($action, $user_id, $description) {
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "INSERT INTO activity_logs (action, user_id, description, ip_address, user_agent, created_at) 
                  VALUES (:action, :user_id, :description, :ip_address, :user_agent, NOW())";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':ip_address', $_SERVER['REMOTE_ADDR']);
        $stmt->bindParam(':user_agent', $_SERVER['HTTP_USER_AGENT']);
        
        return $stmt->execute();
        
    } catch (Exception $e) {
        error_log("Failed to log activity: " . $e->getMessage());
        return false;
    }
}

/**
 * Format phone number for display
 */
function formatPhoneNumber($phone) {
    if (empty($phone)) {
        return '';
    }
    
    // Remove all non-digit characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Format based on length
    if (strlen($phone) == 10) {
        return '(' . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' . substr($phone, 6);
    } elseif (strlen($phone) == 11) {
        return '+' . substr($phone, 0, 1) . ' (' . substr($phone, 1, 3) . ') ' . substr($phone, 4, 3) . '-' . substr($phone, 7);
    }
    
    return $phone;
}

/**
 * Generate slug from text
 */
function generateSlug($text) {
    // Replace non-alphanumeric characters with hyphens
    $slug = preg_replace('/[^a-zA-Z0-9]/', '-', $text);
    
    // Remove multiple consecutive hyphens
    $slug = preg_replace('/-+/', '-', $slug);
    
    // Remove leading and trailing hyphens
    $slug = trim($slug, '-');
    
    return strtolower($slug);
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user data
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT id, company_name, contact_name, email, is_verified 
                  FROM brands WHERE id = :id";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();
        
        return $stmt->fetch();
        
    } catch (Exception $e) {
        error_log("Failed to get current user: " . $e->getMessage());
        return null;
    }
}

/**
 * Redirect user
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Check if request is POST
 */
function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Check if request is GET
 */
function isGet() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Get file upload error message
 */
function getUploadErrorMessage($error_code) {
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE:
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        case UPLOAD_ERR_FORM_SIZE:
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
        case UPLOAD_ERR_PARTIAL:
            return 'The uploaded file was only partially uploaded';
        case UPLOAD_ERR_NO_FILE:
            return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing a temporary folder';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION:
            return 'A PHP extension stopped the file upload';
        default:
            return 'Unknown upload error';
    }
}

/**
 * Validate image file
 */
function validateImageFile($file) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowed_types)) {
        return 'Please upload a valid image file (JPEG, PNG, GIF, or WebP)';
    }
    
    if ($file['size'] > $max_size) {
        return 'File size must be less than 5MB';
    }
    
    return true;
}

/**
 * Generate pagination links
 */
function generatePagination($current_page, $total_pages, $base_url) {
    $pagination = [];
    
    // Previous page
    if ($current_page > 1) {
        $pagination[] = [
            'url' => $base_url . '?page=' . ($current_page - 1),
            'text' => 'Previous',
            'active' => false
        ];
    }
    
    // Page numbers
    $start = max(1, $current_page - 2);
    $end = min($total_pages, $current_page + 2);
    
    for ($i = $start; $i <= $end; $i++) {
        $pagination[] = [
            'url' => $base_url . '?page=' . $i,
            'text' => $i,
            'active' => $i == $current_page
        ];
    }
    
    // Next page
    if ($current_page < $total_pages) {
        $pagination[] = [
            'url' => $base_url . '?page=' . ($current_page + 1),
            'text' => 'Next',
            'active' => false
        ];
    }
    
    return $pagination;
}

/**
 * Time ago function
 */
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) {
        return 'just now';
    } elseif ($time < 3600) {
        return floor($time / 60) . ' minutes ago';
    } elseif ($time < 86400) {
        return floor($time / 3600) . ' hours ago';
    } elseif ($time < 2592000) {
        return floor($time / 86400) . ' days ago';
    } elseif ($time < 31536000) {
        return floor($time / 2592000) . ' months ago';
    } else {
        return floor($time / 31536000) . ' years ago';
    }
}

/**
 * Validate email address
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate URL
 */
function isValidURL($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Get user's IP address
 */
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Format currency
 */
function formatCurrency($amount, $currency = 'USD') {
    $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
    return $formatter->formatCurrency($amount, $currency);
}

/**
 * Format file size
 */
function formatFileSize($size) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $factor = floor((strlen($size) - 1) / 3);
    return sprintf("%.2f", $size / pow(1024, $factor)) . ' ' . $units[$factor];
}

/**
 * Clean file name
 */
function cleanFileName($filename) {
    // Remove special characters and spaces
    $filename = preg_replace('/[^a-zA-Z0-9\._-]/', '', $filename);
    
    // Remove multiple dots
    $filename = preg_replace('/\.+/', '.', $filename);
    
    return $filename;
}

/**
 * Generate unique filename
 */
function generateUniqueFileName($original_filename, $directory = '') {
    $info = pathinfo($original_filename);
    $name = $info['filename'];
    $extension = isset($info['extension']) ? '.' . $info['extension'] : '';
    
    $counter = 1;
    $filename = $name . $extension;
    
    while (file_exists($directory . $filename)) {
        $filename = $name . '_' . $counter . $extension;
        $counter++;
    }
    
    return $filename;
}

/**
 * Send notification email
 */
function sendNotificationEmail($to, $subject, $message, $from_name = 'InfluConnect') {
    try {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: $from_name <noreply@influconnect.com>" . "\r\n";
        $headers .= "Reply-To: support@influconnect.com" . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        return mail($to, $subject, $message, $headers);
        
    } catch (Exception $e) {
        error_log("Failed to send notification email: " . $e->getMessage());
        return false;
    }
}

/**
 * Rate limiting check
 */
function checkRateLimit($key, $limit = 5, $window = 300) {
    // Simple rate limiting using session
    if (!isset($_SESSION['rate_limit'])) {
        $_SESSION['rate_limit'] = [];
    }
    
    $current_time = time();
    $rate_key = $key . '_' . getUserIP();
    
    if (!isset($_SESSION['rate_limit'][$rate_key])) {
        $_SESSION['rate_limit'][$rate_key] = [];
    }
    
    // Clean old entries
    $_SESSION['rate_limit'][$rate_key] = array_filter(
        $_SESSION['rate_limit'][$rate_key], 
        function($timestamp) use ($current_time, $window) {
            return ($current_time - $timestamp) < $window;
        }
    );
    
    // Check if limit exceeded
    if (count($_SESSION['rate_limit'][$rate_key]) >= $limit) {
        return false;
    }
    
    // Add current request
    $_SESSION['rate_limit'][$rate_key][] = $current_time;
    
    return true;
}

/**
 * Validate password strength
 */
function validatePasswordStrength($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter';
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter';
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number';
    }
    
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = 'Password must contain at least one special character';
    }
    
    return empty($errors) ? true : $errors;
}

/**
 * Escape HTML output
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate random color
 */
function generateRandomColor() {
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}

/**
 * Truncate text
 */
function truncateText($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $suffix;
}

/**
 * Get gravatar URL
 */
function getGravatarURL($email, $size = 80) {
    $hash = md5(strtolower(trim($email)));
    return "https://www.gravatar.com/avatar/$hash?s=$size&d=identicon";
}

/**
 * Check if string starts with
 */
function startsWith($string, $prefix) {
    return substr($string, 0, strlen($prefix)) === $prefix;
}

/**
 * Check if string ends with
 */
function endsWith($string, $suffix) {
    return substr($string, -strlen($suffix)) === $suffix;
}

/**
 * Convert array to XML
 */
function arrayToXML($array, $root_element = 'root', $xml = null) {
    if ($xml === null) {
        $xml = new SimpleXMLElement("<$root_element/>");
    }
    
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayToXML($value, $key, $xml->addChild($key));
        } else {
            $xml->addChild($key, htmlspecialchars($value));
        }
    }
    
    return $xml->asXML();
}

/**
 * Debug function
 */
function debug($data, $die = false) {
    echo '<pre>';
    if (is_array($data) || is_object($data)) {
        print_r($data);
    } else {
        var_dump($data);
    }
    echo '</pre>';
    
    if ($die) {
        die();
    }
}
?>