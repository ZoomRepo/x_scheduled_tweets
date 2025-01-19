<?php
$host = '192.168.1.87';
$user = 'root';
$password = 'A1-cxurnk';
$dbname = 'mydb';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>