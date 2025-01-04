<?php
// Include database connection
include 'db_connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check for duplicate email
    $check_email_sql = "SELECT * FROM Users WHERE email = '$email'";
    $result = $conn->query($check_email_sql);

    if ($result->num_rows > 0) {
        $error = "The email address is already registered. Please use a different email.";
    } else {
        // Insert the user into the database
        $sql = "INSERT INTO Users (name, email, password, phone, country) 
                VALUES ('$name', '$email', '$hashed_password', '$phone number', '$country')";

        if ($conn->query($sql) === TRUE) {
            $success = "Registration successful. You can now <a href='login.php'>login</a>.";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            background-image: url('img/userregister.jpg'); /* Replace with your image path */
            background-position: fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
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
            background: #28a745;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        .success {
            color: green;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <select name="country" required>
                <option value="" disabled selected>Select Country</option>
                <option value="India">India</option>
                <option value="USA">USA</option>
                <option value="UK">UK</option>
                <option value="Canada">Canada</option>
                <option value="Australia">Australia</option>
                <!-- Add more countries as needed -->
            </select>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>