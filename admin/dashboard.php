<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

// Get statistics
try {
    // Total users
    $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
    $users_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Total products
    $stmt = $conn->query("SELECT COUNT(*) as count FROM products");
    $products_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Total orders today
    $stmt = $conn->query("SELECT COUNT(*) as count, SUM(total_amount) as total FROM orders WHERE DATE(created_at) = CURDATE()");
    $orders = $stmt->fetch(PDO::FETCH_ASSOC);
    $orders_count = $orders['count'] ?? 0;
    $total_sales = $orders['total'] ?? 0;

    // Recent orders
    $stmt = $conn->query("
        SELECT o.*, t.table_number 
        FROM orders o 
        LEFT JOIN tables t ON o.table_id = t.table_id 
        ORDER BY o.created_at DESC 
        LIMIT 5
    ");
    $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - HarahQR Sales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
        }

        .navbar {
            background: linear-gradient(135deg, #4e73df, #224abe);
            padding: 1rem;
        }

        .navbar-brand {
            font-weight: 600;
            color: white !important;
        }

        .nav-link {
            color: white !important;
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            opacity: 1;
        }

        .nav-link.active {
            opacity: 1;
            font-weight: 500;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-users { background: #e3fcef; color: #1cc88a; }
        .stat-products { background: #e8f4ff; color: #4e73df; }
        .stat-orders { background: #fff4e5; color: #f6c23e; }
        .stat-sales { background: #ffe9e9; color: #e74a3b; }

        .stat-title {
            color: #858796;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0;
        }

        .quick-actions {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .action-card {
            background: #f8f9fc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .action-card:hover {
            background: #4e73df;
            color: white;
            transform: translateX(5px);
        }

        .action-icon {
            font-size: 24px;
            width: 40px;
        }

        .recent-orders {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .order-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-info {
            flex-grow: 1;
            padding-right: 15px;
        }

        .order-table {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .order-time {
            font-size: 0.85rem;
            color: #858796;
        }

        .order-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-pending { background: #fff4e5; color: #f6c23e; }
        .status-paid { background: #e3fcef; color: #1cc88a; }
        .status-preparing { background: #e8f4ff; color: #4e73df; }
        .status-completed { background: #edf2f9; color: #858796; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-qrcode me-2"></i>
                Admin Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_products.php">
                            <i class="fas fa-box me-2"></i>Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_categories.php">
                            <i class="fas fa-tags me-2"></i>Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_tables.php">
                            <i class="fas fa-chair me-2"></i>Tables
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php">
                            <i class="fas fa-users me-2"></i>Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports.php">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <button class="btn btn-link nav-link position-relative" data-bs-toggle="modal" data-bs-target="#notificationModal">
                            <i class="fas fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" style="display: none;">
                                0
                            </span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon stat-users">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-title">Total Users</div>
                    <div class="stat-value"><?php echo $users_count; ?></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon stat-products">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-title">Total Products</div>
                    <div class="stat-value"><?php echo $products_count; ?></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon stat-orders">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-title">Orders Today</div>
                    <div class="stat-value"><?php echo $orders_count; ?></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon stat-sales">
                        <i class="fas fa-peso-sign"></i>
                    </div>
                    <div class="stat-title">Sales Today</div>
                    <div class="stat-value">₱<?php echo number_format($total_sales, 2); ?></div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="quick-actions">
                    <h5 class="mb-4">Quick Actions</h5>
                    <a href="manage_products.php" class="action-card">
                        <i class="fas fa-box action-icon"></i>
                        <div>
                            <h6 class="mb-1">Manage Products</h6>
                            <small>Add, edit, or remove products</small>
                        </div>
                    </a>
                    <a href="manage_categories.php" class="action-card">
                        <i class="fas fa-tags action-icon"></i>
                        <div>
                            <h6 class="mb-1">Manage Categories</h6>
                            <small>Organize your menu</small>
                        </div>
                    </a>
                    <a href="manage_tables.php" class="action-card">
                        <i class="fas fa-chair action-icon"></i>
                        <div>
                            <h6 class="mb-1">Manage Tables</h6>
                            <small>Add tables and generate QR codes</small>
                        </div>
                    </a>
                    <a href="manage_users.php" class="action-card">
                        <i class="fas fa-users action-icon"></i>
                        <div>
                            <h6 class="mb-1">Manage Users</h6>
                            <small>Add or edit staff accounts</small>
                        </div>
                    </a>
                    <a href="reports.php" class="action-card">
                        <i class="fas fa-chart-bar action-icon"></i>
                        <div>
                            <h6 class="mb-1">View Reports</h6>
                            <small>Check sales and analytics</small>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="recent-orders">
                    <h5 class="mb-4">Recent Orders</h5>
                    <?php foreach($recent_orders as $order): ?>
                    <div class="order-item">
                        <div class="order-info">
                            <div class="order-table">Table <?php echo htmlspecialchars($order['table_number']); ?></div>
                            <div class="order-time"><?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?></div>
                        </div>
                        <div class="order-amount me-4">
                            ₱<?php echo number_format($order['total_amount'], 2); ?>
                        </div>
                        <span class="order-status status-<?php echo strtolower($order['status']); ?>">
                            <?php echo ucfirst(strtolower($order['status'])); ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="notificationList" class="list-group">
                        <!-- Notifications will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function fetchNotifications() {
        $.ajax({
            url: '../api/get_notifications.php',
            method: 'GET',
            success: function(response) {
                if (response.notifications && response.notifications.length > 0) {
                    let notificationHtml = '';
                    response.notifications.forEach(function(notification) {
                        notificationHtml += `
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <p class="mb-1">${notification.message}</p>
                                    <small class="text-muted">${new Date(notification.created_at).toLocaleTimeString()}</small>
                                </div>
                            </div>`;
                    });
                    $('#notificationList').html(notificationHtml);
                    $('.notification-badge').text(response.notifications.length).show();
                } else {
                    $('#notificationList').html('<div class="list-group-item">No new notifications</div>');
                    $('.notification-badge').hide();
                }
            },
            error: function(xhr) {
                console.error('Error fetching notifications:', xhr);
                $('#notificationList').html('<div class="list-group-item text-danger">Error loading notifications</div>');
            }
        });
    }

    // Add to document ready
    $(document).ready(function() {
        // Existing ready functions...
        setInterval(fetchNotifications, 30000); // Check for new notifications every 30 seconds
        fetchNotifications(); // Initial fetch
        
        $('#notificationModal').on('show.bs.modal', function () {
            fetchNotifications();
        });
    });
    </script>
</body>
</html> 