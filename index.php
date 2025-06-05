<?php session_start(); 

// Include database connection 
include('./db.php'); // 

// Query to get the total number of products
$sql = "SELECT COUNT(*) AS total_products FROM products";
$result = $conn->query($sql);

$total_products = 0;
if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $total_products = $row['total_products'];
}


if (!isset($_SESSION['user'])) { 
    header("Location: login.php"); 
    exit; 
} 
?>
<!DOCTYPE html>
<html lang="hi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="logo">
            <h1>Inventory Management System</h1>
        </div>
    </header>

    <div class="container">
        <nav>
            <h3>Navigation</h3>
            <ul>
                <li><a href="products/add.php">Add Product</a></li>
                <li><a href="products/list.php">View Products</a></li>
                <li><a href="customers/add.php">Add Customer</a></li>
                <li><a href="customers/list.php">View Customers</a></li>
                <li><a href="stock/in.php">Stock In</a></li>
                <li><a href="stock/out.php">Stock Out</a></li>
                <li><a href="stock/history.php">Stock History</a></li>
                <li><a href="reports/low_stock.php">Low Stock Alert</a></li>
                <li><a href="reports/inventory_value.php">Inventory Value</a></li>
                <li><a href="reports/category_chart.php">Category-wise Summary</a></li>
                <li><a href="reports/transaction_history.php">Transaction History</a></li>
                <li><a href="sales/create.php">New Sale</a></li>
                <li><a href="sales/history.php">Sales History</a></li>
            </ul>
        </nav>

        <main>
            <div class="welcome-box">
                <h2>Welcome, <?= htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') ?>!</h2>
                <p>Your control panel to manage inventory efficiently.</p>
                <p><strong>Start by selecting an option from the left-hand menu.</strong></p>
            </div>
        </main>
        <main>
    <h2>Welcome, <?= $_SESSION['user'] ?>!</h2>
    <p>Use the navigation menu to manage products, customers, stock, and sales.</p>
    
    <!-- Dashboard Summary Section -->
    <div class="dashboard-summary">
        <h3>Dashboard Overview</h3>
        <div class="summary-cards">
            <div class="card">
                <h4>Total Products</h4>
                <p>125</p> <!-- Example number -->
            </div>
            <div class="card">
                <h4>Total Sales Today</h4>
                <p>$800</p> <!-- Example number -->
            </div>
            <div class="card">
                <h4>Current Stock</h4>
                <p>50</p> <!-- Example number -->
            </div>
        </div>
    </div>

    <!-- Placeholder for Chart or Graph -->
    <div class="chart-container">
        <h3>Stock and Sales Overview</h3>
        <!-- You can use Chart.js or any other chart library here -->
        <canvas id="stockSalesChart"></canvas>
    </div>
</main>

    </div>

    <footer>
        <p>Logged in as <strong><?= htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') ?></strong> | <a href="logout.php">Logout</a></p>
    </footer>
</body>

</html>
