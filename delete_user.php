<?php
require_once __DIR__ . '/../PHPLogicPages/UsersLogic.php';

if (isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']); // sanitize input

    if (DeleteUser($user_id)) {
        header("Location: users.php?deleted=1");
        exit;
    } else {
        echo "Failed to delete user.";
    }
} else {
    echo "Invalid request.";
}
?>
