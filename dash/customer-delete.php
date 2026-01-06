<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if customer ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: customers.php");
    exit();
}

$customer_id = intval($_GET['id']);

// Begin transaction to ensure data consistency
$conn->begin_transaction();

try {
    // Delete related orders
    $stmt = $conn->prepare("DELETE FROM orders WHERE user_id = ?");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $stmt->close();

    // Delete the customer
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $stmt->close();

    // Commit the transaction
    $conn->commit();
} catch (Exception $e) {
    // Roll back the transaction on error
    $conn->rollback();
    // Redirect with an error message
    header("Location: customers.php?error=Unable to delete customer: " . urlencode($e->getMessage()));
    exit();
}

$conn->close();

// Redirect back to customers page
header("Location: customers.php");
exit();
?>