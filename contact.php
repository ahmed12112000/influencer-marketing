<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - InfluConnect</title>
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
            padding: 20px;
        }

        .contact-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 900px;
            max-width: 95%;
            display: flex;
            min-height: 600px;
        }

        .contact-left {
            flex: 1;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            padding: 40px;
            color: white;
            text-align: center;
        }

        .contact-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"><rect fill="%23667eea" width="400" height="300"/><circle fill="%23764ba2" cx="100" cy="100" r="80" opacity="0.3"/><circle fill="%23764ba2" cx="300" cy="200" r="60" opacity="0.3"/><path d="M50,250 Q200,150 350,250" stroke="%23ffffff" stroke-width="2" fill="none" opacity="0.2"/></svg>') center/cover;
            opacity: 0.3;
        }

        .logo {
            position: absolute;
            top: 30px;
            left: 30px;
            z-index: 10;
        }

        .logo a {
            color: white;
            text-decoration: none;
            font-size: 24px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }

        .logo a:hover {
            color: #f0f0f0;
            transform: scale(1.05);
        }

        .contact-info {
            z-index: 2;
            position: relative;
        }

        .contact-info h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .contact-info p {
            font-size: 1.1em;
            margin-bottom: 30px;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .contact-details {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 1em;
        }

        .contact-item i {
            font-size: 1.2em;
            width: 20px;
            text-align: center;
        }

        .contact-right {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .contact-form h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2em;
            font-weight: 300;
        }

        .contact-form .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #fafafa;
            font-family: inherit;
        }

        .form-group textarea {
            height: 120px;
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

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .contact-btn {
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
            margin-top: 10px;
        }

        .contact-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .contact-btn:active {
            transform: translateY(0);
        }

        .success-message {
            background: #efe;
            color: #363;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #cfc;
            display: none;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fcc;
            display: none;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            justify-content: center;
        }

        .social-links a {
            color: white;
            font-size: 1.5em;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            transform: scale(1.2);
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .contact-container {
                flex-direction: column;
                width: 95%;
                max-width: 500px;
            }
            
            .contact-left {
                min-height: 300px;
                padding: 30px 20px;
            }
            
            .contact-right {
                padding: 30px 20px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .form-row .form-group {
                margin-bottom: 20px;
            }

            .logo {
                top: 20px;
                left: 20px;
            }

            .logo a {
                font-size: 20px;
            }

            .contact-info h1 {
                font-size: 2em;
            }

            .contact-info p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <div class="contact-left">
            <div class="logo">
                <a href="index.php">InfluConnect</a>
            </div>
            
            <div class="contact-info">
                <h1>Get in Touch</h1>
                <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                
                <div class="contact-details">
                    <div class="contact-item">
                        <i>📧</i>
                        <span>hello@influconnect.com</span>
                    </div>
                    <div class="contact-item">
                        <i>📞</i>
                        <span>+1 (555) 123-4567</span>
                    </div>
                    <div class="contact-item">
                        <i>📍</i>
                        <span>123 Creator Street, Digital City, DC 12345</span>
                    </div>
                    <div class="contact-item">
                        <i>🕒</i>
                        <span>Mon - Fri: 9:00 AM - 6:00 PM</span>
                    </div>
                </div>
                
              
            </div>
        </div>
        
        <div class="contact-right">
            <div class="contact-form">
                <h2>Send us a Message</h2>
                <p class="subtitle">We're here to help with any questions or concerns</p>
                
                <div id="success-message" class="success-message"></div>
                <div id="error-message" class="error-message"></div>
                
                <form id="contactForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" id="first_name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject_type">Subject Type</label>
                            <select id="subject_type" name="subject_type">
                                <option value="">Select a topic</option>
                                <option value="general">General Inquiry</option>
                                <option value="creator">Creator Support</option>
                                <option value="brand">Brand Partnership</option>
                                <option value="technical">Technical Support</option>
                                <option value="billing">Billing Question</option>
                                <option value="feedback">Feedback</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject" placeholder="Brief description of your inquiry" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" placeholder="Please provide details about your inquiry..." required></textarea>
                    </div>
                    
                    <button type="submit" class="contact-btn">Send Message</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const successDiv = document.getElementById('success-message');
            const errorDiv = document.getElementById('error-message');
            
            // Clear previous messages
            successDiv.style.display = 'none';
            errorDiv.style.display = 'none';
            
            // Get form data
            const formData = new FormData(this);
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            // Validation
            const requiredFields = ['first_name', 'last_name', 'email', 'subject', 'message'];
            let hasError = false;
            let errorMessage = '';
            
            for (let field of requiredFields) {
                if (!data[field] || data[field].trim() === '') {
                    hasError = true;
                    errorMessage = 'Please fill in all required fields.';
                    break;
                }
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
            
            // Simulate successful submission
            successDiv.textContent = 'Thank you for your message! We\'ll get back to you within 24 hours.';
            successDiv.style.display = 'block';
            successDiv.scrollIntoView({ behavior: 'smooth' });
            
            // Reset form
            this.reset();
            
            // In a real application, you would send the data to your server here
            console.log('Contact form data:', data);
        });
        
        // Add smooth animations to form elements
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
        
        // Auto-populate subject based on subject type
        document.getElementById('subject_type').addEventListener('change', function() {
            const subjectInput = document.getElementById('subject');
            const subjectMap = {
                'general': 'General Inquiry',
                'creator': 'Creator Support Request',
                'brand': 'Brand Partnership Inquiry',
                'technical': 'Technical Support Needed',
                'billing': 'Billing Question',
                'feedback': 'Feedback and Suggestions',
                'other': 'Other Inquiry'
            };
            
            if (this.value && subjectMap[this.value]) {
                subjectInput.value = subjectMap[this.value];
            }
        });
    </script>
</body>
</html>