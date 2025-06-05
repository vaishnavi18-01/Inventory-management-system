<?php 
include('../db.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Ensure the quantity is a positive integer
    if (filter_var($quantity, FILTER_VALIDATE_INT) && $quantity > 0) {
        
        // Begin transaction to ensure data consistency
        $conn->begin_transaction();
        
        try {
            // Update product stock using prepared statements
            $stmt = $conn->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
            $stmt->bind_param("ii", $quantity, $product_id);
            $stmt->execute();

            // Record stock history using prepared statements
            $stmt_history = $conn->prepare
            ("INSERT INTO stock_history (product_id, `change`, type)
             VALUES (?, ?, 'IN')");
             if(!$stmt_history)
             {
                die("prepare failed:".$conn->error);
             }
            $stmt_history->bind_param("ii", $product_id, $quantity);
            $stmt_history->execute();
            // After updating stock
mysqli_query($conn, "INSERT INTO product_transactions (product_id, quantity, transaction_type) VALUES ('$product_id', '$quantity', 'purchase')");

            // Commit the transaction
            $conn->commit();

            // Success message
            $message = "Stock added successfully!";
        } catch (Exception $e) {
            // Rollback if any error occurs
            $conn->rollback();
            $message = "Error adding stock: " . $e->getMessage();
        }
    } else {
        $message = "Please enter a valid quantity.";
    }
}

// Get products list
$products = $conn->query("SELECT id, name FROM products");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock In</title>
    <!-- Include Bootstrap CSS for better styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Add Stock</h2>

        <!-- Display success or error message -->
        <?php if (isset($message)): ?>
            <div class="alert <?= isset($message) && strpos($message, 'Error') !== false ? 'alert-danger' : 'alert-success' ?> alert-dismissible fade show" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Form to add stock -->
        <form method="POST" class="card p-4 shadow">
            <div class="mb-3">
                <label for="product_id" class="form-label">Select Product</label>
                <select name="product_id" id="product_id" class="form-select" required>
                    <option value="">Choose a product</option>
                    <?php while($row = $products->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Enter quantity" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Add Stock</button>
        </form>
    </div>

    <!-- Include Bootstrap JS for interactivity (like alerts closing) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
