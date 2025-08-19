<?php
// api.php - REST API endpoints for brand management

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'BrandCRUD.php';

// Parse the request
$request_method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path_parts = explode('/', trim($path, '/'));

// Get the endpoint
$endpoint = isset($path_parts[1]) ? $path_parts[1] : '';
$id = isset($path_parts[2]) ? $path_parts[2] : null;

// Initialize response
$response = [
    'success' => false,
    'message' => 'Invalid request',
    'data' => null
];

$brandCRUD = new BrandCRUD();

try {
    switch ($endpoint) {
        case 'brands':
            switch ($request_method) {
                case 'GET':
                    if ($id) {
                        // Get single brand
                        $result = $brandCRUD->read($id);
                        $response = $result;
                    } else {
                        // Get all brands with pagination and filters
                        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                        $limit = isset($_GET['limit']) ? max(1, min(50, intval($_GET['limit']))) : 10;
                        
                        $filters = [
                            'industry' => $_GET['industry'] ?? '',
                            'company_size' => $_GET['company_size'] ?? '',
                            'search' => $_GET['search'] ?? ''
                        ];
                        
                        $result = $brandCRUD->readAll($page, $limit, $filters);
                        $response = $result;
                    }
                    break;
                    
                case 'POST':
                    // Create new brand
                    $input = json_decode(file_get_contents('php://input'), true);
                    
                    if (!$input) {
                        throw new Exception('Invalid JSON data');
                    }
                    
                    $required_fields = ['company_name', 'contact_name', 'email', 'password'];
                    foreach ($required_fields as $field) {
                        if (empty($input[$field])) {
                            throw new Exception("Field '$field' is required");
                        }
                    }
                    
                    // Validate email
                    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                        throw new Exception('Invalid email format');
                    }
                    
                    // Validate password
                    if (strlen($input['password']) < 6) {
                        throw new Exception('Password must be at least 6 characters long');
                    }
                    
                    // Validate website URL if provided
                    if (!empty($input['website']) && !filter_var($input['website'], FILTER_VALIDATE_URL)) {
                        throw new Exception('Invalid website URL');
                    }
                    
                    $result = $brandCRUD->create($input);
                    $response = $result;
                    break;
                    
                case 'PUT':
                    // Update brand
                    if (!$id) {
                        throw new Exception('Brand ID is required for update');
                    }
                    
                    $input = json_decode(file_get_contents('php://input'), true);
                    
                    if (!$input) {
                        throw new Exception('Invalid JSON data');
                    }
                    
                    // Validate email if provided
                    if (isset($input['email']) && !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                        throw new Exception('Invalid email format');
                    }
                    
                    // Validate website URL if provided
                    if (isset($input['website']) && !empty($input['website']) && !filter_var($input['website'], FILTER_VALIDATE_URL)) {
                        throw new Exception('Invalid website URL');
                    }
                    
                    $result = $brandCRUD->update($id, $input);
                    $response = $result;
                    break;
                    
                case 'DELETE':
                    // Delete brand
                    if (!$id) {
                        throw new Exception('Brand ID is required for deletion');
                    }
                    
                    $result = $brandCRUD->delete($id);
                    $response = $result;
                    break;
                    
                default:
                    throw new Exception('Method not allowed');
            }
            break;
            
        case 'verify':
            if ($request_method == 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                
                if (empty($input['token'])) {
                    throw new Exception('Verification token is required');
                }
                
                $result = $brandCRUD->verifyBrand($input['token']);
                $response = $result;
            } else {
                throw new Exception('Method not allowed');
            }
            break;
            
        case 'authenticate':
            if ($request_method == 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                
                if (empty($input['email']) || empty($input['password'])) {
                    throw new Exception('Email and password are required');
                }
                
                $result = $brandCRUD->authenticate($input['email'], $input['password']);
                $response = $result;
            } else {
                throw new Exception('Method not allowed');
            }
            break;
            
        case 'stats':
            if ($request_method == 'GET') {
                // Get brand statistics
                $stats = getBrandStats($brandCRUD);
                $response = [
                    'success' => true,
                    'data' => $stats
                ];
            } else {
                throw new Exception('Method not allowed');
            }
            break;
            
        default:
            throw new Exception('Endpoint not found');
    }
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'data' => null
    ];
    
    // Set appropriate HTTP status code
    if (strpos($e->getMessage(), 'not found') !== false) {
        http_response_code(404);
    } elseif (strpos($e->getMessage(), 'required') !== false || strpos($e->getMessage(), 'Invalid') !== false) {
        http_response_code(400);
    } elseif (strpos($e->getMessage(), 'not allowed') !== false) {
        http_response_code(405);
    } else {
        http_response_code(500);
    }
}

// Set HTTP status code for successful responses
if ($response['success']) {
    switch ($request_method) {
        case 'POST':
            http_response_code(201);
            break;
        case 'DELETE':
            http_response_code(204);
            break;
        default:
            http_response_code(200);
    }
}

// Return JSON response
echo json_encode($response, JSON_PRETTY_PRINT);

// Helper function to get brand statistics
function getBrandStats($brandCRUD) {
    $stats = [
        'total_brands' => 0,
        'verified_brands' => 0,
        'pending_brands' => 0,
        'by_industry' => [],
        'by_company_size' => [],
        'recent_signups' => []
    ];
    
    try {
        // Get all brands
        $result = $brandCRUD->readAll(1, 1000); // Get a large number to calculate stats
        
        if ($result['success']) {
            $brands = $result['data'];
            $stats['total_brands'] = count($brands);
            
            $industry_counts = [];
            $size_counts = [];
            
            foreach ($brands as $brand) {
                // Count verified/pending
                if ($brand['is_verified']) {
                    $stats['verified_brands']++;
                } else {
                    $stats['pending_brands']++;
                }
                
                // Count by industry
                $industry = $brand['industry'] ?: 'Other';
                $industry_counts[$industry] = ($industry_counts[$industry] ?? 0) + 1;
                
                // Count by company size
                $size = $brand['company_size'] ?: 'Not specified';
                $size_counts[$size] = ($size_counts[$size] ?? 0) + 1;
            }
            
            $stats['by_industry'] = $industry_counts;
            $stats['by_company_size'] = $size_counts;
            
            // Get recent signups (last 7 days)
            $recent_brands = array_filter($brands, function($brand) {
                return strtotime($brand['created_at']) > strtotime('-7 days');
            });
            
            $stats['recent_signups'] = count($recent_brands);
        }
        
    } catch (Exception $e) {
        error_log("Error calculating stats: " . $e->getMessage());
    }
    
    return $stats;
}

// API Usage Examples (comment out in production)
/*
API Usage Examples:

1. Get all brands:
GET /api.php/brands

2. Get all brands with pagination:
GET /api.php/brands?page=1&limit=10

3. Get brands with filters:
GET /api.php/brands?industry=technology&company_size=1-10&search=tech

4. Get single brand:
GET /api.php/brands/1

5. Create new brand:
POST /api.php/brands
{
    "company_name": "Tech Company",
    "contact_name": "John Doe",
    "email": "john@techcompany.com",
    "phone": "+1234567890",
    "website": "https://techcompany.com",
    "industry": "technology",
    "company_size": "1-10",
    "description": "A tech startup",
    "password": "password123"
}

6. Update brand:
PUT /api.php/brands/1
{
    "company_name": "Updated Tech Company",
    "phone": "+0987654321"
}

7. Delete brand:
DELETE /api.php/brands/1

8. Verify brand:
POST /api.php/verify
{
    "token": "verification_token_here"
}

9. Authenticate brand:
POST /api.php/authenticate
{
    "email": "john@techcompany.com",
    "password": "password123"
}

10. Get statistics:
GET /api.php/stats
*/
?>