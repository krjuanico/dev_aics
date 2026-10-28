<?php
//error_reporting(0);
include "dbconnect.php"; // Database connection

if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == 0) {
    $fileTmpPath = $_FILES['csvFile']['tmp_name'];
    $fileName = $_FILES['csvFile']['name'];

    // Check if the file already exists in tbl_import_log
    $checkQuery = "SELECT * FROM tbl_import_log WHERE file_name = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $fileName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "file_exists"; // Notify JavaScript
    } else {
        // Process the CSV file
        if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
            fgetcsv($handle); // Skip header row (if exists)

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $col0 = $conn->real_escape_string($data[0]);
                $col1 = $conn->real_escape_string($data[1]);
                $col2 = $conn->real_escape_string($data[2]);
                $col3 = $conn->real_escape_string($data[3]);
                $col4 = $conn->real_escape_string($data[4]);
                $col5 = $conn->real_escape_string($data[5]);
                $col6 = $conn->real_escape_string($data[6]);
                $col7 = $conn->real_escape_string($data[7]);
                $col8 = $conn->real_escape_string($data[8]);
                $col9 = $conn->real_escape_string($data[9]);
                $col10 = $conn->real_escape_string($data[10]);
				$lastName = mb_convert_encoding(str_replace(['Ã', 'Ã±'], 'Ñ', $col1), "UTF-8", "auto");
				$firstName = mb_convert_encoding(str_replace(['Ã', 'Ã±'], 'Ñ', $col2), "UTF-8", "auto");
				$midName = mb_convert_encoding(str_replace(['Ã', 'Ã±'], 'Ñ', $col3), "UTF-8", "auto");


                $sql = "INSERT INTO aics_served_database (control_number,last_name, first_name, middle_name, extension_name, birth_day, birth_month, birth_year, province, city_municipality, date_last_served) 
                        VALUES ('$col0','$lastName', '$firstName', '$midName', '$col4', '$col5', '$col6', '$col7', '$col8', '$col9', '$col10')";
                $conn->query($sql);
            }

            fclose($handle);

            // Log the uploaded file in tbl_import_log
            $logQuery = "INSERT INTO tbl_import_log (file_name, import_date) VALUES (?, NOW())";
            $stmt = $conn->prepare($logQuery);
            $stmt->bind_param("s", $fileName);
            $stmt->execute();

            echo "success"; // Notify JavaScript
        } else {
            echo "Error opening file.";
        }
    }

    $stmt->close();
} else {
    echo "Error uploading file.";
}

$conn->close();
?>
