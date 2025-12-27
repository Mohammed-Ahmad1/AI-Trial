<?php
require_once __DIR__ . '/../includes/config.php';

function GetNumberOfProducts() {
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) die("DB error: " . $conn->connect_error);

    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM products");
    $stmt->execute();
    $total = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt->close(); $conn->close();
    return $total;
}

function ListAllProducts($start, $limit) {
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) die("DB error: " . $conn->connect_error);

    $sql = "SELECT p.product_id,  p.name AS ProductName, p.price, p.image, 
            p.description, COALESCE(c.name, 'Uncategorized') AS CategoryName FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            ORDER BY p.product_id DESC
            LIMIT ?, ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $start, $limit);
    $stmt->execute();
    $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close(); $conn->close();
    return $products;
}
?>