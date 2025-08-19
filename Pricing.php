<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing Plans</title>
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
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
            z-index: 10;
        }

        .logo:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            background: rgba(255, 255, 255, 0.15);
        }

        .home-logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 60px;
            height: 60px;
            background:Transparent;
            backdrop-filter: blur(10px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
            z-index: 10;
            object-fit: cover;
        }

        .home-logo:hover {
            transform: scale(1.1) rotate(-5deg);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            background: rgba(255, 255, 255, 0.15);
        }

        .header {
            text-align: center;
            margin-bottom: 60px;
            color: white;
        }

        .header h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .pricing-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            flex-direction: column;
            min-height: 500px;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .pricing-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .pricing-card:hover::before {
            opacity: 1;
        }

        .popular {
            transform: scale(1.05);
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .popular-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
        }

        .plan-name {
            font-size: 1.8rem;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .plan-price {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 5px;
            background: linear-gradient(45deg, #ffffff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .plan-period {
            font-size: 1rem;
            opacity: 0.8;
            margin-bottom: 30px;
        }

        .features {
            list-style: none;
            margin-bottom: 40px;
            flex-grow: 1;
        }

        .features li {
            padding: 10px 0;
            position: relative;
            padding-left: 30px;
        }

        .features li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #4ecdc4;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .cta-button {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .popular .cta-button {
            background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
        }

        .guarantee {
            text-align: center;
            margin-top: 40px;
            color: white;
            opacity: 0.9;
        }

        .guarantee p {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .guarantee small {
            opacity: 0.7;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .float-element {
            position: absolute;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 3s ease-in-out infinite;
        }

        .float-element:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .float-element:nth-child(2) { top: 60%; left: 90%; animation-delay: 1s; }
        .float-element:nth-child(3) { top: 40%; left: 20%; animation-delay: 2s; }
        .float-element:nth-child(4) { top: 80%; left: 80%; animation-delay: 0.5s; }
        .float-element:nth-child(5) { top: 10%; left: 70%; animation-delay: 1.5s; }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .pricing-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .popular {
                transform: none;
            }
        }
        .logo-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover; /* ensures the image fills the circle without distortion */
}
    </style>
</head>
<body>
    <div class="home-logo">
<a href="index.php">
  <img src="imagelogo.jpg" alt="Logo" class="logo-img">
</a>
    </div>

    <div class="floating-elements">
        <div class="float-element"></div>
        <div class="float-element"></div>
        <div class="float-element"></div>
        <div class="float-element"></div>
        <div class="float-element"></div>
    </div>

    <div class="container">
        <div class="header">
            <h1>Choose Your Plan</h1>
            <p>Select the perfect plan for your needs. All plans include our core features with flexible scaling options.</p>
        </div>

        <div class="pricing-grid">
            <div class="pricing-card">
                <h2 class="plan-name">Starter</h2>
                <div class="plan-price">$9</div>
                <div class="plan-period">per month</div>
                <ul class="features">
                    <li>Up to 5 projects</li>
                    <li>10GB storage</li>
                    <li>Basic support</li>
                    <li>Mobile app access</li>
                    <li>Standard templates</li>
                </ul>
                <button class="cta-button">Get Started</button>
            </div>

            <div class="pricing-card popular">
                <div class="popular-badge">Most Popular</div>
                <h2 class="plan-name">Professional</h2>
                <div class="plan-price">$29</div>
                <div class="plan-period">per month</div>
                <ul class="features">
                    <li>Unlimited projects</li>
                    <li>100GB storage</li>
                    <li>Priority support</li>
                    <li>Advanced analytics</li>
                    <li>Custom templates</li>
                    <li>Team collaboration</li>
                </ul>
                <button class="cta-button">Start Free Trial</button>
            </div>

            <div class="pricing-card">
                <h2 class="plan-name">Enterprise</h2>
                <div class="plan-price">$99</div>
                <div class="plan-period">per month</div>
                <ul class="features">
                    <li>Unlimited everything</li>
                    <li>1TB storage</li>
                    <li>24/7 dedicated support</li>
                    <li>Advanced security</li>
                    <li>Custom integrations</li>
                    <li>API access</li>
                    <li>White-label options</li>
                </ul>
                <button class="cta-button">Contact Sales</button>
            </div>
        </div>

        <div class="guarantee">
            <p>💯 30-Day Money-Back Guarantee</p>
            <small>No questions asked. Cancel anytime.</small>
        </div>
    </div>

    <script>
        // Add smooth hover effects and animations
        document.querySelectorAll('.pricing-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                if (this.classList.contains('popular')) {
                    this.style.transform = 'translateY(0) scale(1.05)';
                } else {
                    this.style.transform = 'translateY(0) scale(1)';
                }
            });
        });

        // Add button click animations
        document.querySelectorAll('.cta-button').forEach(button => {
            button.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });

        // Add floating animation to elements
        function createFloatingElements() {
            const container = document.querySelector('.floating-elements');
            for (let i = 0; i < 10; i++) {
                const element = document.createElement('div');
                element.className = 'float-element';
                element.style.left = Math.random() * 100 + '%';
                element.style.top = Math.random() * 100 + '%';
                element.style.animationDelay = Math.random() * 3 + 's';
                element.style.animationDuration = (Math.random() * 3 + 2) + 's';
                container.appendChild(element);
            }
        }

        createFloatingElements();
    </script>
</body>
</html>