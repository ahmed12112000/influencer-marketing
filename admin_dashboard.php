<?php
// admin_dashboard.php - Admin dashboard for managing brands

require_once 'BrandCRUD.php';

session_start();

// Simple authentication check (you should implement proper admin authentication)
// if (!isset($_SESSION['admin_logged_in'])) {
//     header('Location: admin_login.php');
//     exit();
// }

$brandCRUD = new BrandCRUD();
$message = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'delete':
            $brand_id = $_POST['brand_id'] ?? '';
            if ($brand_id) {
                $result = $brandCRUD->delete($brand_id);
                $message = $result['message'];
            }
            break;
            
        case 'verify':
            $brand_id = $_POST['brand_id'] ?? '';
            if ($brand_id) {
                $result = $brandCRUD->update($brand_id, ['is_verified' => true]);
                $message = $result['success'] ? 'Brand verified successfully' : $result['message'];
            }
            break;
    }
}

// Get filters
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$filters = [
    'industry' => $_GET['industry'] ?? '',
    'company_size' => $_GET['company_size'] ?? '',
    'search' => $_GET['search'] ?? ''
];

// Get brands with pagination
$brands_result = $brandCRUD->readAll($page, $limit, $filters);
$brands = $brands_result['success'] ? $brands_result['data'] : [];
$total_pages = $brands_result['total_pages'] ?? 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - InfluConnect</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f8f9fa;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .dashboard-title {
            margin-bottom: 30px;
        }

        .dashboard-title h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .dashboard-title p {
            color: #666;
            font-size: 16px;
        }

        .filters {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .filters h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .filter-row {
            display: flex;
            gap: 20px;
            align-items: end;
        }

        .filter-group {
            flex: 1;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 14px;
        }

        .filter-btn {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            height: 42px;
        }

        .filter-btn:hover {
            background: #5a67d8;
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .message.success {
            background: #efe;
            color: #363;
            border: 1px solid #cfc;
        }

        .message.error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .brands-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .table-header h3 {
            margin-bottom: 5px;
        }

        .table-header p {
            color: #666;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-verified {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-view {
            background: #007bff;
            color: white;
        }

        .btn-verify {
            background: #28a745;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
        }

        .pagination .current {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .pagination a:hover {
            background: #f8f9fa;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state h3 {
            margin-bottom: 10px;
            font-size: 24px;
        }

        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
            }
            
            .brands-table {
                overflow-x: auto;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="logo">InfluConnect Admin</div>
            <div class="admin-info">
                <span>Welcome, Admin</span>
                <a href="logout.php" style="color: white; text-decoration: none;">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="dashboard-title">
            <h1>Brand Management</h1>
            <p>Manage and monitor all registered brands</p>
        </div>

        <div class="filters">
            <h3>Filter Brands</h3>
            <form method="GET" action="">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="search">Search</label>
                        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($filters['search']); ?>" placeholder="Company or contact name...">
                    </div>
                    <div class="filter-group">
                        <label for="industry">Industry</label>
                        <select id="industry" name="industry">
                            <option value="">All Industries</option>
                            <option value="technology" <?php echo $filters['industry'] === 'technology' ? 'selected' : ''; ?>>Technology</option>
                            <option value="fashion" <?php echo $filters['industry'] === 'fashion' ? 'selected' : ''; ?>>Fashion</option>
                            <option value="beauty" <?php echo $filters['industry'] === 'beauty' ? 'selected' : ''; ?>>Beauty</option>
                            <option value="food" <?php echo $filters['industry'] === 'food' ? 'selected' : ''; ?>>Food & Beverage</option>
                            <option value="travel" <?php echo $filters['industry'] === 'travel' ? 'selected' : ''; ?>>Travel</option>
                            <option value="health" <?php echo $filters['industry'] === 'health' ? 'selected' : ''; ?>>Health & Wellness</option>
                            <option value="other" <?php echo $filters['industry'] === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="company_size">Company Size</label>
                        <select id="company_size" name="company_size">
                            <option value="">All Sizes</option>
                            <option value="1-10" <?php echo $filters['company_size'] === '1-10' ? 'selected' : ''; ?>>1-10 employees</option>
                            <option value="11-50" <?php echo $filters['company_size'] === '11-50' ? 'selected' : ''; ?>>11-50 employees</option>
                            <option value="51-200" <?php echo $filters['company_size'] === '51-200' ? 'selected' : ''; ?>>51-200 employees</option>
                            <option value="201-1000" <?php echo $filters['company_size'] === '201-1000' ? 'selected' : ''; ?>>201-1000 employees</option>
                            <option value="1000+" <?php echo $filters['company_size'] === '1000+' ? 'selected' : ''; ?>>1000+ employees</option>
                        </select>
                    </div>
                    <button type="submit" class="filter-btn">Filter</button>
                </div>
            </form>
        </div>

        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'success') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="brands-table">
            <div class="table-header">
                <h3>Registered Brands</h3>
                <p>Total: <?php echo $brands_result['total'] ?? 0; ?> brands</p>
            </div>

            <?php if (empty($brands)): ?>
                <div class="empty-state">
                    <h3>No brands found</h3>
                    <p>No brands match your current filters.</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Company</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Industry</th>
                            <th>Size</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($brands as $brand): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($brand['id']); ?></td>
                                <td><?php echo htmlspecialchars($brand['company_name']); ?></td>
                                <td><?php echo htmlspecialchars($brand['contact_name']); ?></td>
                                <td><?php echo htmlspecialchars($brand['email']); ?></td>
                                <td><?php echo htmlspecialchars($brand['industry'] ?: 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($brand['company_size'] ?: 'N/A'); ?></td>
                                <td>
                                    <?php if ($brand['is_verified']): ?>
                                        <span class="status-badge status-verified">Verified</span>
                                    <?php else: ?>
                                        <span class="status-badge status-pending">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($brand['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="view_brand.php?id=<?php echo $brand['id']; ?>" class="btn btn-view">View</a>
                                        
                                        <?php if (!$brand['is_verified']): ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="verify">
                                                <input type="hidden" name="brand_id" value="<?php echo $brand['id']; ?>">
                                                <button type="submit" class="btn btn-verify" onclick="return confirm('Verify this brand?')">Verify</button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="brand_id" value="<?php echo $brand['id']; ?>">
                                            <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this brand?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>&<?php echo http_build_query($filters); ?>">Previous</a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="current"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="?page=<?php echo $i; ?>&<?php echo http_build_query($filters); ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page + 1; ?>&<?php echo http_build_query($filters); ?>">Next</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>