<?php
include('../db.php');
$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM customers WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo "Customer deleted.";
?>
<a href="list.php">Back to list</a>