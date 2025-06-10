<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "electricity"; 

$conn = new mysqli($servername, $username, $password, $dbname);

//  DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
