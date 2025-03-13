<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and has cashier role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'CASHIER') {
    header('Location: ../login.php');
    exit();
}

// Get active orders with items
try {
    $stmt = $conn->query("
        SELECT 
            o.*,
            t.table_number,
            CONCAT(
                '[',
                GROUP_CONCAT(
                    JSON_OBJECT(
                        'product_id', oi.product_id,
                        'name', p.name,
                        'quantity', oi.quantity,
                        'unit_price', oi.unit_price
                    )
                ),
                ']'
            ) as items
        FROM orders o 
        LEFT JOIN tables t ON o.table_id = t.table_id 
        LEFT JOIN order_items oi ON o.order_id = oi.order_id
        LEFT JOIN products p ON oi.product_id = p.product_id
        WHERE o.status != 'COMPLETED' 
        AND o.payment_status != 'PAID'
        GROUP BY o.order_id, o.status, o.created_at, o.total_amount, t.table_number
        ORDER BY o.created_at DESC
    ");
    $active_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // No need for manual JSON processing anymore since the query returns proper JSON
    foreach ($active_orders as &$order) {
        if (!$order['items']) {
            $order['items'] = '[]';
        }
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Orders - HarahQR Sales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Custom responsive styles */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.1rem;
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }
            
            .order-row td {
                padding: 0.5rem;
            }
            
            .modal-dialog {
                margin: 0.5rem;
                max-width: 98%;
            }
            
            .badge {
                font-size: 0.7rem;
            }
            
            .card-header h5 {
                font-size: 1.1rem;
            }
            
            .alert {
                font-size: 0.9rem;
            }
            
            .btn-group {
                flex-wrap: wrap;
                gap: 0.25rem;
            }
            
            .btn-group .btn {
                flex: 1;
                min-width: calc(50% - 0.25rem);
                margin: 0;
            }
        }
        
        /* General improvements */
        .table > :not(caption) > * > * {
            padding: 0.75rem;
            vertical-align: middle;
        }
        
        .order-row:hover {
            background-color: rgba(0,0,0,0.02);
        }
        
        .modal-body {
            max-height: calc(100vh - 210px);
            overflow-y: auto;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-cash-register me-2"></i>Cashier Orders
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-3">
                    <a href="completed_orders.php" class="btn btn-outline-light">
                        <i class="fas fa-check-circle me-2"></i>Completed Orders
                    </a>
                </li>
                <li class="nav-item me-3">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tableViewModal">
                        <i class="fas fa-chair me-2"></i>View Tables
                    </button>
                </li>
                <li class="nav-item me-3">
                    <button class="btn btn-link nav-link position-relative" data-bs-toggle="modal" data-bs-target="#notificationModal">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" style="display: none;">
                            0
                        </span>
                    </button>
                </li>
                <li class="nav-item me-3">
                    <button class="btn btn-warning" onclick="sendSoundAlert()">
                        <i class="fas fa-bell me-2"></i>Sound Alert
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
        <div class="alert alert-danger fade-in">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Add toggle button for view modes -->
    <div class="mb-4">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary active" id="tableViewBtn">
                <i class="fas fa-table me-2"></i>Table View
            </button>
            <button type="button" class="btn btn-primary" id="cardViewBtn">
                <i class="fas fa-th-large me-2"></i>Kitchen View
            </button>
        </div>
    </div>

    <!-- Table View -->
    <div id="tableView">
        <div class="row">
            <div class="col-12">
                <div class="card fade-in">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>Active Orders
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="btn-group">
                                <button class="btn btn-light active" data-filter="all">All</button>
                                <button class="btn btn-light" data-filter="pending">Pending</button>
                                <button class="btn btn-light" data-filter="preparing">Preparing</button>
                                <button class="btn btn-light" data-filter="ready">Ready</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Table</th>
                                        <th>Items</th>
                                        <th class="text-end">Total</th>
                                        <th>Status</th>
                                        <th class="d-none d-md-table-cell">Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($active_orders as $order): ?>
                                        <tr class="order-row fade-in" data-status="<?php echo strtolower($order['status']); ?>">
                                            <td>
                                                <span class="fw-bold">#<?php echo htmlspecialchars($order['order_id']); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <i class="fas fa-chair me-1"></i>
                                                    <?php echo htmlspecialchars($order['table_number']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $items = json_decode($order['items'], true);
                                                $itemCount = is_array($items) ? count($items) : 0;
                                                ?>
                                                <button class="btn btn-info btn-sm" onclick="viewItems(<?php echo htmlspecialchars(json_encode($order)); ?>)">
                                                    <i class="fas fa-list me-1"></i><span class="d-none d-md-inline">See </span>Items (<?php echo $itemCount; ?>)
                                                </button>
                                            </td>
                                            <td class="text-end">
                                                <span class="fw-bold text-success">
                                                    ₱<?php echo number_format($order['total_amount'], 2); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $order['status'] === 'PENDING' ? 'warning' : 
                                                        ($order['status'] === 'PREPARING' ? 'info' : 
                                                        ($order['status'] === 'READY' ? 'success' : 'secondary')); 
                                                ?>">
                                                    <i class="fas fa-<?php 
                                                        echo $order['status'] === 'PENDING' ? 'clock' : 
                                                            ($order['status'] === 'PREPARING' ? 'fire' : 
                                                            ($order['status'] === 'READY' ? 'check' : 'times')); 
                                                    ?> me-1"></i>
                                                    <?php echo htmlspecialchars($order['status']); ?>
                                                </span>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <small class="text-muted">
                                                    <?php echo date('h:i A', strtotime($order['created_at'])); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php if ($order['payment_status'] !== 'PAID'): ?>
                                                    <button class="btn btn-primary btn-sm" onclick="processPayment(<?php echo $order['order_id']; ?>)">
                                                        <i class="fas fa-credit-card me-1"></i><span class="d-none d-md-inline">Payment</span>
                                                    </button>
                                                <?php else: ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Paid
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kitchen-style Card View -->
    <div id="cardView" style="display: none;">
        <div class="row" id="ordersContainer">
            <?php foreach ($active_orders as $order): ?>
                <div class="col-md-4 mb-4 fade-in">
                    <div class="order-card card">
                        <div class="card-header bg-<?php echo $order['status'] === 'PENDING' ? 'warning' : 
                            ($order['status'] === 'PREPARING' ? 'info' : 
                            ($order['status'] === 'READY' ? 'success' : 'secondary')); ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-receipt me-2"></i>
                                    Order #<?php echo htmlspecialchars($order['order_id']); ?>
                                </h5>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-chair me-1"></i>
                                    Table <?php echo htmlspecialchars($order['table_number']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="timer mb-3" data-created="<?php echo htmlspecialchars($order['created_at']); ?>">
                                <i class="fas fa-clock me-2"></i>
                                Waiting time: <span class="waiting-time">0:00</span>
                            </div>
                            <h6 class="card-subtitle mb-3">
                                <i class="fas fa-clipboard-list me-2"></i>Items:
                            </h6>
                            <div class="order-items">
                                <?php
                                $items = json_decode($order['items'], true);
                                if ($items && is_array($items)):
                                    foreach ($items as $item):
                                        if (isset($item['name']) && isset($item['quantity'])):
                                ?>
                                    <div class="order-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-utensils me-2"></i>
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </span>
                                        <span class="badge bg-primary rounded-pill">
                                            x<?php echo htmlspecialchars($item['quantity']); ?>
                                        </span>
                                    </div>
                                <?php 
                                        endif;
                                    endforeach;
                                else:
                                ?>
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>No items found
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Total Amount:</span>
                                    <span class="fw-bold text-success">₱<?php echo number_format($order['total_amount'], 2); ?></span>
                                </div>
                                <?php if ($order['payment_status'] !== 'PAID'): ?>
                                    <button class="btn btn-primary w-100" onclick="processPayment(<?php echo $order['order_id']; ?>)">
                                        <i class="fas fa-credit-card me-2"></i>Process Payment
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-success w-100" disabled>
                                        <i class="fas fa-check me-2"></i>Paid
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-credit-card me-2"></i>Process Payment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    <input type="hidden" id="orderId" name="orderId">
                    <div class="mb-4">
                        <label for="amount" class="form-label">Amount Due</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="text" class="form-control form-control-lg" id="amount" readonly>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="paymentMethod" class="form-label">Payment Method</label>
                        <select class="form-select form-select-lg" id="paymentMethod" required>
                            <option value="CASH">
                                <i class="fas fa-money-bill-wave"></i> Cash
                            </option>
                            <option value="GCASH">
                                <i class="fas fa-mobile-alt"></i> GCash
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="amountReceived" class="form-label">Amount Received</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control form-control-lg" id="amountReceived" required step="0.01">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="change" class="form-label">Change</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="text" class="form-control form-control-lg" id="change" readonly>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="completePayment()">
                    <i class="fas fa-check me-1"></i>Complete Payment
                </button>
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

<!-- Table View Modal -->
<div class="modal fade" id="tableViewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-chair me-2"></i>Restaurant Tables
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <style>
                    .table-card {
                        background: white;
                        border-radius: 15px;
                        padding: 20px;
                        margin-bottom: 20px;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                        transition: transform 0.3s ease;
                    }

                    .table-card:hover {
                        transform: translateY(-5px);
                    }

                    .status-badge {
                        padding: 5px 15px;
                        border-radius: 20px;
                        font-size: 0.9rem;
                        font-weight: 500;
                    }

                    .status-available { background: #e3fcef; color: #1cc88a; }
                    .status-occupied { background: #ffe9e9; color: #e74a3b; }
                    .status-ready { background: #fff4e5; color: #f6c23e; }
                    .status-cleaning { background: #edf2f9; color: #858796; }

                    .qr-code {
                        text-align: center;
                        margin: 15px 0;
                    }

                    .qr-code canvas {
                        border-radius: 10px;
                        padding: 10px;
                        background: white;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    }
                </style>

                <div class="row" id="tableContainer">
                    <?php
                    try {
                        $tableStmt = $conn->query("SELECT * FROM tables ORDER BY table_number");
                        while ($table = $tableStmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "
                            <div class='col-md-6 col-lg-4 mb-4'>
                                <div class='table-card'>
                                    <div class='d-flex justify-content-between align-items-center mb-3'>
                                        <h5 class='mb-0'>Table {$table['table_number']}</h5>
                                        <span class='status-badge status-" . strtolower($table['status']) . "'>
                                            " . ucfirst(strtolower($table['status'])) . "
                                        </span>
                                    </div>
                                    
                                    <div class='qr-code' id='qrcode-{$table['table_id']}'></div>
                                    
                                    <div class='d-flex justify-content-between align-items-center mt-3'>";
                            
                            if ($table['status'] === 'AVAILABLE') {
                                echo "
                                <button class='btn btn-primary' onclick='window.open(\"../order.php?table={$table['qr_code']}\", \"_blank\")'>
                                    <i class='fas fa-plus me-2'></i>New Order
                                </button>";
                            } else {
                                echo "
                                <button class='btn btn-secondary' disabled>
                                    <i class='fas fa-lock me-2'></i>Table Occupied
                                </button>";
                            }
                            
                            echo "
                                <button class='btn btn-link' onclick='downloadQR({$table['table_id']}, {$table['table_number']})'>
                                    <i class='fas fa-download me-2'></i>Download QR
                                </button>
                                    </div>
                                </div>
                            </div>";
                        }
                    } catch (PDOException $e) {
                        echo "<div class='col-12'><div class='alert alert-danger'>Error loading tables</div></div>";
                    }
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Items List Modal -->
<div class="modal fade" id="itemsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-list me-2"></i>Order Items
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <p class="mb-1"><strong id="itemsOrderId"></strong></p>
                    <p class="mb-1" id="itemsTableNumber"></p>
                    <p class="mb-0" id="itemsStatus"></p>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="itemsList">
                            <!-- Items will be dynamically inserted here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total Amount:</th>
                                <th class="text-end" id="itemsTotalAmount"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let currentOrder = null;
const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));

function processPayment(orderId) {
    $.ajax({
        url: '../api/get_order.php',
        method: 'GET',
        data: { order_id: orderId },
        success: function(response) {
            if (response.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.error,
                    confirmButtonColor: '#4e73df'
                });
                return;
            }
            // Check if order is already paid
            if (response.payment_status === 'PAID') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Already Paid',
                    text: 'This order has already been paid.',
                    confirmButtonColor: '#4e73df'
                });
                return;
            }
            currentOrder = response;
            $('#orderId').val(orderId);
            $('#amount').val(parseFloat(response.total_amount).toFixed(2));
            $('#amountReceived').val('').focus();
            $('#change').val('0.00');
            paymentModal.show();
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error loading order details. Please try again.',
                confirmButtonColor: '#4e73df'
            });
            console.error('Error:', xhr.responseText);
        }
    });
}

$('#amountReceived').on('input', function() {
    if (!currentOrder) return;
    
    const amountReceived = parseFloat($(this).val()) || 0;
    const totalAmount = parseFloat(currentOrder.total_amount) || 0;
    const change = amountReceived - totalAmount;
    $('#change').val(change.toFixed(2));
    
    // Highlight change amount
    if (change >= 0) {
        $('#change').removeClass('is-invalid').addClass('is-valid');
    } else {
        $('#change').removeClass('is-valid').addClass('is-invalid');
    }
});

function completePayment() {
    if (!currentOrder) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Order details not loaded',
            confirmButtonColor: '#4e73df'
        });
        return;
    }

    // Check again if order is already paid
    if (currentOrder.payment_status === 'PAID') {
        Swal.fire({
            icon: 'warning',
            title: 'Already Paid',
            text: 'This order has already been paid.',
            confirmButtonColor: '#4e73df'
        });
        paymentModal.hide();
        return;
    }

    const orderId = $('#orderId').val();
    const paymentMethod = $('#paymentMethod').val();
    const amountReceived = parseFloat($('#amountReceived').val());
    const totalAmount = parseFloat(currentOrder.total_amount);
    
    if (isNaN(amountReceived) || amountReceived < totalAmount) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Amount',
            text: 'Please enter a valid amount that covers the total bill.',
            confirmButtonColor: '#4e73df'
        });
        return;
    }

    // Show loading state
    const submitButton = $('.modal-footer .btn-primary');
    const originalText = submitButton.html();
    submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');

    $.ajax({
        url: '../api/complete_payment.php',
        method: 'POST',
        data: {
            order_id: orderId,
            payment_method: paymentMethod,
            amount_received: amountReceived.toFixed(2)
        },
        success: function(response) {
            if (response.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Error',
                    text: response.error,
                    confirmButtonColor: '#4e73df'
                });
                submitButton.prop('disabled', false).html(originalText);
                return;
            }
            
            Swal.fire({
                icon: 'success',
                title: 'Payment Complete',
                text: 'The payment has been processed successfully!',
                confirmButtonColor: '#4e73df'
            }).then(() => {
                paymentModal.hide();
                location.reload();
            });
        },
        error: function(xhr) {
            let errorMessage = 'Error processing payment.';
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.error) {
                    errorMessage += ' ' + response.error;
                }
            } catch (e) {
                console.error('Parse error:', e);
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Payment Error',
                text: errorMessage,
                confirmButtonColor: '#4e73df'
            });
            submitButton.prop('disabled', false).html(originalText);
        }
    });
}

// Filter functionality
document.querySelectorAll('[data-filter]').forEach(button => {
    button.addEventListener('click', function() {
        const filter = this.dataset.filter;
        
        // Update active button
        document.querySelectorAll('[data-filter]').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        
        // Filter rows
        document.querySelectorAll('.order-row').forEach(row => {
            if (filter === 'all' || row.dataset.status === filter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});

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
            Swal.fire({
                icon: 'error',
                title: 'Notification Error',
                text: 'Error loading notifications',
                confirmButtonColor: '#4e73df'
            });
        }
    });
}

// Add to document ready
$(document).ready(function() {
    setInterval(fetchNotifications, 30000); // Check for new notifications every 30 seconds
    fetchNotifications(); // Initial fetch
    
    $('#notificationModal').on('show.bs.modal', function () {
        fetchNotifications();
    });

    // Add view toggle functionality
    $('#tableViewBtn').click(function() {
        $(this).addClass('active');
        $('#cardViewBtn').removeClass('active');
        $('#tableView').show();
        $('#cardView').hide();
    });
    
    $('#cardViewBtn').click(function() {
        $(this).addClass('active');
        $('#tableViewBtn').removeClass('active');
        $('#tableView').hide();
        $('#cardView').show();
    });
    
    // Add timer update functionality
    function updateWaitingTimes() {
        $('.timer').each(function() {
            const createdAt = new Date($(this).data('created'));
            const now = new Date();
            
            // Calculate time difference in seconds
            const diffInSeconds = Math.floor((now - createdAt) / 1000);
            
            // Calculate minutes and seconds
            const minutes = Math.floor(diffInSeconds / 60);
            const seconds = diffInSeconds % 60;
            
            // Update the display
            const timeString = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            $(this).find('.waiting-time').text(timeString);
            
            // Update styling
            if (minutes >= 15) {
                $(this).addClass('danger').removeClass('warning');
            } else if (minutes >= 10) {
                $(this).addClass('warning').removeClass('danger');
            } else {
                $(this).removeClass('warning danger');
            }
        });
    }

    // Initial update
    updateWaitingTimes();
    
    // Update timers every second
    setInterval(updateWaitingTimes, 1000);
});

function initializeQRCodes() {
    <?php
    try {
        $tableStmt = $conn->query("SELECT * FROM tables");
        while ($table = $tableStmt->fetch(PDO::FETCH_ASSOC)) {
            echo "
            new QRCode(document.getElementById('qrcode-{$table['table_id']}'), {
                text: window.location.origin + '/harahqrsales/order.php?table={$table['qr_code']}',
                width: 128,
                height: 128,
                colorDark: '#2c3e50',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });";
        }
    } catch (PDOException $e) {
        echo "console.error('Error initializing QR codes:', " . json_encode($e->getMessage()) . ");";
    }
    ?>
}

function downloadQR(tableId, tableNumber) {
    const canvas = document.querySelector(`#qrcode-${tableId} canvas`);
    const link = document.createElement('a');
    link.download = `table-${tableNumber}-qr.png`;
    link.href = canvas.toDataURL();
    link.click();
}

// Initialize QR codes when the modal is shown
$('#tableViewModal').on('shown.bs.modal', function () {
    initializeQRCodes();
});

// Add keyboard shortcut for table view
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 't') { // Ctrl+T
        e.preventDefault();
        $('#tableViewModal').modal('show');
    }
});

// Add viewItems function
function viewItems(order) {
    const items = JSON.parse(order.items);
    
    document.getElementById('itemsOrderId').textContent = `Order #${order.order_id}`;
    document.getElementById('itemsTableNumber').textContent = `Table ${order.table_number}`;
    document.getElementById('itemsStatus').innerHTML = `
        <span class="badge bg-${
            order.status === 'PENDING' ? 'warning' : 
            (order.status === 'PREPARING' ? 'info' : 
            (order.status === 'READY' ? 'success' : 'secondary'))
        }">
            <i class="fas fa-${
                order.status === 'PENDING' ? 'clock' : 
                (order.status === 'PREPARING' ? 'fire' : 
                (order.status === 'READY' ? 'check' : 'times'))
            } me-1"></i>
            ${order.status}
        </span>`;
    
    let itemsHtml = '';
    items.forEach(item => {
        const subtotal = parseFloat(item.unit_price) * parseInt(item.quantity);
        itemsHtml += `
            <tr>
                <td>${item.name}</td>
                <td class="text-center">
                    <span class="badge bg-secondary">x${item.quantity}</span>
                </td>
                <td class="text-end">₱${parseFloat(item.unit_price).toFixed(2)}</td>
                <td class="text-end">₱${subtotal.toFixed(2)}</td>
            </tr>
        `;
    });
    document.getElementById('itemsList').innerHTML = itemsHtml;
    document.getElementById('itemsTotalAmount').textContent = `₱${parseFloat(order.total_amount).toFixed(2)}`;
    
    new bootstrap.Modal(document.getElementById('itemsModal')).show();
}

let alertCount = 0;
let alertInterval = null;

function sendSoundAlert() {
    if (alertCount >= 3 || alertInterval) {
        return; // Don't allow new alerts if we're still playing or reached limit
    }

    alertCount = 0;
    alertInterval = setInterval(() => {
        if (alertCount >= 3) {
            clearInterval(alertInterval);
            alertInterval = null;
            alertCount = 0;
            return;
        }

        $.ajax({
            url: '../api/send_alert.php',
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    alertCount++;
                    // Visual feedback
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: `Alert sent (${alertCount}/3)`,
                        showConfirmButton: false,
                        timer: 1000,
                        toast: true
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to send alert',
                    confirmButtonColor: '#4e73df'
                });
                clearInterval(alertInterval);
                alertInterval = null;
                alertCount = 0;
            }
        });
    }, 2000); // Send alert every 2 seconds
}
</script>
</body>
</html> 