<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfluConnect - Profile Dashboard</title>
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
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
            width: auto;
            transition: transform 0.3s ease;
        }

        .logo:hover img {
            transform: scale(1.05);
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

        .nav-links a:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .profile-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }

        .profile-sidebar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            height: fit-content;
            position: sticky;
            top: 120px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            font-weight: bold;
            position: relative;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
        }

        .avatar-upload {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #667eea;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .avatar-upload:hover {
            background: #5a6fd8;
        }

        .profile-name {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .profile-username {
            text-align: center;
            color: #667eea;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-2px);
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.25rem;
        }

        .social-links {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .social-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateX(5px);
        }

        .main-content {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .content-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .edit-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .edit-btn:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .info-item {
            padding: 1rem;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .info-label {
            font-weight: 600;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .info-value {
            color: #333;
            font-size: 1.1rem;
        }

        .platforms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .platform-card {
            background: rgba(102, 126, 234, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .platform-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .platform-card.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .platform-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .platform-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .bio-text {
            line-height: 1.6;
            color: #666;
            margin-bottom: 1rem;
        }

        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .tag {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .analytics-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .analytics-card:hover {
            transform: translateY(-5px);
        }

        .analytics-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .analytics-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 2rem;
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            resize: vertical;
            min-height: 100px;
            transition: border-color 0.3s ease;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .save-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .save-btn:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .profile-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .nav-container {
                padding: 0 1rem;
            }
            
            .profile-container {
                padding: 0 1rem;
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

             <a href="influencerdashboard.php" class="logo">
                <img src="logo2.png" alt="Linkify Logo">
            </a>
            <div class="nav-links">
                <a href="influencerdashboard.php" onclick="showSection('dashboard')">Dashboard</a>
                <a href="collaborationpage.php" onclick="showSection('collaborations')">Collaborations</a>
                <a href="analiticpage.php" onclick="showSection('analytics')">Analytics</a>
                <a href="index.php" style="color: #667eea;">Logout</a>
            </div>
        </div>
    </nav>

    <div class="profile-container">
        <div class="profile-sidebar">
            <div class="profile-avatar" onclick="uploadAvatar()">
                <span id="avatar-initial">JD</span>
                <div class="avatar-upload">📷</div>
            </div>
            <div class="profile-name" id="profile-name">Jane Doe</div>
            <div class="profile-username" id="profile-username">@jane_creates</div>
            
            <div class="profile-stats">
                <div class="stat-item">
                    <div class="stat-number" id="total-followers">125K</div>
                    <div class="stat-label">Total Followers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="engagement-rate">4.2%</div>
                    <div class="stat-label">Engagement Rate</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="collaborations-count">23</div>
                    <div class="stat-label">Collaborations</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="earning-this-month">$2,450</div>
                    <div class="stat-label">This Month</div>
                </div>
            </div>
            
            <div class="social-links">
                <a href="#" class="social-link" id="instagram-link">
                    <span style="font-size: 1.2rem;">📷</span>
                    <span>Instagram</span>
                </a>
                <a href="#" class="social-link" id="youtube-link">
                    <span style="font-size: 1.2rem;">🎥</span>
                    <span>YouTube</span>
                </a>
                <a href="#" class="social-link" id="tiktok-link">
                    <span style="font-size: 1.2rem;">🎵</span>
                    <span>TikTok</span>
                </a>
                <a href="#" class="social-link" id="twitter-link">
                    <span style="font-size: 1.2rem;">🐦</span>
                    <span>Twitter/X</span>
                </a>
            </div>
        </div>

        <div class="main-content">
            <div class="content-section" id="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">Profile Information</h2>
                    <button class="edit-btn" onclick="openEditModal()">Edit Profile</button>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Primary Niche</div>
                        <div class="info-value" id="niche-display">Fashion & Beauty</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Location</div>
                        <div class="info-value" id="location-display">Los Angeles, CA</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Collaboration Rate</div>
                        <div class="info-value" id="rate-display">$500 per post</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Preferred Collaboration</div>
                        <div class="info-value" id="collab-type-display">Sponsored Posts</div>
                    </div>
                </div>

                <div class="info-item" style="margin-bottom: 2rem;">
                    <div class="info-label">About Me</div>
                    <div class="bio-text" id="bio-display">
                        Passionate fashion and beauty creator with 5+ years of experience. I love sharing style tips, 
                        makeup tutorials, and lifestyle content that inspires my audience to feel confident and beautiful. 
                        I'm always excited to collaborate with brands that align with my values and aesthetic.
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Target Audience</div>
                    <div class="bio-text" id="audience-display">
                        Women aged 18-35, primarily in the US and Europe. Interested in fashion, beauty, lifestyle, 
                        and wellness. High engagement with beauty tutorials and fashion hauls.
                    </div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Active Platforms</h2>
                </div>
                
                <div class="platforms-grid">
                    <div class="platform-card active">
                        <div class="platform-icon">📷</div>
                        <div class="platform-name">Instagram</div>
                        <div class="stat-number">85K</div>
                        <div class="stat-label">followers</div>
                    </div>
                    <div class="platform-card active">
                        <div class="platform-icon">🎥</div>
                        <div class="platform-name">YouTube</div>
                        <div class="stat-number">25K</div>
                        <div class="stat-label">subscribers</div>
                    </div>
                    <div class="platform-card active">
                        <div class="platform-icon">🎵</div>
                        <div class="platform-name">TikTok</div>
                        <div class="stat-number">15K</div>
                        <div class="stat-label">followers</div>
                    </div>
                    <div class="platform-card">
                        <div class="platform-icon">🐦</div>
                        <div class="platform-name">Twitter/X</div>
                        <div class="stat-number">-</div>
                        <div class="stat-label">Not Connected</div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Performance Analytics</h2>
                </div>
                
                <div class="analytics-grid">
                    <div class="analytics-card">
                        <div class="analytics-number">456</div>
                        <div class="analytics-label">Total Posts</div>
                    </div>
                    <div class="analytics-card">
                        <div class="analytics-number">5.2M</div>
                        <div class="analytics-label">Total Reach</div>
                    </div>
                    <div class="analytics-card">
                        <div class="analytics-number">234K</div>
                        <div class="analytics-label">Total Likes</div>
                    </div>
                    <div class="analytics-card">
                        <div class="analytics-number">$12,450</div>
                        <div class="analytics-label">Total Earnings</div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Content Categories</h2>
                </div>
                
                <div class="tags">
                    <span class="tag">Fashion</span>
                    <span class="tag">Beauty</span>
                    <span class="tag">Lifestyle</span>
                    <span class="tag">Makeup Tutorials</span>
                    <span class="tag">OOTD</span>
                    <span class="tag">Skincare</span>
                    <span class="tag">Style Tips</span>
                    <span class="tag">Product Reviews</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Profile</h3>
                <button class="close-btn" onclick="closeEditModal()">&times;</button>
            </div>
            
            <form id="editForm">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-input" id="edit-name" value="Jane Doe">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-input" id="edit-username" value="jane_creates">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Primary Niche</label>
                    <select class="form-input" id="edit-niche">
                        <option value="fashion" selected>Fashion & Beauty</option>
                        <option value="lifestyle">Lifestyle</option>
                        <option value="fitness">Health & Fitness</option>
                        <option value="food">Food & Cooking</option>
                        <option value="travel">Travel</option>
                        <option value="tech">Technology</option>
                        <option value="gaming">Gaming</option>
                        <option value="education">Education</option>
                        <option value="entertainment">Entertainment</option>
                        <option value="business">Business</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Location</label>
                    <input type="text" class="form-input" id="edit-location" value="Los Angeles, CA">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Rate per Post (USD)</label>
                    <input type="number" class="form-input" id="edit-rate" value="500">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Bio/About Yourself</label>
                    <textarea class="form-textarea" id="edit-bio">Passionate fashion and beauty creator with 5+ years of experience. I love sharing style tips, makeup tutorials, and lifestyle content that inspires my audience to feel confident and beautiful. I'm always excited to collaborate with brands that align with my values and aesthetic.</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Target Audience Demographics</label>
                    <textarea class="form-textarea" id="edit-audience">Women aged 18-35, primarily in the US and Europe. Interested in fashion, beauty, lifestyle, and wellness. High engagement with beauty tutorials and fashion hauls.</textarea>
                </div>
                
                <button type="submit" class="save-btn">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        // Sample profile data (in a real app, this would come from a database)
        let profileData = {
            fullName: "Jane Doe",
            username: "jane_creates",
            niche: "fashion",
            location: "Los Angeles, CA",
            rate: 500,
            bio: "Passionate fashion and beauty creator with 5+ years of experience. I love sharing style tips, makeup tutorials, and lifestyle content that inspires my audience to feel confident and beautiful. I'm always excited to collaborate with brands that align with my values and aesthetic.",
            audience: "Women aged 18-35, primarily in the US and Europe. Interested in fashion, beauty, lifestyle, and wellness. High engagement with beauty tutorials and fashion hauls.",
            totalFollowers: 125000,
            engagementRate: 4.2,
            collaborations: 23,
            monthlyEarning: 2450
        };

        // Initialize profile display
        function initializeProfile() {
            updateProfileDisplay();
        }

        // Update profile display with current data
        function updateProfileDisplay() {
            document.getElementById('profile-name').textContent = profileData.fullName;
            document.getElementById('profile-username').textContent = '@' + profileData.username;
            document.getElementById('avatar-initial').textContent = profileData.fullName.split(' ').map(n => n[0]).join('');
            
            // Update niche display
            const nicheMap = {
                'fashion': 'Fashion & Beauty',
                'lifestyle': 'Lifestyle',
                'fitness': 'Health & Fitness',
                'food': 'Food & Cooking',
                'travel': 'Travel',
                'tech': 'Technology',
                'gaming': 'Gaming',
                'education': 'Education',
                'entertainment': 'Entertainment',
                'business': 'Business',
                'other': 'Other'
            };
            document.getElementById('niche-display').textContent = nicheMap[profileData.niche] || profileData.niche;
            document.getElementById('location-display').textContent = profileData.location;
            document.getElementById('rate-display').textContent = '$' + profileData.rate + ' per post';
            document.getElementById('bio-display').textContent = profileData.bio;
            document.getElementById('audience-display').textContent = profileData.audience;
            
            // Update stats
            document.getElementById('total-followers').textContent = formatNumber(profileData.totalFollowers);
            document.getElementById('engagement-rate').textContent = profileData.engagementRate + '%';
            document.getElementById('collaborations-count').textContent = profileData.collaborations;
            document.getElementById('earning-this-month').textContent = '$' + profileData.monthlyEarning.toLocaleString();
        }

        // Format large numbers
        function formatNumber(num) {
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            } else if (num >= 1000) {
                return (num / 1000).toFixed(0) + 'K';
            }
            return num.toString();
        }

        // Open edit modal
        function openEditModal() {
            document.getElementById('editModal').style.display = 'block';
            
            // Populate form with current data
            document.getElementById('edit-name').value = profileData.fullName;
            document.getElementById('edit-username').value = profileData.username;
            document.getElementById('edit-niche').value = profileData.niche;
            document.getElementById('edit-location').value = profileData.location;
            document.getElementById('edit-rate').value = profileData.rate;
            document.getElementById('edit-bio').value = profileData.bio;
            document.getElementById('edit-audience').value = profileData.audience;
        }

        // Close edit modal
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Handle form submission
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Update profile data
            profileData.fullName = document.getElementById('edit-name').value;
            profileData.username = document.getElementById('edit-username').value;
            profileData.niche = document.getElementById('edit-niche').value;
            profileData.location = document.getElementById('edit-location').value;
            profileData.rate = parseInt(document.getElementById('edit-rate').value);
            profileData.bio = document.getElementById('edit-bio').value;
            profileData.audience = document.getElementById('edit-audience').value;
            
            // Update display
            updateProfileDisplay();
            
            // Close modal
            closeEditModal();
            
            // Show success message (in a real app, this would save to database)
            alert('Profile updated successfully!');
        });

        // Upload avatar function
        function uploadAvatar() {
            // In a real app, this would open a file picker
            alert('Avatar upload feature - would open file picker in real implementation');
        }

        // Navigation function (placeholder)
        function showSection(section) {
            // In a real app, this would show different sections
            console.log('Navigating to:', section);
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initializeProfile);

        // Add smooth animations on scroll
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
    </script>
</body>
</html>