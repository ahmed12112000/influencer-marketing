
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - InfluConnect</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
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
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo img {
            height: 40px;
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
            transition: color 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 6px;
        }

        .nav-links a:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .nav-links a.active {
            color: #667eea;
            background: rgba(102, 126, 234, 0.15);
        }

        .analytics-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            color: white;
        }

        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .time-filter {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .time-filter button {
            padding: 0.75rem 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .time-filter button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .time-filter button.active {
            background: rgba(255, 255, 255, 0.9);
            color: #667eea;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .stat-change {
            font-size: 0.9rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .stat-change.positive {
            background: #e8f5e8;
            color: #28a745;
        }

        .stat-change.negative {
            background: #fdeaea;
            color: #dc3545;
        }

        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .chart-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .chart-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .platforms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .platform-analytics {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .platform-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .platform-icon {
            font-size: 2rem;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .platform-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }

        .platform-metrics {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .metric-item {
            text-align: center;
            padding: 1rem;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 8px;
        }

        .metric-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }

        .metric-label {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.25rem;
        }

        .content-performance {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .content-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .content-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .content-thumbnail {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .content-info {
            flex: 1;
        }

        .content-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .content-date {
            font-size: 0.9rem;
            color: #666;
        }

        .content-metrics {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .content-metric {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.9rem;
            color: #666;
        }

        .engagement-breakdown {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .engagement-item {
            text-align: center;
            padding: 1.5rem;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 12px;
        }

        .engagement-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .engagement-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }

        .engagement-label {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.25rem;
        }

        @media (max-width: 768px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .nav-links {
                gap: 1rem;
            }
            
            .analytics-container {
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
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
            <a href="index.php" class="logo">
                <img src="logo2.png" alt="InfluConnect Logo">
            </a>
            <div class="nav-links">
                <a href="influencerdashboard.php">Dashboard</a>
                <a href="collaborationpage.php">Collaborations</a>
                <a href="#" class="active">Analytics</a>
                <a href="#">Settings</a>
                <a href="index.php" style="color: #667eea;">Logout</a>
            </div>
        </div>
    </nav>

    <div class="analytics-container">
        <div class="page-header">
            <h1>Analytics Dashboard</h1>
            <p>Track your performance and grow your influence</p>
        </div>

        <div class="time-filter">
            <button class="active" onclick="setTimeFilter('7d')">Last 7 Days</button>
            <button onclick="setTimeFilter('30d')">Last 30 Days</button>
            <button onclick="setTimeFilter('90d')">Last 90 Days</button>
            <button onclick="setTimeFilter('1y')">Last Year</button>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-icon">📊</span>
                <div class="stat-number" id="total-reach">5.2M</div>
                <div class="stat-label">Total Reach</div>
                <span class="stat-change positive">+12.3%</span>
            </div>
            <div class="stat-card">
                <span class="stat-icon">❤️</span>
                <div class="stat-number" id="total-engagement">234K</div>
                <div class="stat-label">Total Engagement</div>
                <span class="stat-change positive">+8.7%</span>
            </div>
            <div class="stat-card">
                <span class="stat-icon">👥</span>
                <div class="stat-number" id="new-followers">+2.1K</div>
                <div class="stat-label">New Followers</div>
                <span class="stat-change positive">+15.2%</span>
            </div>
            <div class="stat-card">
                <span class="stat-icon">💰</span>
                <div class="stat-number" id="total-earnings">$12,450</div>
                <div class="stat-label">Total Earnings</div>
                <span class="stat-change positive">+23.1%</span>
            </div>
        </div>

        <div class="charts-section">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Engagement Over Time</h3>
                </div>
                <div class="chart-container">
                    <canvas id="engagementChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Audience Demographics</h3>
                </div>
                <div class="chart-container">
                    <canvas id="demographicsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="platforms-grid">
            <div class="platform-analytics">
                <div class="platform-header">
                    <div class="platform-icon">📷</div>
                    <div class="platform-name">Instagram</div>
                </div>
                <div class="platform-metrics">
                    <div class="metric-item">
                        <div class="metric-number">85K</div>
                        <div class="metric-label">Followers</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-number">4.8%</div>
                        <div class="metric-label">Engagement Rate</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-number">2.3M</div>
                        <div class="metric-label">Reach</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-number">156K</div>
                        <div class="metric-label">Impressions</div>
                    </div>
                </div>
            </div>
            <div class="platform-analytics">
                <div class="platform-header">
                    <div class="platform-icon">🎥</div>
                    <div class="platform-name">YouTube</div>
                </div>
                <div class="platform-metrics">
                    <div class="metric-item">
                        <div class="metric-number">25K</div>
                        <div class="metric-label">Subscribers</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-number">3.2%</div>
                        <div class="metric-label">Engagement Rate</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-number">1.8M</div>
                        <div class="metric-label">Views</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-number">45K</div>
                        <div class="metric-label">Watch Time (hrs)</div>
                    </div>
                </div>
            </div>
            <div class="platform-analytics">
                <div class="platform-header">
                    <div class="platform-icon">🎵</div>
                    <div class="platform-name">TikTok</div>
                </div>
                <div class="platform-metrics">
                    <div class="metric-item">
                        <div class="metric-number">15K</div>
                        <div class="metric-label">Followers</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-number">6.1%</div>
                        <div class="metric-label">Engagement Rate</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-number">1.1M</div>
                        <div class="metric-label">Views</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-number">23K</div>
                        <div class="metric-label">Shares</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-performance">
            <div class="content-header">
                <h3 class="chart-title">Top Performing Content</h3>
            </div>
            <div class="content-list">
                <div class="content-item">
                    <div class="content-thumbnail">📸</div>
                    <div class="content-info">
                        <div class="content-title">Summer Fashion Haul 2024</div>
                        <div class="content-date">3 days ago</div>
                    </div>
                    <div class="content-metrics">
                        <div class="content-metric">
                            <span>👁️</span>
                            <span>45K</span>
                        </div>
                        <div class="content-metric">
                            <span>❤️</span>
                            <span>3.2K</span>
                        </div>
                        <div class="content-metric">
                            <span>💬</span>
                            <span>287</span>
                        </div>
                    </div>
                </div>
                <div class="content-item">
                    <div class="content-thumbnail">🎥</div>
                    <div class="content-info">
                        <div class="content-title">10-Minute Makeup Tutorial</div>
                        <div class="content-date">5 days ago</div>
                    </div>
                    <div class="content-metrics">
                        <div class="content-metric">
                            <span>👁️</span>
                            <span>32K</span>
                        </div>
                        <div class="content-metric">
                            <span>❤️</span>
                            <span>2.8K</span>
                        </div>
                        <div class="content-metric">
                            <span>💬</span>
                            <span>156</span>
                        </div>
                    </div>
                </div>
                <div class="content-item">
                    <div class="content-thumbnail">✨</div>
                    <div class="content-info">
                        <div class="content-title">Skincare Routine for Glowing Skin</div>
                        <div class="content-date">1 week ago</div>
                    </div>
                    <div class="content-metrics">
                        <div class="content-metric">
                            <span>👁️</span>
                            <span>28K</span>
                        </div>
                        <div class="content-metric">
                            <span>❤️</span>
                            <span>2.1K</span>
                        </div>
                        <div class="content-metric">
                            <span>💬</span>
                            <span>94</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="engagement-breakdown">
            <div class="engagement-item">
                <div class="engagement-icon">❤️</div>
                <div class="engagement-number">89K</div>
                <div class="engagement-label">Total Likes</div>
            </div>
            <div class="engagement-item">
                <div class="engagement-icon">💬</div>
                <div class="engagement-number">12K</div>
                <div class="engagement-label">Comments</div>
            </div>
            <div class="engagement-item">
                <div class="engagement-icon">📤</div>
                <div class="engagement-number">8.5K</div>
                <div class="engagement-label">Shares</div>
            </div>
            <div class="engagement-item">
                <div class="engagement-icon">🔖</div>
                <div class="engagement-number">15K</div>
                <div class="engagement-label">Saves</div>
            </div>
        </div>
    </div>

    <script>
        // Analytics data
        let analyticsData = {
            totalReach: 5200000,
            totalEngagement: 234000,
            newFollowers: 2100,
            totalEarnings: 12450,
            timeFilter: '7d'
        };

        // Time filter functionality
        function setTimeFilter(period) {
            analyticsData.timeFilter = period;
            
            // Update active button
            document.querySelectorAll('.time-filter button').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Update charts and data
            updateAnalytics(period);
        }

        // Update analytics based on time filter
        function updateAnalytics(period) {
            // Simulate different data for different periods
            const multipliers = {
                '7d': 1,
                '30d': 4.2,
                '90d': 12.8,
                '1y': 52
            };
            
            const mult = multipliers[period] || 1;
            
            // Update stats
            document.getElementById('total-reach').textContent = formatNumber(analyticsData.totalReach * mult);
            document.getElementById('total-engagement').textContent = formatNumber(analyticsData.totalEngagement * mult);
            document.getElementById('new-followers').textContent = '+' + formatNumber(analyticsData.newFollowers * mult);
            document.getElementById('total-earnings').textContent = '$' + formatNumber(analyticsData.totalEarnings * mult);
            
            // Update charts
            updateCharts(period);
        }

        // Format numbers
        function formatNumber(num) {
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            } else if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toString();
        }

        // Initialize charts
        function initializeCharts() {
            // Engagement Over Time Chart
            const engagementCtx = document.getElementById('engagementChart').getContext('2d');
            new Chart(engagementCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Engagement',
                        data: [12000, 15000, 18000, 14000, 22000, 25000, 20000],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatNumber(value);
                                }
                            }
                        }
                    }
                }
            });

            // Demographics Chart
            const demographicsCtx = document.getElementById('demographicsChart').getContext('2d');
            new Chart(demographicsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['18-24', '25-34', '35-44', '45+'],
                    datasets: [{
                        data: [35, 40, 20, 5],
                        backgroundColor: [
                            '#667eea',
                            '#764ba2',
                            '#f093fb',
                            '#f5576c'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Update charts based on time filter
        function updateCharts(period) {
            // In a real app, this would fetch new data and update charts
            console.log('Updating charts for period:', period);
        }

        // Initialize everything
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            updateAnalytics('7d');
        });

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

        // Observe all cards
        document.querySelectorAll('.stat-card, .chart-card, .platform-analytics, .content-performance').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>