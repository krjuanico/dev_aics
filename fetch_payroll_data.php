<?php
include "dbconnect.php"; // Database connection

header('Content-Type: application/json');

$sql = "SELECT * 
        FROM aics_served_database
        WHERE DATEDIFF(CURDATE(), STR_TO_DATE(`date_last_served`, '%m/%d/%Y')) < 91 limit 10";

$result = $conn->query($sql);
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['first_name'] = str_replace(['Ã', 'Ã±'], 'Ñ', $row['first_name']);
        $row['middle_name'] = str_replace('Ã', 'Ñ', $row['middle_name']);
        $row['last_name'] = str_replace(['Ã', 'Ã±'], 'Ñ', $row['last_name']);
        $row['extension_name'] = str_replace(['Ã', 'Ã±'], 'Ñ', $row['extension_name']);
        $row['full_name'] = $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name'];
        $row['dob'] = $row['birth_day'] . '/' . $row['birth_month'] . '/' . $row['birth_year'];
        $data[] = $row;
    }
}

echo json_encode($data);
?>
