
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Influencer Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            color: #333;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 20px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            height: 40px;
            width: auto;
            transition: all 0.3s ease;
            max-width: 150px;
        }

        .sidebar.collapsed .logo {
            height: 30px;
            max-width: 30px;
        }

        .toggle-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            background: rgba(102, 126, 234, 0.1);
        }

        .nav-menu {
            padding: 1rem 0;
            flex: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            background: rgba(102, 126, 234, 0.1);
            border-left-color: #667eea;
        }

        .nav-item.active {
            background: rgba(102, 126, 234, 0.15);
            border-left-color: #667eea;
            color: #667eea;
        }

        .nav-icon {
            font-size: 1.2rem;
            margin-right: 1rem;
            min-width: 24px;
        }

        .nav-text {
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            transform: translateX(-10px);
        }

        .sidebar.collapsed .nav-item {
            justify-content: center;
            padding: 1rem 0.5rem;
        }

        .sidebar.collapsed .nav-icon {
            margin-right: 0;
        }

        .profile-section {
            padding: 1.5rem;
            border-top: 1px solid #e0e0e0;
            margin-top: auto;
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 15px;
            color: white;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .profile-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .profile-status {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .sidebar.collapsed .profile-info {
            display: none;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 70px;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.5rem 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 2rem;
            color: #333;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        .notification-btn {
            position: relative;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        .notification-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4757;
            color: white;
            font-size: 0.8rem;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            min-width: 20px;
            text-align: center;
        }

        .content-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        .brands-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .brand-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .brand-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            border-color: #667eea;
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .brand-info h3 {
            margin-bottom: 0.5rem;
            color: #333;
        }

        .brand-category {
            color: #666;
            font-size: 0.9rem;
        }

        .brand-details {
            margin-bottom: 1rem;
        }

        .brand-budget {
            font-size: 1.1rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .brand-description {
            color: #666;
            line-height: 1.5;
        }

        .brand-actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            flex: 1;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 1px solid #e0e0e0;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .messages-container {
            max-height: 600px;
            overflow-y: auto;
        }

        .message-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .message-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .message-sender {
            font-weight: 600;
            color: #333;
        }

        .message-time {
            color: #666;
            font-size: 0.9rem;
        }

        .message-content {
            color: #555;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .message-actions {
            display: flex;
            gap: 1rem;
        }

        .profile-update-form {
            display: grid;
            gap: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-group-full {
            grid-column: 1 / -1;
        }

        .success-message {
            background: linear-gradient(45deg, #2ecc71, #27ae60);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            display: none;
        }

        .error-message {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            display: none;
        }

        .campaign-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-left: 4px solid #667eea;
        }

        .campaign-status {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: #e8f5e8;
            color: #27ae60;
        }

        .status-pending {
            background: #fff3cd;
            color: #f39c12;
        }

        .status-completed {
            background: #e3f2fd;
            color: #2196f3;
        }

        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .analytics-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .chart-container {
            height: 200px;
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1rem;
            color: #666;
        }

        .earnings-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .earnings-card {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
        }

        .earnings-amount {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .earnings-label {
            opacity: 0.9;
        }

        .pending-section {
            background: #fff3cd;
            border-left: 4px solid #f39c12;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .loading {
            display: inline-block;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .main-content {
                margin-left: 70px;
            }

            .sidebar .nav-text {
                display: none;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .brands-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .analytics-grid {
                grid-template-columns: 1fr;
            }

            .earnings-summary {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="logo2.png" alt="InfluencerHub Logo" class="logo">
                <button class="toggle-btn" id="toggleSidebar">☰</button>
            </div>

            <nav class="nav-menu">
                <div class="nav-item active" data-section="dashboard">
                    <span class="nav-icon">📊</span>
                    <span class="nav-text">Dashboard</span>
                </div>
                <div class="nav-item" data-section="brands"  onclick="window.location.href='AvailableBrands.php'">
                    <span class="nav-icon">🏢</span>
                    <span class="nav-text">Available Brands</span>    
                </div>
                <div class="nav-item" data-section="messages">
                    <span class="nav-icon">💬</span>
                    <span class="nav-text">Message Requests</span>
                </div>
              
                <div class="nav-item" data-section="analytics"  onclick="window.location.href='analiticpage.php'">
                    <span class="nav-icon">📈</span>
                    <span class="nav-text">Analytics</span>
                </div>
               
            </nav>

            <div class="profile-section">
<div class="profile-card" onclick="window.location.href='influencreupdateprofile.php'">
                    <div class="profile-avatar">JD</div>
                    <div class="profile-info">
                        <div class="profile-name">John Doe</div>
                        <div class="profile-status">Update Profile</div>    
                    </div>
                </div>
            </div>
        </div>

        <main class="main-content" id="mainContent">
            <div class="header">
                <h1 id="pageTitle">Dashboard</h1>
                <div class="header-actions">
                    <button class="notification-btn" onclick="showNotifications()">
                        🔔
                        <span class="notification-badge" id="notificationCount">5</span>
                    </button>
                </div>
            </div>

            <!-- Dashboard Section -->
            <div class="content-section active" id="dashboard">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value">12</div>
                        <div class="stat-label">Active Campaigns</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">$2,450</div>
                        <div class="stat-label">Monthly Earnings</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">8.5K</div>
                        <div class="stat-label">Total Followers</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">4.9</div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                </div>
                
                <h3 style="margin-bottom: 1.5rem;">Recent Activity</h3>
                <div class="message-card">
                    <div class="message-header">
                        <span class="message-sender">New brand collaboration from TechCorp</span>
                        <span class="message-time">2 hours ago</span>
                    </div>
                    <div class="message-content">
                        You have a new collaboration opportunity worth $500. Check your available brands section for more details.
                    </div>
                </div>
                
                <div class="message-card">
                    <div class="message-header">
                        <span class="message-sender">Campaign milestone reached</span>
                        <span class="message-time">5 hours ago</span>
                    </div>
                    <div class="message-content">
                        Your Fashion Brand campaign has reached 10K views! Bonus payment of $100 has been added to your account.
                    </div>
                </div>
            </div>

            <!-- Available Brands Section -->
            <div class="content-section" id="brands">
                <h3 style="margin-bottom: 1.5rem;">Available Brand Collaborations</h3>
                <div class="brands-grid">
                    <div class="brand-card">
                        <div class="brand-header">
                            <div class="brand-logo">TC</div>
                            <div class="brand-info">
                                <h3>TechCorp</h3>
                                <p class="brand-category">Technology & Gadgets</p>
                            </div>
                        </div>
                        <div class="brand-details">
                            <div class="brand-budget">$500 - $800</div>
                            <p class="brand-description">Looking for tech influencers to review our latest smartphone. Must have 5K+ followers and good engagement rate.</p>
                        </div>
                        <div class="brand-actions">
                            <button class="btn btn-primary">Apply Now</button>
                            <button class="btn btn-secondary">View Details</button>
                        </div>
                    </div>

                    <div class="brand-card">
                        <div class="brand-header">
                            <div class="brand-logo">FB</div>
                            <div class="brand-info">
                                <h3>Fashion Brand</h3>
                                <p class="brand-category">Fashion & Lifestyle</p>
                            </div>
                        </div>
                        <div class="brand-details">
                            <div class="brand-budget">$300 - $500</div>
                            <p class="brand-description">Seeking fashion influencers for our summer collection launch. Looking for authentic content creators.</p>
                        </div>
                        <div class="brand-actions">
                            <button class="btn btn-primary">Apply Now</button>
                            <button class="btn btn-secondary">View Details</button>
                        </div>
                    </div>

                    <div class="brand-card">
                        <div class="brand-header">
                            <div class="brand-logo">HB</div>
                            <div class="brand-info">
                                <h3>Health & Beauty Co</h3>
                                <p class="brand-category">Beauty & Wellness</p>
                            </div>
                        </div>
                        <div class="brand-details">
                            <div class="brand-budget">$200 - $400</div>
                            <p class="brand-description">Looking for beauty influencers to promote our new skincare line. Must create authentic reviews.</p>
                        </div>
                        <div class="brand-actions">
                            <button class="btn btn-primary">Apply Now</button>
                            <button class="btn btn-secondary">View Details</button>
                        </div>
                    </div>

                    <div class="brand-card">
                        <div class="brand-header">
                            <div class="brand-logo">SF</div>
                            <div class="brand-info">
                                <h3>Sports & Fitness</h3>
                                <p class="brand-category">Health & Fitness</p>
                            </div>
                        </div>
                        <div class="brand-details">
                            <div class="brand-budget">$400 - $600</div>
                            <p class="brand-description">Seeking fitness influencers to promote our new workout gear. Looking for active lifestyle content creators.</p>
                        </div>
                        <div class="brand-actions">
                            <button class="btn btn-primary">Apply Now</button>
                            <button class="btn btn-secondary">View Details</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Section -->
            <div class="content-section" id="messages">
                <h3 style="margin-bottom: 1.5rem;">Message Requests</h3>
                <div class="messages-container">
                    <div class="message-card">
                        <div class="message-header">
                            <span class="message-sender">Sarah from TechCorp</span>
                            <span class="message-time">2 hours ago</span>
                        </div>
                        <div class="message-content">
                            Hi! We'd love to collaborate with you on our upcoming smartphone launch. We're offering $600 for an Instagram post and story. Are you interested?
                        </div>
                        <div class="message-actions">
                            <button class="btn btn-primary">Accept</button>
                            <button class="btn btn-secondary">Negotiate</button>
                        </div>
                    </div>

                    <div class="message-card">
                        <div class="message-header">
                            <span class="message-sender">Mike from Fashion Brand</span>
                            <span class="message-time">1 day ago</span>
                        </div>
                        <div class="message-content">
                            We're launching a new summer collection and think you'd be perfect for our campaign. Budget is $400 for a TikTok video. Let us know if you're interested!
                        </div>
                        <div class="message-actions">
                            <button class="btn btn-primary">Accept</button>
                            <button class="btn btn-secondary">Decline</button>
                        </div>
                    </div>

                    <div class="message-card">
                        <div class="message-header">
                            <span class="message-sender">Emma from Beauty Co</span>
                            <span class="message-time">3 days ago</span>
                        </div>
                        <div class="message-content">
                            Hello! We've been following your content and love your style. We'd like to send you our new skincare products for review. Compensation: $300 + free products.
                        </div>
                        <div class="message-actions">
                            <button class="btn btn-primary">Reply</button>
                            <button class="btn btn-secondary">Archive</button>
                        </div>
                    </div>

                    <div class="message-card">
                        <div class="message-header">
                            <span class="message-sender">Alex from Sports & Fitness</span>
                            <span class="message-time">5 days ago</span>
                        </div>
                        <div class="message-content">
                            We're impressed with your fitness content! We'd like to partner with you for our new workout gear line. Offering $500 for a YouTube video review.
                        </div>
                        <div class="message-actions">
                            <button class="btn btn-primary">Accept</button>
                            <button class="btn btn-secondary">Decline</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaigns Section -->
            <div class="content-section" id="campaigns">
                <h3 style="margin-bottom: 1.5rem;">My Campaigns</h3>
                
                <div class="campaign-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h4>TechCorp Smartphone Review</h4>
                        <span class="campaign-status status-active">Active</span>
                    </div>
                    <p><strong>Budget:</strong> $600</p>
                    <p><strong>Deadline:</strong> March 15, 2024</p>
                    <p><strong>Deliverables:</strong> 1 Instagram post + 1 story</p>
                    <div style="margin-top: 1rem;">
                        <div style="background: #e9ecef; height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="background: #667eea; height: 100%; width: 75%; transition: width 0.3s ease;"></div>
                        </div>
                        <small style="color: #666; margin-top: 0.5rem; display: block;">75% Complete</small>
                    </div>
                </div>

                <div class="campaign-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h4>Fashion Brand Summer Collection</h4>
                        <span class="campaign-status status-pending">Pending Review</span>
                    </div>
                    <p><strong>Budget
                        :</strong>$400</p>