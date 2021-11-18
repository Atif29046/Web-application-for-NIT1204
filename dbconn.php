<?php
$conn= new mysqli('localhost', 'root', '', 'shopping');

if($conn->connect_error) {
    die('Connection Error (' . $conn->connect_errno . ')'
    . $conn->connect_error);
}
?>
