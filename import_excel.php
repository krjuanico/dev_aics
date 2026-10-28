<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

$emm = $_SESSION['userid'] ?? '';
date_default_timezone_set('Asia/Manila');
$date_reg = date("Y-m-d");
$date_imported = date("Y-m-d H:i:s");   
include "dbconnect.php";  // Database connection

$CSV_ESCAPE = "\\";

$payroll_date  = $_POST['payroll_date'] ?? '';
$payroll_staff = $_POST['payroll_staff'] ?? '';
$project_name  = $_POST['project_name'] ?? '';
$amount        = $_POST['amount'] ?? '';
$sdo_num       = $_POST['sdo'] ?? '';
$agency        = $_POST['fund_source'] ?? '';
$team_leader   = $emm; 

$payrollNo = "";
$charging  = "";
$file_name = "";

if (isset($_POST['submit'])) {
    $file = $_FILES['file']['tmp_name'] ?? '';
    $file_name = $_FILES['file']['name'] ?? '';

    if (!$file) {
        echo "<script>alert('No file uploaded!');</script>";
        exit;
    }

    $file_name_esc   = $conn->real_escape_string($file_name);
    $team_leader_esc = $conn->real_escape_string($team_leader);
    $sdo_num_esc     = $conn->real_escape_string($sdo_num);
    $project_name_esc = $conn->real_escape_string($project_name);

    // ✅ Prevent duplicate imports
    $checkFileQuery = "SELECT * FROM tbl_import_log WHERE file_name = '$file_name_esc'";
    $checkFileResult = $conn->query($checkFileQuery);

    if ($checkFileResult && $checkFileResult->num_rows > 0) {
        echo "<script>alert('Error: This file has already been uploaded.')</script>";
        exit;
    }

    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle, 1000, ",", '"', $CSV_ESCAPE); // Skip header row

        // ✅ Read all valid rows first so we know $charging before creating the payroll
        $rows = [];
        while (($data = fgetcsv($handle, 1000, ",", '"', $CSV_ESCAPE)) !== FALSE) {
            if (count($data) < 22) {
                continue; // Skip incomplete rows
            }
            $rows[] = $data;
        }
        fclose($handle);

        if (empty($rows)) {
            echo "<script>alert('No valid rows found in the file.');</script>";
            $conn->close();
            exit;
        }

        // Charging taken from the first valid row
        $charging = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $rows[0][21]));

        // ✅ Find existing payroll for this team leader
        $payrollNoTl = "SELECT payroll_no FROM tbl_payroll_list 
                        WHERE team_leader='$team_leader_esc' AND payroll_status IS NULL";
        $resultTl = $conn->query($payrollNoTl);

        if ($resultTl && $resultTl->num_rows > 0) {
            $rowTl = $resultTl->fetch_assoc();
            $payrollNo = $rowTl['payroll_no'];
        } else {
            $checkCleanList = "SELECT * FROM aics_clean_list";
            $cleanListResult = $conn->query($checkCleanList);

            if ($cleanListResult->num_rows == 0) {
                $createPayroll = "SELECT MAX(payroll_no)+1 as payrollNo FROM tbl_payroll_list";
                $resultPayroll = $conn->query($createPayroll);
                $rowPayroll = $resultPayroll->fetch_assoc();
                $payrollNo = $rowPayroll['payrollNo'] ?? 1;

                // ✅ Save payroll list record
                $savePayroll = "INSERT INTO tbl_payroll_list (payroll_no,sdo,program,project_title,payroll_status,file_name,team_leader) 
                                VALUES ('$payrollNo','$sdo_num_esc','$charging','$project_name_esc','Active','$file_name_esc','$team_leader_esc')";
                $conn->query($savePayroll);
            } else {
                $createPayroll = "SELECT MAX(payroll_no) as payrollNo FROM tbl_payroll_list";
                $resultPayroll = $conn->query($createPayroll);
                $rowPayroll = $resultPayroll->fetch_assoc();
                $payrollNo = $rowPayroll['payrollNo'];
            }
        }

        // ✅ Insert beneficiaries
        foreach ($rows as $data) {
            $controlNo  = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[0]));
            $lname      = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[1]));
            $fname      = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[2]));
            $mname      = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[3]));
            $ename      = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[4]));
            $birth_day  = $conn->real_escape_string($data[5]);
            $birth_month= $conn->real_escape_string($data[6]);
            $birth_year = $conn->real_escape_string($data[7]);
            $sex        = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[8]));
            $civil_status = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[9]));
            $occupation = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[10]));
            $monthly_salary = $conn->real_escape_string(!empty($data[11]) ? $data[11] : '0');
            $category   = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[12]));
            $subcategory= $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[13]));
            $contact    = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[14]));
            $purok      = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[15]));
            $brgy       = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[16]));
            $city       = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[17]));
            $province   = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[18]));
            $typeofassistance = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[19]));
            $amount     = $conn->real_escape_string(!empty($data[20]) ? $data[20] : '0');
            $rowCharging = $conn->real_escape_string(iconv('ISO-8859-1', 'UTF-8', $data[21]));

            $sql2 = "INSERT INTO aics_clean_list (
                        payroll_no, control_number, first_name, middle_name, last_name, extension_name,
                        birth_day, birth_month, birth_year, sex, civil_status, occupation, monthly_salary,
                        category, subcategory, purok, barangay, city_municipality, province,
                        type_of_assistance, amount, charging, date_imported
                    )
                    VALUES (
                        '$payrollNo','$controlNo','$fname','$mname','$lname','$ename',
                        '$birth_day','$birth_month','$birth_year','$sex','$civil_status','$occupation',
                        '$monthly_salary','$category','$subcategory','$purok','$brgy','$city','$province',
                        '$typeofassistance','$amount','$rowCharging','$date_imported'
                    )";

            if ($conn->query($sql2) !== TRUE) {
                echo "Error: " . $conn->error;
            }
        }

        // ✅ Log import file
        $logFileQuery = "INSERT INTO tbl_import_log (file_name, import_date) VALUES ('$file_name_esc', NOW())";
        $conn->query($logFileQuery);

        // ✅ Update or insert Team Leader
        $selectUser = "SELECT empid FROM lib_users WHERE empid='$team_leader_esc'";
        $resultUser = $conn->query($selectUser);
        $rowUser = $resultUser->fetch_assoc();
        $empid = $rowUser['empid'] ?? null;

        if ($empid !== null && $empid == $team_leader) {
            $save_special = "UPDATE lib_users 
                             SET payroll_no='$payrollNo', date_assigned='$date_reg',
                                 user_type='Team Leader', team_leader='$empid' 
                             WHERE empid='$empid'";
            $conn->query($save_special);
        } else {
            $sqlUser = "INSERT INTO lib_users (empid, user_type, user_status, team_leader, payroll_no, date_assigned)
                        VALUES ('$team_leader_esc', 'Team Leader', '1', '$team_leader_esc', '$payrollNo', '$date_reg')";
            $conn->query($sqlUser);
        }

        echo "<script>alert('Import Done!');window.location.href='validation.php?payroll_no=$payrollNo';</script>";
    } else {
        echo "Error opening the CSV file.";
    }

    $conn->close();
}
?>