<?php
// Start the session
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Determine the table based on the selected role
    $table = "";
    switch ($role) {
        case "admin":
            $table = "admin";
            break;
        case "manager":
            $table = "manager";
            break;
        case "user":
            $table = "users";
            break;
        default:
            $error = "Invalid role selected.";
            break;
    }

    if (!empty($table)) {
        // Prepare the SQL query
        $sql = "SELECT * FROM $table WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Directly compare passwords
            if ($password === $row['password']) {
                // Store session data
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $role;

                // Redirect based on role
                switch ($role) {
                    case 'admin':
                        header("Location: admin_dashboard.php");
                        break;
                    case 'managers':
                        header("Location: manager_dashboard.php");
                        break;
                    case 'user':
                        header("Location: customer_dashboard.php");
                        break;
                }
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No user found with this email in the $role table.";
        }

        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Login</title>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            background-image: url('img/userlogin.jpg'); /* Replace with your image path */
            background-position: center;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007BFF;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }
        a.back-button {
            display: inline-block;
            text-decoration: none;
            color: #007BFF;
            font-size: 14px;
            margin-top: 10px;
        }
</style>
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
    <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="admin">admin</option>
            <option value="managers">manager</option>
            <option value="user">user</option>
        </select>
        <button type="submit">Login</button>
        <a class="back-button" href="index.php">Back to Home</a>
    </form>
</div>
</body>
</html>