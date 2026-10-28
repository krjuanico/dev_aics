<?php
session_start();
include 'dbconnect.php';  // Include your database connection file
$emm=$_SESSION['userid'];
date_default_timezone_set('Asia/Manila');
$date_val = date("Y-m-d H:i:s");
    $bene_id = strtoupper($_POST['bene_id']);
echo $bene_id;
			$sql_update = "UPDATE aics_clean_list SET  status = 'Disqualified',updated_by='$emm' WHERE id = '$bene_id'";
			$stmt_update = mysqli_query($conn, $sql_update);
			
			
			$action = 'Updated Client status to '.$status.' with ID no. ' . $bene_id;
			// Get the current audit log ID
			$sql1 = "SELECT MAX(id) as ctr_audit FROM tbl_audit_log";
			$result1 = $conn->query($sql1);
			$row1 = $result1->fetch_assoc();
			$ctr_audit = $row1['ctr_audit'] + 1;
			$save_to_dtr1 = "INSERT INTO tbl_audit_log (description, updated_by, date_updated, audit_no) VALUES ('$action', '$emm', '$date_val', '$ctr_audit')";
			$rundtr1 = mysqli_query($conn, $save_to_dtr1);
        // Close the update statement
        $stmt_update->close();

$ctr_payroll=0;
$count_payroll_id=0;
    $conn->close();

?>
