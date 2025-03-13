<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

// Handle table creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $table_number = $_POST['table_number'];
        $qr_code = 'table_' . $table_number . '_' . uniqid();
        
        $stmt = $conn->prepare("INSERT INTO tables (table_number, qr_code, status) VALUES (?, ?, 'AVAILABLE')");
        $stmt->execute([$table_number, $qr_code]);
        
        $success = "Table {$table_number} created successfully!";
    } catch (PDOException $e) {
        $error = "Error creating table: " . $e->getMessage();
    }
}

// Get all tables
try {
    $stmt = $conn->query("SELECT * FROM tables ORDER BY table_number");
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tables - HarahQR Sales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
        }

        .navbar {
            background: linear-gradient(135deg, #4e73df, #224abe);
            padding: 1rem;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .table-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

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

        .btn-add-table {
            background: linear-gradient(135deg, #4e73df, #224abe);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-add-table:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
            color: white;
        }

        .btn-view-order {
            background: #f8f9fa;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            color: #4e73df;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-view-order:hover {
            background: #4e73df;
            color: white;
        }
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
                        <a class="nav-link" href="dashboard.php">
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
                        <a class="nav-link active" href="manage_tables.php">
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
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Add New Table</h5>
                        <form method="POST" class="d-flex gap-3">
                            <input type="number" name="table_number" class="form-control" placeholder="Table Number" required>
                            <button type="submit" class="btn btn-add-table">
                                <i class="fas fa-plus me-2"></i>Add Table
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <?php foreach($tables as $table): ?>
            <div class="col-md-6 col-lg-4">
                <div class="table-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Table <?php echo htmlspecialchars($table['table_number']); ?></h5>
                        <span class="status-badge status-<?php echo strtolower($table['status']); ?>">
                            <?php echo ucfirst(strtolower($table['status'])); ?>
                        </span>
                    </div>
                    
                    <div class="qr-code" id="qrcode-<?php echo $table['table_id']; ?>"></div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button class="btn btn-view-order" onclick="window.open('../order.php?table=<?php echo $table['qr_code']; ?>', '_blank')">
                            <i class="fas fa-external-link-alt me-2"></i>View Order Page
                        </button>
                        <button class="btn btn-link" onclick="downloadQR(<?php echo $table['table_id']; ?>, <?php echo $table['table_number']; ?>)">
                            <i class="fas fa-download me-2"></i>Download QR
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Generate QR codes for each table
        <?php foreach($tables as $table): ?>
        new QRCode(document.getElementById("qrcode-<?php echo $table['table_id']; ?>"), {
            text: window.location.origin + "/harahqrsales/order.php?table=<?php echo $table['qr_code']; ?>",
            width: 128,
            height: 128,
            colorDark: "#2c3e50",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
        <?php endforeach; ?>

        function downloadQR(tableId, tableNumber) {
            const canvas = document.querySelector(`#qrcode-${tableId} canvas`);
            const link = document.createElement('a');
            link.download = `table-${tableNumber}-qr.png`;
            link.href = canvas.toDataURL();
            link.click();
        }
    </script>
</body>
</html> 