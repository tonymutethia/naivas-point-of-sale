<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$user_id = intval($_SESSION['user_id']);
$stmt = $conn->prepare("SELECT firstname, lastname FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
$user_name = $user ? htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) : "Admin";

// Fetch total sales today (paid orders only)
$today = date('Y-m-d'); // e.g., 2025-06-10
$stmt = $conn->prepare("SELECT SUM(total) as total_sales FROM orders WHERE DATE(order_date) = ? AND status = 'paid'");
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$total_sales = $result['total_sales'] ?? 0.00;
$stmt->close();

// Fetch total products
$result = $conn->query("SELECT COUNT(*) as count FROM products");
$product_count = $result->fetch_assoc()['count'] ?? 0;

// Fetch total customers
$result = $conn->query("SELECT COUNT(*) as count FROM users");
$customer_count = $result->fetch_assoc()['count'] ?? 0;

// Fetch recent orders (latest 5)
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
$recent_orders = [];
while ($row = $result->fetch_assoc()) {
    $recent_orders[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Dashboard</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dash.css">
</head>
<body class="bg-dark text-light">
    <div class="d-flex">
        <!-- Sidebar -->
       <?php include 'sidebar.html';?>

        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4">
            <!-- Header -->
            <header class="d-flex justify-content-between align-items-center mb-4">
                
                <button class="btn btn-outline-light btn-sm">Profile</button>
            </header>

            <!-- Widgets -->
            <div class="row">
                <!-- Sales Summary -->
                <div class="col-md-4 mb-4">
                    <div class="card bg-dark-purple text-light">
                        <div class="card-body">
                            <h5 class="card-title">Total Sales Today</h5>
                            <p class="card-text display-6">ksh<?php echo number_format($total_sales, 2); ?></p>
                        </div>
                    </div>
                </div>
                <!-- Product Count -->
                <div class="col-md-4 mb-4">
                    <div class="card bg-dark-purple text-light">
                        <div class="card-body">
                            <h5 class="card-title">Total Products</h5>
                            <p class="card-text display-6"><?php echo $product_count; ?></p>
                        </div>
                    </div>
                </div>
                <!-- Customer Count -->
                <div class="col-md-4 mb-4">
                    <div class="card bg-dark-purple text-light">
                        <div class="card-body">
                            <h5 class="card-title">Total Customers</h5>
                            <p class="card-text display-6"><?php echo $customer_count; ?></p>
                        </div>
                    </div>
                </div>
            <!-- Recent Orders -->
             <div class="card bg-dark-purple text-light">
             
            </div>
                    <h5 class="card-title">Recent Orders</h5>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer Product</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['id']; ?></td>
                                        <td><?php echo htmlspecialchars($order['customer_product']); ?></td>
                                        <td>$<?php echo number_format($order['total'], 2); ?></td>
                                        <td><?php echo $order['order_date']; ?></td>
                                        <td><a href="orders.php" class="btn btn-sm btn-primary">View</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>