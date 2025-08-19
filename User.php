<?php
// User.php - User Authentication and CRUD Class

class User {
    private $db;
    private $pdo;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }
    
    // Create tables if they don't exist
    public function createTables() {
        $this->createCreatorsTable();
        $this->createBrandsTable();
        $this->createLoginAttemptsTable();
        
        // Handle migration for existing tables
        $this->migrateExistingTables();
    }
    
    // Method to handle migration of existing tables
    private function migrateExistingTables() {
        try {
            // Use the Database class migration method
            $this->db->migrateExistingTables();
        } catch (Exception $e) {
            // Log error but don't break the application
            error_log("Migration warning: " . $e->getMessage());
        }
    }
    
    private function createCreatorsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS creators (
            id SERIAL PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            first_name VARCHAR(100),
            last_name VARCHAR(100),
            bio TEXT,
            profile_image VARCHAR(255),
            social_platforms JSONB,
            follower_count INTEGER DEFAULT 0,
            engagement_rate DECIMAL(5,2) DEFAULT 0.00,
            niche VARCHAR(100),
            location VARCHAR(100),
            phone VARCHAR(20),
            status VARCHAR(20) DEFAULT 'active',
            email_verified BOOLEAN DEFAULT FALSE,
            verification_token VARCHAR(100),
            remember_token VARCHAR(100),
            last_login TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        try {
            $this->pdo->exec($sql);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Failed to create creators table: " . $e->getMessage());
        }
    }
    
    private function createBrandsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS brands (
            id SERIAL PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            company_name VARCHAR(255) NOT NULL,
            contact_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            website VARCHAR(255),
            industry VARCHAR(50),
            company_size VARCHAR(20),
            description TEXT,
            logo VARCHAR(255),
            status VARCHAR(20) DEFAULT 'active',
            email_verified BOOLEAN DEFAULT FALSE,
            verification_token VARCHAR(100),
            remember_token VARCHAR(100),
            last_login TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        try {
            $this->pdo->exec($sql);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Failed to create brands table: " . $e->getMessage());
        }
    }
    
    private function createLoginAttemptsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS login_attempts (
            id SERIAL PRIMARY KEY,
            identifier VARCHAR(255) NOT NULL,
            ip_address INET,
            user_type VARCHAR(20),
            attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            success BOOLEAN DEFAULT FALSE
        )";
        
        try {
            $this->pdo->exec($sql);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Failed to create login_attempts table: " . $e->getMessage());
        }
    }
    
    // Helper function to generate username from company name
    private function generateUsernameFromCompanyName($companyName) {
        // Convert to lowercase, remove special characters, replace spaces with underscores
        $username = strtolower(trim($companyName));
        $username = preg_replace('/[^a-z0-9\s]/', '', $username);
        $username = preg_replace('/\s+/', '_', $username);
        $username = substr($username, 0, 50); // Limit to 50 characters
        
        // Ensure uniqueness
        return $this->ensureUniqueUsername($username, 'brands');
    }
    
    // Helper function to generate username from full name
    private function generateUsernameFromFullName($firstName, $lastName) {
        $fullName = trim($firstName . ' ' . $lastName);
        
        // Convert to lowercase, remove special characters, replace spaces with underscores
        $username = strtolower($fullName);
        $username = preg_replace('/[^a-z0-9\s]/', '', $username);
        $username = preg_replace('/\s+/', '_', $username);
        $username = substr($username, 0, 50); // Limit to 50 characters
        
        // Ensure uniqueness
        return $this->ensureUniqueUsername($username, 'creators');
    }
    
    // Helper function to ensure username uniqueness
    private function ensureUniqueUsername($baseUsername, $table) {
        $username = $baseUsername;
        $counter = 1;
        
        while ($this->usernameExists($username, $table)) {
            $username = $baseUsername . '_' . $counter;
            $counter++;
            
            // Prevent infinite loop
            if ($counter > 1000) {
                $username = $baseUsername . '_' . uniqid();
                break;
            }
        }
        
        return $username;
    }
    
    // Check if username exists in specific table
    private function usernameExists($username, $table) {
        $sql = "SELECT 1 FROM {$table} WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetchColumn() !== false;
    }
    
    // Authentication Methods
    public function authenticate($username, $password) {
        // Check if user is locked out
        if ($this->isLockedOut($username)) {
            return ['success' => false, 'message' => 'Account temporarily locked due to multiple failed attempts.'];
        }
        
        // Try to find user in creators table first
        $user = $this->findUserByUsername($username, 'creators');
        $userType = 'creator';
        
        // If not found in creators, try brands table
        if (!$user) {
            $user = $this->findUserByUsername($username, 'brands');
            $userType = 'brand';
        }
        
        // If still not found, try by email
        if (!$user) {
            $user = $this->findUserByEmail($username, 'creators');
            $userType = 'creator';
        }
        
        if (!$user) {
            $user = $this->findUserByEmail($username, 'brands');
            $userType = 'brand';
        }
        
        // Record login attempt
        $this->recordLoginAttempt($username, $user ? true : false, $userType);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Update last login
            $this->updateLastLogin($user['id'], $userType);
            
            return [
                'success' => true,
                'user' => $user,
                'user_type' => $userType,
                'message' => 'Login successful!'
            ];
        }
        
        return ['success' => false, 'message' => 'Invalid credentials.'];
    }
    
    private function findUserByUsername($username, $table) {
        $sql = "SELECT * FROM {$table} WHERE username = ? AND status = 'active'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    
    private function findUserByEmail($email, $table) {
        $sql = "SELECT * FROM {$table} WHERE email = ? AND status = 'active'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    private function updateLastLogin($userId, $userType) {
        $table = $userType === 'creator' ? 'creators' : 'brands';
        $sql = "UPDATE {$table} SET last_login = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
    }
    
    // Login Attempt Tracking
    private function recordLoginAttempt($identifier, $success, $userType = null) {
        $sql = "INSERT INTO login_attempts (identifier, ip_address, user_type, success) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$identifier, $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', $userType, $success]);
    }
    
    private function isLockedOut($identifier) {
        $sql = "SELECT COUNT(*) FROM login_attempts 
                WHERE identifier = ? 
                AND success = false 
                AND attempted_at > NOW() - INTERVAL '" . LOGIN_LOCKOUT_TIME . " seconds'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$identifier]);
        $failedAttempts = $stmt->fetchColumn();
        
        return $failedAttempts >= MAX_LOGIN_ATTEMPTS;
    }
    
    // Remember Me Functionality
    public function setRememberToken($userId, $userType, $token) {
        $table = $userType === 'creator' ? 'creators' : 'brands';
        $sql = "UPDATE {$table} SET remember_token = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$token, $userId]);
    }
    
    public function getUserByRememberToken($token) {
        // Check creators table
        $sql = "SELECT *, 'creator' as user_type FROM creators WHERE remember_token = ? AND status = 'active'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if ($user) {
            return $user;
        }
        
        // Check brands table
        $sql = "SELECT *, 'brand' as user_type FROM brands WHERE remember_token = ? AND status = 'active'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$token]);
        return $stmt->fetch();
    }
    
    // CRUD Operations for Creators
    public function createCreator($data) {
        // Generate username from full name
        $username = $this->generateUsernameFromFullName(
            $data['first_name'] ?? '', 
            $data['last_name'] ?? ''
        );
        
        $sql = "INSERT INTO creators (username, email, password_hash, first_name, last_name, bio, niche, location, phone) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $username,
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['first_name'] ?? null,
            $data['last_name'] ?? null,
            $data['bio'] ?? null,
            $data['niche'] ?? null,
            $data['location'] ?? null,
            $data['phone'] ?? null
        ]);
    }
    
    public function getCreator($id) {
        $sql = "SELECT * FROM creators WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function updateCreator($id, $data) {
        $fields = [];
        $values = [];
        
        // Check if name fields are being updated to regenerate username
        if (isset($data['first_name']) || isset($data['last_name'])) {
            $currentUser = $this->getCreator($id);
            if ($currentUser) {
                $firstName = $data['first_name'] ?? $currentUser['first_name'];
                $lastName = $data['last_name'] ?? $currentUser['last_name'];
                $data['username'] = $this->generateUsernameFromFullName($firstName, $lastName);
            }
        }
        
        foreach ($data as $key => $value) {
            if ($key !== 'id' && $key !== 'password') {
                $fields[] = "{$key} = ?";
                $values[] = $value;
            }
        }
        
        if (isset($data['password'])) {
            $fields[] = "password_hash = ?";
            $values[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $fields[] = "updated_at = CURRENT_TIMESTAMP";
        $values[] = $id;
        
        $sql = "UPDATE creators SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }
    
    public function deleteCreator($id) {
        $sql = "UPDATE creators SET status = 'deleted' WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function getAllCreators($limit = 50, $offset = 0) {
        $sql = "SELECT * FROM creators WHERE status != 'deleted' ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }
    
    // CRUD Operations for Brands
    public function createBrand($data) {
        // Generate username from company name
        $username = $this->generateUsernameFromCompanyName($data['company_name']);
        
        $sql = "INSERT INTO brands (username, company_name, contact_name, email, password_hash, phone, website, industry, company_size, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $username,
            $data['company_name'],
            $data['contact_name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['phone'] ?? null,
            $data['website'] ?? null,
            $data['industry'] ?? null,
            $data['company_size'] ?? null,
            $data['description'] ?? null
        ]);
    }
    
    public function getBrand($id) {
        $sql = "SELECT * FROM brands WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function updateBrand($id, $data) {
        $fields = [];
        $values = [];
        
        // Check if company_name is being updated to regenerate username
        if (isset($data['company_name'])) {
            $data['username'] = $this->generateUsernameFromCompanyName($data['company_name']);
        }
        
        foreach ($data as $key => $value) {
            if ($key !== 'id' && $key !== 'password') {
                $fields[] = "{$key} = ?";
                $values[] = $value;
            }
        }
        
        if (isset($data['password'])) {
            $fields[] = "password_hash = ?";
            $values[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $fields[] = "updated_at = CURRENT_TIMESTAMP";
        $values[] = $id;
        
        $sql = "UPDATE brands SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }
    
    public function deleteBrand($id) {
        $sql = "UPDATE brands SET status = 'deleted' WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function getAllBrands($limit = 50, $offset = 0) {
        $sql = "SELECT * FROM brands WHERE status != 'deleted' ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }
    
    // Utility Methods
    public function userExists($username, $email) {
        $sql = "SELECT 1 FROM creators WHERE username = ? OR email = ? 
                UNION 
                SELECT 1 FROM brands WHERE username = ? OR email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username, $email, $username, $email]);
        return $stmt->fetchColumn() !== false;
    }
    
    public function generateVerificationToken() {
        return bin2hex(random_bytes(32));
    }
    
    public function generateRememberToken() {
        return bin2hex(random_bytes(32));
    }
}