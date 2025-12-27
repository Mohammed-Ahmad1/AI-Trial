<?php
require_once __DIR__ . '/../includes/config.php';


function GetNumberOfUsers()
{
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM users where role!='admin' and email !='admin@gmail.com'");
    $stmt->execute();

    $total = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

    $stmt->close();
    $conn->close();

    return $total;
}

function ListAllUsers()
{
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM users where role!='admin' and email !='admin@gmail.com'");
    $stmt->execute();

    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();

    return $users;
}

function DeleteUser($user_id) {
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        return false;
    }

    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return true;
    } catch (mysqli_sql_exception $e) {
        // 1451 = Cannot delete or update a parent row: a foreign key constraint fails
        if ($e->getCode() == 1451) {
            return 'HAS_ORDERS';
        }
        return false;
    }
}
