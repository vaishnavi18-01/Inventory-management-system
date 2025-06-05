<?php
include('../db.php'); // adjust path if needed

// Fetch transactions with product names
$query = "SELECT pt.id, p.name AS product_name, pt.quantity, pt.transaction_type, pt.transaction_date 
          FROM product_transactions pt 
          JOIN products p ON pt.product_id = p.id 
          ORDER BY pt.transaction_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaction History</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f2f2f2; }
        .purchase { color: green; font-weight: bold; }
        .sale { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Product Transaction History</h2>
    <table>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Type</th>
            <th>Date</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td class="<?= $row['transaction_type'] === 'purchase' ? 'purchase' : 'sale' ?>">
                    <?= ucfirst($row['transaction_type']) ?>
                </td>
                <td><?= $row['transaction_date'] ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No transactions found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>