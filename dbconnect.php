<?php
$servername = "localhost";
$username = "root";
$password = "uplift_server";
$database ="dev_aics";

$conn = mysqli_connect($servername, $username, $password,$database);
mysqli_set_charset($conn, "utf8");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

} 


?>