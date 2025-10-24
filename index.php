<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Redirect to appropriate dashboard based on user role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: student/dashboard.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Water Delivery System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-tint me-2"></i>AquaCampus
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light ms-2" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section bg-gradient-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Smart Water Delivery for Campus</h1>
                    <p class="lead mb-4">Order bottled water directly to your dorm room with our convenient online system. Track deliveries, manage payments, and never run out of clean water again.</p>
                    <div class="d-flex gap-3">
                        <a href="register.php" class="btn btn-light btn-lg">Get Started</a>
                        <a href="#features" class="btn btn-outline-light btn-lg">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-tint display-1 opacity-75"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-5 fw-bold">Why Choose AquaCampus?</h2>
                    <p class="lead text-muted">Convenient, reliable, and student-friendly water delivery service</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-mobile-alt text-primary display-4 mb-3"></i>
                            <h5 class="card-title">Easy Ordering</h5>
                            <p class="card-text">Order water bottles in different sizes (5L, 10L, 20L) with just a few clicks from your mobile or desktop.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-truck text-primary display-4 mb-3"></i>
                            <h5 class="card-title">Delivery Tracking</h5>
                            <p class="card-text">Track your water delivery in real-time. Know exactly when your order will arrive at your dorm room.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-credit-card text-primary display-4 mb-3"></i>
                            <h5 class="card-title">Flexible Payment</h5>
                            <p class="card-text">Pay online securely or choose to pay when your water is delivered. Multiple payment options available.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4">About AquaCampus</h2>
                    <p class="lead mb-4">We understand the challenges of campus life. That's why we've created a smart water delivery system specifically designed for university students.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Direct delivery to your dorm room</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Multiple bottle sizes available</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Flexible delivery scheduling</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Secure online payments</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Real-time order tracking</li>
                    </ul>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-university display-1 text-primary opacity-75"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-tint me-2"></i>AquaCampus</h5>
                    <p class="text-muted">Smart water delivery system for university students.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">&copy; 2024 AquaCampus. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
