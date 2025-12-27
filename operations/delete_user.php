<?php
require_once __DIR__ . '/../PHPLogicPages/UsersLogic.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: users.php');
    exit;
}

$user_id = intval($_POST['user_id'] ?? 0);
if ($user_id <= 0) {
    header('Location: users.php');
    exit;
}

// Delete user
$result = DeleteUser($user_id);

// Show popup based on result
if ($result === true) {
    echo "<script>alert('User deleted successfully'); window.location.href='users.php';</script>";
} elseif ($result === 'HAS_ORDERS') {
    echo "<script>alert('Cannot delete this user because they have orders'); window.location.href='users.php';</script>";
} else {
    echo "<script>alert('Unable to delete user'); window.location.href='users.php';</script>";
}

exit;
