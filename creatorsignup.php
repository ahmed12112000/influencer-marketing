<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join as Creator - InfluConnect</title>
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
            padding: 20px 0;
        }

        .signup-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 1200px;
            max-width: 90%;
            min-height: 900px;
            display: flex;
        }

        .signup-left {
            flex: 1;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .signup-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"><rect fill="%23667eea" width="400" height="300"/><circle fill="%23764ba2" cx="100" cy="100" r="80" opacity="0.3"/><circle fill="%23764ba2" cx="300" cy="200" r="60" opacity="0.3"/></svg>') center/cover;
            opacity: 0.5;
        }

        .logo {
            position: absolute;
            top: 30px;
            left: 30px;
            z-index: 10;
            width:200px
        }

        .logo a {
            display: block;
            transition: all 0.3s ease;
        }

        .logo a:hover {
            transform: scale(1.05);
        }

        .logo img {
            height: 50px;
            width: auto;
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.5));
            transition: all 0.3s ease;
        }

        .hero-content {
            text-align: center;
            color: white;
            z-index: 2;
            position: relative;
            padding: 10px;
            margin-top: -1000px;
        }

        .hero-content h1 {
            font-size: 3em;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-content p {
            font-size: 1.2em;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .signup-right {
            flex: 1;
            padding: 30px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }

        .signup-form h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2.2em;
            font-weight: 300;
            text-align: center;
        }

        .signup-form .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            margin-bottom: 20px;
            position: relative;
        }

        .form-group.full-width {
            flex: none;
            width: 100%;
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
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="url"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-group textarea {
            height: 80px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .social-platforms {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .platform-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .platform-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #667eea;
        }

        .platform-group label {
            color: #666;
            font-size: 14px;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 12px;
            margin-top: 2px;
            accent-color: #667eea;
        }

        .checkbox-group label {
            color: #666;
            font-size: 14px;
            cursor: pointer;
            user-select: none;
            line-height: 1.4;
        }

        .signup-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            width: 100%;
        }

        .signup-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .signup-btn:active {
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

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .section-title {
            color: #333;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            margin-top: 20px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }

        @media (max-width: 768px) {
            .signup-container {
                flex-direction: column;
                width: 95%;
                max-width: 500px;
            }
            
            .signup-left {
                min-height: 200px;
            }
            
            .signup-right {
                padding: 30px 20px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .social-platforms {
                grid-template-columns: 1fr;
            }

            .logo {
                top: 20px;
                left: 20px;
            }

            .logo img {
                height: 40px;
            }

            .hero-content h1 {
                font-size: 2em;
            }

            .hero-content p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-left">
            <div class="logo">
                <a href="index.php">
                    <img src="logo2.png" alt="InfluConnect Logo">
                </a>
            </div>
            <div class="hero-content">
                <h1>Join Our Creator Community</h1>
                <p>Connect with brands and monetize your influence</p>
            </div>
        </div>
        
        <div class="signup-right">
            <div class="signup-form">
                <h2>Join as a Creator</h2>
                <p class="subtitle">Monetize your influence and connect with top brands</p>
                
                <div id="error-message" class="error-message" style="display: none;"></div>
                <div id="success-message" class="success-message" style="display: none;"></div>
                
                <form id="signupForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username *</label>
                            <input type="text" id="username" name="username" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" id="phone" name="phone">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password *</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password *</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="niche">Primary Niche *</label>
                            <select id="niche" name="niche" required>
                                <option value="">Select Your Niche</option>
                                <option value="fashion">Fashion & Beauty</option>
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
                            <label for="location">Location</label>
                            <input type="text" id="location" name="location" placeholder="City, Country">
                        </div>
                    </div>
                    
                    <div class="section-title">Social Media Platforms</div>
                    <div class="social-platforms">
                        <div class="platform-group">
                            <input type="checkbox" id="instagram" name="platforms[]" value="instagram">
                            <label for="instagram">Instagram</label>
                        </div>
                        <div class="platform-group">
                            <input type="checkbox" id="youtube" name="platforms[]" value="youtube">
                            <label for="youtube">YouTube</label>
                        </div>
                        <div class="platform-group">
                            <input type="checkbox" id="tiktok" name="platforms[]" value="tiktok">
                            <label for="tiktok">TikTok</label>
                        </div>
                        <div class="platform-group">
                            <input type="checkbox" id="twitter" name="platforms[]" value="twitter">
                            <label for="twitter">Twitter/X</label>
                        </div>
                        <div class="platform-group">
                            <input type="checkbox" id="facebook" name="platforms[]" value="facebook">
                            <label for="facebook">Facebook</label>
                        </div>
                        <div class="platform-group">
                            <input type="checkbox" id="linkedin" name="platforms[]" value="linkedin">
                            <label for="linkedin">LinkedIn</label>
                        </div>
                        <div class="platform-group">
                            <input type="checkbox" id="snapchat" name="platforms[]" value="snapchat">
                            <label for="snapchat">Snapchat</label>
                        </div>
                        <div class="platform-group">
                            <input type="checkbox" id="twitch" name="platforms[]" value="twitch">
                            <label for="twitch">Twitch</label>
                        </div>
                    </div>
                    
                    <div class="section-title">Social Media Links (Optional)</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="instagram_url">Instagram URL</label>
                            <input type="url" id="instagram_url" name="instagram_url" placeholder="https://instagram.com/username">
                        </div>
                        <div class="form-group">
                            <label for="youtube_url">YouTube URL</label>
                            <input type="url" id="youtube_url" name="youtube_url" placeholder="https://youtube.com/channel/...">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tiktok_url">TikTok URL</label>
                            <input type="url" id="tiktok_url" name="tiktok_url" placeholder="https://tiktok.com/@username">
                        </div>
                        <div class="form-group">
                            <label for="twitter_url">Twitter/X URL</label>
                            <input type="url" id="twitter_url" name="twitter_url" placeholder="https://twitter.com/username">
                        </div>
                    </div>
                    
                    <div class="section-title">Audience Information</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="total_followers">Total Followers (All Platforms)</label>
                            <input type="number" id="total_followers" name="total_followers" placeholder="e.g., 10000">
                        </div>
                        <div class="form-group">
                           <label for="engagement_rate">Average Engagement Rate (%)</label>
                         <br>    <input type="number" id="engagement_rate" name="engagement_rate" step="0.1" placeholder="e.g., 3.5">
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="audience_demographics">Target Audience Demographics</label>
                        <textarea id="audience_demographics" name="audience_demographics" placeholder="Describe your audience (age, gender, interests, location, etc.)"></textarea>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="bio">Bio/About Yourself</label>
                        <textarea id="bio" name="bio" placeholder="Tell us about yourself and your content..."></textarea>
                    </div>
                    
                    <div class="section-title">Collaboration Preferences</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="collaboration_types">Preferred Collaboration Types</label>
                            <select id="collaboration_types" name="collaboration_types">
                                <option value="">Select Type</option>
                                <option value="sponsored_posts">Sponsored Posts</option>
                                <option value="product_reviews">Product Reviews</option>
                                <option value="brand_ambassador">Brand Ambassador</option>
                                <option value="affiliate_marketing">Affiliate Marketing</option>
                                <option value="event_promotion">Event Promotion</option>
                                <option value="giveaways">Giveaways & Contests</option>
                                <option value="all">All Types</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="rate_per_post">Rate per Post (USD)</label>
                           <br> <input type="number" id="rate_per_post" name="rate_per_post" placeholder="e.g., 500">
                        </div>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="portfolio_sharing" name="portfolio_sharing">
                        <label for="portfolio_sharing">I agree to share my content portfolio with potential brand partners</label>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="email_notifications" name="email_notifications" checked>
                        <label for="email_notifications">I want to receive email notifications about new collaboration opportunities</label>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the <a href="#" style="color: #667eea;">Terms of Service</a> and <a href="#" style="color: #667eea;">Privacy Policy</a> *</label>
                    </div>
                    
                    <button type="submit" class="signup-btn">Create Account</button>
                </form>
                
                <div class="login-link">
                    Already have an account? <a href="login.php">Sign in here</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const errorDiv = document.getElementById('error-message');
            const successDiv = document.getElementById('success-message');
            
            // Clear previous messages
            errorDiv.style.display = 'none';
            successDiv.style.display = 'none';
            
            // Get form data
            const formData = new FormData(this);
            const data = {};
            
            // Convert FormData to object
            for (let [key, value] of formData.entries()) {
                if (key.includes('[]')) {
                    const cleanKey = key.replace('[]', '');
                    if (!data[cleanKey]) data[cleanKey] = [];
                    data[cleanKey].push(value);
                } else {
                    data[key] = value;
                }
            }
            
            // Basic validation
            const requiredFields = ['full_name', 'username', 'email', 'password', 'confirm_password', 'niche'];
            let hasError = false;
            let errorMessage = '';
            
            for (let field of requiredFields) {
                if (!data[field] || data[field].trim() === '') {
                    hasError = true;
                    errorMessage = 'Please fill in all required fields.';
                    break;
                }
            }
            
            if (!hasError && data.password !== data.confirm_password) {
                hasError = true;
                errorMessage = 'Passwords do not match.';
            }
            
            if (!hasError && data.password.length < 6) {
                hasError = true;
                errorMessage = 'Password must be at least 6 characters long.';
            }
            
            if (!hasError && !data.terms) {
                hasError = true;
                errorMessage = 'You must accept the terms and conditions.';
            }
            
            if (!hasError) {
                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(data.email)) {
                    hasError = true;
                    errorMessage = 'Please enter a valid email address.';
                }
            }
            
            if (hasError) {
                errorDiv.textContent = errorMessage;
                errorDiv.style.display = 'block';
                errorDiv.scrollIntoView({ behavior: 'smooth' });
                return;
            }
            
            // Simulate successful registration
            successDiv.textContent = 'Registration successful! Please check your email for verification.';
            successDiv.style.display = 'block';
            successDiv.scrollIntoView({ behavior: 'smooth' });
            
            // Reset form
            this.reset();
            
            // In a real application, you would send the data to your server here
            console.log('Registration data:', data);
        });
        
        // Add some interactivity to the form
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>