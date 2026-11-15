# AquaCampus: University Water Delivery System

A PHP and MySQL web application for ordering and managing drinking-water deliveries on a university campus. Students see their delivery orders, and administrators track orders, users and revenue from a dashboard.

## Features
- User registration, login and logout with PHP sessions
- Role-based access: separate **student** and **admin** dashboards
- Student dashboard showing their water-delivery orders
- Admin dashboard with order, user and revenue statistics, a list of all orders with students' room numbers, and system settings
- Responsive UI built with Bootstrap 5 and Font Awesome

## Tech stack
- PHP (PDO)
- MySQL / MariaDB
- Bootstrap 5, HTML, CSS

## Project structure
```
index.php              Landing page; redirects signed-in users to their dashboard
login.php / register.php / logout.php
admin/dashboard.php    Admin dashboard
student/dashboard.php  Student dashboard
config/database.php    Database connection settings
includes/functions.php Shared helpers
assets/css/style.css   Styles
```

## Running locally
1. Install a local PHP + MySQL stack (for example XAMPP).
2. Create a MySQL database named `aquacampus` with `users`, `orders` and `system_settings` tables.
3. Change the connection settings in `config/database.php` if your MySQL user or password is different.
4. Put the project folder in your web server's document root (for example `htdocs/aquacampus`) and open `http://localhost/aquacampus`.
