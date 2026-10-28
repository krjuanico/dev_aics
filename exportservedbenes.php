<?php
include "dbconnect.php";  // Database connection
//error_reporting(0);

// Prepare SQL query to fetch data
$sql = "SELECT id,control_number,first_name,middle_name,last_name,extension_name,birth_day,birth_month,birth_year,sex,civil_status,occupation,monthly_salary,category,subcategory,contact_number,barangay,purok,city_municipality,province,type_of_assistance,charging,amount,date(date_last_served) as date_last_served,last_served_location FROM aics_clean_list WHERE status in ('Claimed','Validated')";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Start output buffer to generate CSV
    $output = '';
    $output .= 'S/N,CONTROL NUMBER,LAST NAME,FIRST NAME,MIDDLE NAME,EXTENSION NAME,BIRTH DAY,BIRTH MONTH,BIRTH YEAR,SEX,CIVIL STATUS,OCCUPATION,MONTHLY SALARY,CATEGORY,SUB-CATEGORY,CONTACT NUMBER,PUROK,BARANGAY,CITY/MUNICIPALITY,PROVINCE,TYPE OF ASSISTANCE,AMOUNT,CHARGING,DATE LAST SERVED,LAST SERVED LOCATION' . "\n";

    // Fetch and format rows into CSV
    while ($row = $result->fetch_assoc()) {
        $output .= $row['id'] . ',' . $row['control_number'] . ',' . $row['last_name'] . ',' . $row['first_name'] . ',' . $row['middle_name'] . ',' . $row['extension_name'] . ',' . $row['birth_day'] . ',' . $row['birth_month'] . ',' . $row['birth_year'] . ',' . $row['sex'] . ',' . $row['civil_status'] . ',' . $row['occupation'] . ',' . $row['monthly_salary'] . ',' . $row['category'] . ',' . $row['subcategory'] . ',' . $row['contact_number'] . ',' . $row['purok'] . ',' . $row['barangay'] . ',' . $row['city_municipality'] . ',' . $row['province'] . ',' . $row['type_of_assistance'] . ',' . $row['amount'] . ',' . $row['charging']. ',' . $row['date_last_served'] . ',' . $row['last_served_location'] . "\n";
    }

    // Output the CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="served_benes.csv"');
    echo $output;

} else {
    echo 'No records found.';
}
?>
