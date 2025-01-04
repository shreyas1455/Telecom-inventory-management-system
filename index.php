<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar with Background</title>
    <style>
        /* Add background image to the entire page */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-image: url('img/welcome.jpg'); 
            background-size: cover;
            background-position: fixed;
            color: white;
        }

        /* Navbar Styling */
        .navbar {
            background-color: rgba(0, 0, 0, 0.8); 
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
            color: #f4f4f9;
        }

        /* Center content for demo purposes */
        .content {
            text-align: center;
            padding: 150px 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">Inventory Management</div>
        <div>
            <a href="admin_login.php">Admin Login</a>
            <a href="login.php">User Login</a>
            <a href="register.php">User Register</a>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content">
        <h1>Welcome to Inventory Management</h1>
        <p>Manage your inventory effectively and efficiently.</p>
    </div>
</body>
</html>