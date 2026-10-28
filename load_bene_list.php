<?php
include "dbconnect.php";  // Database connection
error_reporting(0);
  $pageNumber = $_POST['pageNumber'];
  $benePerPage = $_POST['benePerPage'];
  $payrollNo = $_POST['payrollNo'];

  $sql_bene = "SELECT * 
				FROM tbl_bene 
				WHERE payroll_no = '$payrollNo' 
				AND status in ('Claimed','Validated')
				ORDER BY payroll_id ASC 
				LIMIT " . (($pageNumber - 1) * $benePerPage) . ", $benePerPage";
  $result_bene = $conn->query($sql_bene);

  if ($result_bene->num_rows > 0) {
    echo "<br><table width='100%' id='bene_list1' class='table table-bordered table-striped'>";
    echo "<thead><tr><th>No</th><th>First Name</th><th>Last Name</th><th>Date of Birth</th></tr></thead>";
    echo "<tbody>";
    while ($bene = $result_bene->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $bene['payroll_id'] . "</td>";
      echo "<td>" . $bene['fname'] . "</td>";
      echo "<td>" . $bene['lname'] . "</td>";
      echo "<td>" . $bene['dob'] . "</td>";
      echo "</tr>";
    }
    echo "</tbody></table>";
  } else {
    echo "<p>No beneficiaries found for this payroll.</p>";
  }
?>
<script>
$(document).ready(function() {
	$('#bene_list1').DataTable({
        "aLengthMenu": [[10, 20, 30, -1], [10, 20, 30, "All"]],
        "iDisplayLength": 10
    });
});
</script>