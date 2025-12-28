<?php
session_start();

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../PHPLogicPages/DiscountsLogic.php';

// Validate product_id from GET
if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    $_SESSION['error'] = "Invalid product ID.";
    header("Location: products.php");
    exit;
}

$product_id = intval($_GET['product_id']);

// Fetch product info
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT product_id, name, price FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Product not found.";
    $stmt->close();
    $conn->close();
    header("Location: products.php");
    exit;
}

$product = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Handle POST
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $discount_percent = isset($_POST['discount_percent']) ? floatval($_POST['discount_percent']) : null;
    $start_date = trim($_POST['start_date'] ?? '');
    $end_date = trim($_POST['end_date'] ?? '');

    // Validation
    if ($discount_percent === null || $discount_percent < 0 || $discount_percent > 100) {
        $error = "Discount percentage must be between 0 and 100.";
    } elseif (empty($start_date) || empty($end_date)) {
        $error = "Start date and end date are required.";
    } elseif (strtotime($end_date) < strtotime($start_date)) {
        $error = "End date must be on or after the start date.";
    } else {
        // Attempt to add discount
        if (AddDiscount($product_id, $discount_percent, $start_date, $end_date)) {
            $_SESSION['success'] = "✅ Discount of " . number_format($discount_percent, 2) . "% added successfully to '" . htmlspecialchars($product['name']) . "'.";
            header("Location: products.php");
            exit;
        } else {
            $error = "❌ Failed to add discount. Please check inputs and try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Discount — <?= htmlspecialchars($product['name']) ?> | FELUX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .main-content { margin-left: 260px; padding: 20px; }
        .bg-orange { background-color: #ff7f50 !important; }
        .card-header {
            font-weight: 600;
        }
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
        <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link"><i class="fas fa-home me-2"></i> Home</a></li>
        <li class="nav-item mb-2"><a href="orders.php" class="nav-link"><i class="fas fa-box me-2"></i> Orders</a></li>
        <li class="nav-item mb-2"><a href="products.php" class="nav-link active"><i class="fas fa-tag me-2"></i> Products</a></li>
        <li class="nav-item mb-2"><a href="categories.php" class="nav-link"><i class="fas fa-folder me-2"></i> Categories</a></li>
        <li class="nav-item mb-2"><a href="users.php" class="nav-link"><i class="fas fa-users me-2"></i> Users</a></li>
        <li class="nav-item mb-2"><a href="admins.php" class="nav-link"><i class="fas fa-user-shield me-2"></i> Admins</a></li>
        <li class="nav-item mb-2"><a href="edit_profile.php" class="nav-link"><i class="fas fa-user-edit me-2"></i> Edit Profile</a></li>
        <li class="nav-item"><a href="../Team-project-php/login.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-percent text-warning me-2"></i> Add Discount</h1>
        <a href="products.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success'] /* safe due to prior escaping */ ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Product Info -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-box-open me-2"></i> Target Product
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Product ID:</strong> #<?= htmlspecialchars($product['product_id']) ?>
                </div>
                <div class="col-md-4">
                    <strong>Name:</strong> <?= htmlspecialchars($product['name']) ?>
                </div>
                <div class="col-md-4">
                    <strong>Price:</strong> <span class="text-success fw-bold">JD <?= number_format($product['price'], 2) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Discount Form -->
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <i class="fas fa-tag me-2"></i> Discount Settings
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label for="discount_percent" class="form-label">Discount Percentage (%)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0" max="100" name="discount_percent" id="discount_percent"
                               class="form-control" required 
                               value="<?= isset($_POST['discount_percent']) ? htmlspecialchars($_POST['discount_percent']) : '' ?>">
                        <span class="input-group-text">%</span>
                    </div>
                    <div class="form-text">e.g., 10.5 for 10.5% off</div>
                </div>

                <div class="col-md-6">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" required
                           value="<?= isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date']) : date('Y-m-d') ?>">
                </div>

                <div class="col-md-6">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" required
                           value="<?= isset($_POST['end_date']) ? htmlspecialchars($_POST['end_date']) : date('Y-m-d', strtotime('+7 days')) ?>">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-plus-circle me-2"></i> Apply Discount
                    </button>
                    <a href="products.php" class="btn btn-secondary px-4 ms-2">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>