<?php
require_once __DIR__ . '/../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products.php');
    exit;
}

$id = intval($_POST['product_id'] ?? 0);
if ($id <= 0) {
    header('Location: products.php');
    exit;
}

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("DB error");

// Get image path
$stmt = $conn->prepare("SELECT image FROM products WHERE product_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Delete product
$stmt = $conn->prepare("DELETE FROM products WHERE product_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Remove image file (optional)
if (!empty($res['image']) && file_exists($res['image']) && strpos($res['image'], 'products') !== false) {
    unlink($res['image']);
}

$conn->close();

header('Location: products.php?success=' . urlencode('Product deleted successfully'));
exit;