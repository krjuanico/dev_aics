<?php
include "php/db.php";
include "dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = $_POST['categoryName'];
    $newfsname 	= $_POST['newfsname'];

    // Insert the new SDO into the database
    $sql = "INSERT INTO subcategory (FkcategoryId,subCategoryName,subCategoryStatus) VALUES ('$categoryName','$newfsname','Active')";

    if (mysqli_query($conn, $sql)) {
        echo "New Sub-Category added successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
