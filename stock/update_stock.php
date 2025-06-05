<?php
include('../db.php');

if ($_POST) {
    $id = $_POST['pk'];
    $column = $_POST['name'];
    $value = $conn->real_escape_string($_POST['value']);
    $sql = "UPDATE stock_history SET $column = '$value' WHERE id = $id";
    if ($conn->query($sql)) {
        echo 'Updated successfully.';
    } else {
        echo 'Error updating record: ' . $conn->error;
    }
}
?>
