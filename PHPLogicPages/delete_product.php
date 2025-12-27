<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../PHPLogicPages/ProductsLogic.php'; // Adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int) $_POST['product_id']; // cast to int for safety

    if (deleteProduct($product_id)) {
        header("Location: products.php");
        exit;
    } else {
        echo "Error deleting product.";
    }
} else {
    // Invalid request
    header("Location: products.php");
    exit;
}

function deleteProduct($product_id) {
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $product_id);
    $success = $stmt->execute();

    $stmt->close();
    $conn->close();

    return $success;
}
