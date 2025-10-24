<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!is_logged_in()) {
    header('Location: ../login.php');
    exit();
}

// Redirect admin users
if (is_admin()) {
    header('Location: ../admin/dashboard.php');
    exit();
}

$database = new Database();
$pdo = $database->getConnection();

// Get user information
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get recent orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$recent_orders = $stmt->fetchAll();

// Get order statistics
$stmt = $pdo->prepare("SELECT 
    COUNT(*) as total_orders,
    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
    SUM(total_amount) as total_spent
    FROM orders WHERE user_id = ?");
$stmt->execute([$user_id]);
$stats = $stmt->fetch();

// Get flash message
$flash = get_flash_message();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - AquaCampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-tint me-2"></i>AquaCampus
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($user['first_name']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="orders.php"><i class="fas fa-shopping-cart me-2"></i>My Orders</a></li>
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
                            <a class="nav-link" href="order.php">
                                <i class="fas fa-plus-circle"></i>New Order
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="orders.php">
                                <i class="fas fa-shopping-cart"></i>My Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="tracking.php">
                                <i class="fas fa-truck"></i>Track Delivery
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">
                                <i class="fas fa-user"></i>Profile
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
                            <h1 class="h3 mb-0">Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>!</h1>
                            <p class="text-muted">Manage your water orders and track deliveries</p>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-card">
                                <div class="card-icon bg-primary">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <h3 class="fw-bold"><?php echo $stats['total_orders'] ?? 0; ?></h3>
                                <p class="text-muted mb-0">Total Orders</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-card">
                                <div class="card-icon bg-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h3 class="fw-bold"><?php echo $stats['delivered_orders'] ?? 0; ?></h3>
                                <p class="text-muted mb-0">Delivered</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-card">
                                <div class="card-icon bg-warning">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h3 class="fw-bold"><?php echo $stats['pending_orders'] ?? 0; ?></h3>
                                <p class="text-muted mb-0">Pending</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-card">
                                <div class="card-icon bg-info">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <h3 class="fw-bold"><?php echo format_currency($stats['total_spent'] ?? 0); ?></h3>
                                <p class="text-muted mb-0">Total Spent</p>
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
                                        <div class="col-md-4 mb-3">
                                            <a href="order.php" class="btn btn-primary btn-lg w-100">
                                                <i class="fas fa-plus-circle me-2"></i>Place New Order
                                            </a>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <a href="orders.php" class="btn btn-outline-primary btn-lg w-100">
                                                <i class="fas fa-list me-2"></i>View All Orders
                                            </a>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <a href="tracking.php" class="btn btn-outline-primary btn-lg w-100">
                                                <i class="fas fa-truck me-2"></i>Track Delivery
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
                                            <p class="text-muted">Start by placing your first water order!</p>
                                            <a href="order.php" class="btn btn-primary">Place Order</a>
                                        </div>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Order #</th>
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <th>Payment</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($recent_orders as $order): ?>
                                                        <tr>
                                                            <td><strong><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                                                            <td><?php echo format_date($order['created_at']); ?></td>
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
                                                            <td>
                                                                <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
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
