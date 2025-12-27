<?php
require_once __DIR__ . '/../includes/config.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: products.php');
    exit;
}

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("DB error");

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['productName']);
    $price = floatval($_POST['productPrice']);
    $description = trim($_POST['productDetails']);
    $category_id = intval($_POST['category_id']);

    $image = $_POST['old_image'];

    // Image upload (optional)
    if (!empty($_FILES['productImage']['name'])) {
        $uploadDir = 'assets/img/products/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $ext = strtolower(pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {
            $filename = uniqid('prod_') . '.' . $ext;
            $target = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['productImage']['tmp_name'], $target)) {
                $image = $target;
            }
        }
    }

    $stmt = $conn->prepare("
        UPDATE products 
        SET name=?, price=?, description=?, category_id=?, image=?
        WHERE product_id=?
    ");
    $stmt->bind_param("sdsssi", $name, $price, $description, $category_id, $image, $id);
    $stmt->execute();

    header('Location: products.php?success=' . urlencode('Product updated successfully'));
    exit;
}

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
if (!$product) {
    header('Location: products.php');
    exit;
}

// Fetch categories
$cats = $conn->query("SELECT category_id, name FROM categories ORDER BY name")
             ->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Product</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="main-content" style="margin-left:260px; padding:20px">
<h1>Edit Product</h1>

<form method="POST" action="edit_product.php?id=<?= $id ?>" enctype="multipart/form-data">
    <input type="hidden" name="old_image" value="<?= htmlspecialchars($product['image']) ?>">

    <div class="mb-3">
        <label>Product Name</label>
        <input type="text" name="productName" class="form-control"
               value="<?= htmlspecialchars($product['name']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Price</label>
        <input type="number" step="0.01" name="productPrice"
               class="form-control" value="<?= $product['price'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Category</label>
        <select name="category_id" class="form-select">
            <?php foreach ($cats as $c): ?>
                <option value="<?= $c['category_id'] ?>"
                    <?= $c['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Image</label>
        <input type="file" name="productImage" class="form-control">
        <img src="<?= htmlspecialchars($product['image']) ?>" width="80" class="mt-2">
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="productDetails" class="form-control" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>
    </div>

    <button class="btn btn-primary">💾 Update Product</button>
    <a href="products.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
</body>
</html>