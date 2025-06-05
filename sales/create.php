<?php
session_start();
include('../db.php');

// Fetch customers and products
$customers = $conn->query("SELECT id, name FROM customers");
$products = $conn->query("SELECT id, name, price, stock FROM products");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Fetch product details
    $product = $conn->query("SELECT price, stock FROM products WHERE id = $product_id")->fetch_assoc();
    $price = $product['price'];
    $stock = $product['stock'];

    // Check stock availability
    if ($quantity > $stock) {
        echo "<div class='alert alert-danger'>Not enough stock available!</div>";
        exit;
    }

    // Calculate total price
    $total_price = $price * $quantity;

    // Insert sale record
    $stmt = $conn->prepare("INSERT INTO sales (customer_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $customer_id, $product_id, $quantity, $total_price);
    $stmt->execute();

    // Update product stock
    $conn->query("UPDATE products SET stock = stock - $quantity WHERE id = $product_id");

    // Log stock history
    $conn->query("INSERT INTO stock_history (product_id, change, type) VALUES ($product_id, $quantity, 'OUT')");

    echo "<div class='alert alert-success'>Sale recorded successfully!</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Sale</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
        }
        select, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Record a New Sale</h2>
    <form method="POST">
        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select name="customer_id" id="customer_id" required>
                <option value="">Select Customer</option>
                <?php while ($c = $customers->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" id="product_id" required>
                <option value="">Select Product</option>
                <?php while ($p = $products->fetch_assoc()): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?> - ₹<?= number_format($p['price'], 2) ?> (Stock: <?= $p['stock'] ?>)</option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" min="1" required>
        </div>

        <button type="submit">Create Sale</button>
    </form>
</div>

</body>
</html>
