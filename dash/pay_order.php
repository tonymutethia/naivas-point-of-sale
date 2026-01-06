<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if order ID is provided
if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = intval($_GET['id']);

// Update order status to paid
$stmt = $conn->prepare("UPDATE orders SET status = 'paid' WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: orders.php");
exit();
?>