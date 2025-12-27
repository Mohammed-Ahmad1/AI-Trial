<?php
require_once __DIR__ . '/../includes/config.php';

function ListAllCategories() {
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT category_id, name, description, created_at FROM categories");
    
    $stmt->execute();

    $result = $stmt->get_result();

    $categories = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }

    $stmt->close();
    $conn->close();

    return $categories;
}


function GetNumberOfCategories()
{
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM categories");
    $stmt->execute();

    $total = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

    $stmt->close();
    $conn->close();

    return $total;
}
?>