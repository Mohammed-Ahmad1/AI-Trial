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
function ListAllProducts($start = 0, $limit = 50, $name = '', $category_id = '', $min_price = '', $max_price = '')
{
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $sql = "
        SELECT 
            p.product_id,
            p.image,
            p.name AS ProductName,
            p.price,
            c.name AS CategoryName
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        WHERE 1=1
    ";

    $params = [];
    $types  = "";

    if (!empty($name)) {
        $sql .= " AND p.name LIKE ?";
        $params[] = "%" . $name . "%";
        $types .= "s";
    }

    if (!empty($category_id)) {
        $sql .= " AND p.category_id = ?";
        $params[] = $category_id;
        $types .= "i";
    }

    if ($min_price !== '') {
        $sql .= " AND p.price >= ?";
        $params[] = $min_price;
        $types .= "d";
    }

    if ($max_price !== '') {
        $sql .= " AND p.price <= ?";
        $params[] = $max_price;
        $types .= "d";
    }

    $sql .= " ORDER BY p.product_id DESC LIMIT ?, ?";
    $params[] = $start;
    $params[] = $limit;
    $types .= "ii";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $result = $stmt->get_result();
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $products;
}
