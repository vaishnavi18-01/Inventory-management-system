<?php
include('../db.php');
$result = $conn->query("SELECT h.*, p.name AS product_name FROM stock_history h JOIN products p ON h.product_id = p.id ORDER BY h.timestamp DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
</head>
<body>
    <div class="container mt-5">
        <h2>Stock History</h2>
        <table class="table table-bordered" id="stockTable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Type</th>
                    <th>Change</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr data-id="<?= $row['id'] ?>">
                        <td><a href="#" class="editable" data-name="product_name" data-type="text" data-pk="<?= $row['id'] ?>" data-title="Enter product name"><?= $row['product_name'] ?></a></td>
                        <td><a href="#" class="editable" data-name="type" data-type="text" data-pk="<?= $row['id'] ?>" data-title="Enter type"><?= $row['type'] ?></a></td>
                        <td><a href="#" class="editable" data-name="change" data-type="text" data-pk="<?= $row['id'] ?>" data-title="Enter change"><?= $row['change'] ?></a></td>
                        <td><a href="#" class="editable" data-name="timestamp" data-type="text" data-pk="<?= $row['id'] ?>" data-title="Enter timestamp"><?= $row['timestamp'] ?></a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize X-editable for inline editing
            $('.editable').editable({
                url: '/update_stock.php',
                type: 'text',
                pk: 1,
                name: 'name',
                title: 'Enter value'
            });
        });
    </script>
</body>
</html>
