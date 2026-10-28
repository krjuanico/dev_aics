<?php
require __DIR__ . '/vendor/autoload.php'; // Load PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include "dbconnect.php"; // Database connection
date_default_timezone_set('Asia/Manila');
$datetoday = date("Y-m-d");
mysqli_set_charset($conn, "utf8mb4");

// Ensure 'venue' is set
$venue = isset($_GET['venue']) ? $_GET['venue'] : 'Unknown';

// Update venue and export status before generating the report
$sql_update = "UPDATE aics_clean_list SET last_served_location='$venue', dateExported='$datetoday' where exportStatus is null";
if (!$conn->query($sql_update)) {
    die("Error updating aics_clean_list: " . $conn->error);
}


// Create a new spreadsheet
$spreadsheet = new Spreadsheet();

// Define headers
$headers = [
    'PAYROLL NO','CONTROL NUMBER', 'LAST NAME', 'FIRST NAME', 'MIDDLE NAME', 'EXTENSION NAME',
    'BIRTH DAY', 'BIRTH MONTH', 'BIRTH YEAR', 'SEX', 'CIVIL STATUS', 'OCCUPATION',
    'MONTHLY SALARY', 'CATEGORY', 'SUB-CATEGORY', 'CONTACT NUMBER', 'PUROK',
    'BARANGAY', 'CITY/MUNICIPALITY', 'PROVINCE', 'TYPE OF ASSISTANCE', 'AMOUNT',
    'CHARGING', 'DATE LAST SERVED', 'LAST SERVED LOCATION','TAGGED BY'
];

/**
 * Function to add data to a sheet
 */
function addDataToSheet($spreadsheet, $sheetIndex, $sheetName, $sql, $conn, $headers) {
    $sheet = ($sheetIndex == 0) ? $spreadsheet->setActiveSheetIndex($sheetIndex) : $spreadsheet->createSheet();
    $sheet->setTitle($sheetName);
    $spreadsheet->setActiveSheetIndex($sheetIndex);

    // Set headers
    $sheet->fromArray([$headers], NULL, 'A1');

    // Fetch and format data
    $result = $conn->query($sql);
    if (!$result) {
        die("Error in query for $sheetName: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $formattedData = [];
        foreach ($data as $row) {
            $formattedData[] = [
                $row['payroll_id'] ?? '',$row['control_number'] ?? '', str_replace(['Ã','Ã±','ÃÃ','ÃÂ', 'Ã'], 'Ñ', $row['last_name']) ?? '', str_replace(['Ã','Ã±','ÃÃ','ÃÂ','Ã'], 'Ñ', $row['first_name']) ?? '',
                str_replace(['Ã','Ã±','ÃÃ','ÃÂ', 'Ã'], 'Ñ', $row['middle_name']) ?? '', $row['extension_name'] ?? '', $row['birth_day'] ?? '',
                $row['birth_month'] ?? '', $row['birth_year'] ?? '', $row['sex'] ?? '',
                $row['civil_status'] ?? '', $row['occupation'] ?? '', $row['monthly_salary'] ?? '',
                $row['category'] ?? '', $row['subcategory'] ?? '', $row['contact_number'] ?? '',
                $row['purok'] ?? '', $row['barangay'] ?? '', $row['city_municipality'] ?? '',
                $row['province'] ?? '', $row['type_of_assistance'] ?? '', $row['amount'] ?? '',
                $row['charging'] ?? '', $row['date_last_served'] ?? '', $row['last_served_location'] ?? '', $row['updated_by'] ?? ''
            ];
        }
        $sheet->fromArray($formattedData, NULL, 'A2');
    }
}

// SQL queries for each sheet
$queries = [
    ["Claimed Beneficiaries", "SELECT payroll_id,control_number, last_name, first_name, middle_name, extension_name, birth_day, birth_month, birth_year, sex, civil_status, occupation, monthly_salary, category, subcategory, contact_number, purok, barangay, city_municipality, province, type_of_assistance, charging, amount,date_last_served, last_served_location,updated_by FROM aics_clean_list WHERE status = 'Claimed' AND exportStatus IS NULL order by payroll_no ASC"],
    ["Corrections", "SELECT controlNo AS control_number, new_lname AS last_name, new_fname AS first_name, new_mname AS middle_name, new_ename AS extension_name, birthday AS birth_day, birthmonth AS birth_month, birthyear AS birth_year, sex, civilStatus AS civil_status, occupation, salary AS monthly_salary, category, subCategory AS subcategory, contactNumber AS contact_number, purok, barangay, city AS city_municipality, province  FROM tbl_correction_details WHERE status = 'Correction' AND exportStatus IS NULL"],
    ["Disqualified Beneficiaries", "SELECT control_number, last_name, first_name, middle_name, extension_name, birth_day, birth_month, birth_year, sex, civil_status, occupation, monthly_salary, category, subcategory, contact_number, purok, barangay, city_municipality, province FROM aics_clean_list WHERE status IN ('Disqualified') AND exportStatus IS NULL"],
    ["No Show Beneficiaries", "SELECT control_number, last_name, first_name, middle_name, extension_name, birth_day, birth_month, birth_year, sex, civil_status, occupation, monthly_salary, category, subcategory, contact_number, purok, barangay, city_municipality, province, type_of_assistance, charging, amount FROM aics_clean_list WHERE status IS NULL AND exportStatus IS NULL"]
];

// Populate sheets
foreach ($queries as $index => [$sheetName, $sql]) {
    addDataToSheet($spreadsheet, $index, $sheetName, $sql, $conn, $headers);
}

// Update venue and export status before generating the report
$sql_update = "INSERT INTO aics_conso_list (
    control_number,
    first_name,
    middle_name,
    last_name,
    extension_name,
    birth_day,
    birth_month,
    birth_year,
    sex,
    civil_status,
    occupation,
    monthly_salary,
    category,
    subcategory,
    type_of_assistance,
    province,
    city_municipality,
    barangay,
    purok,
    contact_number,
    amount,
    charging,
    file_source,
    date_processed,
    created_at,
    date_last_served,
    status,
    payroll_id,
    last_served_location,
    payroll_no,
    date_synced,
    codeforsyncing,
    updated_by,
    exportStatus,
    dateExported
)
SELECT 
    control_number,
    first_name,
    middle_name,
    last_name,
    extension_name,
    birth_day,
    birth_month,
    birth_year,
    sex,
    civil_status,
    occupation,
    monthly_salary,
    category,
    subcategory,
    type_of_assistance,
    province,
    city_municipality,
    barangay,
    purok,
    contact_number,
    amount,
    charging,
    file_source,
    date_processed,
    created_at,
    date_last_served,
    status,
    payroll_id,
    last_served_location,
    payroll_no,
    date_synced,
    codeforsyncing,
    updated_by,
    exportStatus,
    dateExported
FROM aics_clean_list";
if (!$conn->query($sql_update)) {
    die("Error updating aics_clean_list: " . $conn->error);
}

$sql_update12 = "truncate aics_clean_list";
if (!$conn->query($sql_update12)) {
    die("Error updating tbl_correction_details: " . $conn->error);
}
$sql_update1 = "UPDATE tbl_correction_details SET exportStatus = 'Exported', dateExported='$datetoday' where exportStatus is null";
if (!$conn->query($sql_update1)) {
    die("Error updating tbl_correction_details: " . $conn->error);
}
// Set the first sheet as active
$spreadsheet->setActiveSheetIndex(0);

// Output the Excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Beneficiary_Report.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
