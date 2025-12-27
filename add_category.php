<?php
require_once __DIR__ . '/../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['categoryName'] ?? '');
    $description = trim($_POST['categoryDescription'] ?? '');

    if ($name === '') {
        $error = "Category name is required.";
    } else {
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) die("DB error");

        $stmt = $conn->prepare("
            INSERT INTO categories (name, description, created_at)
            VALUES (?, ?, NOW())
        ");
        $stmt->bind_param("ss", $name, $description);

        if ($stmt->execute()) {
            header('Location: categories.php?success=' . urlencode('Category added successfully'));
            exit;
        } else {
            $error = "Database error";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Category</title>
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

<div class="main-content" style="margin-left:260px; padding:20px">
<h1>Add Category</h1>

<?php if (!empty($error)): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="add_category.php">
    <div class="mb-3">
        <label>Category Name *</label>
        <input type="text" name="categoryName" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="categoryDescription" class="form-control" rows="4"></textarea>
    </div>

    <button class="btn btn-success">➕ Add Category</button>
    <a href="categories.php" class="btn btn-secondary">Cancel</a>
</form>
</div>

<script src="assets/js/form-fix.js"></script>
</body>
</html>
