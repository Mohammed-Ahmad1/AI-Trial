<?php
require_once __DIR__ . '/../includes/config.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: categories.php');
    exit;
}

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("DB error");

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM products WHERE category_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$count = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
$stmt->close();

if ($count > 0) {
    header('Location: categories.php?success=' . urlencode('Cannot delete: category has products'));
    exit;
}

$stmt = $conn->prepare("DELETE FROM categories WHERE category_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

$conn->close();

header('Location: categories.php?success=' . urlencode('Category deleted successfully'));
exit;
