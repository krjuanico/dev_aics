<?php
include "dbconnect.php";  // Database connection
error_reporting(0);
$reportType = $_POST['report_type'];
$payroll_no = $_POST['payrollNo'];
if ($reportType == 'Correction') {
    $sql = "SELECT * FROM tbl_correction_details ";
			
			
	$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $output = '';
    $output .= 'S/N.,CONTROL NUMBER,LAST NAME,FIRST NAME,MIDDLE NAME,EXTENSION NAME,BIRTH DAY,BIRTH MONTH,BIRTH YEAR,SEX,CIVIL STATUS,OCCUPATION,MONTHLY SALARY,CATEGORY,SUB-CATEGORY,CONTACT NUMBER,PUROK,BARANGAY,CITY/MUNICIPALITY,PROVINCE,TYPE OF ASSISTANCE,AMOUNT,CHARGING' . "\n";

    while ($row = $result->fetch_assoc()) {
        $output .= $row['bene_id']. ',' . $row['new_lname']. ',' . $row['new_lname'] .',' . $row['new_fname'] .',' . $row['new_mname'] .',' . $row['new_ename'] .',' . $row['birthday'] .',' . $row['birthmonth'] .',' . $row['birthyear'] .',' . $row['sex'] .',' . $row['civilStatus'] .',' . $row['occupation'] .',' . $row['salary'] .',' . $row['category'] .',' . $row['subCategory'] .',' . $row['contactNumber'] .',' . $row['purok'] .',' . $row['barangay'] .',' . $row['city'] .',' . $row['province'] .',' . $row['typeOfAssistance'] .',' . $row['amount'] .',' . $row['charging'] . "\n";
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report_' . date('Y-m-d_H-i-s') . '.csv"');

    echo $output;
    exit;
} else {
    echo 'No records found.';
}
		
} elseif ($reportType == 'Replacement') {
    $sql = "SELECT * FROM tbl_correction_details as a
			INNER JOIN tbl_bene as b on b.id = a.bene_id
			WHERE a.status='Replacement' and b.payroll_no='$payroll_no'";
	$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $output = '';
    $output .= 'No.,Last Name,First Name,Middle Name,Date of Birth,Province,City/Municipality,Barangay,Purok' . "\n";

    while ($row = $result->fetch_assoc()) {
        $output .= $row['bene_id'] . ',' . $row['new_lname'] .',' . $row['new_fname'] .',' . $row['new_mname'] .',' . $row['new_dob'] .',' . $row['province'] .',' . $row['city_muni'] .',' . $row['barangay'] .',' . $row['purok'] . "\n";
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report_' . date('Y-m-d_H-i-s') . '.csv"');

    echo $output;
    exit;
} else {
    echo 'No records found.';
}
} elseif ($reportType == 'Claimed') {
	$ctr=1;
    $sql = "SELECT * FROM tbl_bene WHERE payroll_no = '$payroll_no' AND status ='Claimed' ORDER BY payroll_id ASC";
	$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $output = '';
    $output .= 'No.,Last Name,First Name,Middle Name,Ext. Name,Date of Birth,Province,City/Municipality,Barangay,Purok' . "\n";

    while ($row = $result->fetch_assoc()) {
        $output .= $ctr . ',' . $row['lname'] .',' . $row['fname'] .',' . $row['mname'] .',' . $row['ename'] .',' . $row['dob'] .',' . $row['province'] .',' . $row['city_muni'] .',' . $row['barangay'] .',' . $row['purok'] . "\n";
    $ctr++;
	}

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report_' . date('Y-m-d_H-i-s') . '.csv"');

    echo $output;
    exit;
} else {
    echo 'No records found.';
}
} elseif ($reportType == 'Validated') {
    $sql = "SELECT * FROM tbl_bene 
			WHERE status='Validated' and payroll_no='$payroll_no' ORDER BY payroll_id ASC";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		$output = '';
		$output .= 'No.,Last Name,First Name,Middle Name,Ext. Name,Date of Birth,Province,City/Municipality,Barangay,Purok' . "\n";

		while ($row = $result->fetch_assoc()) {
			$output .= $row['id'] . ',' . $row['lname'] .',' . $row['fname'] .',' . $row['mname'] .',' . $row['ename'] .',' . $row['dob'] .',' . $row['province'] .',' . $row['city_muni'] .',' . $row['barangay'] .',' . $row['purok'] . "\n";
		}

		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="report_' . date('Y-m-d_H-i-s') . '.csv"');

		echo $output;
		exit;
	} else {
		echo 'No records found.';
	}
} elseif ($reportType == 'TagClaimed') {
    $sql = "UPDATE tbl_bene set status='Claimed' where payroll_no='$payroll_no' and status='Validated'";
	$result = $conn->query($sql);

}
?>