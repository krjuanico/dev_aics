<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
//error_reporting(0);
$emm = $_SESSION['userid'];
date_default_timezone_set('Asia/Manila');
$date_reg = date("Y-m-d");

include "dbconnect.php";  // Database connection
include "php/db.php";  // Database connection

$payroll_date    		= $_POST['payroll_date_from'];
$payroll_date_to 		= $_POST['payroll_date_to'];
$sdo   					= $_POST['sdo'];
$venue		    		= $_POST['venue'];
$team_leader   			= $_POST['team_leader'];
$selectedFile      		= $_POST['selectedFile'];
$fundSource      		= $_POST['partnerId'];
					
// Check if the form was submitted
if (isset($_POST['submit'])) {
	
	$updatePayroll = "UPDATE tbl_payroll_list SET team_leader='$team_leader',sdo='$sdo',city_muni='$venue',dateFrom='$payroll_date',dateTo='$payroll_date_to',fund_source='$fundSource' WHERE payroll_no='$selectedFile'";
	$result = $conn->query($updatePayroll);
		 
	$selectUser = "SELECT empid from lib_users where empid='$team_leader'";
	$resultUser = $conn->query($selectUser);
	$rowUser = $resultUser->fetch_assoc();
		$empid = $rowUser['empid'];

	if($empid==$team_leader){
		 $save_special = "UPDATE lib_users SET payroll_no='$selectedFile',date_assigned='$date_reg',user_type='Team Leader',team_leader='$empid' WHERE empid='$empid'";
			if ($conn->query($save_special) === TRUE) { 
				echo "Status updated successfully";
			} else {
				echo "Error: " . $conn->error;
			}
	}else{
		$sql2 = "INSERT INTO lib_users (empid, user_type, user_status,team_leader, payroll_no, date_assigned)
			VALUES ('$team_leader', 'Team Leader', '1', '$team_leader', '$selectedFile', '$date_reg')";
		if ($conn->query($sql2) !== TRUE) {
			echo "Error: " . $conn->error;
		}
	}
    // Close the database connection
$conn->close();
	echo "<script>alert('Team Leader has been successfully assigned to $venue!');
					window.location.href='teamLeaderAssignment.php';
		  </script>";

}
?>