<?php
include "dbconnect.php";  // Database connection

$beneId = $_POST['bene_id'];
$newFname = $_POST['new_fname'];
$newMname = $_POST['new_mname'];
$newLname = $_POST['new_lname'];
$newDob = $_POST['new_dob'];

$sql = "INSERT INTO tbl_correction_details (bene_id, new_fname, new_mname, new_lname, new_dob,status) VALUES ('$beneId', '$newFname', '$newMname', '$newLname', '$newDob','Replacement')";
$result = $conn->query($sql);

$sqlq = "UPDATE tbl_bene set status='Replacement' where id='$beneId'";
$resultq = $conn->query($sqlq);

if ($result) {
  echo "Correction details saved successfully!";
} else {
  echo "Error saving correction details. Please try again.";
}
?>