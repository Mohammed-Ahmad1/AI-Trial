<?php
require_once __DIR__ . '/../PHPLogicPages/UsersLogic.php';
$ListAllUsers  = ListAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - FELUX</title>
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
            <li class="nav-item mb-2">
                <a href="dashboard.php" class="nav-link d-flex align-items-center">
                    <i class="fas fa-home me-2"></i> Home
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="orders.php" class="nav-link d-flex align-items-center">
                    <i class="fas fa-box me-2"></i> Orders
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="products.php" class="nav-link d-flex align-items-center">
                    <i class="fas fa-tag me-2"></i> Product
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="categories.php" class="nav-link d-flex align-items-center">
                    <i class="fas fa-folder me-2"></i> Category
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="users.php" class="nav-link d-flex align-items-center active">
                    <i class="fas fa-users me-2"></i> Users
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="admins.php" class="nav-link d-flex align-items-center">
                    <i class="fas fa-user-shield me-2"></i> Admins
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="edit_profile.php" class="nav-link d-flex align-items-center">
                    <i class="fas fa-user-edit me-2"></i> Edit Profile
                </a>
            </li>
            <li class="nav-item mt-0">
                
                <a href="login.php" class="nav-link d-flex align-items-center text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1 class="mb-4">Users</h1>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User id</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                            <tbody>
<?php if (!empty($ListAllUsers)) : ?>
    <?php foreach ($ListAllUsers as $user) : ?>
        <tr>
            <td><?= htmlspecialchars($user['user_id']) ?></td>
            <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
    <form method="POST" action="delete_user.php" onsubmit="return confirm('Are you sure you want to delete this user?');">
        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</td>
        </tr>
    <?php endforeach; ?>
<?php else : ?>
    <tr>
        <td colspan="4" class="text-center text-muted">No users found</td>
    </tr>
<?php endif; ?>
</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>