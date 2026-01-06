<?php
session_start();
include 'db.php';

// Check if user is logged in

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $product_price = floatval($_POST['product_price']);

    if ($user_id && $product_id && $quantity > 0 && $product_price > 0) {
        $total = $quantity * $product_price;
        $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, total, status) VALUES (?, ?, ?, ?, 'unpaid')");
        $stmt->bind_param("iiid", $user_id, $product_id, $quantity, $total);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php?ordered=1");
        exit();
    }
}

$conn->close();
header("Location: index.php");
exit();
?>