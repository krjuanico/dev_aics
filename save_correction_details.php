<?php
header('Content-Type: text/html; charset=UTF-8');
include "dbconnect.php";  // Database connection
$conn->set_charset("utf8mb4");   // <-- important: makes Ñ survive the trip to MySQL
session_start();

// Repairs garbled Ñ (e.g. "Ã‘", "Ã±") and uppercases it properly
function fixN($str) {
    $str = (string)$str;
    $str = str_replace(['Ã‘', 'Ã±', 'ÃÂ', 'Ã'], 'Ñ', $str);
    return mb_strtoupper(trim($str), 'UTF-8');
}

$emm            = $_SESSION['userid'];
$beneId         = $_POST['bene_id'];
$newFname       = fixN($_POST['new_fname']);
$newMname       = fixN($_POST['new_mname']);
$newLname       = fixN($_POST['new_lname']);
$new_ename      = fixN($_POST['new_ename'] ?? "");
$sex            = $_POST['sex'];
$birthday       = $_POST['birthday'];
$birthmonth     = $_POST['birthmonth'];
$birthyear      = $_POST['birthyear'];
$civil_status1  = $_POST['civil_status'];
$occupation     = $_POST['occupation'];
$salary         = $_POST['salary'] ?? 0;
$category       = $_POST['category'];
$sub_category   = $_POST['sub_category'];
$cnum           = $_POST['cnum'];
$purok          = fixN($_POST['purok']);
$barangay       = fixN($_POST['barangay']);
$citymuni       = fixN($_POST['citymuni']);
$province       = fixN($_POST['province']);
$assistanceType = $_POST['assistanceType'];
$amount         = $_POST['amount'] ?? 0;
$charging       = $_POST['charging'];
$control_no     = $_POST['control_no'];
$payroll_no     = $_POST['payroll_no'];

// Prepared statement: handles Ñ, apostrophes (e.g. "Dela Cruz's"), and blocks SQL injection
$stmt = $conn->prepare("INSERT INTO tbl_correction_details
    (bene_id,new_fname,new_mname,new_lname,new_ename,birthday,birthmonth,birthyear,sex,civilStatus,category,subCategory,occupation,salary,contactNumber,purok,barangay,city,province,typeOfAssistance,amount,charging,controlNo,payroll_no)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
$stmt->bind_param(str_repeat('s', 24),
    $beneId,$newFname,$newMname,$newLname,$new_ename,$birthday,$birthmonth,$birthyear,
    $sex,$civil_status1,$category,$sub_category,$occupation,$salary,$cnum,$purok,
    $barangay,$citymuni,$province,$assistanceType,$amount,$charging,$control_no,$payroll_no);
$result = $stmt->execute();

$stmt1 = $conn->prepare("SELECT * FROM aics_clean_list WHERE control_number=?");
$stmt1->bind_param('s', $control_no);
$stmt1->execute();
$result1 = $stmt1->get_result();

if ($row = $result1->fetch_assoc()) {
    $fname          = fixN($row['first_name']);
    $mname          = fixN($row['middle_name']);
    $lname          = fixN($row['last_name']);
    $ename          = fixN($row['extension_name']);
    $birth_day      = $row['birth_day'];
    $birth_month    = $row['birth_month'];
    $birth_year     = $row['birth_year'];
    $beneId         = $row['id'];
    $civilStatus    = $row['civil_status'];
    $category       = $row['category'];
    $subcategory    = $row['subcategory'];
    $sex            = $row['sex'];
    $monthly_salary = $row['monthly_salary'];
    $status         = 'Correction';

    $stmt2 = $conn->prepare("INSERT INTO tbl_correction_details
        (bene_id,controlNo,new_fname,new_mname,new_lname,new_ename,birthday,birthmonth,birthyear,status,added_by,civilStatus,category,subCategory,salary,sex,payroll_no)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt2->bind_param(str_repeat('s', 17),
        $beneId,$control_no,$fname,$mname,$lname,$ename,$birth_day,$birth_month,$birth_year,
        $status,$emm,$civilStatus,$category,$subcategory,$monthly_salary,$sex,$payroll_no);
    $stmt2->execute();
}

echo $result
    ? "Correction details saved successfully!"
    : "Error saving correction details. Please try again.";
?>