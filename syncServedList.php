<?php
$servername = "172.31.176.27";
$username = "hr";
$password = 'Xyga-K^4?9u6>"DZtkp*HL';
$database ="employee";
/*
$servername = "localhost";
$username = "dev";
$password = "qnLvYgDC$!XRThb3#eJyNE";
$database ="dev_disaster_db";*/
// Create connection
$conn = mysqli_connect($servername, $username, $password,$database);
mysqli_set_charset($conn, "utf8");


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

} 


    // SQL query to sync data
    $sql = "
        INSERT INTO `aics_served_database` (
            `first_name`,
            `middle_name`,
            `last_name`,
            `extension_name`,
            `birth_day`,
            `birth_month`,
            `birth_year`,
            `province`,
            `city_municipality`,
            `date_last_served`,
            `last_served_location`
        )
       SELECT * 
        FROM aics_served_database
        WHERE DATEDIFF(CURDATE(), STR_TO_DATE(`date_last_served`, '%m/%d/%Y')) < 91 limit 10;
    ";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
		$sql_update = "UPDATE aics_clean_list SET  status = 'Synced' WHERE status = 'Claimed' ";
		$stmt_update = mysqli_query($conn, $sql_update);
		
        echo "Data synchronized successfully.";
    } else {
        echo "Error: " . $conn->error;
    }

?>
