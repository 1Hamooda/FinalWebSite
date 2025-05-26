<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'usertable'; 
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?>
