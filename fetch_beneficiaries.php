<?php
include("dbconnect.php");
session_start();

$empid = $_SESSION['userid'] ?? '';
$payrollNo = null;

// ✅ Get payroll number for user
if (!empty($empid)) {
    $sql = "SELECT payroll_no FROM lib_users WHERE empid = '$empid' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $payrollNo = $row['payroll_no'];
    }
}

// Parameters from DataTables
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length'];
$searchValue = $_POST['search']['value'];

// Base query
$where = "WHERE (status IS NULL OR status='Correction' OR status='Claimed') 
          AND exportStatus IS NULL ";

if ($payrollNo) {
    $where .= " AND payroll_no='$payrollNo' ";
}

if (!empty($searchValue)) {
    $where .= " AND (control_number LIKE '%$searchValue%' 
                 OR CONCAT(last_name, ', ', first_name, ' ', COALESCE(middle_name,''), ' ', COALESCE(extension_name,'')) LIKE '%$searchValue%'
                 OR barangay LIKE '%$searchValue%')";
}

// Total records
$totalRecordsQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM aics_clean_list $where");
$totalRecords = mysqli_fetch_assoc($totalRecordsQuery)['total'];

// Fetch data
$sql = "SELECT id, control_number,
        CONCAT(last_name, ', ', first_name, ' ', COALESCE(middle_name,''), ' ', COALESCE(extension_name,'')) AS fullname,
        CONCAT(birth_day,'/',birth_month,'/',birth_year) AS dob,
        barangay, status
        FROM aics_clean_list
        $where 
        ORDER BY id ASC
        LIMIT $row, $rowperpage";
$result = mysqli_query($conn, $sql);

$data = [];
$ctr = $row + 1;

while ($row = mysqli_fetch_assoc($result)) {
    $status = ($row['status'] == 'Correction') ? 'Corrected' : $row['status'];
    $data[] = [
        $ctr++,
        $row['control_number'],
        strtoupper(str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ',$row['fullname'])),
        $row['dob'],
        strtoupper($row['barangay']),
        $status,
        "<button class='btn btn-primary view-payroll' data-payroll-no='{$row['control_number']}'>View</button>"
    ];
}

$response = [
    "draw" => intval($draw),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => $data
];

echo json_encode($response);
?>
