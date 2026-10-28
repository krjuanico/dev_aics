<?php
header('Content-Type: text/html; charset=UTF-8');
include "dbconnect.php";  // Database connection
mysqli_set_charset($conn, "utf8mb4");

// Prepare SQL query to fetch data
$sql = "SELECT control_number,first_name,middle_name,last_name,extension_name,birth_day,birth_month,birth_year,sex,civil_status,occupation,monthly_salary,category,subcategory,contact_number,barangay,purok,city_municipality,province,type_of_assistance,charging,amount,date(date_last_served) as date_last_served,last_served_location FROM aics_clean_list WHERE status is NULL";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Start output buffer to generate CSV
    $output = "CONTROL NUMBER,LAST NAME,FIRST NAME,MIDDLE NAME,EXTENSION NAME,BIRTH DAY,BIRTH MONTH,BIRTH YEAR,SEX,CIVIL STATUS,OCCUPATION,MONTHLY SALARY,CATEGORY,SUB-CATEGORY,CONTACT NUMBER,PUROK,BARANGAY,CITY/MUNICIPALITY,PROVINCE,TYPE OF ASSISTANCE,AMOUNT,CHARGING,DATE LAST SERVED,LAST SERVED LOCATION\n";

    // Fetch and format rows into CSV
    while ($row = $result->fetch_assoc()) {
        $first_name = strtoupper(str_replace(['Ã','Ã±','ÃÃ','ÃÂ','Ã‘'], 'Ñ', $row['first_name']));
        $middle_name = strtoupper(str_replace(['Ã','Ã±','ÃÃ','ÃÂ','Ã‘'], 'Ñ', $row['middle_name']));
        $last_name = strtoupper(str_replace(['Ã','Ã±','ÃÃ','ÃÂ','Ã‘'], 'Ñ', $row['last_name']));

        $output .= "{$row['control_number']},$last_name,$first_name,$middle_name,{$row['extension_name']},{$row['birth_day']},{$row['birth_month']},{$row['birth_year']},{$row['sex']},{$row['civil_status']},{$row['occupation']},{$row['monthly_salary']},{$row['category']},{$row['subcategory']},{$row['contact_number']},{$row['purok']},{$row['barangay']},{$row['city_municipality']},{$row['province']},{$row['type_of_assistance']},{$row['amount']},{$row['charging']},{$row['date_last_served']},{$row['last_served_location']}\n";
    }

    // Output the CSV with BOM for proper character display
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="noshowBenes.csv"');
    echo "\xEF\xBB\xBF"; // UTF-8 BOM
    echo $output;

} else {
    echo 'No records found.';
}
?>
