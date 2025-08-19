<?php
//  Linkify - PHP Landing Page
// Make sure to place a1.jpg, a2.jpg, a3.jpg in the same directory as this PHP file

// Configuration
$site_title = " Linkify - Connect Brands with Influencers";
$carousel_images = [
    [
        'src' => 'a9.jpg',
        'alt' => 'Brand and Influencer Partnership',
        'icon' => 'fa-handshake'
    ],
    [
        'src' => 'a8.jpg', 
        'alt' => 'Analytics and Growth',
        'icon' => 'fa-chart-line'
    ],
    [
        'src' => 'a6.jpg',
        'alt' => 'Community Network',
        'icon' => 'fa-users'
    ]
];

// Stats data
$stats = [
    ['number' => '50K+', 'label' => 'Verified Influencers'],
    ['number' => '2.5K+', 'label' => 'Happy Brands'],
    ['number' => '1M+', 'label' => 'Successful Campaigns'],
    ['number' => '98%', 'label' => 'Success Rate']
];

// Features data
$features = [
    [
        'icon' => 'fa-search',
        'title' => 'Smart Matching',
        'description' => 'Our AI-powered algorithm matches brands with influencers based on audience demographics, engagement rates, and brand alignment.'
    ],
    [
        'icon' => 'fa-chart-line',
        'title' => 'Analytics Dashboard',
        'description' => 'Track campaign performance with detailed analytics including reach, engagement, conversions, and ROI metrics.'
    ],
    [
        'icon' => 'fa-shield-alt',
        'title' => 'Secure Payments',
        'description' => 'Protected payment system with escrow service ensuring both parties are satisfied before funds are released.'
    ],
    [
        'icon' => 'fa-users',
        'title' => 'Quality Network',
        'description' => 'Curated network of verified influencers across all major social media platforms with authentic engagement.'
    ],
    [
        'icon' => 'fa-handshake',
        'title' => 'Contract Management',
        'description' => 'Streamlined contract creation and management with built-in templates and legal protection.'
    ],
    [
        'icon' => 'fa-headset',
        'title' => '24/7 Support',
        'description' => 'Dedicated support team available around the clock to help with campaigns, disputes, and technical issues.'
    ]
];

// How it works steps
$steps = [
    [
        'number' => '1',
        'title' => 'Create Your Profile',
        'description' => 'Sign up and create a detailed profile showcasing your brand or influence reach and audience demographics.'
    ],
    [
        'number' => '2',
        'title' => 'Browse & Connect',
        'description' => 'Browse through our curated network and connect with potential partners that align with your goals.'
    ],
    [
        'number' => '3',
        'title' => 'Collaborate',
        'description' => 'Work together on authentic content creation with built-in project management and communication tools.'
    ],
    [
        'number' => '4',
        'title' => 'Track Results',
        'description' => 'Monitor campaign performance with real-time analytics and optimize for better results.'
    ]
];

// Function to render carousel slides
function renderCarouselSlides($images) {
    $html = '';
    foreach ($images as $index => $image) {
        $html .= '<div class="carousel-slide">';
        $html .= '<img src="' . htmlspecialchars($image['src']) . '" alt="' . htmlspecialchars($image['alt']) . '">';
        $html .= '</div>';
    }
    return $html;
}

// Function to render carousel dots
function renderCarouselDots($images) {
    $html = '';
    foreach ($images as $index => $image) {
        $active = $index === 0 ? 'active' : '';
        $html .= '<span class="dot ' . $active . '" onclick="currentSlide(' . ($index + 1) . ')"></span>';
    }
    return $html;
}

// Function to render features
function renderFeatures($features) {
    $html = '';
    foreach ($features as $feature) {
        $html .= '<div class="feature-card">';
        $html .= '<div class="feature-icon">';
        $html .= '<i class="fas ' . htmlspecialchars($feature['icon']) . '"></i>';
        $html .= '</div>';
        $html .= '<h3>' . htmlspecialchars($feature['title']) . '</h3>';
        $html .= '<p>' . htmlspecialchars($feature['description']) . '</p>';
        $html .= '</div>';
    }
    return $html;
}

// Function to render stats
function renderStats($stats) {
    $html = '';
    foreach ($stats as $stat) {
        $html .= '<div class="stat-item">';
        $html .= '<h3>' . htmlspecialchars($stat['number']) . '</h3>';
        $html .= '<p>' . htmlspecialchars($stat['label']) . '</p>';
        $html .= '</div>';
    }
    return $html;
}

// Function to render steps
function renderSteps($steps) {
    $html = '';
    foreach ($steps as $step) {
        $html .= '<div class="step">';
        $html .= '<div class="step-number">' . htmlspecialchars($step['number']) . '</div>';
        $html .= '<h3>' . htmlspecialchars($step['title']) . '</h3>';
        $html .= '<p>' . htmlspecialchars($step['description']) . '</p>';
        $html .= '</div>';
    }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_title); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Header & Navigation */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #667eea;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

     .btn {
    padding: 0.9rem 1.3rem;
    font-size: 0.8rem;
        border-radius: 25px;
    cursor: pointer;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    text-align: center;
    

   /* optional: center vertically */
}

        .btn-outline {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
 position: absolute;   /* or 'absolute' if inside a positioned container */
    right: 300px;        /* distance from the right side of the screen */
    top: 20%;           /* optional: center vertically */
        }

        .btn-outline:hover {
            background: #667eea;
            color: white;
            transform: translateY(-3px);
        }

        .btn-primary {
            

            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: 2px solid transparent;
            position: absolute;  
    right: 170px;       
     top: 20%;         
        }

           .btn-primary1 {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: 2px solid transparent;
            position: absolute;  
    right: 30px;       
     top: 20%;         
        }


        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

         .btn-primary1:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        /* Hero Section */
        .hero {
            padding: 150px 0 100px;
            color: white;
            position: relative;
            overflow: hidden;
            min-height: 80vh;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-content {
            animation: fadeInUp 1s ease;
        }

        .hero-content h1 {
            font-size: 2.8rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-hero {
            padding: 1rem 2rem;
            font-size: 1rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-white {
            background: white;
            color: #667eea;
        }

        .btn-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255,255,255,0.3);
        }

        .btn-transparent {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            backdrop-filter: blur(10px);
        }

        .btn-transparent:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-3px);
        }

        /* Carousel Styles */
        .hero-carousel {
            position: relative;
            animation: fadeInUp 1s ease 0.3s both;
        }

        .carousel-container {
            position: relative;
            width: 100%;
            height: 400px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .carousel-slides {
            display: flex;
            width: <?php echo count($carousel_images) * 100; ?>%;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-slide {
            width: <?php echo 100 / count($carousel_images); ?>%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .carousel-slide:hover img {
            transform: scale(1.05);
        }

        /* Image overlay for better text visibility if needed */
        .carousel-slide::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .carousel-slide:hover::after {
            opacity: 1;
        }

        .carousel-dots {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .dot.active {
            background: white;
            box-shadow: 0 0 10px rgba(255,255,255,0.5);
        }

        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            font-size: 1.5rem;
            padding: 10px 15px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .carousel-nav:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-prev {
            left: 15px;
        }

        .carousel-next {
            right: 15px;
        }

        /* Features Section */
        .features {
            padding: 100px 0;
            background: white;
            position: relative;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-header p {
            font-size: 1.2rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats {
            padding: 80px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat-item h3 {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .stat-item p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* How It Works */
        .how-it-works {
            padding: 100px 0;
            background: #f8f9fa;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-top: 4rem;
        }

        .step {
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 1rem;
        }

        .step h3 {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .step p {
            color: #666;
        }

        /* CTA Section */
        .cta {
            padding: 100px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
        }

        .cta h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .cta p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 3rem 0 2rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            color: #ecf0f1;
        }

        .footer-section a {
            color: #bdc3c7;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: #667eea;
        }

        .footer-bottom {
            border-top: 1px solid #34495e;
            padding-top: 2rem;
            text-align: center;
            color: #bdc3c7;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero-container {
                grid-template-columns: 1fr;
                gap: 2rem;
                text-align: center;
            }

            .hero-content h1 {
                font-size: 2.2rem;
            }

            .hero-buttons {
                justify-content: center;
            }

            .btn-hero {
                width: 100%;
                max-width: 280px;
            }

            .carousel-container {
                height: 300px;
            }
        }

        /* Floating Action Button */
        .fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .fab:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }

        .logo-img {
    height: 50px;           /* or adjust as needed */
      display: flex;
    justify-content: center;  /* center horizontally */
    align-items: center; 
}

    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <div class="logo"> <div> <img src="logo.jpg" alt="Logo"  class="logo-img"  style="position: relative; top: 10px; left: 5px;"></div>
</div>
            <nav>
                <ul class="nav-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="Pricing.php">Pricing</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <a href="login.php" class="btn btn-outline">Login</a>
                <a href="brandSignup.php" class="btn btn-primary">Join as Brand </a>
                <a href="creatorsignup.php" class="btn btn-primary1">Join as Creator </a>

            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-container">
            <div class="hero-content">
                <h1>Connect Brands with Top Influencers</h1>
                <p>The ultimate platform for authentic influencer partnerships. Find the perfect match for your brand or monetize your influence with premium collaborations.</p>
                <div class="hero-buttons">
                    <a href="#signup-brand" class="btn-hero btn-white">I'm a Brand</a>
                    <a href="#signup-influencer" class="btn-hero btn-transparent">I'm an Influencer</a>
                </div>
            </div>
            <div class="hero-carousel">
                <div class="carousel-container">
                    <div class="carousel-slides" id="carouselSlides">
                        <?php echo renderCarouselSlides($carousel_images); ?>
                    </div>
                    <button class="carousel-nav carousel-prev" onclick="prevSlide()">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="carousel-nav carousel-next" onclick="nextSlide()">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <div class="carousel-dots">
                        <?php echo renderCarouselDots($carousel_images); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
 
    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header">
                <h2>Why Choose  Linkify?</h2>
                <p>Everything you need to create successful influencer marketing campaigns</p>
            </div>
            <div class="features-grid">
                <?php echo renderFeatures($features); ?>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <?php echo renderStats($stats); ?>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2>How It Works</h2>
                <p>Get started in just a few simple steps</p>
            </div>
            <div class="steps-grid">
                <?php echo renderSteps($steps); ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Ready to Start Your Influencer Journey?</h2>
            <p>Join thousands of brands and influencers who trust  Linkify for their marketing needs</p>
            <div class="hero-buttons">
                <a href="#signup" class="btn-hero btn-white">Get Started Today</a>
                <a href="#demo" class="btn-hero btn-transparent">Request Demo</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3> Linkify</h3>
                    <p>The leading platform for authentic influencer marketing partnerships.</p>
                </div>
                <div class="footer-section">
                    <h3>For Brands</h3>
                    <a href="#">Find Influencers</a>
                    <a href="#">Campaign Management</a>
                    <a href="#">Analytics</a>
                    <a href="#">Case Studies</a>
                </div>
                <div class="footer-section">
                    <h3>For Influencers</h3>
                    <a href="#">Join Network</a>
                    <a href="#">Pricing</a>
                    <a href="#">Resources</a>
                    <a href="#">Success Stories</a>
                </div>
                <div class="footer-section">
                    <h3>Support</h3>
                    <a href="#">Help Center</a>
                    <a href="#">Contact Us</a>
                    <a href="#">API Documentation</a>
                    <a href="#">Status</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?>  Linkify. All rights reserved. | Privacy Policy | Terms of Service</p>
            </div>
        </div>
    </footer>

    <!-- Floating Action Button -->
    <div class="fab">
        <i class="fas fa-comments"></i>
    </div>

    <script>
        // Carousel functionality
        let currentSlideIndex = 0;
        const slides = document.getElementById('carouselSlides');
        const dots = document.querySelectorAll('.dot');
        const totalSlides = <?php echo count($carousel_images); ?>;

        function showSlide(index) {
            currentSlideIndex = index;
            const slideWidth = 100 / totalSlides;
            slides.style.transform = `translateX(-${index * slideWidth}%)`;
            
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
        }

        function nextSlide() {
            currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
            showSlide(currentSlideIndex);
        }

        function prevSlide() {
            currentSlideIndex = (currentSlideIndex - 1 + totalSlides) % totalSlides;
            showSlide(currentSlideIndex);
        }

        function currentSlide(index) {
            showSlide(index - 1);
        }

        // Auto-play carousel
        setInterval(nextSlide, 4000);

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

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });

        // Counter animation for stats
        const observerOptions = {
            threshold: 0.7
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                }
            });
        }, observerOptions);

      document.querySelectorAll('.stat-item h3').forEach(counter => {
            observer.observe(counter);
        });

        // Add floating animation to hero background
        const heroBackground = document.querySelector('.hero::before');
        
        // Parallax effect for hero section
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.hero');
            const speed = scrolled * 0.5;
            
            if (parallax) {
                parallax.style.transform = `translateY(${speed}px)`;
            }
        });

        // Add hover effects to feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Animate elements on scroll
        const animateOnScroll = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        // Add initial styles and observe elements
        document.querySelectorAll('.feature-card, .step-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            animateOnScroll.observe(el);
        });
    </script>
</body>
</html>