<?php include('../db.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $sku = mysqli_real_escape_string($conn, $_POST['sku']);
    $category_id = intval($_POST['category_id']);  // Ensure it's an integer
    $price = floatval($_POST['price']);  // Ensure it's a float
    $stock = intval($_POST['stock']);  // Ensure it's an integer

    $stmt = $conn->prepare("INSERT INTO products (name, sku, category_id, price, stock) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidi", $name, $sku, $category_id, $price, $stock);

    if ($stmt->execute()) {
        echo "<div class='success'>Product added successfully!</div>";
    } else {
        echo "<div class='error'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        input[type="text"], input[type="number"], input[type="email"], button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="number"] {
            -moz-appearance: textfield;  /* Remove number stepper in Firefox */
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .success {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin-top: 20px;
            text-align: center;
            border-radius: 5px;
        }

        .error {
            background-color: #f44336;
            color: white;
            padding: 10px;
            margin-top: 20px;
            text-align: center;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Product</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Product Name" required><br>
            <input type="text" name="sku" placeholder="SKU" required><br>
            <input type="number" name="category_id" placeholder="Category ID" required><br>
            <input type="number" step="0.01" name="price" placeholder="Price" required><br>
            <input type="number" name="stock" placeholder="Stock Quantity" required><br>
            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
