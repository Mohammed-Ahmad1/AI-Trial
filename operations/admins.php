<?php
require_once __DIR__ . '/../PHPLogicPages/AdminsLogic.php';

$ListAllAdmins = ListAllAdmins();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admins - FELUX</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom CSS -->
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
        <li class="nav-item mb-2">
            <a href="dashboard.php" class="nav-link">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="orders.php" class="nav-link">
                <i class="fas fa-box me-2"></i> Orders
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="products.php" class="nav-link">
                <i class="fas fa-tag me-2"></i> Products
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="categories.php" class="nav-link">
                <i class="fas fa-folder me-2"></i> Categories
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="users.php" class="nav-link">
                <i class="fas fa-users me-2"></i> Users
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="admins.php" class="nav-link active">
                <i class="fas fa-user-shield me-2"></i> Admins
            </a>
        </li>
 <li class="nav-item mb-2"><a href="edit_profile.php" class="nav-link d-flex align-items-center">
    <i class="fas fa-user-edit me-2">
    </i> Edit Profile</a></li>

        <li class="nav-item mt-0">
            <a href="../Team-project-php/login.php" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content p-4" style="margin-left:260px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Admins</h2>
        <a href="add_admin.php" class="btn btn-success">
            <i class="fas fa-plus me-2"></i> Add New Admin
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ListAllAdmins)) : ?>
                            <?php foreach ($ListAllAdmins as $admin) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($admin['user_id']) ?></td>
                                    <td><?= htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']) ?></td>
                                    <td><?= htmlspecialchars($admin['email']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No admins found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert popup handling -->
<?php if (isset($_GET['success'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: <?= json_encode($_GET['success']) ?>,
    confirmButtonColor: '#198754'
});
</script>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: <?= json_encode($_GET['error']) ?>,
    confirmButtonColor: '#dc3545'
});
</script>
<?php endif; ?>

<!-- Clean URL after popup -->
<script>
if (window.location.search.includes('success') || window.location.search.includes('error')) {
    window.history.replaceState({}, document.title, window.location.pathname);
}
</script>

</body>
</html>
