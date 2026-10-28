<?php
include "dbconnect.php";  // Database connection

// Keep errors out of the browser but record them in a log file
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

$logFile = "C:/laragon/www/aicsakap_sys/rdv_debug.log";
function rdv_log($msg) {
    global $logFile;
    error_log("[" . date("Y-m-d H:i:s") . "] " . $msg . "\n", 3, $logFile);
}

rdv_log("---- RDV started on PHP " . PHP_VERSION . " ----");

try {
    $sql = "SELECT * FROM tbl_correction_details WHERE status IS NULL";
    $result = $conn->query($sql);

    if (!$result) {
        rdv_log("Query failed: " . $conn->error);
        echo "Query failed. Check rdv_debug.log";
        exit;
    }

    if ($result->num_rows === 0) {
        rdv_log("No records found.");
        echo 'No records found.';
        exit;
    }

    // Directory and filename configuration
    $directory = "C:/laragon/www/aicsakap_sys/forrdv/";
    if (!is_dir($directory)) {
        if (!mkdir($directory, 0777, true)) {
            rdv_log("Cannot create directory: $directory");
            echo "Cannot create directory for CSV. Check rdv_debug.log";
            exit;
        }
    }

    $timestamp = date("Ymd_His") . "_" . sprintf("%03d", round(microtime(true) * 1000) % 1000);
    $filename  = "rdv_" . $timestamp . ".csv";
    $filepath  = $directory . $filename;

    $fp = fopen($filepath, 'w');
    if ($fp === false) {
        rdv_log("Cannot open file for writing: $filepath");
        echo "Failed to save the file. Check rdv_debug.log";
        exit;
    }

    $header = [
        'S/N', 'CONTROL NUMBER', 'LAST NAME', 'FIRST NAME', 'MIDDLE NAME', 'EXTENSION NAME',
        'BIRTH DAY', 'BIRTH MONTH', 'BIRTH YEAR', 'SEX', 'CIVIL STATUS', 'OCCUPATION',
        'MONTHLY SALARY', 'CATEGORY', 'SUB-CATEGORY', 'CONTACT NUMBER', 'PUROK', 'BARANGAY',
        'CITY/MUNICIPALITY', 'PROVINCE', 'TYPE OF ASSISTANCE', 'AMOUNT', 'CHARGING', 'STATUS',
        'PAYROLL NUMBER'
    ];
    // $escape passed explicitly (empty string) to avoid PHP 8.4 deprecation
    fputcsv($fp, $header, ',', '"', '');

    $count = 0;
    while ($row = $result->fetch_assoc()) {
        fputcsv($fp, [
            $row['bene_id'] ?? '',
            $row['controlNo'] ?? '',
            $row['new_lname'] ?? '',
            $row['new_fname'] ?? '',
            $row['new_mname'] ?? '',
            $row['new_ename'] ?? '',
            $row['birthday'] ?? '',
            $row['birthmonth'] ?? '',
            $row['birthyear'] ?? '',
            $row['sex'] ?? '',
            $row['civilStatus'] ?? '',
            $row['occupation'] ?? '',
            $row['salary'] ?? '',
            $row['category'] ?? '',
            $row['subCategory'] ?? '',
            $row['contactNumber'] ?? '',
            $row['purok'] ?? '',
            $row['barangay'] ?? '',
            $row['city'] ?? '',
            $row['province'] ?? '',
            $row['typeOfAssistance'] ?? '',
            $row['amount'] ?? '',
            $row['charging'] ?? '',
            $row['status'] ?? '',
            $row['payroll_no'] ?? ''
        ], ',', '"', '');
        $count++;
    }
    fclose($fp);

    if (!file_exists($filepath) || filesize($filepath) === 0) {
        rdv_log("CSV missing or empty after write: $filepath");
        echo "Failed to save the file. Check rdv_debug.log";
        exit;
    }

    rdv_log("CSV written: $filepath ($count rows)");

    // Delete only after the CSV is confirmed on disk
    $conn->query("DELETE FROM tbl_correction_details WHERE status IS NULL");
    rdv_log("Deleted source rows from tbl_correction_details. Error: " . ($conn->error ?: 'none'));

} catch (Throwable $e) {
    rdv_log("EXCEPTION: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit;
}

// Run Python script
$pythonPath = "C:\\Users\\CIS\\AppData\\Local\\Microsoft\\WindowsApps\\python.exe";
$scriptPath = "C:\\laragon\\www\\aicsakap_sys\\fuzzy_match.py";

if (!function_exists('exec')) {
    rdv_log("exec() is disabled in php.ini (disable_functions).");
}

$command = escapeshellarg($pythonPath) . " " . escapeshellarg($scriptPath) . " " . escapeshellarg($filename) . " 2>&1";
$pyOutput = [];
$return_var = 0;

rdv_log("Running: $command");
exec($command, $pyOutput, $return_var);
rdv_log("Python exit code: $return_var");
rdv_log("Python output: " . implode(" | ", $pyOutput));

$msg = ($return_var === 0) ? 'RDV Done' : 'RDV finished with a Python error. Check rdv_debug.log';

echo "<script>
    alert(" . json_encode($msg) . ");
    window.open('validation.php', '_self');
</script>";
?>