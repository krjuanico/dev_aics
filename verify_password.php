<?php
// verify_password.php

include("php/db.php"); 
include("dbconnect.php"); 

$password = $_POST['password'];

$sql = "SELECT * FROM employee_info WHERE  emppass = '$password'";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    echo 'true';
} else {
    echo 'false';
}
?>