<?php
// Database.php - PostgreSQL Database Connection Class

class Database {
    private static $instance = null;
    private $pdo;
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;
    
    private function __construct() {
        $this->host = DB_HOST;
        $this->port = DB_PORT;
        $this->dbname = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            $this->pdo = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    public function getVersion() {
        try {
            $stmt = $this->pdo->query("SELECT version()");
            $version = $stmt->fetchColumn();
            return substr($version, 0, 50) . "...";
        } catch (PDOException $e) {
            return "Unable to get version: " . $e->getMessage();
        }
    }
    
    public function testConnection() {
        try {
            $stmt = $this->pdo->query("SELECT 1");
            return $stmt->fetchColumn() === 1;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function tableExists($tableName) {
        try {
            $stmt = $this->pdo->prepare("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = ?)");
            $stmt->execute([$tableName]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function createBrandsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS brands (
            id SERIAL PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            company_name VARCHAR(255) NOT NULL,
            contact_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            phone VARCHAR(20),
            website VARCHAR(255),
            industry VARCHAR(50),
            company_size VARCHAR(20),
            description TEXT,
            logo VARCHAR(255),
            password_hash VARCHAR(255) NOT NULL,
            email_verified BOOLEAN DEFAULT FALSE,
            verification_token VARCHAR(100),
            remember_token VARCHAR(100),
            status VARCHAR(20) DEFAULT 'active',
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
    
    public function createCreatorsTable() {
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
    
    public function createLoginAttemptsTable() {
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
    
    // Method to create all tables at once
    public function createAllTables() {
        $this->createCreatorsTable();
        $this->createBrandsTable();
        $this->createLoginAttemptsTable();
    }
    
    // Method to add username column to existing brands table (for migration)
    public function addUsernameColumnToBrands() {
        try {
            // Check if username column already exists
            $stmt = $this->pdo->prepare("SELECT column_name FROM information_schema.columns WHERE table_name = 'brands' AND column_name = 'username'");
            $stmt->execute();
            
            if (!$stmt->fetch()) {
                // Add username column
                $this->pdo->exec("ALTER TABLE brands ADD COLUMN username VARCHAR(50) UNIQUE");
                
                // Populate username column with company_name values (cleaned up)
                $this->pdo->exec("
                    UPDATE brands 
                    SET username = LOWER(REGEXP_REPLACE(REGEXP_REPLACE(company_name, '[^a-zA-Z0-9\\s]', '', 'g'), '\\s+', '_', 'g'))
                    WHERE username IS NULL
                ");
                
                // Make username NOT NULL after populating
                $this->pdo->exec("ALTER TABLE brands ALTER COLUMN username SET NOT NULL");
                
                return true;
            }
            
            return true; // Column already exists
        } catch (PDOException $e) {
            throw new Exception("Failed to add username column to brands table: " . $e->getMessage());
        }
    }
    
    // Method to add missing columns to existing tables
    public function migrateExistingTables() {
        try {
            // Add username column to brands if it doesn't exist
            $this->addUsernameColumnToBrands();
            
            // Add other missing columns that might be needed
            $this->addMissingColumnsToTables();
            
            return true;
        } catch (Exception $e) {
            throw new Exception("Migration failed: " . $e->getMessage());
        }
    }
    
    private function addMissingColumnsToTables() {
        try {
            // Check and add missing columns to brands table
            $brandsColumns = [
                'logo' => 'VARCHAR(255)',
                'remember_token' => 'VARCHAR(100)',
                'last_login' => 'TIMESTAMP'
            ];
            
            foreach ($brandsColumns as $column => $type) {
                $stmt = $this->pdo->prepare("SELECT column_name FROM information_schema.columns WHERE table_name = 'brands' AND column_name = ?");
                $stmt->execute([$column]);
                
                if (!$stmt->fetch()) {
                    $this->pdo->exec("ALTER TABLE brands ADD COLUMN {$column} {$type}");
                }
            }
            
            // Check and add missing columns to creators table if needed
            $creatorsColumns = [
                'remember_token' => 'VARCHAR(100)',
                'last_login' => 'TIMESTAMP'
            ];
            
            foreach ($creatorsColumns as $column => $type) {
                $stmt = $this->pdo->prepare("SELECT column_name FROM information_schema.columns WHERE table_name = 'creators' AND column_name = ?");
                $stmt->execute([$column]);
                
                if (!$stmt->fetch()) {
                    $this->pdo->exec("ALTER TABLE creators ADD COLUMN {$column} {$type}");
                }
            }
            
            return true;
        } catch (PDOException $e) {
            throw new Exception("Failed to add missing columns: " . $e->getMessage());
        }
    }
    
    // Prevent cloning
    private function __clone() {}
    
    // Prevent unserialization - Changed to public visibility for PHP 8.0+ compatibility
    public function __wakeup() {
        throw new Exception("Cannot unserialize a singleton.");
    }
}
?>