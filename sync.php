<?php
//error_reporting(0);
include "dbconnect.php"; // Source database connection ($conn)

// Database connection to target database (Server 2)
$servername = "172.31.176.27";
$username = "hr";
$password = 'Xyga-K^4?9u6>"DZtkp*HL';
$database = "hr_employee";

// Initialize MySQL connection
$con = mysqli_init();
mysqli_options($con, MYSQLI_OPT_CONNECT_TIMEOUT, 100); // Set timeout

if (!mysqli_real_connect($con, $servername, $username, $password, $database)) {
    die("Connection failed: " . mysqli_connect_error());
}

// Reconnect if connection is lost
if (!mysqli_ping($con)) {
    mysqli_close($con);
    $con = mysqli_connect($servername, $username, $password, $database);
}


// Get the venue from the request and sanitize it
$venue = isset($_GET['venue']) ? $con->real_escape_string($_GET['venue']) : '';
$sql_fetch = "
    SELECT 
        `control_number`,
        `first_name`,
        `middle_name`,
        `last_name`,
        `extension_name`,
        `birth_day`,
        `birth_month`,
        `birth_year`,
        `province`,
        `barangay`,
        `city_municipality`,
        DATE(`date_last_served`) as `date_last_served`,
        `last_served_location`
    FROM 
        `aics_clean_list`
    WHERE 
        `status` IN ('Claimed', 'Validated');
";

$result = $conn->query($sql_fetch);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Prepare the insert query for target database
        $sql_insert = "
            INSERT INTO `aics_served_database` (
                `control_number`,
                `first_name`,
                `middle_name`,
                `last_name`,
                `extension_name`,
                `birth_day`,
                `birth_month`,
                `birth_year`,
                `province`,
                `barangay`,
                `city_municipality`,
                `date_last_served`,
                `last_served_location`
            ) VALUES (
                '" . $con->real_escape_string($row['control_number']) . "',
                '" . $con->real_escape_string($row['first_name']) . "',
                '" . $con->real_escape_string($row['middle_name']) . "',
                '" . $con->real_escape_string($row['last_name']) . "',
                '" . $con->real_escape_string($row['extension_name']) . "',
                '" . $con->real_escape_string($row['birth_day']) . "',
                '" . $con->real_escape_string($row['birth_month']) . "',
                '" . $con->real_escape_string($row['birth_year']) . "',
                '" . $con->real_escape_string($row['province']) . "',
                '" . $con->real_escape_string($row['barangay']) . "',
                '" . $con->real_escape_string($row['city_municipality']) . "',
                '" . $con->real_escape_string($row['date_last_served']) . "',
                '" . $venue . "'
            );
        ";

        if (!$con->query($sql_insert)) {
            echo "Error inserting record: " . $con->error;
        }
    }

    // Update the status in the source database (Server 1)
    $sql_update = "UPDATE aics_clean_list SET status = 'Synced' WHERE status IN ('Claimed')";
    if ($conn->query($sql_update)) {
        echo "Data synchronized successfully.";
    } else {
        echo "Error updating source database: " . $conn->error;
    }
} else {
    echo "No data found to synchronize.";
}
  echo "Data synchronized successfully.";
// Close both connections
$conn->close();
$con->close();
?>
