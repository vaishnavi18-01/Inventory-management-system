<?php
// Include database connection file
include('config/db.php'); // सही path देने का ध्यान रखें

// Query to get the total number of products
$sql_total_products = "SELECT COUNT(*) AS total_products FROM products";
$result_total_products = $conn->query($sql_total_products);
$total_products = 0;
if ($result_total_products->num_rows > 0) {
    $row = $result_total_products->fetch_assoc();
    $total_products = $row['total_products'];
}

// Query to get total sales (example query, adjust based on your database schema)
$sql_total_sales = "SELECT SUM(amount) AS total_sales FROM sales WHERE DATE(date) = CURDATE()"; // Sales today
$result_total_sales = $conn->query($sql_total_sales);
$total_sales = 0;
if ($result_total_sales->num_rows > 0) {
    $row = $result_total_sales->fetch_assoc();
    $total_sales = $row['total_sales'];
}

// Query to get current stock (adjust this query based on your table structure)
$sql_total_stock = "SELECT SUM(stock) AS total_stock FROM products";
$result_total_stock = $conn->query($sql_total_stock);
$total_stock = 0;
if ($result_total_stock->num_rows > 0) {
    $row = $result_total_stock->fetch_assoc();
    $total_stock = $row['total_stock'];
}

// Query to get the most recent products (Last 5 added products)
$sql_recent_products = "SELECT name, price, stock FROM products ORDER BY created_at DESC LIMIT 5";
$result_recent_products = $conn->query($sql_recent_products);

// Close the connection
$conn->close();
?>

<!-- HTML: Dashboard Overview -->
<main>
    <h2>Welcome, <?= $_SESSION['user'] ?>!</h2>
    <p>Use the navigation menu to manage products, customers, stock, and sales.</p>

    <!-- Dashboard Summary Section -->
    <div class="dashboard-summary">
        <h3>Dashboard Overview</h3>
        <div class="summary-cards">
            <div class="card">
                <h4>Total Products</h4>
                <p><?= $total_products ?></p> <!-- Display total products count -->
            </div>
            <div class="card">
                <h4>Total Sales Today</h4>
                <p>$<?= number_format($total_sales, 2) ?></p> <!-- Display total sales today -->
            </div>
            <div class="card">
                <h4>Current Stock</h4>
                <p><?= $total_stock ?> units</p> <!-- Display total stock -->
            </div>
        </div>
    </div>

    <!-- Recently Inserted Products -->
    <div class="recent-products">
        <h3>Recently Inserted Products</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_recent_products->num_rows > 0) {
                    while ($row = $result_recent_products->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>$" . number_format($row['price'], 2) . "</td>";
                        echo "<td>" . $row['stock'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No products found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>
