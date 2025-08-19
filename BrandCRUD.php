<?php
// BrandCRUD.php - Fixed version with better error handling and debugging

class BrandCRUD {
    private $db;
    private $pdo;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
        
        // Ensure PDO uses exceptions for error handling
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Ensure brands table exists
        $this->ensureBrandsTable();
    }
    
    // Generate a unique username from email or company name
    private function generateUsername($email, $companyName) {
        try {
            // Try to use the part before @ in email
            $baseUsername = strtolower(explode('@', $email)[0]);
            
            // Clean the username (remove special characters, keep only alphanumeric and underscores)
            $baseUsername = preg_replace('/[^a-z0-9_]/', '', $baseUsername);
            
            // Ensure username is not empty
            if (empty($baseUsername)) {
                $baseUsername = strtolower(preg_replace('/[^a-z0-9_]/', '', $companyName));
            }
            
            // If still empty, use a default
            if (empty($baseUsername)) {
                $baseUsername = 'brand_' . time();
            }
            
            // Check if username already exists and make it unique
            $username = $baseUsername;
            $counter = 1;
            
            while ($this->usernameExists($username)) {
                $username = $baseUsername . '_' . $counter;
                $counter++;
            }
            
            return $username;
            
        } catch (Exception $e) {
            error_log("Username generation error: " . $e->getMessage());
            return 'brand_' . time(); // Fallback username
        }
    }
    
    // Check if username already exists
    private function usernameExists($username) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM brands WHERE username = ?");
            $stmt->execute([$username]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Username check error: " . $e->getMessage());
            return false;
        }
    }
    
    private function ensureBrandsTable() {
        if (!$this->db->tableExists('brands')) {
            $this->db->createBrandsTable();
        }
    }
    
    public function create($data) {
        try {
            // Debug: Log the incoming data
            error_log("BrandCRUD::create - Incoming data: " . print_r($data, true));
            
            // Check if email already exists
            if ($this->emailExists($data['email'])) {
                error_log("BrandCRUD::create - Email already exists: " . $data['email']);
                return [
                    'success' => false,
                    'message' => 'An account with this email already exists'
                ];
            }
            
            // Hash password
            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            
            // Generate verification token
            $verification_token = bin2hex(random_bytes(32));
            
            // Debug: Log password hash and token generation
            error_log("BrandCRUD::create - Password hashed successfully");
            error_log("BrandCRUD::create - Verification token generated: " . $verification_token);
            
            // Generate username from email (before @ symbol) or use company name
            $username = $this->generateUsername($data['email'], $data['company_name']);
            
            // Prepare SQL statement - Fixed for PostgreSQL with username
            $sql = "INSERT INTO brands (
                company_name, contact_name, email, phone, website, 
                industry, company_size, description, password_hash, 
                verification_token, username, created_at, updated_at
            ) VALUES (
                :company_name, :contact_name, :email, :phone, :website,
                :industry, :company_size, :description, :password_hash,
                :verification_token, :username, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
            )";
            
            // Debug: Log SQL statement
            error_log("BrandCRUD::create - SQL: " . $sql);
            
            $stmt = $this->pdo->prepare($sql);
            
            // Debug: Check if prepare was successful
            if (!$stmt) {
                error_log("BrandCRUD::create - Prepare failed: " . print_r($this->pdo->errorInfo(), true));
                return [
                    'success' => false,
                    'message' => 'Database prepare error'
                ];
            }
            
            // Bind parameters with explicit data types
            $stmt->bindParam(':company_name', $data['company_name'], PDO::PARAM_STR);
            $stmt->bindParam(':contact_name', $data['contact_name'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR);
            $stmt->bindParam(':website', $data['website'], PDO::PARAM_STR);
            $stmt->bindParam(':industry', $data['industry'], PDO::PARAM_STR);
            $stmt->bindParam(':company_size', $data['company_size'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindParam(':verification_token', $verification_token, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            
            // Debug: Log bound parameters
            error_log("BrandCRUD::create - Parameters bound successfully");
            
            // Execute the statement
            $result = $stmt->execute();
            
            // Debug: Log execution result
            error_log("BrandCRUD::create - Execute result: " . ($result ? 'true' : 'false'));
            error_log("BrandCRUD::create - Row count: " . $stmt->rowCount());
            
            // Get the inserted ID
            $brand_id = $this->pdo->lastInsertId();
            
            // Debug: Log inserted ID
            error_log("BrandCRUD::create - Last insert ID: " . $brand_id);
            
            // Check if insertion was successful
            if ($result && $stmt->rowCount() > 0) {
                // TODO: Send verification email here
                // $this->sendVerificationEmail($data['email'], $verification_token);
                
                return [
                    'success' => true,
                    'message' => 'Brand account created successfully',
                    'brand_id' => $brand_id,
                    'verification_token' => $verification_token
                ];
            } else {
                error_log("BrandCRUD::create - Insert failed: No rows affected");
                return [
                    'success' => false,
                    'message' => 'Failed to create brand account'
                ];
            }
            
        } catch (PDOException $e) {
            // Enhanced error logging with more details
            error_log("BrandCRUD::create - PDO Error: " . $e->getMessage());
            error_log("BrandCRUD::create - PDO Error Code: " . $e->getCode());
            error_log("BrandCRUD::create - PDO Error Info: " . print_r($e->errorInfo ?? [], true));
            error_log("BrandCRUD::create - Stack trace: " . $e->getTraceAsString());
            
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage() // Show actual error in development
            ];
        } catch (Exception $e) {
            error_log("BrandCRUD::create - General Error: " . $e->getMessage());
            error_log("BrandCRUD::create - Stack trace: " . $e->getTraceAsString());
            
            return [
                'success' => false,
                'message' => 'An error occurred while creating your account: ' . $e->getMessage()
            ];
        }
    }
    
    public function emailExists($email) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM brands WHERE email = ?");
            $stmt->execute([$email]);
            $count = $stmt->fetchColumn();
            error_log("BrandCRUD::emailExists - Email: $email, Count: $count");
            return $count > 0;
        } catch (PDOException $e) {
            error_log("BrandCRUD::emailExists - Error: " . $e->getMessage());
            return false;
        }
    }
    
    public function findByEmail($email) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM brands WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Find by email error: " . $e->getMessage());
            return false;
        }
    }
    
    public function findById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM brands WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Find by ID error: " . $e->getMessage());
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            // Build dynamic update query
            $fields = [];
            $values = [];
            
            foreach ($data as $key => $value) {
                if ($key !== 'id' && $key !== 'password') {
                    $fields[] = "$key = :$key";
                    $values[$key] = $value;
                }
            }
            
            // Handle password separately if provided
            if (isset($data['password'])) {
                $fields[] = "password_hash = :password_hash";
                $values['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            
            // Add updated_at
            $fields[] = "updated_at = CURRENT_TIMESTAMP";
            $values['id'] = $id;
            
            $sql = "UPDATE brands SET " . implode(', ', $fields) . " WHERE id = :id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
            
            return [
                'success' => true,
                'message' => 'Brand updated successfully',
                'affected_rows' => $stmt->rowCount()
            ];
            
        } catch (PDOException $e) {
            error_log("Brand update error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while updating the brand'
            ];
        }
    }
    
    public function verifyEmail($token) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE brands 
                SET email_verified = TRUE, verification_token = NULL, updated_at = CURRENT_TIMESTAMP 
                WHERE verification_token = ?
            ");
            $stmt->execute([$token]);
            
            return [
                'success' => $stmt->rowCount() > 0,
                'message' => $stmt->rowCount() > 0 ? 'Email verified successfully' : 'Invalid verification token'
            ];
            
        } catch (PDOException $e) {
            error_log("Email verification error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred during email verification'
            ];
        }
    }
    
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM brands WHERE id = ?");
            $stmt->execute([$id]);
            
            return [
                'success' => $stmt->rowCount() > 0,
                'message' => $stmt->rowCount() > 0 ? 'Brand deleted successfully' : 'Brand not found'
            ];
            
        } catch (PDOException $e) {
            error_log("Brand deletion error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while deleting the brand'
            ];
        }
    }
    
    public function getAll($limit = 50, $offset = 0) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, company_name, contact_name, email, phone, website, 
                       industry, company_size, email_verified, status, created_at 
                FROM brands 
                ORDER BY created_at DESC 
                LIMIT ? OFFSET ?
            ");
            $stmt->execute([$limit, $offset]);
             
            return [
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
            
        } catch (PDOException $e) {
            error_log("Get all brands error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while fetching brands'
            ];
        }
    }
    
    public function getCount() {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM brands");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Get count error: " . $e->getMessage());
            return 0; 
        }
    }
    
    // Debug method to check table structure
    public function getTableStructure() {
        try {
            $stmt = $this->pdo->query("
                SELECT column_name, data_type, is_nullable, column_default 
                FROM information_schema.columns 
                WHERE table_name = 'brands' 
                ORDER BY ordinal_position
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get table structure error: " . $e->getMessage());
            return [];
        }
    }
}