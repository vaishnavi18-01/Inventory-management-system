<?php 
include('../db.php');  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {     
    $name = $_POST['name'];     
    $mobile = $_POST['mobile'];     
    $address = $_POST['address'];     

    $stmt = $conn->prepare("INSERT INTO customers (name, mobile, address) VALUES (?, ?, ?)");     
    $stmt->bind_param("sss", $name, $mobile, $address);     

    if ($stmt->execute()) {         
        echo "<div class='alert success'>Customer added!</div>";     
    } else {         
        echo "<div class='alert error'>Error: " . $conn->error . "</div>";     
    } 
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 16px;
            transition: border 0.3s;
        }
        input:focus, textarea:focus {
            border-color: #007BFF;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .alert {
            padding: 10px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }
        .alert.success {
            background-color: #28a745;
            color: white;
        }
        .alert.error {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Add Customer</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="mobile" placeholder="Mobile" required>
            <textarea name="address" placeholder="Address" required></textarea>
            <button type="submit">Add</button>
        </form>
    </div>

</body>
</html>
