<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $reportType = $_POST['report_type'] ?? null;
    $pageNumber = $_POST['pageNumber'] ?? null;
    $isLastPage = $_POST['isLastPage'] ?? null;

    if (!$reportType) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
        exit();
    }

    // Store data in the session
    $_SESSION['report_type'] = $reportType;
    $_SESSION['pageNumber'] = $pageNumber;
    $_SESSION['isLastPage'] = $isLastPage;

    echo json_encode(['status' => 'success']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ensure session data exists
    if (!isset($_SESSION['report_type'])) {
        echo "Invalid session data. Please try again.";
        exit();
    }

    $reportType = $_SESSION['report_type'];

    // Redirect based on report type
	if ($reportType === 'GIS') {
		echo "<script>window.open('print/pdfcopy.php', '_blank');</script>";
	} elseif ($reportType === 'COE') {
		echo "<script>window.open('print/coe.php', '_blank');</script>";
	} else {
		echo "Invalid report type.";
	}
echo "<script> history.back();</script>";


    // Clear session data after redirection
    exit();
}
?>
