<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!is_logged_in() || !is_admin()) {
    header('Location: ../login.php');
    exit();
}

$database = new Database();
$pdo = $database->getConnection();

// Get dashboard statistics
$stats = [];

// Total orders
$stmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
$stats['total_orders'] = $stmt->fetch()['total'];

// Pending orders
$stmt = $pdo->query("SELECT COUNT(*) as total FROM orders WHERE status = 'pending'");
$stats['pending_orders'] = $stmt->fetch()['total'];

// Today's orders
$stmt = $pdo->query("SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) = CURDATE()");
$stats['today_orders'] = $stmt->fetch()['total'];

// Total revenue
$stmt = $pdo->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'delivered'");
$stats['total_revenue'] = $stmt->fetch()['total'] ?? 0;

// Total users
$stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE user_role = 'student'");
$stats['total_users'] = $stmt->fetch()['total'];

// Recent orders
$stmt = $pdo->query("SELECT o.*, u.first_name, u.last_name, u.room_number FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 10");
$recent_orders = $stmt->fetchAll();

// Get flash message
$flash = get_flash_message();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AquaCampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-tint me-2"></i>AquaCampus Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-shield me-1"></i><?php echo htmlspecialchars($_SESSION['first_name']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="orders.php">
                                <i class="fas fa-shopping-cart"></i>Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="users.php">
                                <i class="fas fa-users"></i>Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">
                                <i class="fas fa-tint"></i>Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="deliveries.php">
                                <i class="fas fa-truck"></i>Deliveries
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="payments.php">
                                <i class="fas fa-credit-card"></i>Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reports.php">
                                <i class="fas fa-chart-bar"></i>Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="settings.php">
                                <i class="fas fa-cog"></i>Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="container-fluid py-4">
                    <!-- Flash Message -->
                    <?php if ($flash): ?>
                        <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i><?php echo $flash['message']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Welcome Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h1 class="h3 mb-0">Admin Dashboard</h1>
                            <p class="text-muted">Manage orders, users, and system settings</p>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-card">
                                <div class="card-icon bg-primary">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <h3 class="fw-bold"><?php echo $stats['total_orders']; ?></h3>
                                <p class="text-muted mb-0">Total Orders</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-card">
                                <div class="card-icon bg-warning">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h3 class="fw-bold"><?php echo $stats['pending_orders']; ?></h3>
                                <p class="text-muted mb-0">Pending Orders</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-card">
                                <div class="card-icon bg-info">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <h3 class="fw-bold"><?php echo $stats['today_orders']; ?></h3>
                                <p class="text-muted mb-0">Today's Orders</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-card">
                                <div class="card-icon bg-success">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <h3 class="fw-bold"><?php echo format_currency($stats['total_revenue']); ?></h3>
                                <p class="text-muted mb-0">Total Revenue</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <a href="orders.php?status=pending" class="btn btn-warning btn-lg w-100">
                                                <i class="fas fa-clock me-2"></i>Pending Orders
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="products.php" class="btn btn-primary btn-lg w-100">
                                                <i class="fas fa-tint me-2"></i>Manage Products
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="users.php" class="btn btn-info btn-lg w-100">
                                                <i class="fas fa-users me-2"></i>Manage Users
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="reports.php" class="btn btn-success btn-lg w-100">
                                                <i class="fas fa-chart-bar me-2"></i>View Reports
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i>Recent Orders</h5>
                                    <a href="orders.php" class="btn btn-sm btn-outline-primary">View All</a>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($recent_orders)): ?>
                                        <div class="text-center py-4">
                                            <i class="fas fa-shopping-cart text-muted display-4 mb-3"></i>
                                            <h5 class="text-muted">No orders yet</h5>
                                            <p class="text-muted">Orders will appear here once students start placing them.</p>
                                        </div>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Order #</th>
                                                        <th>Customer</th>
                                                        <th>Room</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <th>Payment</th>
                                                        <th>Date</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($recent_orders as $order): ?>
                                                        <tr>
                                                            <td><strong><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                                                            <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                                                            <td><?php echo htmlspecialchars($order['room_number'] ?? 'N/A'); ?></td>
                                                            <td><?php echo format_currency($order['total_amount']); ?></td>
                                                            <td>
                                                                <span class="badge <?php echo get_status_badge_class($order['status']); ?>">
                                                                    <?php echo ucfirst(str_replace('_', ' ', $order['status'])); ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="badge <?php echo get_payment_status_badge_class($order['payment_status']); ?>">
                                                                    <?php echo ucfirst(str_replace('_', ' ', $order['payment_status'])); ?>
                                                                </span>
                                                            </td>
                                                            <td><?php echo format_date($order['created_at']); ?></td>
                                                            <td>
                                                                <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="update_order_status.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-success">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
