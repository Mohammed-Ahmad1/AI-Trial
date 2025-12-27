<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../PHPLogicPages/OrdersLogic.php';

// Fetch all orders
$orders = ListAllOrdersData();
$totalOrders = count($orders);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - FELUX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
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
            <li class="nav-item mb-2"><a href="orders.php" class="nav-link d-flex align-items-center active"><i class="fas fa-box me-2"></i> Orders</a></li>
            <li class="nav-item mb-2"><a href="products.php" class="nav-link d-flex align-items-center"><i class="fas fa-tag me-2"></i> Product</a></li>
            <li class="nav-item mb-2"><a href="categories.php" class="nav-link d-flex align-items-center"><i class="fas fa-folder me-2"></i> Category</a></li>
            <li class="nav-item mb-2"><a href="users.php" class="nav-link d-flex align-items-center"><i class="fas fa-users me-2"></i> Users</a></li>
            <li class="nav-item mb-2"><a href="admins.php" class="nav-link d-flex align-items-center"><i class="fas fa-user-shield me-2"></i> Admins</a></li>
            <li class="nav-item mb-2"><a href="edit_profile.php" class="nav-link d-flex align-items-center"><i class="fas fa-user-edit me-2"></i> Edit Profile</a></li>
            <li class="nav-item"><a href="../Team-project-php/login.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" style="margin-left: 260px; padding: 20px;">
        <h1 class="mb-4">Orders</h1>

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Orders</h5>

                <!-- 🔍 Search Bar (WIDER, SAME DESIGN) -->
                <div class="d-flex">
                    <input 
                        type="text" 
                        id="ordersSearch" 
                        class="form-control form-control-sm me-2"
                        placeholder="Search (name, email, phone, product…)"
                        style="width: 260px;"  >
                    <button class="btn btn-sm btn-outline-secondary me-2">
                        <i class="fas fa-search"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="ordersTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Number</th>
                                <th>Email</th>
                                <th>Product Name</th>
                                <th>Order Time</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($totalOrders > 0) : ?>
                                <?php foreach ($orders as $order) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($order['full_name']) ?></td>
                                        <td><?= htmlspecialchars($order['phone']) ?></td>
                                        <td><?= htmlspecialchars($order['email']) ?></td>
                                        <td><?= htmlspecialchars($order['product_name']) ?></td>
                                        <td><?= htmlspecialchars($order['order_time']) ?></td>
                                        <td><?= htmlspecialchars($order['quantity']) ?></td>
                                        <td>JD<?= number_format($order['price_at_purchase'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7" class="text-center">No orders found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ FILTER SCRIPT (NO DESIGN CHANGE) -->
    <script>
        document.getElementById('ordersSearch').addEventListener('keyup', function () {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#ordersTable tbody tr');

            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchValue) ? '' : 'none';
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
