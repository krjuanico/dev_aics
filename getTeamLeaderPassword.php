<?php
// Database connection
include 'dbconnect.php';
session_start();
date_default_timezone_set('Asia/Manila');

$empid    = $_SESSION['userid'] ?? '';   // ✅ get current user
$date_val = date("Y-m-d H:i:s");

// Get input parameters
$enteredPassword = $_GET['password'] ?? '';
$beneId          = $_GET['beneId'] ?? '';
$actionType      = $_GET['action'] ?? 'validate'; // default to validate
$teamLeader      = 'Team Leader';

$response = [];

// ✅ Validate input
if (empty($enteredPassword) || empty($beneId)) {
    echo json_encode([
        "error" => "Missing parameters",
        "enteredPassword" => $enteredPassword,
        "beneId" => $beneId
    ]);
    exit;
}

// 🔹 Check TL password
$sql = "SELECT * FROM lib_users WHERE password = ? AND user_type = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $enteredPassword, $teamLeader);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    // Get next audit number
    $sql1 = "SELECT MAX(id) as ctr_audit FROM tbl_audit_log";
    $result1 = $conn->query($sql1);
    $row1 = $result1->fetch_assoc();
    $ctr_audit = ($row1['ctr_audit'] ?? 0) + 1;

    $save_special = "UPDATE aics_clean_list 
    SET status = NULL, payroll_id = NULL 
    WHERE id = ?";

    $stmt2 = $conn->prepare($save_special);
    $stmt2->bind_param("i", $beneId);

    if ($stmt2->execute() && $stmt2->affected_rows > 0) {
        // Log action
        $actionLog = ucfirst($actionType) . " action performed for Beneficiary ID " . $beneId;
        $save_to_dtr1 = "INSERT INTO tbl_audit_log (description, updated_by, date_updated, audit_no) 
                         VALUES (?, ?, ?, ?)";
        $stmt3 = $conn->prepare($save_to_dtr1);
        $stmt3->bind_param("sssi", $actionLog, $empid, $date_val, $ctr_audit);
        $stmt3->execute();
            
        echo json_encode('Status updated');
    } else {
        $response = [
            "error" => "No update performed (maybe already in that state)",
            "sql_error" => $conn->error
        ];
    }

} else {
    $response =$enteredPassword;
    echo json_encode($response);
}



// Close connection
$conn->close();
?>
