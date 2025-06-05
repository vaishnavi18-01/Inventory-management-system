<?php 
include('../db.php'); 

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    // Validate quantity
    if (!is_numeric($quantity) || $quantity <= 0) {
        echo "<div class='error'>Please enter a valid positive quantity!</div>";
        exit;
    }

    // Check current stock with a prepared statement
    $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo "<div class='error'>Product not found!</div>";
        exit;
    }

    $product = $result->fetch_assoc();
    if ($product['stock'] < $quantity) {
        echo "<div class='error'>Not enough stock available!</div>";
        exit;
    }

    // Update the product stock
    $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
    $stmt->bind_param("ii", $quantity, $product_id);
    if (!$stmt->execute()) {
        echo "<div class='error'>Failed to update stock!</div>";
        exit;
    }

    // Record stock history
    $type='OUT';
   $stmt_history = $conn->prepare
            ("INSERT INTO stock_history (product_id, `change`, type)
             VALUES (?, ?, ?)");
             if(!$stmt_history)
             {
                die("prepare failed:".$conn->error);
             }
            $stmt_history->bind_param("iis", $product_id, $quantity,$type);
            $stmt_history->execute();
    echo "<div class='success'>Stock deducted successfully!</div>";
}
// After processing sale
mysqli_query($conn, "INSERT INTO product_transactions (product_id, quantity, transaction_type) VALUES ('$product_id', '$quantity', 'sale')");
// Fetch available products
$products = $conn->query("SELECT id, name FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Out Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .success {
            color: green;
            margin-top: 15px;
        }

        .error {
            color: red;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Stock Out</h2>
        <form method="POST">
            <div class="form-group">
                <select name="product_id" required>
                    <option value="">Select Product</option>
                    <?php while($row = $products->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="number" name="quantity" placeholder="Quantity" required min="1">
            </div>
            <button type="submit">Reduce Stock</button>
        </form>
    </div>
</body>
</html>
