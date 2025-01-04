<?php
// Start the session
session_start();

// Include the database connection
include 'db_connection.php';

// Fetch admin details (optional)
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        header {
            background-color: #343a40;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        header a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            padding: 5px 10px;
            background-color: #dc3545;
            border-radius: 5px;
        }

        header a:hover {
            background-color: #c82333;
        }

        .sidebar {
            width: 220px;
            background-color: #343a40;
            color: white;
            position: fixed;
            height: 100%;
            padding: 20px 0;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
            padding-left: 20px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
        }

        .sidebar ul li a:hover {
            text-decoration: underline;
        }

        .sidebar ul li ul {
            list-style-type: none;
            padding-left: 10px;
        }

        .sidebar ul li ul li a {
            font-size: 0.9rem;
            text-decoration: none;
            color: white;
            display: block;
            padding: 5px 10px;
        }

        .sidebar ul li ul li a:hover {
            background-color: #495057;
            border-radius: 5px;
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .dashboard-header .welcome {
            flex: 1;
            margin-right: 20px;
            background: white;
            padding: 20px;
            text-align: left;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header .cards {
            display: flex;
            flex: 3;
            gap: 10px;
        }

        .dashboard-header .cards .card {
            flex: 1;
            background: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header .cards .card h2 {
            margin: 0;
            font-size: 1.5rem;
            color: #343a40;
        }

        .dashboard-header .cards .card p {
            margin: 10px 0 0;
            font-size: 1rem;
            color: #6c757d;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card h2 {
            margin-bottom: 10px;
            font-size: 1.5rem;
            color: #343a40;
        }

        .card a {
            color: #007bff;
            text-decoration: none;
            font-size: 1rem;
        }

        .card a:hover {
            text-decoration: underline;
        }

        .charts {
            display: flex;
            gap: 20px;
        }

        .chart {
            flex: 1;
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .chart h3 {
            margin-bottom: 10px;
            font-size: 1.2rem;
            color: #343a40;
        }

    </style>
</head>
<body>
    <header>
        <h1>Inventory Management</h1>
        <a href="?logout=true">Logout</a>
    </header>

    <div class="sidebar">
        <ul>
            <li><h5>Administrator Page</h5></li>
            <li><h2>Welcome</h2></li>
            <li><a href="#dashboard">Dashboard</a></li>
            <li><a href="#category">Category</a></li>
            <li>
                <a href="#products" onclick="toggleDropdown('productsDropdown')">Product ▼</a>
                <ul id="productsDropdown" style="display: none; padding-left: 20px;">
                    <li><a href="add_product.php">Add Product</a></li>
                    <li><a href="delete_product.php">Delete Product</a></li>
                    <li><a href="add_replenishment.php">Add Replenishment</a></li>
                    <li><a href="manage_products.php">Manage Products</a></li>
                </ul>
            </li>
            <li>
                <a href="#orders" onclick="toggleDropdown('orderDropdown')">Order ▼</a>
                <ul id="orderDropdown" style="display: none; padding-left: 20px;">
                    <li><a href="add_order.php">Add Order</a></li>
                    <li><a href="manage_orders.php">Manage Orders</a></li>
                </ul>
            </li>
            <li><a href="#reports">Reports</a></li>
            <li><a href="#company">Company</a></li>
        </ul>
    </div>

    <div class="main-content">
        <section id="dashboard">
            <div class="dashboard-header">
                <div class="cards">
                    <div class="card">
                        <h2>10</h2>
                        <p>Total Products</p>
                    </div>
                    <div class="card">
                        <h2>38</h2>
                        <p>Total Paid Orders</p>
                    </div>
                    <div class="card">
                        <h2>6</h2>
                        <p>Total Categories</p>
                    </div>
                    <div class="card">
                        <h2>8</h2>
                        <p>Total Brands</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="analytics">
            <div class="charts">
                <div class="chart">
                    <h3>Phone Brand Sales Analysis</h3>
                    <p>Chart Placeholder</p>
                </div>
                <div class="chart">
                    <h3>Category Sales Analysis</h3>
                    <p>Chart Placeholder</p>
                </div>
            </div>
        </section>

        <section id="reports">
            <div class="card">
                <h2>Reports</h2>
                <p>View and analyze system reports.</p>
                <a href="reports.php">Go to Reports</a>
            </div>
        </section>
    </div>

    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            if (dropdown.style.display === "none") {
                dropdown.style.display = "block";
            } else {
                dropdown.style.display = "none";
            }
        }
    </script>
</body>
</html>