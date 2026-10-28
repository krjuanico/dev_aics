<?php
session_start();
include 'dbconnect.php';  // Include your database connection file
date_default_timezone_set('Asia/Manila');
$datetoday = date("Y-m-d H:i:s");
$emm = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bene_id = $_POST['bene_id'];

    // Step 1: Look for the first missing payroll_id starting from 1
    $sql1 = "
        SELECT seq.seq AS missing_id
        FROM (
            SELECT @rownum := @rownum + 1 AS seq
            FROM information_schema.tables t1,
                 information_schema.tables t2,
                 (SELECT @rownum := 0) r
            LIMIT 100000
        ) seq
        LEFT JOIN aics_clean_list acl
            ON acl.payroll_id = seq.seq
        WHERE acl.payroll_id IS NULL
        ORDER BY seq.seq
        LIMIT 1
    ";
    $result1 = $conn->query($sql1);

    $payroll_id = null;

    if ($result1 && $result1->num_rows > 0) {
        $row1 = $result1->fetch_assoc();
        if (!empty($row1['missing_id'])) {
            $payroll_id = $row1['missing_id']; // ✅ first missing number starting from 1
        }
    }

    // Step 2: If no missing number found, use MAX+1
    if (empty($payroll_id)) {
        $sql2 = "SELECT IFNULL(MAX(payroll_id),0) + 1 AS next_id FROM aics_clean_list";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        $payroll_id = $row2['next_id'];
    }

    echo "Updated: Payroll ID " . $payroll_id;

    // Step 3: Update record
    $save_special = "
        UPDATE aics_clean_list 
        SET status='Claimed',
            payroll_id='$payroll_id',
            updated_by='$emm',
            date_last_served='$datetoday'
        WHERE id='$bene_id'
    ";

    if ($conn->query($save_special) === TRUE) { 
        // success
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
