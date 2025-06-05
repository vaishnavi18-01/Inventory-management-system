<?php
include('../db.php');
$result = $conn->query("SELECT *, (price * stock) AS value FROM products");
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Value Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Rubik', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            font-size: 14px;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        td, th {
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .total-row {
            font-weight: bold;
            background-color: #e8f5e9;
        }
        .total-row td {
            border-top: 2px solid #4CAF50;
        }
    </style>
</head>
<body>

<h2>Inventory Value Report</h2>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <?php $total += $row['value']; ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['stock']) ?></td>
                <td><?= number_format($row['price'], 2) ?></td>
                <td><?= number_format($row['value'], 2) ?></td>
            </tr>
        <?php endwhile; ?>
        <tr class="total-row">
            <td colspan="3">Total Inventory Value</td>
            <td><?= number_format($total, 2) ?></td>
        </tr>
    </tbody>
</table>

</body>
</html>
