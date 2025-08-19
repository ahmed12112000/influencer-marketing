<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features - InfluConnect</title>
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
            color: #333;
        }

        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo a {
            color: white;
            text-decoration: none;
            font-size: 28px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .nav-links a:hover {
            color: #f0f0f0;
            transform: translateY(-2px);
        }

        .hero-section {
            text-align: center;
            padding: 80px 20px;
            color: white;
        }

        .hero-section h1 {
            font-size: 3.5em;
            margin-bottom: 20px;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
            font-weight: 300;
        }

        .hero-section p {
            font-size: 1.3em;
            opacity: 0.9;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            margin: 0 auto;
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, #667eea, #764ba2);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            font-size: 4em;
            margin-bottom: 20px;
            display: block;
        }

        .feature-card h3 {
            font-size: 1.5em;
            margin-bottom: 15px;
            color: #333;
            font-weight: 600;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
            font-size: 14px;
        }

        .cta-section {
            background: white;
            margin: 60px 0;
            padding: 60px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .cta-section h2 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #333;
            font-weight: 300;
        }

        .cta-section p {
            font-size: 1.1em;
            color: #666;
            margin-bottom: 30px;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .cta-btn.secondary {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .cta-btn.secondary:hover {
            background: #667eea;
            color: white;
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin: 60px 0;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            color: white;
        }

        .stat-number {
            font-size: 3em;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .stat-label {
            font-size: 1.1em;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 20px;
            }

            .nav-links {
                gap: 20px;
            }

            .hero-section h1 {
                font-size: 2.5em;
            }

            .hero-section p {
                font-size: 1.1em;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .feature-card {
                padding: 30px;
            }

            .cta-section {
                padding: 40px 20px;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .cta-btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="nav-container">
            <div class="logo">
                <a href="index.php">InfluConnect</a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="features.php">Features</a></li>
                <li><a href="how-it-works.php">How It Works</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero-section">
        <h1>Powerful Features</h1>
        <p>Everything you need to connect influencers with brands and create successful partnerships</p>
    </section>

    <div class="features-container">
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number">10K+</div>
                <div class="stat-label">Active Creators</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">500+</div>
                <div class="stat-label">Brand Partners</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">95%</div>
                <div class="stat-label">Success Rate</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">$2M+</div>
                <div class="stat-label">Campaigns Value</div>
            </div>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <span class="feature-icon">🎯</span>
                <h3>Smart Matching</h3>
                <p>Our AI-powered algorithm connects brands with the perfect influencers based on audience demographics, engagement rates, and content alignment for maximum campaign success.</p>
            </div>

            <div class="feature-card">
                <span class="feature-icon">📊</span>
                <h3>Analytics Dashboard</h3>
                <p>Track campaign performance with real-time analytics, engagement metrics, ROI calculations, and detailed reports to optimize your influencer marketing strategy.</p>
            </div>

            <div class="feature-card">
                <span class="feature-icon">💬</span>
                <h3>Seamless Communication</h3>
                <p>Built-in messaging system allows brands and influencers to communicate directly, share briefs, negotiate terms, and collaborate efficiently on campaigns.</p>
            </div>

            <div class="feature-card">
                <span class="feature-icon">💰</span>
                <h3>Secure Payments</h3>
                <p>Automated payment processing with escrow protection ensures timely and secure transactions between brands and influencers for all completed campaigns.</p>
            </div>

            <div class="feature-card">
                <span class="feature-icon">📱</span>
                <h3>Multi-Platform Support</h3>
                <p>Manage campaigns across Instagram, YouTube, TikTok, Twitter, and more from one unified dashboard with platform-specific optimization tools.</p>
            </div>

            <div class="feature-card">
                <span class="feature-icon">🔍</span>
                <h3>Content Discovery</h3>
                <p>Advanced search and filtering tools help brands discover the right influencers by niche, location, follower count, engagement rate, and previous campaign success.</p>
            </div>

            <div class="feature-card">
                <span class="feature-icon">📋</span>
                <h3>Campaign Management</h3>
                <p>End-to-end campaign management from brief creation to final delivery with milestone tracking, approval workflows, and automated reminders.</p>
            </div>

            <div class="feature-card">
                <span class="feature-icon">🛡️</span>
                <h3>Quality Assurance</h3>
                <p>Comprehensive vetting process for influencers including follower authenticity checks, content quality review, and brand safety compliance verification.</p>
            </div>

            <div class="feature-card">
                <span class="feature-icon">📈</span>
                <h3>Performance Tracking</h3>
                <p>Monitor campaign metrics in real-time including reach, impressions, engagement, clicks, conversions, and ROI with customizable reporting dashboards.</p>
            </div>
        </div>

        <div class="cta-section">
            <h2>Ready to Get Started?</h2>
            <p>Join thousands of creators and brands already using InfluConnect to create successful partnerships</p>
            <div class="cta-buttons">
                <a href="signup-creator.php" class="cta-btn">Join as Creator</a>
                <a href="signup-brand.php" class="cta-btn secondary">Partner with Us</a>
            </div>
        </div>
    </div>
</body>
</html>