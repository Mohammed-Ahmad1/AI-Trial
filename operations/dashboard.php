<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../PHPLogicPages/ProductsLogic.php';
require_once __DIR__ . '/../PHPLogicPages/OrdersLogic.php';
require_once __DIR__ . '/../PHPLogicPages/UsersLogic.php';
require_once __DIR__ . '/../PHPLogicPages/PaymentsLogic.php';
require_once __DIR__ . '/../PHPLogicPages/CategoriesLogic.php';

// Fetch statistics
$totalSales      = GetTotalSales();         
$totalProducts   = GetNumberOfProducts();   
$totalUsers      = GetNumberOfUsers();
$totalOrders     = GetNumberOfOrders();
$totalCategories = GetNumberOfCategories();

// Fetch recent orders (last 7 days)
$RecentOrders = GetRecentOrdersLast7Days();

// Aggregate orders by order_id
$orders = [];
foreach ($RecentOrders as $item) {
    $orderId = $item['order_id'];
    if (!isset($orders[$orderId])) {
        $orders[$orderId] = [
            'order_id' => $orderId,
            'order_time' => $item['order_time'],
            'total_quantity' => 0,
            'total_price' => 0
        ];
    }
    $orders[$orderId]['total_quantity'] += $item['quantity'];
    $orders[$orderId]['total_price'] += $item['quantity'] * $item['price_at_purchase'];
}

// --- Daily Revenue ---
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$showAll = isset($_GET['all']) && $_GET['all'] == 1;

if (!$showAll) {
    // Today revenue
    $revenueSql = "
        SELECT DATE(p.paid_at) AS day, SUM(p.amount) AS daily_total
        FROM payments p
        WHERE DATE(p.paid_at) = CURDATE()
        GROUP BY DATE(p.paid_at)
        ORDER BY day DESC
    ";
} else {
    // All-time revenue
    $revenueSql = "
        SELECT DATE(p.paid_at) AS day, SUM(p.amount) AS daily_total
        FROM payments p
        GROUP BY DATE(p.paid_at)
        ORDER BY day DESC
    ";
}

$revenueResult = $conn->query($revenueSql);
$dailyRevenue = [];
if ($revenueResult) {
    while ($row = $revenueResult->fetch_assoc()) {
        $dailyRevenue[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - FELUX</title>
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
        <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link active"><i class="fas fa-home me-2"></i> Home</a></li>
        <li class="nav-item mb-2"><a href="orders.php" class="nav-link"><i class="fas fa-box me-2"></i> Orders</a></li>
        <li class="nav-item mb-2"><a href="products.php" class="nav-link"><i class="fas fa-tag me-2"></i> Products</a></li>
        <li class="nav-item mb-2"><a href="categories.php" class="nav-link"><i class="fas fa-layer-group me-2"></i> Categories</a></li>
        <li class="nav-item mb-2"><a href="users.php" class="nav-link"><i class="fas fa-users me-2"></i> Users</a></li>
        <li class="nav-item mb-2"><a href="admins.php" class="nav-link"><i class="fas fa-user-shield me-2"></i> Admins</a></li>
        <li class="nav-item mb-2"><a href="edit_profile.php" class="nav-link"><i class="fas fa-user-edit me-2"></i> Edit Profile</a></li>
        <li class="nav-item"><a href="login.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h1 class="mb-4">Dashboard</h1>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5">
        <div class="col">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary rounded-circle p-3 me-3"><i class="fas fa-box text-white fs-4"></i></div>
                    <div><h3 class="mb-0"><?= $totalProducts ?></h3><p class="mb-0 text-muted">Products</p></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning rounded-circle p-3 me-3"><i class="fas fa-users text-white fs-4"></i></div>
                    <div><h3 class="mb-0"><?= $totalUsers ?></h3><p class="mb-0 text-muted">Users</p></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-orange rounded-circle p-3 me-3"><i class="fas fa-shopping-cart text-white fs-4"></i></div>
                    <div><h3 class="mb-0"><?= $totalOrders ?></h3><p class="mb-0 text-muted">Orders</p></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info rounded-circle p-3 me-3"><i class="fas fa-layer-group text-white fs-4"></i></div>
                    <div><h3 class="mb-0"><?= $totalCategories ?></h3><p class="mb-0 text-muted">Categories</p></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success rounded-circle p-3 me-3"><i class="fas fa-dollar-sign text-white fs-4"></i></div>
                    <div><h3 class="mb-0"><?= $totalSales ?></h3><p class="mb-0 text-muted">Total Sales</p></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Revenue Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daily Revenue <?= !$showAll ? '(Today)' : '(All Time)' ?></h5>
            <a href="dashboard.php?all=<?= $showAll ? '0' : '1' ?>" class="btn btn-sm btn-outline-primary">
                <?= $showAll ? 'Show Today Revenue' : 'Show All History' ?>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dailyRevenue)) : ?>
                            <?php foreach ($dailyRevenue as $day) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($day['day']) ?></td>
                                    <td>JD<?= number_format($day['daily_total'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr><td colspan="2" class="text-center">No revenue data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Orders</h5>
            <div class="d-flex">
                <input type="text" id="orderSearch" class="form-control form-control-sm me-2" placeholder="Search orders..." style="max-width:200px;">
                <button class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('orderSearch').focus();"><i class="fas fa-search"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="recentOrdersTable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)) : ?>
                            <?php foreach ($orders as $order) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['order_id']) ?></td>
                                    <td><?= htmlspecialchars($order['order_time']) ?></td>
                                    <td><?= htmlspecialchars($order['total_quantity']) ?></td>
                                    <td>JD<?= number_format($order['total_price'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr><td colspan="4" class="text-center">No recent orders</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('orderSearch').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    document.querySelectorAll('#recentOrdersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
});
</script>
</body>
</html>
