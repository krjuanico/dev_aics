<?php 
    header('Content-Type: text/html; charset=utf-8');
    session_start();
    include "dbconnect.php";
	error_reporting(0);
    ini_set('max_execution_time', 0); 
    error_reporting(0);
    $date_today = date("Y-m-d");
    $payroll_no = $_GET['payroll_no'];

    $search_service = "SELECT * FROM tbl_payroll_list WHERE payroll_no='$payroll_no'";
    $result = mysqli_query($conn, $search_service);

    if ($row = mysqli_fetch_assoc($result)) {
        $project_title = strtoupper($row["project_title"]);  
        $program = strtoupper($row["program"]); 
        $province = strtoupper($row["province"]); 
        $amount = $row["amount"]; 
    }

    // Get all barangays
    $sql2 = "SELECT barangay FROM tbl_bene WHERE payroll_no='$payroll_no' GROUP BY barangay;";
    $result2 = mysqli_query($conn, $sql2);
    $resultchecks2 = mysqli_num_rows($result2);

    $grandtotal = 0;
    $page_number = 0;
?>

<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="images/icons/dswd_icon.jpg">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>DSWD FO XI - Payroll</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

<body>
<div id="printableArea">
<style>
    .table01 {
        border-collapse: collapse;
        border: 1px solid black;
        font-size: 14px;
    }
    .td01, .th01 {
        font-size: 100%;
        border: 1px solid black;
    }
    .th01 {
        font-weight: bold;
    }
    @media print {
        .page-break { display: block; page-break-before: always; }
		 .no-print {
            visibility: hidden;
        }
    }

</style>

<?php
$ctr_page = 1;

while ($row2 = mysqli_fetch_assoc($result2)) {
    $barangay2 = strtoupper($row2["barangay"]); 

    // Count total beneficiaries in barangay
    $sqls1 = "SELECT COUNT(id) AS total_bene FROM tbl_bene WHERE payroll_no='$payroll_no' AND barangay='$barangay2'";
    $results1 = mysqli_query($conn, $sqls1);
    $resultchecks1 = mysqli_fetch_assoc($results1)['total_bene'];
	$resultchecks2 = mysqli_num_rows($result2);
    // Divide into pages, 10 records per page
    $total_pages = ceil($resultchecks1 / 10);
	
    $offset = 0;

    for ($current_page = 1; $current_page <= $total_pages; $current_page++) {
?>
        <div style="page-break-before: always">
            <table width="100%">
                <tr>
                    <th width="25%">&emsp;</th>
                    <th width="50%" align="center"><label>Republic of the Philippines</label></th>
                    <th width="25%" align="right"><label>Page <?php echo $page_number.' of '.$resultchecks2; ?></label></th>
                </tr>
                <tr>
                    <th width="25%">&emsp;</th>
                    <th width="50%" align="center"><label>DEPARTMENT OF SOCIAL WELFARE AND DEVELOPMENT</label></th>
                    <th width="25%" align="right">&emsp;</th>
                </tr>
                <tr>
                    <th width="25%">&emsp;</th>
                    <th width="50%" align="center"><label>Field Office XI, Davao City</label></th>
                    <th width="25%" align="right">&emsp;</th>
                </tr>
                <tr>
                    <th width="25%">&emsp;</th>
                    <th width="50%" align="center"><h3>CASH ASSISTANCE PAYROLL</h3></th>
                    <th width="25%" align="right">&emsp;</th>
                </tr>
            </table>

            <h5><?php echo $project_title; ?></h5>  
            <h5>BARANGAY: <?php echo $barangay2; ?></h5>
            <table class="table01" width="100%">
                <thead>
                    <tr class="th01">
                        <th width="5%">NO</th>
                        <th width="12%">LAST NAME</th>
                        <th width="12%">FIRST NAME</th>
                        <th width="29%">BARANGAY</th>
                        <th width="10%">AMOUNT</th>
                        <th width="20%">SIGNATURE</th>
                        <th width="10%">DATE PAID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch beneficiaries, 10 at a time (pagination per barangay)
                    $sql_bene = "SELECT * FROM tbl_bene WHERE payroll_no='$payroll_no' AND barangay='$barangay2' LIMIT 10 OFFSET $offset";
                    $result_bene = mysqli_query($conn, $sql_bene);
                    $subtotal = 0;
                    $counter = $offset + 1; // Adjust counter to display proper numbering

                    while ($row_bene = mysqli_fetch_assoc($result_bene)) {
                        $lname = strtoupper($row_bene['lname']);
                        $fname = strtoupper($row_bene['fname']);
                        $barangay = strtoupper($row_bene['barangay']);
                        $subtotal += $amount;
                    ?>
                        <tr class="td01">
                            <td class="td01" align="center"><?php echo $counter; ?></td>
                            <td class="td01" align="center"><?php echo $lname; ?></td>
                            <td class="td01" align="center"><?php echo $fname; ?></td>
                            <td class="td01" align="center"><?php echo $barangay; ?></td>
                            <td class="td01" align="center"><?php echo number_format($amount, 2); ?></td>
                            <td class="td01" align="left">&emsp;</td>
                            <td class="td01" align="left">&emsp;</td>
                        </tr>
                    <?php $counter++; } ?>
                    <tr>
                        <td colspan="4" align="right"><h3>Subtotal</h3></td>
                        <td align="center"><h3><?php echo number_format($subtotal, 2); ?></h3></td>
                        <td colspan="2">&emsp;</td>
                    </tr>
                </tbody>
            </table>
        </div>

<?php 
        // Update pagination variables
        $offset += 10;
        $page_number++;
        $grandtotal += $subtotal;
        // Check if it's the last page for this barangay
        if ($page_number == $resultchecks2) {
?>
            <div class="table-blue-fotter"> 
                <table width="100%">
                    <tbody>
                        <tr>
                            <td colspan="4" align="right"><h2>Total</h2></td>
                            <td align="right"><h2><?php echo number_format($grandtotal, 2); ?></h2></td>
                            <td colspan="2">&emsp;</td>
                        </tr>
                        <!-- Approval section -->
                        <tr>
                            <td colspan="2" align="center"><label>Recommending Approval:</label></td>
                            <td colspan="4" align="center"><label>Approved By:</label></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center"><b>_________<u>DAHLIA S. PADILLO</u>_________</b></td>
                            <td colspan="4" align="center"><b>_________<u>ATTY. VANESSA B. GOC-ONG</u>_________</b></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center"><b>ARD for Operations</b></td>
                            <td colspan="4" align="center"><b>Regional Director</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
<?php
        }
    }
}
?>
  

</div>
 <center>
       <button class="no-print" onClick="window.print()">Print Payroll</button>
   </center>
<script>
function homeDiv() {
    window.location.href = 'home.php';
}
</script>

</body>
</html>
