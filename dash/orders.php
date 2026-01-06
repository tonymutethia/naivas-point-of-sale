<?php
include '../db.php';

// Fetch users (customers) for the form
$result = $conn->query("SELECT id, firstname, lastname FROM users");
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Fetch products for the form
$result = $conn->query("SELECT id, name, price FROM products");
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Fetch all orders (use LEFT JOIN for users and products)
$result = $conn->query("
    SELECT o.id, 
           CONCAT(
               COALESCE(CONCAT(u.firstname, ' ', u.lastname), 'Unknown User'),
               ' - ',
               COALESCE(p.name, 'Unknown Product')
           ) AS customer_product, 
           o.total, 
           o.status, 
           o.order_date
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    LEFT JOIN products p ON o.product_id = p.id
    ORDER BY o.order_date DESC
");
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

// Handle form submission (add order)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = !empty($_POST['user_id']) ? intval($_POST['user_id']) : 0; // Default to guest user_id = 0
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    
    // Fetch product price
    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($product && $quantity > 0) {
        $total = $quantity * $product['price'];
        $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, total, status) VALUES (?, ?, ?, ?, 'unpaid')");
        $stmt->bind_param("iiid", $user_id, $product_id, $quantity, $total);
        if (!$stmt->execute()) {
            echo "Error inserting order: " . $conn->error;
            $stmt->close();
            $conn->close();
            exit;
        }
        $stmt->close();
    }
    
    header("Location: orders.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dash.css">
<style>
@media print {
    body * {
        visibility: hidden;
    }
    .main-content, .main-content * {
        visibility: visible;
    }
    .main-content {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        background: white;
        color: black;
        padding: 20px;
    }
    .btn, .form-select, form, header, .card-title, .card-body > form {
        display: none !important;
    }
    table {
        width: 100%;
        border-collapse: collapse !important;
    }
    th, td {
        border: 1px solid #000 !important;
        padding: 5px;
        color: black !important;
    }
}
</style>

</head>

<body class="bg-dark text-light">
    <div class="d-flex">
        <!-- Sidebar -->
        <?php include 'sidebar.html'; ?>
        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4">
            <!-- Header -->
            <header class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manage Orders</h2>
                <button class="btn btn-outline-light btn-sm">Profile</button>
            </header>

            <!-- Horizontal Form -->
            <div class="card bg-dark-purple text-light mb-4">
                <div class="card-body">
                    <h5 class="card-title">Add Order</h5>
                    <form action="orders.php" method="POST" class="row g-3">
                        <div class="col-md-3">
                            <label for="user_id" class="form-label">Customer</label>
                            <select class="form-select bg-dark text-light" id="user_id" name="user_id">
                                <option value="">No Customer (Guest)</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?php echo $user['id']; ?>">
                                        <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select class="form-select bg-dark text-light" id="product_id" name="product_id" required>
                                <option value="">Select Product</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?php echo $product['id']; ?>">
                                        <?php echo htmlspecialchars($product['name']); ?> ($<?php echo number_format($product['price'], 2); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control bg-dark text-light" id="quantity" name="quantity" min="1" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Add</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="card bg-dark-purple text-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="card-title">Order List</h5>
    <button class="btn btn-outline-light btn-sm" onclick="window.print()">Print Orders</button>
</div>

                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer - Product</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['id']; ?></td>
                                        <td><?php echo htmlspecialchars($order['customer_product']); ?></td>
                                        <td>$<?php echo number_format($order['total'], 2); ?></td>
                                        <td>
                                            <?php 
                                            $status = $order['status'] ?: 'Unknown';
                                            echo htmlspecialchars($status);
                                            ?>
                                        </td>
                                        <td><?php echo $order['order_date']; ?></td>
                                        <td>
                                            <?php if ($order['status'] === 'paid'): ?>
                                                <span class="badge bg-success">Paid</span>
                                            <?php else: ?>
                                                <a href="pay_order.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-success">Pay Now</a>
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

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>