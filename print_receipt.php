<?php
include 'db.php';

// Check if product ID is provided
if (!isset($_GET['id'])) {
    echo "Product ID not found.";
    exit;
}

$product_id = intval($_GET['id']);
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Default quantity to 1
$default_user_id = 0; // Guest user ID
$status = 'unpaid'; // Default status

// Validate quantity
if ($quantity < 1) {
    echo "Invalid quantity.";
    exit;
}

// 1. Fetch product
$stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Product not found.";
    $stmt->close();
    $conn->close();
    exit;
}

$product = $result->fetch_assoc();
$stmt->close();

// 2. Calculate total
$total = $product['price'] * $quantity;

// 3. Insert into orders table
$insert = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, total, status, order_date) VALUES (?, ?, ?, ?, ?, NOW())");
$insert->bind_param("iiids", $default_user_id, $product_id, $quantity, $total, $status);
if (!$insert->execute()) {
    echo "Error inserting order: " . $conn->error;
    $insert->close();
    $conn->close();
    exit;
}
$order_id = $conn->insert_id; // Get inserted order ID
$insert->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grocery POS Receipt</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 2rem;
            font-family: Arial, sans-serif;
        }
        .receipt-box {
            max-width: 600px;
            margin: auto;
            padding: 2rem;
            border: 1px solid #eee;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body onload="window.print();">
    <div class="receipt-box">
        <div class="receipt-header">
            <h2>Naivas Grocery POS Receipt</h2>
            <small><?php echo date('d M Y, H:i'); ?></small>
        </div>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>
        <p><strong>Product:</strong> <?php echo htmlspecialchars($product['name']); ?></p>
        <p><strong>Price per Unit:</strong> $<?php echo number_format($product['price'], 2); ?></p>
        <p><strong>Quantity:</strong> <?php echo htmlspecialchars($quantity); ?></p>
        <p><strong>Total:</strong> $<?php echo number_format($total, 2); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($status); ?></p>
        <p><strong>Order Date:</strong> <?php echo date('d M Y, H:i'); ?></p>
        <hr>
        <p>Thank you for shopping with Naivas!</p>
    </div>
</body>
</html>