<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../PHPLogicPages/DiscountsLogic.php';

// Validate IDs
$discount_id = isset($_GET['discount_id']) ? intval($_GET['discount_id']) : 0;
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if (!$discount_id || !$product_id) {
    $_SESSION['error'] = "Invalid discount or product ID.";
    header("Location: products.php");
    exit;
}

// Fetch discount + product data
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("DB error");

// Get discount
$stmt = $conn->prepare("
    SELECT d.*, p.name AS product_name, p.price AS original_price
    FROM discounts d
    JOIN products p ON d.product_id = p.product_id
    WHERE d.discount_id = ? AND d.product_id = ?
");
$stmt->bind_param("ii", $discount_id, $product_id);
$stmt->execute();
$discount = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$discount) {
    $_SESSION['error'] = "Discount not found.";
    $conn->close();
    header("Location: products.php");
    exit;
}

$product_name = $discount['product_name'];
$original_price = $discount['original_price'];
$current_discounted = $original_price * (1 - $discount['discount_percent'] / 100);

$error = "";

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'update'; // 'update' or 'delete'

    if ($action === 'delete') {
        //  Delete discount & restore original price in UI (DB unchanged)
        if (DeleteDiscount($discount_id)) {
            $_SESSION['success'] = "Discount removed. Price restored to JD" . number_format($original_price, 2);
        } else {
            $_SESSION['error'] = "Failed to delete discount.";
        }
        header("Location: products.php");
        exit;
    } else {
        // Update
        $percent = floatval($_POST['discount_percent']);
        $start = $_POST['start_date'];
        $end = $_POST['end_date'];

        if ($percent < 0 || $percent > 100) {
            $error = "Discount % must be 0–100.";
        } elseif (empty($start) || empty($end)) {
            $error = "Dates are required.";
        } elseif (strtotime($end) < strtotime($start)) {
            $error = "End date must be after start date.";
        } else {
            // Update DB
            $stmt = $conn->prepare("
                UPDATE discounts 
                SET discount_percent = ?, start_date = ?, end_date = ?
                WHERE discount_id = ?
            ");
            $stmt->bind_param("dssi", $percent, $start, $end, $discount_id);
            $ok = $stmt->execute();
            $stmt->close();

            if ($ok) {
                $_SESSION['success'] = "Discount updated successfully.";
                header("Location: products.php");
                exit;
            } else {
                $error = "Failed to update discount.";
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Discount — <?= htmlspecialchars($product_name) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>.main-content { margin-left: 260px; padding: 20px; }</style>
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

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-edit text-info me-2"></i> Edit Discount</h1>
        <a href="products.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-box-open me-2"></i> Product: <?= htmlspecialchars($product_name) ?>
        </div>
        <div class="card-body">
            <p><strong>Original Price:</strong> <span class="text-success">JD<?= number_format($original_price, 2) ?></span></p>
            <p><strong>Current Discount:</strong> 
                <span class="badge bg-danger"><?= number_format($discount['discount_percent'], 1) ?>% off</span>
                → <strong>JD<?= number_format($current_discounted, 2) ?></strong>
            </p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <i class="fas fa-sliders-h me-2"></i> Update Discount
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="action" value="update">

                <div class="col-md-6">
                    <label class="form-label">Discount Percentage (%)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0" max="100" name="discount_percent"
                               class="form-control" required 
                               value="<?= htmlspecialchars($discount['discount_percent']) ?>">
                        <span class="input-group-text">%</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" required
                           value="<?= htmlspecialchars($discount['start_date']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" required
                           value="<?= htmlspecialchars($discount['end_date']) ?>">
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>

                    <!-- Delete Button (danger) -->
                    <button type="submit" name="action" value="delete"
                            class="btn btn-danger"
                            onclick="return confirm('Remove this discount? The product price will revert to JD<?= number_format($original_price, 2) ?>.')">
                        <i class="fas fa-trash me-1"></i> Remove Discount
                    </button>

                    <a href="products.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>