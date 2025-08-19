<?php
// brandSignup.php - Fixed version with proper includes and error handling

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session first
session_start();

// Include required files with proper error handling
$required_files = [
    'config.php',
    'Database.php', 
    'BrandCRUD.php'
];

foreach ($required_files as $file) {
    $file_path = __DIR__ . '/' . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
    } else {
        die("Required file not found: {$file}. Please ensure it exists in the same directory as this script.");
    }
}

// Test database connection and setup


// Initialize variables
$errors = [];
$success_message = '';
$form_data = [];

// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = 'Invalid security token. Please try again.';
    } else {
        // Collect and sanitize form data
        $form_data = [
            'company_name' => trim($_POST['company_name'] ?? ''),
            'contact_name' => trim($_POST['contact_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'website' => trim($_POST['website'] ?? ''),
            'industry' => trim($_POST['industry'] ?? ''),
            'company_size' => trim($_POST['company_size'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? ''
        ];
        
        // Validate required fields
        if (empty($form_data['company_name'])) {
            $errors[] = 'Company name is required.';
        }
        
        if (empty($form_data['contact_name'])) {
            $errors[] = 'Contact name is required.';
        }
        
        if (empty($form_data['email'])) {
            $errors[] = 'Email address is required.';
        } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
        
        if (empty($form_data['password'])) {
            $errors[] = 'Password is required.';
        } elseif (strlen($form_data['password']) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }
        
        if ($form_data['password'] !== $form_data['confirm_password']) {
            $errors[] = 'Passwords do not match.';
        }
        
        if (!isset($_POST['terms'])) {
            $errors[] = 'You must agree to the Terms of Service and Privacy Policy.';
        }
        
        // Validate website URL if provided
        if (!empty($form_data['website']) && !filter_var($form_data['website'], FILTER_VALIDATE_URL)) {
            $errors[] = 'Please enter a valid website URL.';
        }
        
        // If no errors, attempt to create the brand account
        if (empty($errors)) {
            try {
                $brandCRUD = new BrandCRUD();
                
                // Debug: Print form data before insertion
                error_log("Form data: " . print_r($form_data, true));
                
                $result = $brandCRUD->create($form_data);
                
                // Debug: Print result
                error_log("Creation result: " . print_r($result, true));
                
                if ($result['success']) {
                    $success_message = $result['message'];
                    // Clear form data on success
                    $form_data = [];
                    // Regenerate CSRF token
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                } else {
                    $errors[] = $result['message'];
                }
            } catch (Exception $e) {
                error_log("Brand registration error: " . $e->getMessage());
                $errors[] = 'An unexpected error occurred. Please try again later.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join as Brand - InfluConnect</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .signup-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            display: flex;
            min-height: 600px;
        }
        
        .signup-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .logo {
            margin-top: -30px;
        }
        
        .logo a {
            display: inline-block;
            text-decoration: none;
        }
        
        .logo img {
            height: 50px;
            width: auto;
            display: block;
        }
        
        .content h2 {
            font-size: 32px;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .content p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
        }
        
        .signup-right {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }
        
        .signup-form h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
        }
        
        .error-message {
            background: #fee;
            color: #c33;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fcc;
        }
        
        .success-message {
            background: #efe;
            color: #363;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #cfc;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            flex: 1;
        }
        
        .form-group.full-width {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="url"],
        input[type="password"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        textarea {
            height: 100px;
            resize: vertical;
        }
        
        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-top: 2px;
        }
        
        .checkbox-group label {
            margin-bottom: 0;
            font-size: 14px;
            line-height: 1.4;
        }
        
        .checkbox-group a {
            color: #667eea;
            text-decoration: none;
        }
        
        .checkbox-group a:hover {
            text-decoration: underline;
        }
        
        .signup-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .signup-btn:hover {
            transform: translateY(-2px);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .signup-container {
                flex-direction: column;
                margin: 10px;
            }
            
            .signup-left {
                padding: 30px;
                text-align: center;
            }
            
            .signup-right {
                padding: 30px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .logo img {
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-left">
            <div class="logo">
                <a href="index.php">
                    <img src="logo2.png" alt="Linkify Logo"  style="position: relative; top: -300px;  z-index: 10; ">
                </a>
            </div>
            <div class="content">
                <h2>Join Our Brand Network</h2>
                <p>Connect with top influencers and grow your brand's reach. Create authentic partnerships that drive results and build lasting relationships with content creators who align with your brand values.</p>
            </div>
        </div>
        
        <div class="signup-right">
            <div class="signup-form">
                <h2>Create Brand Account</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <?php foreach ($errors as $error): ?>
                            <div><?php echo htmlspecialchars($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success_message)): ?>
                    <div class="success-message">
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="company_name">Company Name *</label>
                            <input type="text" id="company_name" name="company_name" required maxlength="255" 
                                   value="<?php echo htmlspecialchars($form_data['company_name'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="contact_name">Contact Name *</label>
                            <input type="text" id="contact_name" name="contact_name" required maxlength="255"
                                   value="<?php echo htmlspecialchars($form_data['contact_name'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required maxlength="255"
                                   value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" maxlength="20"
                                   value="<?php echo htmlspecialchars($form_data['phone'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="website">Website URL</label>
                        <input type="url" id="website" name="website" placeholder="https://yourcompany.com" maxlength="255"
                               value="<?php echo htmlspecialchars($form_data['website'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="industry">Industry</label>
                            <select id="industry" name="industry">
                                <option value="">Select Industry</option>
                                <option value="technology" <?php echo ($form_data['industry'] ?? '') === 'technology' ? 'selected' : ''; ?>>Technology</option>
                                <option value="fashion" <?php echo ($form_data['industry'] ?? '') === 'fashion' ? 'selected' : ''; ?>>Fashion</option>
                                <option value="beauty" <?php echo ($form_data['industry'] ?? '') === 'beauty' ? 'selected' : ''; ?>>Beauty</option>
                                <option value="food" <?php echo ($form_data['industry'] ?? '') === 'food' ? 'selected' : ''; ?>>Food & Beverage</option>
                                <option value="travel" <?php echo ($form_data['industry'] ?? '') === 'travel' ? 'selected' : ''; ?>>Travel</option>
                                <option value="health" <?php echo ($form_data['industry'] ?? '') === 'health' ? 'selected' : ''; ?>>Health & Wellness</option>
                                <option value="automotive" <?php echo ($form_data['industry'] ?? '') === 'automotive' ? 'selected' : ''; ?>>Automotive</option>
                                <option value="finance" <?php echo ($form_data['industry'] ?? '') === 'finance' ? 'selected' : ''; ?>>Finance</option>
                                <option value="gaming" <?php echo ($form_data['industry'] ?? '') === 'gaming' ? 'selected' : ''; ?>>Gaming</option>
                                <option value="fitness" <?php echo ($form_data['industry'] ?? '') === 'fitness' ? 'selected' : ''; ?>>Fitness</option>
                                <option value="other" <?php echo ($form_data['industry'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="company_size">Company Size</label>
                            <select id="company_size" name="company_size">
                                <option value="">Select Size</option>
                                <option value="1-10" <?php echo ($form_data['company_size'] ?? '') === '1-10' ? 'selected' : ''; ?>>1-10 employees</option>
                                <option value="11-50" <?php echo ($form_data['company_size'] ?? '') === '11-50' ? 'selected' : ''; ?>>11-50 employees</option>
                                <option value="51-200" <?php echo ($form_data['company_size'] ?? '') === '51-200' ? 'selected' : ''; ?>>51-200 employees</option>
                                <option value="201-1000" <?php echo ($form_data['company_size'] ?? '') === '201-1000' ? 'selected' : ''; ?>>201-1000 employees</option>
                                <option value="1000+" <?php echo ($form_data['company_size'] ?? '') === '1000+' ? 'selected' : ''; ?>>1000+ employees</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="description">Company Description</label>
                        <textarea id="description" name="description" placeholder="Tell us about your company and marketing goals..."><?php echo htmlspecialchars($form_data['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password *</label>
                            <input type="password" id="password" name="password" required minlength="8">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password *</label>
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                        </div>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the <a href="terms.php">Terms of Service</a> and <a href="privacy.php">Privacy Policy</a></label>
                    </div>
                    
                    <button type="submit" class="signup-btn">Create Brand Account</button>
                </form>
                
                <div class="login-link">
                    Already have an account? <a href="login.php">Sign in here</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple form validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });

        // Real-time password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            if (password.length < 8) {
                this.style.borderColor = '#dc3545';
            } else {
                this.style.borderColor = '#28a745';
            }
        });
    </script>
</body>
</html>