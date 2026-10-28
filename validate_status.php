<?php
session_start();
include 'dbconnect.php';  // Include your database connection file
$emm=$_SESSION['userid'];
date_default_timezone_set('Asia/Manila');
$date_val = date("Y-m-d H:i:s");
    $bene_id = strtoupper($_POST['bene_id']);

			$sql_update = "UPDATE aics_clean_list SET  status = 'Validated' WHERE id = '$bene_id'";
			$stmt_update = mysqli_query($conn, $sql_update);
			
			$action = 'Updated Client status to Validated with ID no. ' . $bene_id;
        // Close the update statement
        $stmt_update->close();

    $conn->close();

?>
