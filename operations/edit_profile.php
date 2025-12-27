<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = "";
$success = "";

// Connect to database
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Get current password from database
$stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$current_password_db = $user['password'];
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Simple validations
    if ($current_password != $current_password_db) {
        $error = "Current password is incorrect.";
    } elseif ($new_password != $confirm_password) {
        $error = "New password and confirm password do not match.";
    } elseif (strlen($new_password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Update password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->bind_param("si", $new_password, $user_id);
        if ($stmt->execute()) {
            $success = "Password updated successfully!";
        } else {
            $error = "Something went wrong. Try again.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - FELUX</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<style>
    .main-content { margin-left: 260px; padding: 20px; }
    .bg-orange { background-color: #ff7f50 !important; }
</style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar bg-light p-3 vh-100 position-fixed">
        <div class="d-flex align-items-center mb-4">
            <i class="fas fa-store fa-2x me-2 text-success"></i>
            <h4 class="m-0">FELUX</h4>
        </div>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link d-flex align-items-center"><i class="fas fa-home me-2"></i> Home</a></li>
            <li class="nav-item mb-2"><a href="orders.php" class="nav-link d-flex align-items-center"><i class="fas fa-box me-2"></i> Orders</a></li>
            <li class="nav-item mb-2"><a href="products.php" class="nav-link d-flex align-items-center"><i class="fas fa-tag me-2"></i> Product</a></li>
            <li class="nav-item mb-2"><a href="categories.php" class="nav-link d-flex align-items-center"><i class="fas fa-folder me-2"></i> Category</a></li>
            <li class="nav-item mb-2"><a href="users.php" class="nav-link d-flex align-items-center"><i class="fas fa-users me-2"></i> Users</a></li>
            <li class="nav-item mb-2"><a href="admins.php" class="nav-link d-flex align-items-center"><i class="fas fa-user-shield me-2"></i> Admins</a></li>
            <li class="nav-item mb-2"><a href="edit_profile.php" class="nav-link d-flex align-items-center active"><i class="fas fa-user-edit me-2"></i> Edit Profile</a></li>
            <li class="nav-item mt-0"><a href="login.php" class="nav-link d-flex align-items-center text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1 class="mb-4">Edit Profile</h1>

        <div class="card shadow-sm">
            <div class="card-body">
                <form id="editProfileForm" method="POST">
                    <div class="mb-3">
                        <input type="password" name="current_password" class="form-control" placeholder="Current Password" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="new_password" class="form-control" placeholder="Enter New Password" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm New Password" required>
                    </div>

                    <?php if(!empty($error)) echo '<div class="text-danger mb-3">'.$error.'</div>'; ?>
                    <?php if(!empty($success)) echo '<div class="text-success mb-3">'.$success.'</div>'; ?>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Update Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
