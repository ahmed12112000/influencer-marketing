<?php
session_start();

// Include necessary files
require_once 'config.php';
require_once 'Database.php';
require_once 'User.php';

$error = '';
$success = '';

// Initialize User class
try {
    $user = new User();
    $user->createTables(); // Create tables if they don't exist
} catch (Exception $e) {
    $error = 'Database connection failed: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $remember = isset($_POST['remember']);
    
    // Validation
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields.';
    } elseif (empty($role)) {
        $error = 'Please select your role.';
    } elseif (!in_array($role, ['brand', 'influencer'])) {
        $error = 'Invalid role selected.';
    } else {
        try {
            // Authenticate user
            $authResult = $user->authenticate($username, $password);
            
            if ($authResult['success']) {
                // Set session variables
                $_SESSION['user_id'] = $authResult['user']['id'];
                $_SESSION['user_type'] = $authResult['user_type'];
                $_SESSION['username'] = $authResult['user']['username'];
                $_SESSION['email'] = $authResult['user']['email'];
                $_SESSION['role'] = $role; // Store selected role
                
                // Handle remember me
                if ($remember) {
                    $rememberToken = $user->generateRememberToken();
                    $user->setRememberToken($authResult['user']['id'], $authResult['user_type'], $rememberToken);
                    
                    // Set remember me cookie for 30 days with role information
                    setcookie('remember_token', $rememberToken, time() + (30 * 24 * 60 * 60), '/');
                    setcookie('remember_role', $role, time() + (30 * 24 * 60 * 60), '/');
                }
                
                // Redirect based on selected role
                if ($role === 'brand') {
                    header('Location: dashboardbrand.php');
                } else {
                    header('Location: influencerdashboard.php');
                }
                exit();
            } else {
                $error = $authResult['message'];
            }
        } catch (Exception $e) {
            $error = 'Authentication failed: ' . $e->getMessage();
        }
    }
}

// Check for remembered user
$remembered_user = '';
$remembered_role = '';
if (isset($_COOKIE['remember_token']) && !isset($_SESSION['user_id'])) {
    try {
        $rememberedUser = $user->getUserByRememberToken($_COOKIE['remember_token']);
        if ($rememberedUser) {
            $_SESSION['user_id'] = $rememberedUser['id'];
            $_SESSION['user_type'] = $rememberedUser['user_type'];
            $_SESSION['username'] = $rememberedUser['username'];
            $_SESSION['email'] = $rememberedUser['email'];
            
            // Check if role was remembered
            if (isset($_COOKIE['remember_role'])) {
                $remembered_role = $_COOKIE['remember_role'];
                $_SESSION['role'] = $remembered_role;
                
                // Redirect based on remembered role
                if ($remembered_role === 'brand') {
                    header('Location: dashboardbrand.php');
                } else {
                    header('Location: influencerdashboard.php');
                }
                exit();
            } else {
                // Role not remembered, redirect to default dashboard
                header('Location: dashboardbrand.php');
                exit();
            }
        }
    } catch (Exception $e) {
        // Invalid remember token, clear it
        setcookie('remember_token', '', time() - 3600, '/');
        setcookie('remember_role', '', time() - 3600, '/');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkify - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 1200px;
            max-width: 90%;
            min-height: 600px;
            display: flex;
        }

        .login-left {
            flex: 1;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('a.jpg') center/cover;
            opacity: 0.5;
        }

        .logo {
            position: absolute;
            top: -3px;
            left: 30px;
            z-index: 10;
            width: 300px;
           
        }

        .logo a {
            display: block;
            transition: all 0.3s ease;
        }

        .logo a:hover {
            transform: scale(1.05);
        }

        .logo img {
            height: 80px;
            width: auto;
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.5));
            transition: all 0.3s ease;
        }

        .login-left img {
            max-width: 80%;
            max-height: 80%;
            object-fit: cover;
            border-radius: 15px;
            z-index: 1;
            position: relative;
        }

        .login-right {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 2.2em;
            font-weight: 300;
            text-align: center;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e1e1;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .role-selection {
            margin-bottom: 25px;
        }

        .role-selection > label {
            display: block;
            margin-bottom: 15px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-options {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .role-option {
            flex: 1;
            position: relative;
        }

        .role-option input[type="radio"] {
            display: none;
        }

        .role-option label {
            display: block;
            padding: 15px 20px;
            border: 2px solid #e1e1e1;
            border-radius: 10px;
            background: #fafafa;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 16px;
            font-weight: 500;
            text-transform: none;
            letter-spacing: normal;
            margin-bottom: 0;
        }

        .role-option input[type="radio"]:checked + label {
            border-color: #667eea;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .role-option label:hover {
            border-color: #667eea;
            background: white;
        }

        .role-option input[type="radio"]:checked + label:hover {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 12px;
            accent-color: #667eea;
        }

        .remember-me label {
            color: #666;
            font-size: 14px;
            cursor: pointer;
            user-select: none;
        }

        .login-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            width: 100%;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #fcc;
        }

        .success-message {
            background: #efe;
            color: #363;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #cfc;
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #764ba2;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                width: 95%;
                max-width: 400px;
            }
            
            .login-left {
                min-height: 200px;
            }
            
            .login-right {
                padding: 40px 30px;
            }

            .role-options {
                flex-direction: column;
                gap: 10px;
            }

            .logo img {
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="logo">
                <a href="index.php">
                    <img src="logo3.png" alt="Linkify Logo">
                </a>
            </div>
            <img src="a4.jpg" alt="Login Image" style="opacity: 0.7;">
        </div>
        
        <div class="login-right">
            <div class="login-form">
                <h2>Welcome Back</h2>
                
                <?php if ($error): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Username or Email</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($remembered_user); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="role-selection">
                        <label>Select Your Role</label>
                        <div class="role-options">
                            <div class="role-option">
                                <input type="radio" id="brand" name="role" value="brand" 
                                       <?php echo ($remembered_role === 'brand') ? 'checked' : ''; ?> required>
                                <label for="brand">Brand</label>
                            </div>
                            <div class="role-option">
                                <input type="radio" id="influencer" name="role" value="influencer" 
                                       <?php echo ($remembered_role === 'influencer') ? 'checked' : ''; ?> required>
                                <label for="influencer">Influencer</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    
                    <button type="submit" class="login-btn">Login</button>
                </form>
                
                <div class="forgot-password">
                    <a href="forgot-password.php">Forgot Password?</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>