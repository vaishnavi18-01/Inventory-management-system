<?php
include('../db.php');
$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo "Product deleted.";
?>
<a href="list.php">Back to list</a>