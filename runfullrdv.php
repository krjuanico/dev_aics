<?php
header('Content-Type: application/json'); // Tell the browser this is JSON

include "dbconnect.php";  // Include only if necessary and ensure it has no unintended output
error_reporting(0);       // Suppress PHP warnings/errors in the output
ob_start();               // Start output buffering

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploadDir = 'forrdv/';
    $fileName = basename($_FILES['file']['name']);
    $uploadFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        "C:\\Users\\kajuanico\\AppData\\Local\\Microsoft\\WindowsApps\\python.exe";
        $scriptPath = "C:\\laragon\\www\\aicsakap_sys\\rdv.py";

        $command = escapeshellcmd("$pythonPath $scriptPath $uploadFile $fileName");
        $output = [];
        $return_var = 0;

        exec($command, $output, $return_var);

        if ($return_var === 0) {
            echo json_encode(['success' => true, 'message' => 'RDV process completed successfully.', 'output' => $output]);
        } else {
			echo "dasdasd";
		//echo "<script>window.open('rdv.php', '_blank');</script>";
            echo json_encode(['success' => false, 'message' => 'Error occurred during RDV process.', 'output' => $output]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

ob_end_flush(); // Clean up any extra output
?>
