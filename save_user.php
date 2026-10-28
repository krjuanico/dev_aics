<?php
include 'dbconnect.php';  // Include your database connection file
date_default_timezone_set('Asia/Manila');

$date_today = date("Y-m-d");
$empid = $_POST['empid'];
$password = $_POST['password'];
$userType = $_POST['userType'];
$areaassigned = $_POST['areaassigned'];
$status = 1;

// Check if empid already exists
$sql_check = "SELECT * FROM lib_users WHERE empid = '$empid'";
$result = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($result) > 0) {
    // Update existing user
    $sql_update = "UPDATE lib_users 
                   SET password = '$password', 
                       user_type = '$userType', 
                       areaassigned = '$areaassigned', 
                       user_status = '$status' 
                   WHERE empid = '$empid'";

    if (mysqli_query($conn, $sql_update)) {
        echo "User details updated successfully.";
    } else {
        echo "Error updating user: " . mysqli_error($conn);
    }
} else {
    // Insert new user
    $sql_insert = "INSERT INTO lib_users (empid, user_status, password, user_type, areaassigned) 
                   VALUES ('$empid', '$status', '$password', '$userType', '$areaassigned')";

    if (mysqli_query($conn, $sql_insert)) {
        echo "New user added successfully.";
    } else {
        echo "Error inserting user: " . mysqli_error($conn);
    }
}

$conn->close();
?>
