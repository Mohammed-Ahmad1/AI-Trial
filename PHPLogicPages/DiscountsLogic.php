<?php


function AddDiscount($product_id, $discount_percent, $start_date, $end_date) {
    $conn = new mysqli('localhost', 'root', '', 'phpteamproject');
    if ($conn->connect_error) {
        die('DB ERROR: ' . $conn->connect_error);
    }

    // Insert directly — no checks, no extra steps
    $sql = "INSERT INTO discounts (product_id, discount_percent, start_date, end_date, is_active) 
            VALUES (?, ?, ?, ?, 1)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('PREPARE FAILED: ' . $conn->error);
    }

    $stmt->bind_param("idss", $product_id, $discount_percent, $start_date, $end_date);
    $ok = $stmt->execute();

    $stmt->close();
    
    

    return $ok;
}

function DeleteDiscount($discount_id) {
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) return false;

    $stmt = $conn->prepare("DELETE FROM discounts WHERE discount_id = ?");
    $stmt->bind_param("i", $discount_id);
    $ok = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $ok;
}