<?php
header('Content-Type: text/html; charset=UTF-8');
include "dbconnect.php";  // Database connection
mysqli_set_charset($conn, "utf8mb4");

// Prepare SQL query to fetch data
$sql = "SELECT * FROM tbl_correction_details WHERE status = 'Correction'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Start output buffer to generate CSV
    $output = '';
    $output .= 'CONTROL NUMBER,LAST NAME,FIRST NAME,MIDDLE NAME,EXTENSION NAME,BIRTH DAY,BIRTH MONTH,BIRTH YEAR,SEX,CIVIL STATUS,OCCUPATION,MONTHLY SALARY,CATEGORY,SUB-CATEGORY,CONTACT NUMBER,PUROK,BARANGAY,CITY/MUNICIPALITY,PROVINCE,DATE CORRECTED,CORRECTED BY' . "\n";

    // Fetch and format rows into CSV
    while ($row = $result->fetch_assoc()) {
		$first_name = strtoupper(str_replace(['Ã','Ã±','ÃÃ','ÃÂ'], 'Ñ', $row['new_fname']));
        $middle_name = strtoupper(str_replace(['Ã','Ã±','ÃÃ','ÃÂ'], 'Ñ', $row['new_mname']));
        $last_name = strtoupper(str_replace(['Ã','Ã±','ÃÃ','ÃÂ'], 'Ñ', $row['new_lname']));
        $output .= $row['controlNo'] . ',' . $last_name . ',' . $first_name . ',' . $middle_name . ',' . $row['new_ename'] . ',' . $row['birthday'] . ',' . $row['birthmonth'] . ',' . $row['birthyear'] . ',' . $row['sex'] . ',' . $row['civilStatus'] . ',' . $row['occupation'] . ',' . $row['salary'] . ',' . $row['category'] . ',' . $row['subCategory'] . ',' . $row['contactNumber'] . ',' . $row['purok'] . ',' . $row['barangay'] . ',' . $row['city'] . ',' . $row['province'] . $row['date_added'] . ',' . $row['added_by'] . "\n";
    }

    // Output the CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="benesWithCorrections.csv"');
    echo $output;

} else {
    echo 'No records found.';
}
?>
