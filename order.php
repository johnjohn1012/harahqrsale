<?php
session_start();
require_once 'config/database.php';

// Get table info from QR code
$qr_code = isset($_GET['table']) ? $_GET['table'] : null;

if (!$qr_code) {
    die('Invalid table QR code');
}

try {
    // Verify table exists and get table info
    $stmt = $conn->prepare("SELECT * FROM tables WHERE qr_code = ?");
    $stmt->execute([$qr_code]);
    $table = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$table) {
        die('Invalid table QR code');
    }

    // Get all available categories
    $stmt = $conn->query("SELECT * FROM categories ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get all available products
    $stmt = $conn->query("SELECT * FROM products WHERE is_available = 1 ORDER BY name");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Store table info in session
$_SESSION['table_id'] = $table['table_id'];
$_SESSION['table_number'] = $table['table_number'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order - Table <?php echo htmlspecialchars($table['table_number']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #4e73df, #224abe);
            padding: 1rem;
        }

        .navbar-brand {
            font-weight: 600;
            color: white !important;
        }

        .table-info {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .category-tabs {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 20px;
            padding: 10px 0;
        }

        .category-tabs .nav-link {
            color: var(--secondary-color);
            border-radius: 20px;
            padding: 8px 20px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .category-tabs .nav-link.active {
            background: var(--primary-color);
            color: white;
        }

        .menu-item {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .menu-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .menu-item-content {
            padding: 20px;
        }

        .menu-item-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .menu-item-price {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.2rem;
        }

        .menu-item-description {
            color: var(--secondary-color);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            background: #f8f9fa;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        .cart-fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--primary-color);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cart-fab:hover {
            transform: scale(1.1);
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74a3b;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #cartModal .modal-content {
            border-radius: 15px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
            flex-wrap: wrap;
            gap: 10px;
        }

        .cart-item-info {
            flex-grow: 1;
            padding: 0 15px;
            min-width: 200px;
        }

        .cart-item-title {
            font-weight: 500;
            margin-bottom: 5px;
            font-size: 1rem;
        }

        .cart-item-price {
            color: var(--primary-color);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 8px 0;
        }

        .cart-item-quantity-btn {
            background: #f8f9fa;
            border: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
        }

        .cart-item-quantity-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        .cart-item-subtotal {
            font-weight: 600;
            color: var(--primary-color);
            min-width: 100px;
            text-align: right;
        }

        .cart-total {
            font-size: 1.2rem;
            font-weight: 600;
            padding: 15px;
            border-top: 2px solid #eee;
            background: #f8f9fa;
            border-radius: 0 0 15px 15px;
        }

        .search-box {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .category-selector {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .search-input {
            border-radius: 25px;
            padding: 10px 20px;
            border: 2px solid #eee;
            width: 100%;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }

        .category-select {
            border-radius: 25px;
            padding: 10px 20px;
            border: 2px solid #eee;
            width: 100%;
            transition: all 0.3s ease;
        }

        .category-select:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .menu-item {
                margin-bottom: 15px;
            }

            .menu-item img {
                height: 150px;
            }

            .cart-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 10px;
            }

            .cart-item-info {
                width: 100%;
                padding: 0;
            }

            .cart-item-subtotal {
                width: 100%;
                text-align: left;
                margin-top: 10px;
            }

            .cart-item-quantity {
                margin: 5px 0;
            }

            .modal-dialog {
                margin: 10px;
            }

            .cart-fab {
                width: 50px;
                height: 50px;
                font-size: 20px;
                bottom: 15px;
                right: 15px;
            }
        }

        @media (max-width: 576px) {
            .menu-item-content {
                padding: 15px;
            }

            .menu-item-title {
                font-size: 1rem;
            }

            .menu-item-price {
                font-size: 0.9rem;
            }

            .quantity-btn {
                width: 25px;
                height: 25px;
                font-size: 0.8rem;
            }

            .cart-item-title {
                font-size: 0.9rem;
            }

            .cart-item-price {
                font-size: 0.8rem;
            }

            .cart-total {
                font-size: 1.1rem;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container">
            <span class="navbar-brand">
                <i class="fas fa-utensils me-2"></i>
                Table <?php echo htmlspecialchars($table['table_number']); ?>
            </span>
            <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#policyModal">
                <i class="fas fa-info-circle me-2"></i>Policies & Rules
            </button>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="table-info">
            <h4><i class="fas fa-info-circle me-2"></i>Welcome to Table <?php echo htmlspecialchars($table['table_number']); ?></h4>
            <p class="text-muted mb-0">Scan, Order, and Enjoy your meal!</p>
        </div>

        <div class="search-box">
            <input type="text" class="search-input" id="searchInput" placeholder="Search for items...">
        </div>

        <div class="category-selector">
            <select class="category-select" id="categorySelect">
                <option value="all">All Categories</option>
                <?php foreach($categories as $category): ?>
                <option value="<?php echo $category['category_id']; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="row" id="menuItems">
            <?php foreach($products as $product): ?>
            <div class="col-md-6 col-lg-4 menu-item-container" 
                 data-category="<?php echo $product['category_id']; ?>"
                 data-name="<?php echo htmlspecialchars(strtolower($product['name'])); ?>">
                <div class="menu-item" data-product-id="<?php echo $product['product_id']; ?>">
                    <?php if($product['image_url']): ?>
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php endif; ?>
                    <div class="menu-item-content">
                        <h5 class="menu-item-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="menu-item-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="menu-item-price">₱<?php echo number_format($product['price'], 2); ?></span>
                            <div class="quantity-control">
                                <button class="quantity-btn" onclick="updateQuantity(<?php echo $product['product_id']; ?>, -1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span id="quantity-<?php echo $product['product_id']; ?>">0</span>
                                <button class="quantity-btn" onclick="updateQuantity(<?php echo $product['product_id']; ?>, 1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Cart Button -->
    <button type="button" class="cart-fab" data-bs-toggle="modal" data-bs-target="#cartModal">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-badge" id="cartCount">0</span>
    </button>

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Your Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="cartItems">
                    <!-- Cart items will be inserted here -->
                </div>
                <div class="cart-total">
                    Total: ₱<span id="cartTotal">0.00</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue Ordering</button>
                    <button type="button" class="btn btn-primary" onclick="placeOrder()">Place Order</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Policy Modal -->
    <div class="modal fade" id="policyModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Restaurant Policies & Rules</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="policy-section mb-4">
                        <h6 class="fw-bold text-primary mb-3">Ordering & Payment</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>All orders must be paid at the counter before leaving</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Please State your Table Number when paying</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>We accept cash and GCash payments</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Orders are prepared in the order they are received</li>
                        </ul>
                    </div>
                    <div class="policy-section mb-4">
                        <h6 class="fw-bold text-primary mb-3">Table Rules</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Please stay at your assigned table</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Maximum stay time is 2 hours during peak hours</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Please keep noise levels appropriate</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Children must be supervised at all times</li>
                        </ul>
                    </div>
                    <div class="policy-section">
                        <h6 class="fw-bold text-primary mb-3">Food & Safety</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Please inform staff of any allergies</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Food cannot be returned once served</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Outside food and drinks are not allowed</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Smoking is not allowed inside the restaurant</li>
                        </ul>
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
        let cart = {};
        let products = <?php echo json_encode($products); ?>;
        let cartModal = null;

        document.addEventListener('DOMContentLoaded', function() {
            cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
            
            // Add event listener for cart modal show
            document.getElementById('cartModal').addEventListener('show.bs.modal', function () {
                updateCartDisplay();
            });

            // Add event listeners for search and category filter
            document.getElementById('searchInput').addEventListener('input', filterItems);
            document.getElementById('categorySelect').addEventListener('change', filterItems);
        });

        function filterItems() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const selectedCategory = document.getElementById('categorySelect').value;
            
            document.querySelectorAll('.menu-item-container').forEach(container => {
                const category = container.dataset.category;
                const name = container.dataset.name;
                
                const matchesSearch = name.includes(searchTerm);
                const matchesCategory = selectedCategory === 'all' || category === selectedCategory;
                
                container.style.display = matchesSearch && matchesCategory ? 'block' : 'none';
            });
        }

        function updateQuantity(productId, change) {
            const currentQuantity = cart[productId] || 0;
            const newQuantity = Math.max(0, currentQuantity + change);
            
            if (newQuantity === 0) {
                delete cart[productId];
            } else {
                cart[productId] = newQuantity;
            }
            
            document.getElementById(`quantity-${productId}`).textContent = newQuantity;
            updateCartCount();
            
            // Update cart display if modal is open
            const cartModal = document.getElementById('cartModal');
            if (cartModal.classList.contains('show')) {
                updateCartDisplay();
            }
        }

        function updateCartCount() {
            const count = Object.values(cart).reduce((a, b) => a + b, 0);
            document.getElementById('cartCount').textContent = count;
        }

        function updateCartDisplay() {
            let cartHtml = '';
            let total = 0;

            for (const productId in cart) {
                const product = products.find(p => p.product_id == productId);
                if (product) {
                    const quantity = cart[productId];
                    const subtotal = parseFloat(product.price) * quantity;
                    total += subtotal;

                    cartHtml += `
                        <div class="cart-item">
                            <div class="cart-item-info">
                                <div class="cart-item-title">${product.name}</div>
                                <div class="cart-item-price">₱${parseFloat(product.price).toFixed(2)}</div>
                                <div class="cart-item-quantity">
                                    <button class="cart-item-quantity-btn" onclick="updateCartQuantity(${productId}, -1)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span>${quantity}</span>
                                    <button class="cart-item-quantity-btn" onclick="updateCartQuantity(${productId}, 1)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="cart-item-subtotal">₱${subtotal.toFixed(2)}</div>
                        </div>
                    `;
                }
            }

            document.getElementById('cartItems').innerHTML = cartHtml || '<p class="text-center p-3">Your cart is empty</p>';
            document.getElementById('cartTotal').textContent = total.toFixed(2);
        }

        function updateCartQuantity(productId, change) {
            const currentQuantity = cart[productId] || 0;
            const newQuantity = Math.max(0, currentQuantity + change);
            
            if (newQuantity === 0) {
                delete cart[productId];
            } else {
                cart[productId] = newQuantity;
            }
            
            // Update both cart display and menu item quantity
            document.getElementById(`quantity-${productId}`).textContent = newQuantity;
            updateCartCount();
            updateCartDisplay();
        }

        function placeOrder() {
            if (Object.keys(cart).length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Empty Cart',
                    text: 'Please add items to your cart before placing an order.',
                    confirmButtonColor: '#4e73df'
                });
                return;
            }

            const orderItems = [];
            for (const productId in cart) {
                orderItems.push({
                    product_id: productId,
                    quantity: cart[productId]
                });
            }

            fetch('api/place_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    table_id: <?php echo $table['table_id']; ?>,
                    items: orderItems
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Placed Successfully!',
                        html: `
                            <div class="text-start">
                                <p class="mb-3">Your order has been received and is being prepared.</p>
                                <div class="alert alert-info">
                                    <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Important Instructions:</h6>
                                    <ol class="mb-0">
                                        <li>Please proceed to the counter to pay your order</li>
                                        <li>State your table number: <strong>Table <?php echo htmlspecialchars($table['table_number']); ?></strong></li>
                                        <li>Payment methods accepted: Cash and GCash</li>
                                        <li>Your food will be served at your table</li>
                                    </ol>
                                </div>
                                <div class="alert alert-warning">
                                    <h6 class="mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Please Note:</h6>
                                    <ul class="mb-0">
                                        <li>Payment must be completed before leaving</li>
                                        <li>Orders are prepared in the order they are received</li>
                                        <li>Please inform staff of any allergies or special requests</li>
                                    </ul>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'I Understand',
                        confirmButtonColor: '#4e73df',
                        showCancelButton: true,
                        cancelButtonText: 'View Policies',
                        cancelButtonColor: '#858796'
                    }).then((result) => {
                        // Clear all fields regardless of which button was clicked
                        clearAllFields();
                        
                        if (!result.isConfirmed) {
                            // Show policy modal
                            const policyModal = new bootstrap.Modal(document.getElementById('policyModal'));
                            policyModal.show();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Failed to place order. Please try again.',
                        confirmButtonColor: '#4e73df'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while placing your order. Please try again.',
                    confirmButtonColor: '#4e73df'
                });
                console.error('Error:', error);
            });
        }

        function clearAllFields() {
            // Clear cart
            cart = {};
            updateCartCount();
            
            // Reset all quantity displays
            document.querySelectorAll('[id^="quantity-"]').forEach(el => el.textContent = '0');
            
            // Clear search input
            document.getElementById('searchInput').value = '';
            
            // Reset category selector
            document.getElementById('categorySelect').value = 'all';
            
            // Reset item visibility
            document.querySelectorAll('.menu-item-container').forEach(container => {
                container.style.display = 'block';
            });
            
            // Close cart modal
            if (cartModal) {
                cartModal.hide();
            }
        }
    </script>
</body>
</html> 