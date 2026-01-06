<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if product ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = intval($_GET['id']);

// Begin transaction to ensure data consistency
$conn->begin_transaction();

try {
    // Delete related records in the orders table
    $stmt = $conn->prepare("DELETE FROM orders WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();

    // Delete the product from the products table
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();

    // Optionally, delete the image file from the server if it exists
    $result = $conn->query("SELECT image FROM products WHERE id = $product_id");
    if ($result && $row = $result->fetch_assoc() && $row['image']) {
        if (file_exists($row['image'])) {
            unlink($row['image']);
        }
    }

    // Commit the transaction
    $conn->commit();
} catch (Exception $e) {
    // Roll back the transaction on error
    $conn->rollback();
    // Optionally, log the error or redirect with an error message
    header("Location: products.php?error=Unable to delete product: " . urlencode($e->getMessage()));
    exit();
}

$conn->close();

// Redirect back to products page
header("Location: products.php");
exit();
?>