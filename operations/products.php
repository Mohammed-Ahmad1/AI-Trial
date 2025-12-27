<?php
require_once __DIR__ . '/../PHPLogicPages/ProductsLogic.php';
require_once __DIR__ . '/../PHPLogicPages/CategoriesLogic.php';

/* Filters */
$name      = $_GET['name'] ?? '';
$category  = $_GET['category'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';

$products   = ListAllProducts(0, 50, $name, $category, $min_price, $max_price);
$categories = ListAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products - FELUX</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">

<style>
.main-content { margin-left: 260px; padding: 20px; }
.bg-orange { background-color: #ff7f50 !important; }
</style>
</head>
<body>

<!-- Sidebar (UNCHANGED) -->
<div class="sidebar bg-light p-3 vh-100 position-fixed">
    <div class="d-flex align-items-center mb-4">
        <i class="fas fa-store fa-2x me-2 text-success"></i>
        <h4 class="m-0">FELUX</h4>
    </div>
    <hr>
    <ul class="nav flex-column">
        <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link"><i class="fas fa-home me-2"></i> Home</a></li>
        <li class="nav-item mb-2"><a href="orders.php" class="nav-link"><i class="fas fa-box me-2"></i> Orders</a></li>
        <li class="nav-item mb-2"><a href="products.php" class="nav-link active"><i class="fas fa-tag me-2"></i> Product</a></li>
        <li class="nav-item mb-2"><a href="categories.php" class="nav-link"><i class="fas fa-folder me-2"></i> Category</a></li>
        <li class="nav-item mb-2"><a href="users.php" class="nav-link"><i class="fas fa-users me-2"></i> Users</a></li>
        <li class="nav-item mb-2"><a href="admins.php" class="nav-link"><i class="fas fa-user-shield me-2"></i> Admins</a></li>
        <li class="nav-item mb-2"><a href="edit_profile.php" class="nav-link"><i class="fas fa-user-edit me-2"></i> Edit Profile</a></li>
        <li class="nav-item"><a href="../Team-project-php/login.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Products</h1>
    <a href="add_product.php" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Add Product
    </a>
</div>

<!-- 🔍 FILTER BAR (ADDED – DESIGN SAFE) -->
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="name" class="form-control"
               placeholder="Search by name"
               value="<?= htmlspecialchars($name) ?>">
    </div>

    <div class="col-md-3">
        <select name="category" class="form-select">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['category_id'] ?>"
                    <?= ($category == $cat['category_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-2">
        <input type="number" step="0.01" name="min_price"
               class="form-control" placeholder="Min Price"
               value="<?= htmlspecialchars($min_price) ?>">
    </div>

    <div class="col-md-2">
        <input type="number" step="0.01" name="max_price"
               class="form-control" placeholder="Max Price"
               value="<?= htmlspecialchars($max_price) ?>">
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary w-100">
            <i class="fas fa-search"></i> Filter
        </button>
    </div>
</form>

<div class="card shadow-sm">
<div class="card-body table-responsive">
<table class="table table-hover">
<thead>
<tr>
    <th>ID</th>
    <th>Image</th>
    <th>Name</th>
    <th>Price</th>
    <th>Category</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>

<?php if (!empty($products)): ?>
<?php foreach ($products as $p): ?>
<tr>
    <td><?= $p['product_id'] ?></td>
    <td><img src="<?= htmlspecialchars($p['image']) ?>" width="40" class="rounded"></td>
    <td><?= htmlspecialchars($p['ProductName']) ?></td>
    <td>JD<?= number_format($p['price'], 2) ?></td>
    <td><?= htmlspecialchars($p['CategoryName']) ?></td>
    <td>
        <a href="edit_product.php?id=<?= $p['product_id'] ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-edit"></i>
        </a>
        <form method="POST" action="delete_product.php" style="display:inline"
              onsubmit="return confirm('Delete this product?');">
            <input type="hidden" name="product_id" value="<?= $p['product_id'] ?>">
            <button class="btn btn-sm btn-danger">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td colspan="6" class="text-center">No products found</td></tr>
<?php endif; ?>

</tbody>
</table>
</div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
