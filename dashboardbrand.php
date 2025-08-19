<?php
// Add this at the very top of your dashboardbrand.php file (before any HTML)
session_start();

// Database connection class
class Database {
    private $host = 'localhost';
    private $port = '4200';
    private $dbname = 'Walid';
    private $username = 'postgres';
    private $password = 'HibA98821607';
    private $pdo;
    
    public function __construct() {
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->pdo;
    }
}

// Create database connection
$database = new Database();
$pdo = $database->getConnection();

// Create Messagerie table if it doesn't exist
$createTableSQL = "
CREATE TABLE IF NOT EXISTS messagerie (
    id SERIAL PRIMARY KEY,
    influencer_id INTEGER NOT NULL,
    influencer_name VARCHAR(255) NOT NULL,
    brand_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    budget VARCHAR(100) NOT NULL,
    campaign_type VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

try {
    $pdo->exec($createTableSQL);
} catch(PDOException $e) {
    error_log("Error creating table: " . $e->getMessage());
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Handle AJAX form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => ''];
    
    try {
        // Validate required fields
        $required_fields = ['influencer_id', 'influencer_name', 'brand_name', 'email', 'budget', 'campaign_type', 'message'];
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field '{$field}' is required");
            }
        }
        
        // Sanitize input data
        $influencer_id = filter_var($_POST['influencer_id'], FILTER_SANITIZE_NUMBER_INT);
        $influencer_name = sanitizeInput($_POST['influencer_name']);
        $brand_name = sanitizeInput($_POST['brand_name']);
        $email = sanitizeInput($_POST['email']);
        $budget = sanitizeInput($_POST['budget']);
        $campaign_type = sanitizeInput($_POST['campaign_type']);
        $message = sanitizeInput($_POST['message']);
        
        // Validate email
        if (!validateEmail($email)) {
            throw new Exception("Invalid email format");
        }
        
        // Validate influencer_id is numeric
        if (!is_numeric($influencer_id)) {
            throw new Exception("Invalid influencer ID");
        }
        
        // Prepare SQL statement
        $sql = "INSERT INTO messagerie (
            influencer_id, 
            influencer_name, 
            brand_name, 
            email, 
            budget, 
            campaign_type, 
            message, 
            status,
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', CURRENT_TIMESTAMP)";
        
        $stmt = $pdo->prepare($sql);
        
        // Execute the statement
        $result = $stmt->execute([
            $influencer_id,
            $influencer_name,
            $brand_name,
            $email,
            $budget,
            $campaign_type,
            $message
        ]);
        
        if ($result) {
            $response['success'] = true;
            $response['message'] = "Your message has been sent successfully to {$influencer_name}! They will get back to you soon.";
            $response['message_id'] = $pdo->lastInsertId();
        } else {
            throw new Exception("Failed to save message");
        }
        
    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = $e->getMessage();
        error_log("Messagerie Error: " . $e->getMessage());
    }
    
    echo json_encode($response);
    exit;
}

// Handle regular form submission (fallback)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    try {
        // Same validation and processing as above
        $required_fields = ['influencer_id', 'influencer_name', 'brand_name', 'email', 'budget', 'campaign_type', 'message'];
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field '{$field}' is required");
            }
        }
        
        $influencer_id = filter_var($_POST['influencer_id'], FILTER_SANITIZE_NUMBER_INT);
        $influencer_name = sanitizeInput($_POST['influencer_name']);
        $brand_name = sanitizeInput($_POST['brand_name']);
        $email = sanitizeInput($_POST['email']);
        $budget = sanitizeInput($_POST['budget']);
        $campaign_type = sanitizeInput($_POST['campaign_type']);
        $message = sanitizeInput($_POST['message']);
        
        if (!validateEmail($email)) {
            throw new Exception("Invalid email format");
        }
        
        if (!is_numeric($influencer_id)) {
            throw new Exception("Invalid influencer ID");
        }
        
        $sql = "INSERT INTO messagerie (
            influencer_id, 
            influencer_name, 
            brand_name, 
            email, 
            budget, 
            campaign_type, 
            message, 
            status,
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', CURRENT_TIMESTAMP)";
        
        $stmt = $pdo->prepare($sql);
        
        $result = $stmt->execute([
            $influencer_id,
            $influencer_name,
            $brand_name,
            $email,
            $budget,
            $campaign_type,
            $message
        ]);
        
        if ($result) {
            $response['success'] = true;
            $response['message'] = "Your message has been sent successfully to {$influencer_name}! They will get back to you soon.";
        } else {
            throw new Exception("Failed to save message");
        }
        
    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = $e->getMessage();
        error_log("Messagerie Error: " . $e->getMessage());
    }
    
    $_SESSION['form_response'] = $response;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Get response from session if available
$form_response = null;
if (isset($_SESSION['form_response'])) {
    $form_response = $_SESSION['form_response'];
    unset($_SESSION['form_response']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkify - Find Your Perfect Influencer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            line-height: 1.6;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

         .logo {
            top:10px;
            font-size: 1.8rem;
            font-weight: bold;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .logo a {
            display: inline-block;
            text-decoration: none;
        }
        
        .logo img {
            margin-top:-10px;
            height: 50px;
            width: auto;
            display: block;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #667eea;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .hero {
            text-align: center;
            padding: 4rem 0;
            color: white;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .search-section {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
        }

        .search-form {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }

        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-btn {
            padding: 0.75rem 2rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .search-btn:hover {
            transform: translateY(-2px);
        }

        .filters {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            background: #f8f9fa;
            border: 2px solid #e1e5e9;
            border-radius: 25px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .filter-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .filter-btn:hover {
            transform: translateY(-1px);
        }

        .featured-section {
            margin-bottom: 3rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .section-header h2 {
            color: white;
            font-size: 2rem;
        }

        .see-all {
            color: white;
            text-decoration: none;
            font-weight: 500;
            opacity: 0.9;
        }

        .influencer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        .influencer-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .influencer-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .card-image {
            height: 200px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .card-badges {
            position: absolute;
            top: 1rem;
            left: 1rem;
            display: flex;
            gap: 0.5rem;
        }

        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge.top-creator {
            background: #ffd700;
            color: #333;
        }

        .badge.responds-fast {
            background: #00d4aa;
            color: white;
        }

        .platform-badge {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }

        .card-content {
            padding: 1.5rem;
        }

        .influencer-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .influencer-category {
            color: #666;
            margin-bottom: 0.5rem;
        }

        .influencer-location {
            color: #999;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .price {
            font-size: 1.2rem;
            font-weight: 600;
            color: #667eea;
        }

        .message-btn {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .message-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            color: white;
            font-size: 1.1rem;
        }

        .hidden {
            display: none;
        }

        .message-section {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
        }

        .message-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-row {
            display: flex;
            gap: 1rem;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-group-full {
            width: 100%;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .submit-btn {
            padding: 1rem 2rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            align-self: flex-start;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

       
          .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #c3e6cb;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #f5c6cb;
        }
        .hidden {
            display: none;
        }

        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }
            
            .form-row {
                flex-direction: column;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .nav-links {
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
      <?php if ($form_response): ?>
        <div class="<?php echo $form_response['success'] ? 'success-message' : 'error-message'; ?>" style="display: block;">
            <?php echo htmlspecialchars($form_response['message']); ?>
        </div>
    <?php endif; ?>

    <header class="header">
        <nav class="nav">
          <div class="logo">
                <a href="index.php">
                    <img src="logo2.png" alt="Linkify Logo" style="position: relative; top: 15px; left: 5px;">
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="#search">Search</a></li>
                <li><a href="#featured">Featured</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#contact">Contact</a></li>
               <li> <a href="index.php" style="color: #667eea;">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <section class="hero">
            <h1>Find Your Perfect Influencer</h1>
            <p>Connect with top Instagram, TikTok, YouTube, and UGC creators to grow your brand</p>
        </section>

        <section class="search-section" id="search">
            <form class="search-form" id="searchForm">
                <div class="form-group">
                    <label for="platform">Platform</label>
                    <select id="platform" name="platform">
                        <option value="">Choose a platform</option>
                        <option value="instagram">Instagram</option>
                        <option value="tiktok">TikTok</option>
                        <option value="youtube">YouTube</option>
                        <option value="ugc">UGC</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option value="">Choose a category</option>
                        <option value="lifestyle">Lifestyle</option>
                        <option value="beauty">Beauty & Fashion</option>
                        <option value="tech">Tech & Gaming</option>
                        <option value="fitness">Fitness & Health</option>
                        <option value="food">Food & Travel</option>
                        <option value="business">Business & Finance</option>
                        <option value="education">Education</option>
                        <option value="entertainment">Entertainment</option>
                        <option value="sports">Sports</option>
                        <option value="music">Music</option>
                        <option value="art">Art & Design</option>
                        <option value="parenting">Parenting</option>
                    </select>
                </div>
                <button type="submit" class="search-btn">🔍 Search</button>
            </form>

            <div class="filters">
                <button class="filter-btn active" data-filter="all">⭐ All Results</button>
                <button class="filter-btn" data-filter="most-viewed">📈 Most Viewed</button>
                <button class="filter-btn" data-filter="trending">🔥 Trending</button>
                <button class="filter-btn" data-filter="under-250">💰 Under $250</button>
                <button class="filter-btn" data-filter="fast-turnover">⚡ Fast Turnover</button>
                <button class="filter-btn" data-filter="top-creator">👑 Top Creator</button>
            </div>
        </section>

        <section class="featured-section" id="featured">
            <div class="section-header">
                <h2 id="results-title">Featured Influencers</h2>
                <a href="#" class="see-all">See All →</a>
            </div>
            
            <div class="influencer-grid" id="influencerGrid">
                <!-- Default featured influencers will be shown here -->
            </div>
            
            <div class="no-results hidden" id="noResults">
                <p>No influencers found for your search criteria. Try different filters!</p>
            </div>
        </section>

        <section class="message-section" id="contact">
            <h2 style="margin-bottom: 2rem; color: #333;" id="messageTitle">Send a Message to Influencer</h2>
            
            <div class="success-message" id="successMessage">
                ✅ Your message has been sent successfully! We'll get back to you soon.
            </div>

            <form class="message-form" id="messageForm">
                <input type="hidden" id="selectedInfluencer" name="influencer_id" value="">
                <input type="hidden" id="selectedInfluencerName" name="influencer_name" value="">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="brand_name">Brand Name</label>
                        <input type="text" id="brand_name" name="brand_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="budget">Budget Range</label>
                        <select id="budget" name="budget" required>
                            <option value="">Select budget range</option>
                            <option value="under-250">Under $250</option>
                            <option value="250-500">$250 - $500</option>
                            <option value="500-1000">$500 - $1,000</option>
                            <option value="1000-2500">$1,000 - $2,500</option>
                            <option value="2500-plus">$2,500+</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="campaign_type">Campaign Type</label>
                        <select id="campaign_type" name="campaign_type" required>
                            <option value="">Select campaign type</option>
                            <option value="instagram-post">Instagram Post</option>
                            <option value="instagram-story">Instagram Story</option>
                            <option value="tiktok-video">TikTok Video</option>
                            <option value="youtube-video">YouTube Video</option>
                            <option value="ugc-content">UGC Content</option>
                            <option value="brand-ambassadorship">Brand Ambassadorship</option>
                        </select>
                    </div>
                </div>

                <div class="form-group form-group-full">
                    <label for="message">Message Details</label>
                    <textarea id="message" name="message" placeholder="Tell us about your brand, campaign goals, timeline, and any specific requirements..." required></textarea>
                </div>

                <button type="submit" class="submit-btn">Send Message 🚀</button>
            </form>
        </section>
    </div>



      <script>
        // Enhanced form submission with AJAX
      document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const influencerId = document.getElementById('selectedInfluencer').value;
    const influencerName = document.getElementById('selectedInfluencerName').value;
    
    if (!influencerId) {
        alert('Please select an influencer first by clicking "Send Message" on their card.');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="loading"></span> Sending...';
    submitBtn.disabled = true;
    
    // Send AJAX request to the SAME PAGE (dashboardbrand.php), not messagerie.php
    fetch(window.location.href, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            const successMsg = document.getElementById('successMessage');
            successMsg.style.display = 'block';
            successMsg.innerHTML = `✅ ${data.message}`;
            
            // Reset form
            this.reset();
            document.getElementById('selectedInfluencer').value = '';
            document.getElementById('selectedInfluencerName').value = '';
            document.getElementById('messageTitle').textContent = 'Send a Message to Influencer';
            
            // Hide success message after 5 seconds
            setTimeout(() => {
                successMsg.style.display = 'none';
            }, 5000);
        } else {
            // Show error message
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending the message. Please try again.');
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// 2. Fix the hover effects (around line 1075)
document.addEventListener('DOMContentLoaded', function() {
    // Display default influencers on page load
    displayInfluencers(defaultInfluencers);
    
    // Add hover effects to influencer cards - FIXED VERSION
    document.addEventListener('mouseenter', function(e) {
        // Check if the element has classList before accessing it
        if (e.target && e.target.classList && e.target.classList.contains('influencer-card')) {
            e.target.style.transform = 'translateY(-10px) rotateX(5deg)';
        }
    }, true);
    
    document.addEventListener('mouseleave', function(e) {
        // Check if the element has classList before accessing it
        if (e.target && e.target.classList && e.target.classList.contains('influencer-card')) {
            e.target.style.transform = 'translateY(0) rotateX(0)';
        }
    }, true);
});

    </script>



    <script>
        // Static influencers data
        const influencersData = {
            instagram: {
                lifestyle: [
                    {
                        id: 1,
                        name: "Sarah Johnson",
                        category: "Lifestyle Influencer",
                        location: "Los Angeles, CA, US",
                        rating: 4.9,
                        price: 150,
                        platform: "Instagram",
                        followers: "500k-1M",
                        badges: ["top-creator", "responds-fast"]
                    },
                    {
                        id: 2,
                        name: "Maya Rodriguez",
                        category: "Lifestyle & Wellness",
                        location: "Miami, FL, US",
                        rating: 4.8,
                        price: 200,
                        platform: "Instagram",
                        followers: "100k-500k",
                        badges: ["responds-fast"]
                    }
                ],
                beauty: [
                    {
                        id: 3,
                        name: "Emma Beauty",
                        category: "Beauty & Fashion",
                        location: "New York, NY, US",
                        rating: 4.9,
                        price: 300,
                        platform: "Instagram",
                        followers: "1M+",
                        badges: ["top-creator"]
                    },
                    {
                        id: 4,
                        name: "Zoe Makeup",
                        category: "Beauty Guru",
                        location: "Chicago, IL, US",
                        rating: 4.7,
                        price: 180,
                        platform: "Instagram",
                        followers: "500k-1M",
                        badges: ["responds-fast"]
                    }
                ],
                fitness: [
                    {
                        id: 5,
                        name: "Fit Mike",
                        category: "Fitness Coach",
                        location: "Austin, TX, US",
                        rating: 4.8,
                        price: 220,
                        platform: "Instagram",
                        followers: "500k-1M",
                        badges: ["top-creator"]
                    }
                ]
            },
            tiktok: {
                tech: [
                    {
                        id: 6,
                        name: "Tech Tony",
                        category: "Tech Reviews",
                        location: "Seattle, WA, US",
                        rating: 4.9,
                        price: 250,
                        platform: "TikTok",
                        followers: "1M+",
                        badges: ["top-creator", "responds-fast"]
                    }
                ],
                entertainment: [
                    {
                        id: 7,
                        name: "Funny Fiona",
                        category: "Comedy & Entertainment",
                        location: "Los Angeles, CA, US",
                        rating: 4.6,
                        price: 180,
                        platform: "TikTok",
                        followers: "500k-1M",
                        badges: ["responds-fast"]
                    }
                ]
            },
            youtube: {
                tech: [
                    {
                        id: 8,
                        name: "Alex Tech",
                        category: "Tech Reviewer",
                        location: "San Francisco, CA, US",
                        rating: 4.8,
                        price: 400,
                        platform: "YouTube",
                        followers: "1M+",
                        badges: ["top-creator"]
                    }
                ],
                education: [
                    {
                        id: 9,
                        name: "Professor Kate",
                        category: "Educational Content",
                        location: "Boston, MA, US",
                        rating: 4.7,
                        price: 300,
                        platform: "YouTube",
                        followers: "500k-1M",
                        badges: ["responds-fast"]
                    }
                ]
            },
            ugc: {
                food: [
                    {
                        id: 10,
                        name: "Food Lover Lisa",
                        category: "Food & Travel",
                        location: "Portland, OR, US",
                        rating: 4.6,
                        price: 160,
                        platform: "UGC",
                        followers: "UGC Creator",
                        badges: ["responds-fast"]
                    }
                ],
                lifestyle: [
                    {
                        id: 11,
                        name: "Everyday Emma",
                        category: "Lifestyle UGC",
                        location: "Nashville, TN, US",
                        rating: 4.8,
                        price: 140,
                        platform: "UGC",
                        followers: "UGC Creator",
                        badges: ["top-creator"]
                    }
                ]
            }
        };

        // Default featured influencers
        const defaultInfluencers = [
            influencersData.instagram.lifestyle[0],
            influencersData.tiktok.tech[0],
            influencersData.youtube.tech[0],
            influencersData.ugc.food[0]
        ];

        let currentInfluencers = defaultInfluencers;

        // Create influencer card HTML
   function createInfluencerCard(influencer) {
    const badgeHTML = influencer.badges.map(badge => {
        if (badge === 'top-creator') return '<span class="badge top-creator">👑 Top Creator</span>';
        if (badge === 'responds-fast') return '<span class="badge responds-fast">⚡ Responds Fast</span>';
        return '';
    }).join('');

    const platformIcon = {
        'Instagram': '📷',
        'TikTok': '🎵',
        'YouTube': '📺',
        'UGC': '📱'
    };
    
    // Assign specific images based on influencer name
    let imageUrl;
    switch(influencer.name) {
        case 'Sarah Johnson':
            imageUrl = 'a1.png';
            break;
        case 'Tech Tony':
            imageUrl = 'a2.png';
            break;
        case 'Alex Tech':
            imageUrl = 'a4.png';
            break;
        case 'Food Lover Lisa':
            imageUrl = 'a3.png';
            break;
        default:
            // Fallback for any other influencers
            const imageNumber = (influencer.id % 4) + 1;
            imageUrl = `a${imageNumber}.png`;
    }
    
    return `
        <div class="influencer-card" data-platform="${influencer.platform.toLowerCase()}" data-category="${influencer.category.toLowerCase()}">
            <div class="card-image" style="background-image: url('${imageUrl}'); background-size: cover; background-position: center;">
                <div class="card-badges">
                    ${badgeHTML}
                </div>
                <div class="platform-badge">${platformIcon[influencer.platform]} ${influencer.followers}</div>
            </div>
            <div class="card-content">
                <h3 class="influencer-name">${influencer.name} ⭐ ${influencer.rating}</h3>
                <p class="influencer-category">${influencer.category}</p>
                <p class="influencer-location">${influencer.location}</p>
                <div class="card-footer">
                    <div class="rating">
                        <span class="star">⭐⭐⭐⭐⭐</span>
                    </div>
                    <div class="price">$${influencer.price}</div>
                </div>
                <button class="message-btn" onclick="selectInfluencer(${influencer.id}, '${influencer.name}')">
                    Send Message
                </button>
            </div>
        </div>
    `;
        }

        // Display influencers
        function displayInfluencers(influencers) {
            const grid = document.getElementById('influencerGrid');
            const noResults = document.getElementById('noResults');
            
            if (influencers.length === 0) {
                grid.innerHTML = '';
                noResults.classList.remove('hidden');
            } else {
                grid.innerHTML = influencers.map(createInfluencerCard).join('');
                noResults.classList.add('hidden');
            }
        }

        // Search functionality
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const platform = document.getElementById('platform').value;
            const category = document.getElementById('category').value;
            
            let results = [];
            
            if (platform && category) {
                // Search by both platform and category
                if (influencersData[platform] && influencersData[platform][category]) {
                    results = influencersData[platform][category];
                }
            } else if (platform) {
                // Search by platform only
                if (influencersData[platform]) {
                    results = Object.values(influencersData[platform]).flat();
                }
            } else if (category) {
                // Search by category only
                Object.values(influencersData).forEach(platformData => {
                    if (platformData[category]) {
                        results = results.concat(platformData[category]);
                    }
                });
            } else {
                // No search criteria, show all
                results = Object.values(influencersData).flatMap(platform => 
                    Object.values(platform).flat()
                );
            }
            
            currentInfluencers = results;
            displayInfluencers(results);
            
            // Update title
            const title = document.getElementById('results-title');
            if (platform && category) {
                title.textContent = `${platform.charAt(0).toUpperCase() + platform.slice(1)} - ${category.charAt(0).toUpperCase() + category.slice(1)} Influencers`;
            } else if (platform) {
                title.textContent = `${platform.charAt(0).toUpperCase() + platform.slice(1)} Influencers`;
            } else if (category) {
                title.textContent = `${category.charAt(0).toUpperCase() + category.slice(1)} Influencers`;
            } else {
                title.textContent = 'All Influencers';
            }
        });

        // Select influencer for messaging - UPDATED FUNCTION
        function selectInfluencer(influencerId, influencerName) {
            document.getElementById('selectedInfluencer').value = influencerId;
            document.getElementById('selectedInfluencerName').value = influencerName;
            document.getElementById('messageTitle').textContent = `Send a Message to ${influencerName}`;
            
            // AUTO-FILL THE BRAND NAME WITH THE INFLUENCER'S NAME
            document.getElementById('brand_name').value = influencerName;
            
            // Scroll to message section
            document.getElementById('contact').scrollIntoView({ behavior: 'smooth' });
        }

        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                let filteredInfluencers = currentInfluencers;
                
                switch(filter) {
                    case 'under-250':
                        filteredInfluencers = currentInfluencers.filter(inf => inf.price < 250);
                        break;
                    case 'top-creator':
                        filteredInfluencers = currentInfluencers.filter(inf => inf.badges.includes('top-creator'));
                        break;
                    case 'fast-turnover':
                        filteredInfluencers = currentInfluencers.filter(inf => inf.badges.includes('responds-fast'));
                        break;
                    case 'most-viewed':
                        filteredInfluencers = currentInfluencers.sort((a, b) => b.rating - a.rating);
                        break;
                    case 'trending':
                        filteredInfluencers = currentInfluencers.sort((a, b) => a.price - b.price);
                        break;
                    default:
                        filteredInfluencers = currentInfluencers;
                }
                
                displayInfluencers(filteredInfluencers);
            });
        });

        // Form submission
        document.getElementById('messageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const influencerId = document.getElementById('selectedInfluencer').value;
            const influencerName = document.getElementById('selectedInfluencerName').value;
            
            if (!influencerId) {
                alert('Please select an influencer first by clicking "Send Message" on their card.');
                return;
            }
            
            // Simulate form submission
            setTimeout(() => {
                document.getElementById('successMessage').style.display = 'block';
                document.getElementById('successMessage').innerHTML = `
                    ✅ Your message has been sent successfully to ${influencerName}! They'll get back to you soon.
                `;
                this.reset();
                
                // Reset selected influencer
                document.getElementById('selectedInfluencer').value = '';
                document.getElementById('selectedInfluencerName').value = '';
                document.getElementById('messageTitle').textContent = 'Send a Message to Influencer';
                
                // Hide success message after 5 seconds
                setTimeout(() => {
                    document.getElementById('successMessage').style.display = 'none';
                }, 5000);
            }, 1000);
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add some interactive animations
        document.addEventListener('DOMContentLoaded', function() {
            // Display default influencers on page load
            displayInfluencers(defaultInfluencers);
            
            // Add hover effects to influencer cards
            document.addEventListener('mouseenter', function(e) {
                if (e.target.classList.contains('influencer-card')) {
                    e.target.style.transform = 'translateY(-10px) rotateX(5deg)';
                }
            }, true);
            
            document.addEventListener('mouseleave', function(e) {
                if (e.target.classList.contains('influencer-card')) {
                    e.target.style.transform = 'translateY(0) rotateX(0)';
                }
            }, true);
        });

        // Make selectInfluencer function globally available
        window.selectInfluencer = selectInfluencer;
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Database connection (you'll need to set up your database)
        $servername = "localhost";
        $username = "your_username";
        $password = "your_password";
        $dbname = "influencer_platform";

        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare and execute the insert statement
            $stmt = $pdo->prepare("INSERT INTO messages (influencer_id, influencer_name, brand_name, email, budget, campaign_type, message, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $_POST['influencer_id'],
                $_POST['influencer_name'],
                $_POST['brand_name'],
                $_POST['email'],
                $_POST['budget'],
                $_POST['campaign_type'],
                $_POST['message']
            ]);

            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('successMessage').style.display = 'block';
                    document.getElementById('successMessage').innerHTML = '✅ Your message has been sent successfully to " . $_POST['influencer_name'] . "! They will get back to you soon.';
                    setTimeout(() => {
                        document.getElementById('successMessage').style.display = 'none';
                    }, 5000);
                });
            </script>";

        } catch(PDOException $e) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    alert('Error: " . $e->getMessage() . "');
                });
            </script>";
        }
    }
    ?>
</body>
</html>

<?php
// admin_messages.php - Simple admin panel to view messages
if (isset($_GET['admin']) && $_GET['admin'] === 'true') {
    echo "<h2>Messages Admin Panel</h2>";
    echo "<style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .status-pending { background-color: #fff3cd; }
        .status-read { background-color: #d4edda; }
        .status-replied { background-color: #cce5ff; }
    </style>";
    
    $messages = getAllMessages($pdo);
    
    echo "<table>";
    echo "<tr><th>ID</th><th>Influencer</th><th>Brand</th><th>Email</th><th>Budget</th><th>Campaign</th><th>Status</th><th>Date</th><th>Action</th></tr>";
    
    foreach ($messages as $msg) {
        echo "<tr class='status-{$msg['status']}'>";
        echo "<td>{$msg['id']}</td>";
        echo "<td>{$msg['influencer_name']}</td>";
        echo "<td>{$msg['brand_name']}</td>";
        echo "<td>{$msg['email']}</td>";
        echo "<td>{$msg['budget']}</td>";
        echo "<td>{$msg['campaign_type']}</td>";
        echo "<td>{$msg['status']}</td>";
        echo "<td>{$msg['created_at']}</td>";
        echo "<td><a href='?view={$msg['id']}'>View</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // View individual message
    if (isset($_GET['view'])) {
        $message = getMessageById($pdo, $_GET['view']);
        if ($message) {
            echo "<h3>Message Details</h3>";
            echo "<div style='background: #f9f9f9; padding: 20px; margin: 20px 0; border-radius: 5px;'>";
            echo "<p><strong>From:</strong> {$message['brand_name']} ({$message['email']})</p>";
            echo "<p><strong>To:</strong> {$message['influencer_name']}</p>";
            echo "<p><strong>Budget:</strong> {$message['budget']}</p>";
            echo "<p><strong>Campaign Type:</strong> {$message['campaign_type']}</p>";
            echo "<p><strong>Status:</strong> {$message['status']}</p>";
            echo "<p><strong>Date:</strong> {$message['created_at']}</p>";
            echo "<p><strong>Message:</strong></p>";
            echo "<div style='background: white; padding: 15px; border-radius: 3px;'>";
            echo nl2br(htmlspecialchars($message['message']));
            echo "</div>";
            echo "</div>";
        }
    }
}
?>