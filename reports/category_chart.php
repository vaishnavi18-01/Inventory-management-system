<?php
include('../db.php');

// Query to count products per category
$query = "
    SELECT c.name AS category_name, COUNT(p.id) AS product_count
    FROM categories c
    LEFT JOIN products p ON p.category_id = c.id
    GROUP BY c.id
";
$result = $conn->query($query);

// Initialize arrays to store chart data
$categories = [];
$product_counts = [];

while ($row = $result->fetch_assoc()) {
    $categories[] = $row['category_name'];
    $product_counts[] = $row['product_count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Count by Category</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        canvas {
            max-width: 500px;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Product Count by Category</h2>
        <canvas id="productChart"></canvas>
        <script>
            const ctx = document.getElementById('productChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($categories); ?>,
                    datasets: [{
                        label: 'Product Count',
                        data: <?php echo json_encode($product_counts); ?>,
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#FF9F40', '#4BC0C0',
                            '#9966FF', '#FF66B2', '#FF6666', '#66FF66', '#6699FF'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let total = context.dataset.data.reduce((acc, curr) => acc + curr, 0);
                                    let percentage = ((value / total) * 100).toFixed(2) + '%';
                                    return `${label}: ${value} (${percentage})`;
                                }
                            }
                        }
                    }
                }
            });
        </script>
    </div>
</body>
</html>
