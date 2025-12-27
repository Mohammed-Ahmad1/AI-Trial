<?php

function GetTotalSales()
{
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT SUM(amount) AS total_sales FROM Payments");
    $stmt->execute();

    $totalSales = $stmt->get_result()->fetch_assoc()['total_sales'] ?? 0;

    $stmt->close();
    $conn->close();

    return $totalSales;
}