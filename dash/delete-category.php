<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if category ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: category.php");
    exit();
}

$category_id = intval($_GET['id']);

// Begin transaction to ensure data consistency
$conn->begin_transaction();

try {
    // Fetch products in this category to handle their images and related orders
    $stmt = $conn->prepare("SELECT id, image FROM products WHERE category = (SELECT name FROM categories WHERE id = ?)");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Delete related orders for each product
    foreach ($products as $product) {
        $stmt = $conn->prepare("DELETE FROM orders WHERE product_id = ?");
        $stmt->bind_param("i", $product['id']);
        $stmt->execute();
        $stmt->close();

        // Optionally, delete the product image from the server
        if ($product['image'] && file_exists($product['image'])) {
            unlink($product['image']);
        }
    }

    // Delete products in this category
    $stmt = $conn->prepare("DELETE FROM products WHERE category = (SELECT name FROM categories WHERE id = ?)");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $stmt->close();

    // Delete the category
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $stmt->close();

    // Commit the transaction
    $conn->commit();
} catch (Exception $e) {
    // Roll back the transaction on error
    $conn->rollback();
    // Redirect with an error message
    header("Location: category.php?error=Unable to delete category: " . urlencode($e->getMessage()));
    exit();
}

$conn->close();

// Redirect back to categories page
header("Location: category.php");
exit();
?>