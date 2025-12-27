<?php
require_once __DIR__ . '/../includes/config.php';

function ListAllAdmins()
{
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare(" SELECT user_id, first_name, last_name, email FROM users WHERE role = 'admin'");

    $stmt->execute();
    $result = $stmt->get_result();
    $admins = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();

    return $admins;
}

function AddNewAdmin($data)
{
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        return "Database connection failed";
    }

    $firstName = trim($data['first_name'] ?? '');
    $lastName  = trim($data['last_name'] ?? '');
    $password  = trim($data['password'] ?? '');
    $phone     = trim($data['phone'] ?? '');
    $email     = trim($data['email'] ?? '');
    $role      = 'admin';

    if ($firstName === '' || $lastName === '' || $password === '') {
        return "All required fields must be filled";
    }

    $stmt = $conn->prepare("
        INSERT INTO users (first_name, last_name, email, password, phone, role) VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("ssssss",$firstName,$lastName,$email,$password, $phone,$role);

    if (!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "Failed to add admin";
    }

    $stmt->close();
    $conn->close();

    return true;
}
