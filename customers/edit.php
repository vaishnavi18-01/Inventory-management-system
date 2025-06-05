<?php
include('../db.php');
$id = (int) $_GET['id']; // Sanitize input

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];

    // Basic input validation
    if (empty($name) || empty($mobile) || empty($address)) {
        echo "<p class='error-msg'>All fields are required!</p>";
    } else {
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("UPDATE customers SET name=?, mobile=?, address=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $mobile, $address, $id);

        if ($stmt->execute()) {
            echo "<p class='success-msg'>Customer updated successfully!</p>";
            // Optionally, redirect to another page
            // header('Location: customer_list.php');
            // exit();
        } else {
            echo "<p class='error-msg'>Error updating customer!</p>";
        }
    }
}

$customer = $conn->query("SELECT * FROM customers WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* Reset some default styles */
        body, h2, p {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f9fc;
            color: #333;
            padding: 20px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #4a90e2;
        }

        .form-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #4a90e2;
            outline: none;
        }

        .form-group textarea {
            resize: vertical;
            height: 120px;
        }

        button[type="submit"] {
            background-color: #4a90e2;
            color: #fff;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #357abd;
        }

        .success-msg {
            color: green;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .error-msg {
            color: red;
            font-weight: 600;
            margin-bottom: 10px;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .form-container {
                padding: 20px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Edit Customer</h2>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($name) && !empty($mobile) && !empty($address)): ?>
            <!-- Display success or error messages here -->
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($customer['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile</label>
                <input type="text" id="mobile" name="mobile" value="<?= htmlspecialchars($customer['mobile']) ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" required><?= htmlspecialchars($customer['address']) ?></textarea>
            </div>
            <button type="submit">Update</button>
        </form>
    </div>

</body>
</html>
