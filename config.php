<?php
// config.php - PostgreSQL configuration for pgAdmin

// Database configuration - Updated to match your actual setup
define('DB_HOST', 'localhost');
define('DB_PORT', '4200');           // Your custom PostgreSQL port
define('DB_NAME', 'Walid');
define('DB_USER', 'postgres');       // PostgreSQL superuser
define('DB_PASS', 'HibA98821607');   // Your actual password

// Application configuration
define('APP_NAME', 'InfluConnect');
define('APP_URL', 'http://localhost/influencer-marketing');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development');

// Security settings
define('CSRF_TOKEN_EXPIRE', 3600);
define('SESSION_TIMEOUT', 7200);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900);

// Email configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your_email@gmail.com');
define('SMTP_PASSWORD', 'your_app_password');
define('SMTP_FROM_EMAIL', 'noreply@influconnect.com');
define('SMTP_FROM_NAME', 'InfluConnect');

// Error reporting for development
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('UTC');
?>