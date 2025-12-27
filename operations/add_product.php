<?php
require_once __DIR__ . '/../includes/config.php';


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../includes/config.php';

    $name = trim($_POST['productName'] ?? '');
    $price = floatval($_POST['productPrice'] ?? 0);
    $description = trim($_POST['productDetails'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 1); 

    if (empty($name) || $price <= 0 || empty($description)) {
        $error = "Please fill all required fields.";
    } else {
        
        // Handle image
        $image = 'assets/img/placeholder.png';
        if (!empty($_FILES['productImage']['name'])) {
            $uploadDir = 'assets/img/products/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $ext = pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('prod_') . '.' . strtolower($ext);
            $target = $uploadDir . $filename;

            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array(strtolower($ext), $allowed) && move_uploaded_file($_FILES['productImage']['tmp_name'], $target)) {
                $image = $target;
            }
        }

        // Save to Database
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) die("DB error");

        $stmt = $conn->prepare("
            INSERT INTO products (name, description, price, category_id, image, stock_quantity, status, created_at)
            VALUES (?, ?, ?, ?, ?, 0, 'active', NOW())
        ");
        $stmt->bind_param("ssdss", $name, $description, $price, $category_id, $image);

        if ($stmt->execute()) {
            header('Location: products.php?success=' . urlencode('Product added successfully!'));
            exit;
        } else {
            $error = "Database error: " . $stmt->error;
        }

        $stmt->close(); $conn->close();
    }
}

// Fetch categories for dropdown
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
$categories = [];
if (!$conn->connect_error) {
    $res = $conn->query("SELECT category_id, name FROM categories ORDER BY name");
    $categories = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - FELUX</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="sidebar bg-light p-3 vh-100 position-fixed">
    <div class="d-flex align-items-center mb-4">
        <i class="fas fa-store fa-2x me-2 text-success"></i>
        <h4 class="m-0">FELUX</h4>
    </div>
    <hr>
    <ul class="nav flex-column">
        <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link"><i class="fas fa-home me-2"></i> Home</a></li>
        <li class="nav-item mb-2"><a href="orders.php" class="nav-link"><i class="fas fa-box me-2"></i> Orders</a></li>
        <li class="nav-item mb-2"><a href="products.php" class="nav-link"><i class="fas fa-tag me-2"></i> Product</a></li>
        <li class="nav-item mb-2"><a href="categories.php" class="nav-link"><i class="fas fa-folder me-2"></i> Category</a></li>
        <li class="nav-item mb-2"><a href="users.php" class="nav-link"><i class="fas fa-users me-2"></i> Users</a></li>
        <li class="nav-item mb-2"><a href="admins.php" class="nav-link"><i class="fas fa-user-shield me-2"></i> Admins</a></li>
        <li class="nav-item mb-2"><a href="edit_profile.php" class="nav-link"><i class="fas fa-user-edit me-2"></i> Edit Profile</a></li>
        <li class="nav-item mt-0"><a href="#" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <h1 class="mb-4">Add Product</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="add_product.php" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="productName" class="form-control" required 
                           value="<?= htmlspecialchars($_POST['productName'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Price (JD) *</label>
                    <input type="number" name="productPrice" class="form-control" step="0.01" min="0.01" required
                           value="<?= htmlspecialchars($_POST['productPrice'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                        <?php if (empty($categories)): ?>
                            <option value="1">General</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Image (optional)</label>
                    <input type="file" name="productImage" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description *</label>
                    <textarea name="productDetails" class="form-control" rows="4" required><?= htmlspecialchars($_POST['productDetails'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">✅ Save Product</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/form-fix.js"></script>

</body>
</html>
