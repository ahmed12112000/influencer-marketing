<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfluConnect - Collaborations & Analytics</title>
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
            color: #333;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

       .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo img {
            height: 45px;
            width: auto;
            background: transparent;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover, .nav-links a.active {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .main-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            color: white;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .page-section {
            display: none;
        }

        .page-section.active {
            display: block;
        }

        .content-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .filter-tabs {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 0.5rem 1rem;
            background: rgba(102, 126, 234, 0.1);
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 500;
            color: #667eea;
            transition: all 0.3s ease;
        }

        .filter-tab.active {
            background: #667eea;
            color: white;
        }

        .filter-tab:hover {
            transform: translateY(-2px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .collaborations-grid {
            display: grid;
            gap: 1.5rem;
        }

        .collaboration-card {
            background: rgba(102, 126, 234, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .collaboration-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .collaboration-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .brand-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .brand-details h3 {
            font-size: 1.2rem;
            margin-bottom: 0.25rem;
            color: #333;
        }

        .brand-details p {
            color: #666;
            font-size: 0.9rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: #e8f5e8;
            color: #2d5a2d;
        }

        .status-completed {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-pending {
            background: #fff3e0;
            color: #f57c00;
        }

        .collaboration-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .detail-item {
            text-align: center;
            padding: 0.75rem;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 10px;
        }

        .detail-value {
            font-size: 1.1rem;
            font-weight: bold;
            color: #667eea;
        }

        .detail-label {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.25rem;
        }

        .collaboration-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .btn-secondary:hover {
            background: rgba(102, 126, 234, 0.2);
        }

        .analytics-chart {
            background: rgba(102, 126, 234, 0.05);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .chart-placeholder {
            height: 300px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 1rem;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .metric-card {
            background: rgba(102, 126, 234, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            border-left: 4px solid #667eea;
        }

        .metric-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 1rem;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .metric-change {
            font-size: 0.9rem;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
        }

        .change-positive {
            background: #e8f5e8;
            color: #2d5a2d;
        }

        .change-negative {
            background: #ffeaea;
            color: #d32f2f;
        }

        .platform-breakdown {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .platform-card {
            background: rgba(102, 126, 234, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .platform-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .platform-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .platform-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .platform-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 1rem;
        }

        .platform-stat {
            text-align: center;
        }

        .platform-stat-value {
            font-weight: bold;
            color: #667eea;
        }

        .platform-stat-label {
            font-size: 0.8rem;
            color: #666;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-state-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .empty-state-desc {
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .nav-container {
                padding: 0 1rem;
            }
            
            .main-container {
                padding: 0 1rem;
            }
            
            .section-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-tabs {
                justify-content: center;
            }
            
            .collaboration-header {
                flex-direction: column;
                gap: 1rem;
            }
            
            .collaboration-actions {
                justify-content: center;
            }
             .logo img {
                height: 35px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
  <a href="#" class="logo">
                <img src="logo2.png" alt="InfluConnect Logo">
            </a>      
                  <div class="nav-links">
                <a href="influencerdashboard.php" onclick="showPage('dashboard')">Dashboard</a>
                <a href="#" onclick="showPage('collaborations')" class="active" id="nav-collaborations">Collaborations</a>
                <a href="analiticpage.php" onclick="showPage('analytics')" id="nav-analytics">Analytics</a>
                <a href="#" onclick="showPage('settings')">Settings</a>
                <a href="index.php" style="color: #667eea;">Logout</a>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <!-- Collaborations Page -->
        <div id="collaborations-page" class="page-section active">
            <div class="page-header">
                <h1 class="page-title">Collaborations</h1>
                <p class="page-subtitle">Manage your brand partnerships and track collaboration performance</p>
            </div>

            <div class="content-section">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">23</div>
                        <div class="stat-label">Total Collaborations</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">5</div>
                        <div class="stat-label">Active Projects</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">$12,450</div>
                        <div class="stat-label">Total Earnings</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">4.8</div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Your Collaborations</h2>
                    <div class="filter-tabs">
                        <button class="filter-tab active" onclick="filterCollaborations('all')">All</button>
                        <button class="filter-tab" onclick="filterCollaborations('active')">Active</button>
                        <button class="filter-tab" onclick="filterCollaborations('completed')">Completed</button>
                        <button class="filter-tab" onclick="filterCollaborations('pending')">Pending</button>
                    </div>
                </div>

                <div class="collaborations-grid" id="collaborations-grid">
                    <!-- Collaboration cards will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Analytics Page -->
        <div id="analytics-page" class="page-section">
            <div class="page-header">
                <h1 class="page-title">Analytics</h1>
                <p class="page-subtitle">Track your performance and growth across all platforms</p>
            </div>

            <div class="content-section">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">125K</div>
                        <div class="stat-label">Total Followers</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">5.2M</div>
                        <div class="stat-label">Monthly Reach</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">4.2%</div>
                        <div class="stat-label">Engagement Rate</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">234K</div>
                        <div class="stat-label">Total Interactions</div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Performance Overview</h2>
                    <div class="filter-tabs">
                        <button class="filter-tab active" onclick="filterAnalytics('30d')">30 Days</button>
                        <button class="filter-tab" onclick="filterAnalytics('90d')">90 Days</button>
                        <button class="filter-tab" onclick="filterAnalytics('1y')">1 Year</button>
                    </div>
                </div>

                <div class="analytics-chart">
                    <div class="chart-title">Follower Growth Over Time</div>
                    <div class="chart-placeholder">
                        📈 Interactive Chart Would Go Here<br>
                        <small>In production: Chart.js or similar library</small>
                    </div>
                </div>

                <div class="analytics-chart">
                    <div class="chart-title">Engagement Analytics</div>
                    <div class="chart-placeholder">
                        📊 Engagement Chart Would Go Here<br>
                        <small>Likes, Comments, Shares breakdown</small>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Key Metrics</h2>
                </div>

                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-title">Follower Growth</div>
                        <div class="metric-value">+2,340</div>
                        <div class="metric-change change-positive">+12.5% this month</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-title">Engagement Rate</div>
                        <div class="metric-value">4.2%</div>
                        <div class="metric-change change-positive">+0.3% this month</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-title">Average Post Reach</div>
                        <div class="metric-value">18.5K</div>
                        <div class="metric-change change-negative">-2.1% this month</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-title">Monthly Earnings</div>
                        <div class="metric-value">$2,450</div>
                        <div class="metric-change change-positive">+18.2% this month</div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Platform Breakdown</h2>
                </div>

                <div class="platform-breakdown">
                    <div class="platform-card">
                        <div class="platform-icon">📷</div>
                        <div class="platform-name">Instagram</div>
                        <div class="platform-stats">
                            <div class="platform-stat">
                                <div class="platform-stat-value">85K</div>
                                <div class="platform-stat-label">Followers</div>
                            </div>
                            <div class="platform-stat">
                                <div class="platform-stat-value">5.2%</div>
                                <div class="platform-stat-label">Engagement</div>
                            </div>
                        </div>
                    </div>
                    <div class="platform-card">
                        <div class="platform-icon">🎥</div>
                        <div class="platform-name">YouTube</div>
                        <div class="platform-stats">
                            <div class="platform-stat">
                                <div class="platform-stat-value">25K</div>
                                <div class="platform-stat-label">Subscribers</div>
                            </div>
                            <div class="platform-stat">
                                <div class="platform-stat-value">3.8%</div>
                                <div class="platform-stat-label">Engagement</div>
                            </div>
                        </div>
                    </div>
                    <div class="platform-card">
                        <div class="platform-icon">🎵</div>
                        <div class="platform-name">TikTok</div>
                        <div class="platform-stats">
                            <div class="platform-stat">
                                <div class="platform-stat-value">15K</div>
                                <div class="platform-stat-label">Followers</div>
                            </div>
                            <div class="platform-stat">
                                <div class="platform-stat-value">7.1%</div>
                                <div class="platform-stat-label">Engagement</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sample collaboration data
        const collaborations = [
            {
                id: 1,
                brand: "Beauty Co",
                campaign: "Summer Skincare Campaign",
                status: "active",
                amount: 850,
                deadline: "2025-08-15",
                deliverables: 3,
                completed: 1,
                platforms: ["Instagram", "TikTok"],
                type: "Sponsored Posts"
            },
            {
                id: 2,
                brand: "Fashion Brand",
                campaign: "Fall Collection Launch",
                status: "completed",
                amount: 1200,
                deadline: "2025-07-01",
                deliverables: 5,
                completed: 5,
                platforms: ["Instagram", "YouTube"],
                type: "Brand Ambassador"
            },
            {
                id: 3,
                brand: "Tech Startup",
                campaign: "App Launch Promotion",
                status: "pending",
                amount: 600,
                deadline: "2025-08-30",
                deliverables: 2,
                completed: 0,
                platforms: ["Instagram", "Twitter"],
                type: "Product Review"
            },
            {
                id: 4,
                brand: "Wellness Co",
                campaign: "Health & Fitness Series",
                status: "active",
                amount: 950,
                deadline: "2025-09-10",
                deliverables: 4,
                completed: 2,
                platforms: ["Instagram", "YouTube", "TikTok"],
                type: "Affiliate Marketing"
            },
            {
                id: 5,
                brand: "Food Brand",
                campaign: "Recipe Series",
                status: "completed",
                amount: 750,
                deadline: "2025-06-20",
                deliverables: 3,
                completed: 3,
                platforms: ["Instagram", "TikTok"],
                type: "Sponsored Posts"
            }
        ];

        // Initialize page
        function initializePage() {
            renderCollaborations();
        }

        // Show specific page
        function showPage(page) {
            // Hide all pages
            document.querySelectorAll('.page-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Remove active class from all nav links
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.classList.remove('active');
            });
            
            // Show selected page
            document.getElementById(page + '-page').classList.add('active');
            document.getElementById('nav-' + page).classList.add('active');
        }

        // Render collaborations
        function renderCollaborations(filter = 'all') {
            const grid = document.getElementById('collaborations-grid');
            const filteredCollaborations = filter === 'all' 
                ? collaborations 
                : collaborations.filter(collab => collab.status === filter);

            if (filteredCollaborations.length === 0) {
                grid.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">📝</div>
                        <div class="empty-state-title">No collaborations found</div>
                        <div class="empty-state-desc">You don't have any ${filter === 'all' ? '' : filter} collaborations yet.</div>
                    </div>
                `;
                return;
            }

            grid.innerHTML = filteredCollaborations.map(collab => `
                <div class="collaboration-card">
                    <div class="collaboration-header">
                        <div class="brand-info">
                            <div class="brand-logo">${collab.brand.charAt(0)}</div>
                            <div class="brand-details">
                                <h3>${collab.brand}</h3>
                                <p>${collab.campaign}</p>
                            </div>
                        </div>
                        <div class="status-badge status-${collab.status}">${collab.status}</div>
                    </div>
                    
                    <div class="collaboration-details">
                        <div class="detail-item">
                            <div class="detail-value">$${collab.amount.toLocaleString()}</div>
                            <div class="detail-label">Total Value</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-value">${collab.completed}/${collab.deliverables}</div>
                            <div class="detail-label">Deliverables</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-value">${formatDate(collab.deadline)}</div>
                            <div class="detail-label">Deadline</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-value">${collab.platforms.length}</div>
                            <div class="detail-label">Platforms</div>
                        </div>
                    </div>
                    
                    <div class="collaboration-actions">
                        ${collab.status === 'active' ? `
                            <button class="action-btn btn-primary" onclick="viewCollaboration(${collab.id})">Upload Content</button>
                            <button class="action-btn btn-secondary" onclick="viewCollaboration(${collab.id})">View Brief</button>
                        ` : collab.status === 'pending' ? `
                            <button class="action-btn btn-primary" onclick="acceptCollaboration(${collab.id})">Accept</button>
                            <button class="action-btn btn-secondary" onclick="declineCollaboration(${collab.id})">Decline</button>
                        ` : `
                            <button class="action-btn btn-secondary" onclick="viewCollaboration(${collab.id})">View Details</button>
                            <button class="action-btn btn-secondary" onclick="downloadReport(${collab.id})">Download Report</button>
                        `}
                    </div>
                </div>
            `).join('');
        }

        // Filter collaborations
        function filterCollaborations(filter) {
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Render filtered collaborations
            renderCollaborations(filter);
        }

        // Filter analytics
        function filterAnalytics(period) {
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // In a real app, this would update the charts with new data
            console.log('Filtering analytics for period:', period);
        }

        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric'
            });
        }

        // Collaboration actions
        function viewCollaboration(id) {
            alert(`Viewing collaboration details for ID: ${id}`);
        }

        function acceptCollaboration(id) {
            alert(`Accepting collaboration ID: ${id}`);
            // In a real app, this would update the collaboration status
        }

        function declineCollaboration(id) {
            alert(`Declining collaboration ID: ${id}`);
            // In a real app, this would update the collaboration status
        }

        function downloadReport(id) {
            alert(`Downloading report for collaboration ID: ${id}`);
            // In a real app, this would generate and download a PDF report
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initializePage);

        // Add smooth animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all content sections
        document.querySelectorAll('.content-section').forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(section);
        });

        // Add hover effects to cards
        document.addEventListener('mouseover', function(e) {
            if (e.target.closest('.collaboration-card, .stat-card, .platform-card')) {
                e.target