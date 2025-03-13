<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

try {
    $table_id = $_POST['table_id'];
    $items = json_decode($_POST['items'], true);
    
    if (!$items || !is_array($items)) {
        throw new Exception('Invalid items data');
    }
    
    // Calculate total amount
    $total_amount = 0;
    foreach ($items as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }
    
    $conn->beginTransaction();
    
    // Create order
    $stmt = $conn->prepare("
        INSERT INTO orders (table_id, order_type, status, total_amount) 
        VALUES (?, 'QR', 'PENDING', ?)
    ");
    $stmt->execute([$table_id, $total_amount]);
    $order_id = $conn->lastInsertId();
    
    // Insert order items
    $stmt = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal) 
        VALUES (?, ?, ?, ?, ?)
    ");
    
    foreach ($items as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $stmt->execute([
            $order_id,
            $item['productId'],
            $item['quantity'],
            $item['price'],
            $subtotal
        ]);
    }
    
    // Update table status
    $stmt = $conn->prepare("UPDATE tables SET status = 'OCCUPIED' WHERE table_id = ?");
    $stmt->execute([$table_id]);
    
    // Create notification for cashier
    $stmt = $conn->prepare("
        INSERT INTO notifications (order_id, message, type) 
        VALUES (?, 'New order received for Table #" . $table_id . "', 'PAYMENT')
    ");
    $stmt->execute([$order_id]);
    
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'order_id' => $order_id,
        'message' => 'Order submitted successfully'
    ]);
    
} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?> 