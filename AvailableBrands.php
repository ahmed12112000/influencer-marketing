<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="assets/css/AvailableBrands.css"> <!-- Link to a.css -->  
<style>
  /* Base toast style */
.toast-notification {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background-color: #333;
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
    z-index: 9999;
}

.toast-notification.show {
    opacity: 1;
    transform: translateY(0);
}

/* Success (green) */
.toast-notification.success {
    background-color: #28a745;
}

/* Optional: Error (red) */
.toast-notification.error {
    background-color: #dc3545;
}

/* Optional: Info (blue) */
.toast-notification.info {
    background-color: #007bff;
}
 .logo {
            height: 40px;
            width: auto;
            transition: all 0.3s ease;
            max-width: 150px;
        }
    </style>

    <title>Brand Collaboration Dashboard</title>

    
</head>
<body>
    <div class="dashboard">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                 <img src="logo2.png" alt="InfluencerHub Logo" class="logo">
                <button class="toggle-btn" id="toggleSidebar">☰</button>
            </div>

            <nav class="nav-menu">
                <div class="nav-item" data-section="dashboard"  onclick="window.location.href='influencerdashboard.php'">
                    <span class="nav-icon">📊</span>
                    <span class="nav-text">Dashboard</span>   
                </div>
                <div class="nav-item active" data-section="brands">
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
                <div class="profile-card">
                    <div class="profile-avatar">JD</div>
                    <div class="profile-info" onclick="window.location.href='influencreupdateprofile.php'">
                        <div class="profile-name">John Doe</div>
                        <div class="profile-status">Update Profile</div>
                    </div>
                </div>
            </div>
        </div>

        <main class="main-content" id="mainContent">
            <div class="header">
                <h1 id="pageTitle">
                    <span id="titleIcon">🏢</span>
                    <span id="titleText">Available Brands</span>
                </h1>
                <div class="header-actions">
                    <div class="search-bar">
                        <input type="text" placeholder="Search brands or categories..." id="searchInput">
                    </div>
                    
                   <div class="filter-container">
                <button class="filter-btn" id="filterBtn">
                    <span>🔽</span>
                    Filter
                </button>
                <div class="filter-dropdown" id="filterDropdown" >
                    <div class="filter-group" >
                        <h4>Budget Range</h4>
                        <div class="filter-option" >
                            <input type="checkbox" id="budget1" checked>
                            <label for="budget1">$0 - $300</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="budget2" checked>
                            <label for="budget2">$300 - $600</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="budget3" checked>
                            <label for="budget3">$600+</label>
                        </div>
                    </div>
                    <div class="filter-group">
                        <h4>Follower Requirement</h4>
                        <div class="filter-option">
                            <input type="checkbox" id="followers1" checked>
                            <label for="followers1">5K+</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="followers2" checked>
                            <label for="followers2">10K+</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="followers3" checked>
                            <label for="followers3">50K+</label>
                        </div>
                    </div>
                    <button class="clear-filters" id="clearFilters">Clear All Filters</button>
                </div>
            </div>
            
            <!-- Optional: Backdrop overlay -->
            <div class="dropdown-overlay" id="dropdownOverlay"></div>
            
            <button class="notification-btn">
                🔔
                <span class="notification-badge">5</span>
            </button>
        </div>
    </div>

            <!-- Available Brands Section -->
            <div class="content-section active" id="brands">
                <div class="filter-tabs">
                    <button class="tab active" data-category="all">All Brands</button>
                    <button class="tab" data-category="technology">Technology</button>
                    <button class="tab" data-category="fashion">Fashion</button>
                    <button class="tab" data-category="beauty">Beauty</button>
                    <button class="tab" data-category="fitness">Fitness</button>
                </div>

                <div class="brands-grid" id="brandsGrid">
                    <div class="brand-card" data-category="technology">
                        <div class="brand-header">
                            <div class="brand-logo">TC</div>
                            <div class="brand-info">
                                <h3>TechCorp</h3>
                                <div class="brand-category">Technology & Gadgets</div>
                            </div>
                        </div>
                        <div class="brand-details">
                            <div class="brand-budget">$500 - $800</div>
                            <p class="brand-description">Looking for tech influencers to review our latest smartphone. Must have 5K+ followers and excellent engagement rates. Perfect for tech enthusiasts!</p>
                            <div class="brand-tags">
                                <span class="tag">Tech Review</span>
                                <span class="tag">Smartphone</span>
                                <span class="tag">5K+ Followers</span>
                            </div>
                        </div>
                        <div class="brand-actions">
                            <button class="btn btn-primary">Apply Now</button>
                            <button class="btn btn-secondary">View Details</button>
                        </div>
                    </div>

                    <div class="brand-card" data-category="fashion">
                        <div class="brand-header">
                            <div class="brand-logo">FB</div>
                            <div class="brand-info">
                                <h3>Fashion Forward</h3>
                                <div class="brand-category">Fashion & Lifestyle</div>
                            </div>
                        </div>
                        <div class="brand-details">
                            <div class="brand-budget">$300 - $500</div>
                            <p class="brand-description">Seeking fashion influencers for our summer collection launch. Looking for authentic content creators with strong style presence.</p>
                            <div class="brand-tags">
                                <span class="tag">Fashion</span>
                                <span class="tag">Summer Collection</span>
                                <span class="tag">Lifestyle</span>
                            </div>
                        </div>
                        <div class="brand-actions">
                            <button class="btn btn-primary">Apply Now</button>
                            <button class="btn btn-secondary">View Details</button>
                        </div>
                    </div>

                    <div class="brand-card" data-category="beauty">
                        <div class="brand-header">
                            <div class="brand-logo">HB</div>
                            <div class="brand-info">
                                <h3>Beauty Essentials</h3>
                                <div class="brand-category">Beauty & Wellness</div>
                            </div>
                        </div>
                        <div class="brand-details">
                            <div class="brand-budget">$200 - $400</div>
                            <p class="brand-description">Looking for beauty influencers to promote our new organic skincare line. Must create authentic, detailed reviews.</p>
                            <div class="brand-tags">
                                <span class="tag">Skincare</span>
                                <span class="tag">Organic</span>
                                <span class="tag">Beauty Review</span>
                            </div>
                        </div>
                        <div class="brand-actions">
                            <button class="btn btn-primary">Apply Now</button>
                            <button class="btn btn-secondary">View Details</button>
                        </div>
                    </div>

                    <div class="brand-card" data-category="fitness">
                        <div class="brand-header">
                            <div class="brand-logo">SF</div>
                            <div class="brand-info">
                                <h3>SportsFit Pro</h3>
                                <div class="brand-category">Health & Fitness</div>
                            </div>
                        </div>
                        <div class="brand-details">
                            <div class="brand-budget">$400 - $600</div>
                            <p class="brand-description">Seeking fitness influencers to promote our new workout gear collection. Looking for active lifestyle content creators.</p>
                            <div class="brand-tags">
                                <span class="tag">Fitness</span>
                                <span class="tag">Workout Gear</span>
                                <span class="tag">Active Lifestyle</span>
                            </div>
                        </div>
                        <div class="brand-actions">
                            <button class="btn btn-primary">Apply Now</button>
                            <button class="btn btn-secondary">View Details</button>
                        </div>
                    </div>

                    <div class="brand-card" data-category="technology">
                        <div class="brand-header">
                            <div class="brand-logo">GT</div>
                            <div class="brand-info">
                                <h3>GameTech Studios</h3>
                                <div class="brand-category">Gaming & Technology</div>
                            </div>
                        </div>
                        <div class="brand-details">
                            <div class="brand-budget">$600 - $900</div>
                            <p class="brand-description">Looking for gaming influencers to showcase our latest mobile game. Perfect for streamers and gaming content creators.</p>
                            <div class="brand-tags">
                                <span class="tag">Gaming</span>
                                <span class="tag">Mobile Game</span>
                                <span class="tag">Streaming</span>
                            </div>
                        </div>
                        <div class="brand-actions">
                            <button class="btn btn-primary">Apply Now</button>
                            <button class="btn btn-secondary">View Details</button>
                        </div>
                    </div>

                    <div class="brand-card" data-category="fashion">
                        <div class="brand-header">
                            <div class="brand-logo">UM</div>
                            <div class="brand-info">
                                <h3>Urban Mode</h3>
                                <div class="brand-category">Streetwear & Fashion</div>
                            </div>
                        </div>
                        <div class="brand-details">
                            <div class="brand-budget">$350 - $550</div>
                            <p class="brand-description">Streetwear brand looking for urban fashion influencers. Must have authentic street style and engaged audience.</p>
                            <div class="brand-tags">
                                <span class="tag">Streetwear</span>
                                <span class="tag">Urban Fashion</span>
                                <span class="tag">Authentic Style</span>
                            </div>
                        </div>
                        <div class="brand-actions">
                            <button class="btn btn-primary">Apply Now</button>
                            <button class="btn btn-secondary">View Details</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Requests Section -->
            <div class="content-section" id="messages">
                <div class="filter-tabs">
                    <button class="tab active" data-priority="all">All Messages</button>
                    <button class="tab" data-priority="urgent">Urgent</button>
                    <button class="tab" data-priority="pending">Pending</button>
                    <button class="tab" data-priority="replied">Replied</button>
                </div>

                <div class="messages-container" id="messagesContainer">
                    <div class="message-card" data-priority="urgent">
                        <div class="message-header">
                            <div>
                                <span class="message-sender">Sarah Johnson - TechCorp</span>
                                <span class="priority-badge">Urgent</span>
                            </div>
                            <span class="message-time">2 hours ago</span>
                        </div>
                        <div class="message-meta">
                            <div class="meta-item">
                                <span>💰</span>
                                <span>$600</span>
                            </div>
                            <div class="meta-item">
                                <span>📱</span>
                                <span>Instagram + Story</span>
                            </div>
                            <div class="meta-item">
                                <span>⏰</span>
                                <span>Expires in 24h</span>
                            </div>
                        </div>
                        <div class="message-content">
                            Hi John! We'd love to collaborate with you on our upcoming smartphone launch. We're offering $600 for an Instagram post and story featuring our new device. The campaign needs to go live within the next week. Are you interested in this opportunity?
                        </div>
                        <div class="message-actions">
                            <button class="btn btn-primary">Accept Offer</button>
                            <button class="btn btn-secondary">Negotiate</button>
                        </div>
                    </div>

                    <div class="message-card" data-priority="pending">
                        <div class="message-header">
                            <div>
                                <span class="message-sender">Mike Chen - Fashion Forward</span>
                                <span class="priority-badge medium">Medium</span>
                            </div>
                            <span class="message-time">1 day ago</span>
                        </div>
                        <div class="message-meta">
                            <div class="meta-item">
                                <span>💰</span>
                                <span>$400</span>
                            </div>
                            <div class="meta-item">
                                <span>🎥</span>
                                <span>TikTok Video</span>
                            </div>
                            <div class="meta-item">
                                <span>👥</span>
                                <span>10K+ reach expected</span>
                            </div>
                        </div>
                                             <div class="message-actions">
                            <button class="btn btn-primary">Accept Offer</button>
                            <button class="btn btn-secondary">Negotiate</button>
                        </div>
                    </div>

                    <div class="message-card" data-priority="replied">
                        <div class="message-header">
                            <div>
                                <span class="message-sender">Lisa Wong - Beauty Essentials</span>
                                <span class="priority-badge low">Low</span>
                            </div>
                            <span class="message-time">3 days ago</span>
                        </div>
                        <div class="message-meta">
                            <div class="meta-item">
                                <span>💰</span>
                                <span>$300</span>
                            </div>
                            <div class="meta-item">
                                <span>📸</span>
                                <span>Instagram Post</span>
                            </div>
                            <div class="meta-item">
                                <span>🏷️</span>
                                <span>#Sponsored</span>
                            </div>
                        </div>
                        <div class="message-content">
                            Thank you for your interest in our skincare line! We've reviewed your proposal and would like to proceed with the collaboration. Please confirm if you're still available for the Instagram post we discussed.
                        </div>
                        <div class="message-actions">
                            <button class="btn btn-primary">Confirm</button>
                            <button class="btn btn-secondary">Reschedule</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Application Modal -->
    <div class="modal-overlay" id="applicationModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Apply to Collaborate</h3>
                <button class="close-btn" id="closeModal">&times;</button>
            </div>
            <div class="modal-content">
                <form class="application-form" id="applicationForm">
                    <div class="form-group">
                        <label for="brandName">Brand Name</label>
                        <input type="text" id="brandName" readonly>
                    </div>
                    <div class="form-group">
                        <label for="proposal">Your Proposal</label>
                        <textarea id="proposal" placeholder="Describe your approach for this collaboration..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="rate">Your Rate ($)</label>
                        <input type="number" id="rate" placeholder="Enter your proposed rate" required>
                    </div>
                    <div class="form-group">
                        <label for="platform">Preferred Platform</label>
                        <select id="platform" required>
                            <option value="">Select platform</option>
                            <option value="instagram">Instagram</option>
                            <option value="youtube">YouTube</option>
                            <option value="tiktok">TikTok</option>
                            <option value="twitter">Twitter</option>
                            <option value="blog">Blog</option>
                        </select>
                    </div>
                    <div class="modal-actions">
                        <button type="submit" class="btn btn-primary">Submit Application</button>
                        <button type="button" class="btn btn-secondary" id="cancelApplication">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

   <div class="toast" id="toastNotification">
        <span class="toast-icon">✓</span>
        <span class="toast-message">Your request was sent successfully!</span>
    </div>

  <!-- Toast Notification -->
    <div class="toast" id="toastNotification">
        <span class="toast-icon">✓</span>
        <span class="toast-message">Your request was sent successfully!</span>
    </div>
 <script>
    // Enhanced dropdown positioning
document.addEventListener('DOMContentLoaded', function() {
    
    // ===============================
    // DROPDOWN FUNCTIONALITY - FIXED
    // ===============================
    const filterBtn = document.getElementById('filterBtn');
    const filterDropdown = document.getElementById('filterDropdown');
    const dropdownOverlay = document.getElementById('dropdownOverlay');
    const clearFilters = document.getElementById('clearFilters');

    // Enhanced dropdown positioning
    function positionDropdown() {
        if (!filterDropdown || !filterBtn) return;
        
        const dropdown = filterDropdown;
        const btnRect = filterBtn.getBoundingClientRect();
        const dropdownHeight = dropdown.scrollHeight || 200; // fallback height
        
        // Check if there's enough space above
        if (btnRect.top > dropdownHeight + 20) {
            // Enough space - position above
            dropdown.style.bottom = 'calc(100% + 10px)';
            dropdown.style.top = 'auto';
        } else {
            // Not enough space - position below
            dropdown.style.bottom = 'auto';
            dropdown.style.top = 'calc(100% + 10px)';
        }
    }

    // Open dropdown function
    function openDropdown() {
        if (!filterDropdown || !filterBtn) return;
        
        positionDropdown(); // Calculate position before showing
        filterDropdown.classList.add('show');
        
        // Handle overlay if it exists
        if (dropdownOverlay) {
            dropdownOverlay.classList.add('active');
        }
        
        filterBtn.style.background = '#5855eb';
        
        // Add resize listener while dropdown is open
        window.addEventListener('resize', positionDropdown);
    }

    // Close dropdown function
    function closeDropdown() {
        if (!filterDropdown || !filterBtn) return;
        
        filterDropdown.classList.remove('show');
        
        // Handle overlay if it exists
        if (dropdownOverlay) {
            dropdownOverlay.classList.remove('active');
        }
        
        filterBtn.style.background = '#6366f1';
        
        // Remove resize listener
        window.removeEventListener('resize', positionDropdown);
    }

    // Toggle dropdown on button click
    if (filterBtn) {
        filterBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isActive = filterDropdown.classList.contains('show');
            
            if (isActive) {
                closeDropdown();
            } else {
                openDropdown();
            }
        });
    }

    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (filterDropdown && filterBtn && 
            !filterDropdown.contains(e.target) && 
            !filterBtn.contains(e.target)) {
            closeDropdown();
        }
    });

    // Close when clicking overlay (if it exists)
    if (dropdownOverlay) {
        dropdownOverlay.addEventListener('click', closeDropdown);
    }

    // Prevent dropdown from closing when clicking inside it
    if (filterDropdown) {
        filterDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Clear filters functionality
    if (clearFilters) {
        clearFilters.addEventListener('click', function() {
            const checkboxes = filterDropdown.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        });
    }

    // Close dropdown on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDropdown();
        }
    });

    // ===============================
    // SIDEBAR TOGGLE
    // ===============================
    const toggleSidebar = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    if (toggleSidebar && sidebar && mainContent) {
        toggleSidebar.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        });
    }

    // ===============================
    // NAVIGATION TABS
    // ===============================
    const navItems = document.querySelectorAll('.nav-item');
    const contentSections = document.querySelectorAll('.content-section');

    navItems.forEach(item => {
        item.addEventListener('click', () => {
            navItems.forEach(nav => nav.classList.remove('active'));
            item.classList.add('active');
            
            const sectionId = item.getAttribute('data-section');
            contentSections.forEach(section => {
                section.classList.remove('active');
                if(section.id === sectionId) {
                    section.classList.add('active');
                    const titleText = document.getElementById('titleText');
                    const titleIcon = document.getElementById('titleIcon');
                    
                    if (titleText) {
                        titleText.textContent = 
                            sectionId === 'brands' ? 'Available Brands' : 
                            sectionId === 'messages' ? 'Message Requests' : 
                            sectionId === 'analytics' ? 'Analytics' : 'Dashboard';
                    }
                    
                    if (titleIcon) {
                        titleIcon.textContent = 
                            sectionId === 'brands' ? '🏢' : 
                            sectionId === 'messages' ? '💬' : 
                            sectionId === 'analytics' ? '📈' : '📊';
                    }
                }
            });
        });
    });

    // ===============================
    // CATEGORY FILTER TABS
    // ===============================
    const categoryTabs = document.querySelectorAll('.filter-tabs .tab');
    const brandCards = document.querySelectorAll('.brand-card');

    categoryTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            categoryTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            
            const category = tab.getAttribute('data-category');
            brandCards.forEach(card => {
                if(category === 'all' || card.getAttribute('data-category') === category) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });

    // ===============================
    // SEARCH FUNCTIONALITY
    // ===============================
    const searchInput = document.getElementById('searchInput');
    
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const searchTerm = searchInput.value.toLowerCase();
            brandCards.forEach(card => {
                const brandName = card.querySelector('h3')?.textContent.toLowerCase() || '';
                const brandDesc = card.querySelector('.brand-description')?.textContent.toLowerCase() || '';
                const brandTags = Array.from(card.querySelectorAll('.tag')).map(tag => tag.textContent.toLowerCase()).join(' ');
                
                if(brandName.includes(searchTerm) || brandDesc.includes(searchTerm) || brandTags.includes(searchTerm)) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    }

    // ===============================
    // MODAL AND TOAST FUNCTIONALITY
    // ===============================
    const applicationModal = document.getElementById('applicationModal');
    const closeModal = document.getElementById('closeModal');
    const cancelApplication = document.getElementById('cancelApplication');
    const applicationForm = document.getElementById('applicationForm');
    const toastNotification = document.getElementById('toastNotification');

    // Function to show toast notification
   function showToast(message = "Your request was sent successfully!", type = "success") {
    if (!toastNotification) return;

    const toastMessage = toastNotification.querySelector('.toast-message');
    if (toastMessage) {
        toastMessage.textContent = message;
    }

    // Remove previous type classes (like success, error, etc.)
    toastNotification.classList.remove('success', 'error', 'info');
    
    // Add the current type class (e.g., success)
    toastNotification.classList.add(type);
    toastNotification.classList.add('show');

    setTimeout(() => {
        toastNotification.classList.remove('show', type);
    }, 3000);
}


    // Apply Now button event listeners
    document.querySelectorAll('.btn-primary').forEach(btn => {
        if(btn.textContent === 'Apply Now') {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const brandCard = btn.closest('.brand-card');
                const brandName = brandCard?.querySelector('h3')?.textContent || '';
                
                const brandNameInput = document.getElementById('brandName');
                if (brandNameInput) {
                    brandNameInput.value = brandName;
                }
                
                if (applicationModal) {
                    applicationModal.classList.add('show');
                }
            });
        }
    });

    // Close modal events
    if (closeModal && applicationModal) {
        closeModal.addEventListener('click', () => {
            applicationModal.classList.remove('show');
        });
    }

    if (cancelApplication && applicationModal) {
        cancelApplication.addEventListener('click', () => {
            applicationModal.classList.remove('show');
        });
    }

    // Close modal when clicking outside
    if (applicationModal) {
        applicationModal.addEventListener('click', (e) => {
            if (e.target === applicationModal) {
                applicationModal.classList.remove('show');
            }
        });
    }

    // Form submission handler
    if (applicationForm) {
        applicationForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Get form values
            const proposal = document.getElementById('proposal')?.value.trim() || '';
            const rate = document.getElementById('rate')?.value.trim() || '';
            const platform = document.getElementById('platform')?.value.trim() || '';
            
            // Validate form fields
            if (!proposal || !rate || !platform) {
                alert('Please fill in all required fields.');
                return;
            }
            
            // Validate rate is a positive number
            if (isNaN(rate) || parseFloat(rate) <= 0) {
                alert('Please enter a valid rate.');
                return;
            }
            
            // Get submit button and show loading state
            const submitBtn = applicationForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';
                
                // Simulate form submission with API call
                setTimeout(() => {
                    // Simulate API response (replace with actual API call)
                    const success = Math.random() > 0.1; // 90% success rate for demo
                    
                    if (success) {
                        // Close modal
                        if (applicationModal) {
                            applicationModal.classList.remove('show');
                        }
                        
                        // Show success toast
                        showToast("Your application was submitted successfully!");
                        
                        // Reset form
                        applicationForm.reset();
                    } else {
                        // Handle error
                        alert('Submission failed. Please try again.');
                    }
                    
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                    
                }, 1500); // 1.5 second delay to simulate network request
            }
        });
    }

    // ===============================
    // MESSAGE PRIORITY TABS
    // ===============================
    const messageTabs = document.querySelectorAll('#messages .tab');
    const messageCards = document.querySelectorAll('.message-card');

    messageTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            messageTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            
            const priority = tab.getAttribute('data-priority');
            messageCards.forEach(card => {
                if(priority === 'all' || card.getAttribute('data-priority') === priority) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // ===============================
    // MESSAGE ACTION BUTTONS
    // ===============================
    document.querySelectorAll('.message-card .btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const action = btn.textContent.toLowerCase();
            
            if (action.includes('accept') || action.includes('confirm')) {
                showToast("Message accepted successfully!");
            } else if (action.includes('negotiate') || action.includes('reschedule')) {
                showToast("Negotiation request sent!");
            }
        });
    });

    // ===============================
    // FILTER CHECKBOXES (Optional)
    // ===============================
    if (filterDropdown) {
        const checkboxes = filterDropdown.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Add your filter logic here
                console.log('Filter changed:', this.value, this.checked);
                
                // Example: Apply filters to brand cards
                // You can implement your specific filtering logic here
            });
        });
    }
});
    </script>
</body>
</html>