<?php
include "php/db.php";
include "dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newfsname = $_POST['newfsname'];

    // Insert the new SDO into the database
    $sql = "INSERT INTO category (categoryName,categoryStatus) VALUES ('$newfsname','Active')";

    if (mysqli_query($conn, $sql)) {
        echo "New Category added successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
