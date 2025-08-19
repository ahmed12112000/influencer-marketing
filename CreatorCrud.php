<?php
// CreatorCrud.php - Creator CRUD Operations for PostgreSQL

require_once 'config.php';
require_once 'Database.php';

class CreatorCrud {
    private $db;
    private $pdo;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
        $this->createCreatorsTable();
    }
    
    /**
     * Create the creators table if it doesn't exist
     */
    private function createCreatorsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS creators (
            id SERIAL PRIMARY KEY,
            full_name VARCHAR(255) NOT NULL,
            username VARCHAR(100) UNIQUE NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            phone VARCHAR(20),
            password_hash VARCHAR(255) NOT NULL,
            niche VARCHAR(50) NOT NULL,
            location VARCHAR(255),
            platforms TEXT[], -- Array of selected platforms
            instagram_url VARCHAR(255),
            youtube_url VARCHAR(255),
            tiktok_url VARCHAR(255),
            twitter_url VARCHAR(255),
            facebook_url VARCHAR(255),
            linkedin_url VARCHAR(255),
            snapchat_url VARCHAR(255),
            twitch_url VARCHAR(255),
            total_followers INTEGER DEFAULT 0,
            engagement_rate DECIMAL(5,2) DEFAULT 0.00,
            audience_demographics TEXT,
            bio TEXT,
            collaboration_types VARCHAR(50),
            rate_per_post DECIMAL(10,2) DEFAULT 0.00,
            portfolio_sharing BOOLEAN DEFAULT FALSE,
            email_notifications BOOLEAN DEFAULT TRUE,
            email_verified BOOLEAN DEFAULT FALSE,
            verification_token VARCHAR(100),
            status VARCHAR(20) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        try {
            $this->pdo->exec($sql);
            
            // Create indexes for better performance
            $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_creators_email ON creators(email)");
            $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_creators_username ON creators(username)");
            $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_creators_niche ON creators(niche)");
            $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_creators_status ON creators(status)");
            
        } catch (PDOException $e) {
            throw new Exception("Failed to create creators table: " . $e->getMessage());
        }
    }
    
    /**
     * Register a new creator
     */
    public function registerCreator($data) {
        try {
            // Validate required fields
            $requiredFields = ['full_name', 'username', 'email', 'password', 'niche'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Field '{$field}' is required.");
                }
            }
            
            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format.");
            }
            
            // Validate password strength
            if (strlen($data['password']) < 6) {
                throw new Exception("Password must be at least 6 characters long.");
            }
            
            // Check if email already exists
            if ($this->emailExists($data['email'])) {
                throw new Exception("Email address already registered.");
            }
            
            // Check if username already exists
            if ($this->usernameExists($data['username'])) {
                throw new Exception("Username already taken.");
            }
            
            // Hash password
            $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
            
            // Generate verification token
            $verificationToken = bin2hex(random_bytes(32));
            
            // Convert platforms array to PostgreSQL array format
            $platforms = isset($data['platforms']) ? $data['platforms'] : [];
            $platformsArray = '{' . implode(',', array_map(function($platform) {
                return '"' . $platform . '"';
            }, $platforms)) . '}';
            
            // Prepare SQL statement
            $sql = "INSERT INTO creators (
                full_name, username, email, phone, password_hash, niche, location,
                platforms, instagram_url, youtube_url, tiktok_url, twitter_url,
                facebook_url, linkedin_url, snapchat_url, twitch_url,
                total_followers, engagement_rate, audience_demographics, bio,
                collaboration_types, rate_per_post, portfolio_sharing,
                email_notifications, verification_token
            ) VALUES (
                :full_name, :username, :email, :phone, :password_hash, :niche, :location,
                :platforms, :instagram_url, :youtube_url, :tiktok_url, :twitter_url,
                :facebook_url, :linkedin_url, :snapchat_url, :twitch_url,
                :total_followers, :engagement_rate, :audience_demographics, :bio,
                :collaboration_types, :rate_per_post, :portfolio_sharing,
                :email_notifications, :verification_token
            ) RETURNING id";
            
            $stmt = $this->pdo->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':full_name', $data['full_name']);
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':password_hash', $passwordHash);
            $stmt->bindParam(':niche', $data['niche']);
            $stmt->bindParam(':location', $data['location']);
            $stmt->bindParam(':platforms', $platformsArray);
            $stmt->bindParam(':instagram_url', $data['instagram_url']);
            $stmt->bindParam(':youtube_url', $data['youtube_url']);
            $stmt->bindParam(':tiktok_url', $data['tiktok_url']);
            $stmt->bindParam(':twitter_url', $data['twitter_url']);
            $stmt->bindParam(':facebook_url', $data['facebook_url']);
            $stmt->bindParam(':linkedin_url', $data['linkedin_url']);
            $stmt->bindParam(':snapchat_url', $data['snapchat_url']);
            $stmt->bindParam(':twitch_url', $data['twitch_url']);
            
            $totalFollowers = isset($data['total_followers']) ? (int)$data['total_followers'] : 0;
            $engagementRate = isset($data['engagement_rate']) ? (float)$data['engagement_rate'] : 0.00;
            $ratePerPost = isset($data['rate_per_post']) ? (float)$data['rate_per_post'] : 0.00;
            $portfolioSharing = isset($data['portfolio_sharing']) ? true : false;
            $emailNotifications = isset($data['email_notifications']) ? true : false;
            
            $stmt->bindParam(':total_followers', $totalFollowers, PDO::PARAM_INT);
            $stmt->bindParam(':engagement_rate', $engagementRate);
            $stmt->bindParam(':audience_demographics', $data['audience_demographics']);
            $stmt->bindParam(':bio', $data['bio']);
            $stmt->bindParam(':collaboration_types', $data['collaboration_types']);
            $stmt->bindParam(':rate_per_post', $ratePerPost);
            $stmt->bindParam(':portfolio_sharing', $portfolioSharing, PDO::PARAM_BOOL);
            $stmt->bindParam(':email_notifications', $emailNotifications, PDO::PARAM_BOOL);
            $stmt->bindParam(':verification_token', $verificationToken);
            
            $stmt->execute();
            $creatorId = $stmt->fetchColumn();
            
            // Send verification email (you can implement this later)
            // $this->sendVerificationEmail($data['email'], $verificationToken);
            
            return [
                'success' => true,
                'message' => 'Registration successful! Please check your email for verification.',
                'creator_id' => $creatorId
            ];
            
        } catch (PDOException $e) {
            if ($e->getCode() === '23505') { // Unique constraint violation
                return [
                    'success' => false,
                    'message' => 'Email or username already exists.'
                ];
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    /**
     * Check if email exists
     */
    private function emailExists($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM creators WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Check if username exists
     */
    private function usernameExists($username) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM creators WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Get creator by ID
     */
    public function getCreatorById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM creators WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception("Error fetching creator: " . $e->getMessage());
        }
    }
    
    /**
     * Get creator by email
     */
    public function getCreatorByEmail($email) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM creators WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception("Error fetching creator: " . $e->getMessage());
        }
    }
    
    /**
     * Get creator by username
     */
    public function getCreatorByUsername($username) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM creators WHERE username = ?");
            $stmt->execute([$username]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception("Error fetching creator: " . $e->getMessage());
        }
    }
    
    /**
     * Verify email with token
     */
    public function verifyEmail($token) {
        try {
            $stmt = $this->pdo->prepare("UPDATE creators SET email_verified = TRUE, verification_token = NULL WHERE verification_token = ?");
            $stmt->execute([$token]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception("Error verifying email: " . $e->getMessage());
        }
    }
    
    /**
     * Update creator profile
     */
    public function updateCreator($id, $data) {
        try {
            $allowedFields = [
                'full_name', 'phone', 'location', 'instagram_url', 'youtube_url',
                'tiktok_url', 'twitter_url', 'facebook_url', 'linkedin_url',
                'snapchat_url', 'twitch_url', 'total_followers', 'engagement_rate',
                'audience_demographics', 'bio', 'collaboration_types', 'rate_per_post',
                'portfolio_sharing', 'email_notifications'
            ];
            
            $updateFields = [];
            $values = [];
            
            foreach ($data as $field => $value) {
                if (in_array($field, $allowedFields)) {
                    $updateFields[] = "$field = :$field";
                    $values[$field] = $value;
                }
            }
            
            if (empty($updateFields)) {
                throw new Exception("No valid fields to update.");
            }
            
            $sql = "UPDATE creators SET " . implode(', ', $updateFields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $values['id'] = $id;
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception("Error updating creator: " . $e->getMessage());
        }
    }
    
    /**
     * Delete creator
     */
    public function deleteCreator($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM creators WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception("Error deleting creator: " . $e->getMessage());
        }
    }
    
    /**
     * Get all creators with pagination
     */
    public function getAllCreators($page = 1, $limit = 10, $filters = []) {
        try {
            $offset = ($page - 1) * $limit;
            $whereClause = "WHERE 1=1";
            $params = [];
            
            // Apply filters
            if (!empty($filters['niche'])) {
                $whereClause .= " AND niche = :niche";
                $params['niche'] = $filters['niche'];
            }
            
            if (!empty($filters['status'])) {
                $whereClause .= " AND status = :status";
                $params['status'] = $filters['status'];
            }
            
            if (!empty($filters['min_followers'])) {
                $whereClause .= " AND total_followers >= :min_followers";
                $params['min_followers'] = $filters['min_followers'];
            }
            
            $sql = "SELECT * FROM creators $whereClause ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error fetching creators: " . $e->getMessage());
        }
    }
    
    /**
     * Get creator count
     */
    public function getCreatorCount($filters = []) {
        try {
            $whereClause = "WHERE 1=1";
            $params = [];
            
            // Apply same filters as getAllCreators
            if (!empty($filters['niche'])) {
                $whereClause .= " AND niche = :niche";
                $params['niche'] = $filters['niche'];
            }
            
            if (!empty($filters['status'])) {
                $whereClause .= " AND status = :status";
                $params['status'] = $filters['status'];
            }
            
            if (!empty($filters['min_followers'])) {
                $whereClause .= " AND total_followers >= :min_followers";
                $params['min_followers'] = $filters['min_followers'];
            }
            
            $sql = "SELECT COUNT(*) FROM creators $whereClause";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Error counting creators: " . $e->getMessage());
        }
    }
    
    /**
     * Search creators
     */
    public function searchCreators($query, $page = 1, $limit = 10) {
        try {
            $offset = ($page - 1) * $limit;
            $searchQuery = "%$query%";
            
            $sql = "SELECT * FROM creators 
                    WHERE full_name ILIKE :query 
                    OR username ILIKE :query 
                    OR email ILIKE :query 
                    OR niche ILIKE :query 
                    OR location ILIKE :query
                    ORDER BY created_at DESC 
                    LIMIT :limit OFFSET :offset";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':query', $searchQuery);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error searching creators: " . $e->getMessage());
        }
    }
    
    /**
     * Authenticate creator login
     */
    public function authenticateCreator($email, $password) {
        try {
            $creator = $this->getCreatorByEmail($email);
            
            if (!$creator) {
                return [
                    'success' => false,
                    'message' => 'Invalid email or password.'
                ];
            }
            
            if (!password_verify($password, $creator['password_hash'])) {
                return [
                    'success' => false,
                    'message' => 'Invalid email or password.'
                ];
            }
            
            if (!$creator['email_verified']) {
                return [
                    'success' => false,
                    'message' => 'Please verify your email address first.'
                ];
            }
            
            return [
                'success' => true,
                'creator' => $creator,
                'message' => 'Login successful.'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update creator status
     */
    public function updateCreatorStatus($id, $status) {
        try {
            $validStatuses = ['pending', 'active', 'suspended', 'rejected'];
            
            if (!in_array($status, $validStatuses)) {
                throw new Exception("Invalid status value.");
            }
            
            $stmt = $this->pdo->prepare("UPDATE creators SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
            $stmt->execute(['status' => $status, 'id' => $id]);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception("Error updating creator status: " . $e->getMessage());
        }
    }
}

// Handle AJAX requests if this file is called directly
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        $creatorCrud = new CreatorCrud();
        
        switch ($_POST['action']) {
            case 'register':
                $result = $creatorCrud->registerCreator($_POST);
                echo json_encode($result);
                break;
                
            case 'login':
                if (empty($_POST['email']) || empty($_POST['password'])) {
                    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
                    break;
                }
                
                $result = $creatorCrud->authenticateCreator($_POST['email'], $_POST['password']);
                echo json_encode($result);
                break;
                
            case 'verify_email':
                if (empty($_POST['token'])) {
                    echo json_encode(['success' => false, 'message' => 'Verification token is required.']);
                    break;
                }
                
                $verified = $creatorCrud->verifyEmail($_POST['token']);
                echo json_encode([
                    'success' => $verified,
                    'message' => $verified ? 'Email verified successfully.' : 'Invalid verification token.'
                ]);
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action.']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    
    exit;
}
?>