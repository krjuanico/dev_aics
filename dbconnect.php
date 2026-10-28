<?php
$servername = "localhost";
$username = "root";
$password = "uplift_server";
$database ="aicsakap_db";

$conn = mysqli_connect($servername, $username, $password,$database);
mysqli_set_charset($conn, "utf8");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

} 


?>