<?php
// signup_handler.php - Handle creator registration form submission

session_start();
header('Content-Type: application/json');

// Include required files
require_once 'config.php';
require_once 'Database.php';
require_once 'CreatorCrud.php';

// CORS headers for AJAX requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Debug: Log incoming data
    error_log("Received POST data: " . print_r($_POST, true));
    
    // Create CreatorCrud instance
    $creatorCrud = new CreatorCrud();
    
    // Prepare data array from POST
    $data = [
        'full_name' => $_POST['full_name'] ?? '',
        'username' => $_POST['username'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'password' => $_POST['password'] ?? '',
        'confirm_password' => $_POST['confirm_password'] ?? '',
        'niche' => $_POST['niche'] ?? '',
        'location' => $_POST['location'] ?? '',
        'platforms' => $_POST['platforms'] ?? [],
        'instagram_url' => $_POST['instagram_url'] ?? '',
        'youtube_url' => $_POST['youtube_url'] ?? '',
        'tiktok_url' => $_POST['tiktok_url'] ?? '',
        'twitter_url' => $_POST['twitter_url'] ?? '',
        'facebook_url' => $_POST['facebook_url'] ?? '',
        'linkedin_url' => $_POST['linkedin_url'] ?? '',
        'snapchat_url' => $_POST['snapchat_url'] ?? '',
        'twitch_url' => $_POST['twitch_url'] ?? '',
        'total_followers' => $_POST['total_followers'] ?? 0,
        'engagement_rate' => $_POST['engagement_rate'] ?? 0.0,
        'audience_demographics' => $_POST['audience_demographics'] ?? '',
        'bio' => $_POST['bio'] ?? '',
        'collaboration_types' => $_POST['collaboration_types'] ?? '',
        'rate_per_post' => $_POST['rate_per_post'] ?? 0.0,
        'portfolio_sharing' => isset($_POST['portfolio_sharing']) ? true : false,
        'email_notifications' => isset($_POST['email_notifications']) ? true : false,
        'terms' => isset($_POST['terms']) ? true : false
    ];
    
    // Server-side validation
    $errors = [];
    
    // Required fields validation
    $requiredFields = ['full_name', 'username', 'email', 'password', 'niche'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field]) || trim($data[$field]) === '') {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
        }
    }
    
    // Email validation
    if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    
    // Password validation
    if (!empty($data['password'])) {
        if (strlen($data['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters long.';
        }
        
        if ($data['password'] !== $data['confirm_password']) {
            $errors[] = 'Passwords do not match.';
        }
    }
    
    // Terms validation
    if (!$data['terms']) {
        $errors[] = 'You must accept the terms and conditions.';
    }
    
    // Username validation (alphanumeric and underscores only)
    if (!empty($data['username']) && !preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
        $errors[] = 'Username can only contain letters, numbers, and underscores.';
    }
    
    // If there are validation errors, return them
    if (!empty($errors)) {
        echo json_encode([
            'success' => false, 
            'message' => implode(' ', $errors),
            'errors' => $errors
        ]);
        exit;
    }
    
    // Attempt to register the creator
    $result = $creatorCrud->registerCreator($data);
    
    // Log the result for debugging
    error_log("Registration result: " . print_r($result, true));
    
    echo json_encode($result);
    
} catch (Exception $e) {
    // Log the error
    error_log("Registration error: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'Registration failed: ' . $e->getMessage(),
        'debug' => APP_ENV === 'development' ? $e->getTraceAsString() : null
    ]);
}
?>