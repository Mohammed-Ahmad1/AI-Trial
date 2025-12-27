<?php
require_once __DIR__ . '/../includes/config.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: categories.php');
    exit;
}

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("DB error");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['categoryName']);
    $description = trim($_POST['categoryDescription']);

    $stmt = $conn->prepare("
        UPDATE categories
        SET name=?, description=?
        WHERE category_id=?
    ");
    $stmt->bind_param("ssi", $name, $description, $id);
    $stmt->execute();

    header('Location: categories.php?success=' . urlencode('Category updated successfully'));
    exit;
}

// Fetch category
$stmt = $conn->prepare("SELECT * FROM categories WHERE category_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$category = $stmt->get_result()->fetch_assoc();

if (!$category) {
    header('Location: categories.php');
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Category</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="main-content" style="margin-left:260px; padding:20px">
<h1>Edit Category</h1>

<form method="POST" action="edit_category.php?id=<?= $id ?>">
    <div class="mb-3">
        <label>Category Name *</label>
        <input type="text" name="categoryName" class="form-control"
               value="<?= htmlspecialchars($category['name']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="categoryDescription" class="form-control" rows="4"><?= htmlspecialchars($category['description']) ?></textarea>
    </div>

    <button class="btn btn-primary">💾 Update</button>
    <a href="categories.php" class="btn btn-secondary">Cancel</a>
</form>
</div>

<script src="assets/js/form-fix.js"></script>
</body>
</html>
