<?php
// Include database connection
include 'db_connection.php';

// Fetch products from the database
$sql = "SELECT  serial_number,product_name,description,price FROM Products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .dashboard {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .product {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f9f9f9;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .product h3 {
            margin: 0;
            color: #007bff;
        }
        .product p {
            margin: 5px 0;
            color: #555;
        }
        .product button {
            padding: 10px 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .product button:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>Customer Dashboard</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product">
                    <div>
                        <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p><strong>Price:</strong> $<?php echo htmlspecialchars($row['price']); ?></p>
                    </div>
                    <button>Add to Cart</button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </div>
</body>
</html>