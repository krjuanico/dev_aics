<?php
session_start();
include "dbconnect.php";  // Database connection
include "php/db.php";  // Database connection
error_reporting(0);
$emm=$_SESSION['userid'];
$payroll_no = $_POST['payroll_no'];
$sdo_id="";
    // Fetch payroll details
    $sql = "SELECT * FROM tbl_payroll_list AS a 
			LEFT JOIN lib_fund_source AS d ON d.id=a.fund_source
			WHERE payroll_no = '$payroll_no'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $payroll = $result->fetch_assoc();
		$payroll_status = $payroll['payroll_status'];
		$sdo_id = $payroll['sdo'];
		$fundSource = $payroll['fs_name'];

		if($payroll_status=='Closed'){ ?>
		<div class="container">
			  <div class="row">
			  <?php if($user_type=='Team Leader'){ ?>
				  <!-- Re-open Payroll Button on the left -->
				<div class="col-md-6 text-left">
				  <button type='button' class='btn btn-primary' id='openPayrollBtn'>Re-open Payroll</button>
				</div>
			  <?php }?>
				<!-- Generate Payroll Button and Page Dropdown on the right -->
				<div class="col-md-6 text-right">
					<button type='button' class='btn btn-success' id='generatePayrollBtn1'>Generate Payroll</button></center>
				</div>
			  </div>
			</div>
	<?php		
    echo "<center><br><br><table width='90%' style='text-align:left;'>
            <thead>
                <tr>
                    <th width='20%'>Payroll No</th>
                    <th width='20%'>Program</th>
                    <th width='30%'>Province</th>
                    <th width='20%'>Date Created</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>".$payroll['payroll_no']."</td>
                    <td>".$payroll['program']."</td>
                    <td>".$payroll['province']."</td>
                    <td>".$payroll['date_created']."</td>
                </tr>
            </tbody>
          </table><br>
          <table width='90%' style='text-align:left;'>
            <thead>
                <tr>
                    <th width='20%'>Amount</th>
                    <th width='20%'>Barangay</th>
                    <th width='30%'>SDO</th>
                    <th width='20%'>Agency</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>".number_format($payroll['amount'],2)."</td>
                    <td>".$payroll['city_muni']."</td>
					<td></td>
                    <td>".$payroll['fs_name']."</td>
                </tr>
            </tbody>
          </table><br>";

        
        $sql_bene = "SELECT * FROM tbl_bene WHERE payroll_no = '$payroll_no'";
        $result_bene = $conn->query($sql_bene);
        
        if ($result_bene->num_rows > 0) {
            echo "<form id=''>";
            echo "<table width='100%' id='bene_list' class='table table-bordered table-striped'>";
            echo "<thead><tr><th>No</th><th>First Name</th><th>Last Name</th><th>Date of Birth</th></tr></thead>";
            echo "<tbody>";
            while ($bene = $result_bene->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $bene['no'] . "</td>";
                echo "<td>" . $bene['fname'] . "</td>";
                echo "<td>" . $bene['lname'] . "</td>";
                echo "<td>" . $bene['dob'] . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
            echo "</form>";
        } else {
            echo "<p>No beneficiaries found for this payroll.</p>";
        }
    } 
	else{ ?>
  <div class="row">

    <!-- Close Payroll Button on the left -->
    <div class="col-md-11 text-right">
						<button class="btn btn-primary" id="view_list">View List</button>
		<input hidden id="payrollNo" value="<?php echo $payroll_no;?>"></input>
<script>
$('#generate_rep').click(function() {
    var reportType = 'GIS'; // Get the selected report type
    var payrollNo = document.getElementById('payrollNo').value; // Get payroll number from input

    $.ajax({
        type: 'POST',
        url: 'set_report_session.php', // Unified session handler
        data: { report_type: reportType, payroll_no: payrollNo }, // Send data
        success: function() {
            // Redirect to the session-based report generator
            window.location.href = 'set_report_session.php';
        },
        error: function() {
            alert('Error generating report. Please try again.');
        }
    });
});
</script>
<script>
$('#view_list').click(function() {
    var payrollNo = document.getElementById('payrollNo').value; // Get payroll number from input
	window.location.href = 'validation.php?payroll_no='+payrollNo;
});
</script>

      <button style="display:none" type='button' class='btn btn-info' id='addDetails' onclick="confirmSaveDetails()">Add Details</button>
    </div>

    <!-- Generate Payroll Button and Page Dropdown on the right -->
    <div hidden class="col-md-6 text-right">
      <div class="form-inline">
        <button type='button' class='btn btn-success mr-2' id='generatePayrollBtn'>Generate Payroll</button>
        <select class='status-dropdown form-control' id='pageNumberSelect'>
          <option value=''>Select Page</option>
          <?php
            $sql2 = "SELECT id FROM tbl_bene WHERE payroll_no = '$payroll_no' AND status in ('Validated','Claimed') ORDER BY payroll_id ASC";
            $result2 = $conn->query($sql2);
            $totalBene = mysqli_num_rows($result2); // Get total number of beneficiaries
            $benePerPage = 10; // Number of beneficiaries per page
            $totalPages = ceil($totalBene / $benePerPage); // Calculate total pages              
            for ($i = 1; $i <= $totalPages; $i++) {
              echo "<option value='$i'>Page $i</option>";
            }
          ?>
        </select>
      </div>
    </div>
  </div>

  <!-- Dynamic Table for Payroll Details -->

    <center><br>
    <table width='50%' class='table table-striped'>
      <thead>
        <tr>
          <th hidden width='10%'>Payroll No</th>
          <th width='20%'>Title</th>
          <th width='10%'>Venue</th>
          <th width='10%'>Charging</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td hidden><input type='number' class='form-control' readonly value='<?php echo $payroll['payroll_no']; ?>' id='payroll_no'/></td>
          <td><input type='text' class='form-control' id='title' style='padding-right:20px' value='<?php echo $payroll['project_title']; ?>'/></td>
          <td><input type='text' class='form-control' id='title' style='padding-right:20px' value='<?php echo $payroll['city_muni']; ?>'/></td>
          <td><input type='text' class='form-control' id='title' style='padding-right:20px' value='<?php echo $payroll['program']; ?>'/></td>
        </tr>
      </tbody>
    </table>

    <table width='50%' class='table table-striped' style='text-align:left;'>
      <thead>
        <tr>
          <th width='25%'>Payout Date (From/To)</th>
          <th width='25%'>Amount</th>
          <th width='25%'>SDO</th>
          <th width='25%'>Fund Source</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type='text' class='form-control' id='date' value='<?php echo $payroll['dateFrom'].' to '.$payroll['dateTo']; ?>'/></td>
          <td>
			<?php
				$sqla = "SELECT sum(amount) as amount FROM tbl_bene WHERE payroll_no = '$payroll_no' and status<>NULL";
				$resulta = mysqli_query($conn, $sqla);
				if ($rowa = mysqli_fetch_assoc($resulta)) { ?>
					<input type='number' class='form-control' id='amountu' value='<?php echo number_format($rowa['amount'],2); ?>'/></td>
				<?php }
			?>
          <td>
		  <?php
				$sql1 = "SELECT * FROM tbl_employee WHERE empid = '$sdo_id'";
                  $result1 = mysqli_query($con, $sql1);
                  if ($row1 = mysqli_fetch_assoc($result1)) { ?>
                    <input type="text" class='form-control' value="<?php echo strtoupper($row1['lname'].', '.$row1['fname'].' '.$row1['mname']); ?>"></input>
                  <?php }
              ?>			  
          </td>
			<td>
                <input type="text" class='form-control' value="<?php echo strtoupper($fundSource); ?>"></input>
			</td>
        </tr>
      </tbody>
    </table>
    </center>
    
        <?php
	}
    } 

?><hr>
<div id="bene-list-container">
		  <!-- List of beneficiaries will be loaded here -->
		</div>
<script>
function confirmSaveDetails() {
  if (confirm("Are you sure you want to save the details?")) {
    saveDetails();
  }
}

function saveDetails() {
  var payroll_no = document.getElementById('payroll_no').value;
  var title = document.getElementById('title').value;
  var province = document.getElementById('province').value;
  var date = document.getElementById('date').value;
  var sdo = document.getElementById('sdou').value;
  var amount = document.getElementById('amountu').value;
  var fund_source = document.getElementById('fund_sourceu').value;
//alert(fund_source);
  $.ajax({
    type: 'POST',
    url: 'update_payroll_details.php',
    data: {
      payroll_no: payroll_no,
      title: title,
      province: province,
      date: date,
      amount: amount,
      sdo: sdo,
      fund_source: fund_source
    },
    success: function(response) {
		//alert(response);
      alert("Details saved successfully!");
    },
    error: function() {
      alert("Error saving details. Please try again.");
    }
  });
}
</script>
<script>
  $(document).ready(function() {
    $('#pageNumberSelect').on('change', function() {
      var pageNumber = $(this).val();
      var benePerPage = 10;
      var payrollNo = '<?php echo $payroll_no ?>';
      var totalPages = '<?php echo $totalPages ?>';
      var isLastPage = (pageNumber == totalPages) ? 1 : 0;
      
      $.ajax({
        type: 'POST',
        url: 'load_bene_list.php',
        data: {pageNumber: pageNumber, benePerPage: benePerPage, payrollNo: payrollNo, isLastPage: isLastPage},
        success: function(response) {
          $('#bene-list-container').html(response);
        }
      });
    });
  });
</script>
<script>
$(document).ready(function() {
	$('#bene_list').DataTable({
        "aLengthMenu": [[10, 20, 30, -1], [10, 20, 30, "All"]],
        "iDisplayLength": 10
    });
	$('#bene_list1').DataTable({
        "aLengthMenu": [[10, 20, 30, -1], [10, 20, 30, "All"]],
        "iDisplayLength": 10
    });
    // Handle update status button click
    $('.update-status-btn').click(function() {
        var beneId = $(this).data('bene-id');
        var selectedStatus = $('select[data-bene-id="' + beneId + '"]').val();

        // AJAX request to update the status in the database
        $.ajax({
            url: 'update_status.php',
            type: 'POST',
            data: { bene_id: beneId, status: selectedStatus },
            success: function(response) {
                alert('Status updated successfully!');
            },
            error: function() {
                alert('Error updating status. Please try again.');
            }
        });
    });
});
</script>
<script>
$(document).ready(function() {
    // Handle generate payroll button click
    $('#generatePayrollBtn1').click(function() {
        var payroll_no = '<?php echo $payroll_no; ?>';

        // AJAX request to generate payroll (adjust the URL to your own logic for generating payroll)
        $.ajax({
            url: 'payroll_form_closed.php',
            type: 'POST',
            data: { payroll_no: payroll_no },
            success: function(response) {
				var url = 'payroll_form_closed.php?payroll_no=' + payroll_no;
				window.open(url, '_blank', 'target=_blank');
                // You can also trigger download or other actions here
				// Reload the page after a short delay
                setTimeout(function() {
                    //location.reload();
                }, 1000); // Reload the page after 2 seconds
            },
            error: function() {
                alert('Error generating payroll. Please try again.');
            }
        });
    });
});
</script>
<script>
$(document).ready(function() {
	// Handle generate payroll button click
    $('#generatePayrollBtn').click(function() {
        var payroll_no = '<?php echo $payroll_no; ?>';
		var selectedPage = $('#pageNumberSelect').val();
		var isLastPage = selectedPage == '<?php echo $totalPages ?>' ? 1 : 0;
		//alert(isLastPage);
        // AJAX request to generate payroll (adjust the URL to your own logic for generating payroll)
        $.ajax({
            url: 'payroll_form.php',
            type: 'POST',
            data: { payroll_no: payroll_no,page_no:selectedPage, isLastPage: isLastPage },
            success: function(response) {
				var url = 'payroll_form.php?page_no=' + selectedPage + '&payroll_no=' + payroll_no + '&isLastPage=' + isLastPage;
				window.open(url, '_blank', 'target=_blank');
                // You can also trigger download or other actions here
				// Reload the page after a short delay
                setTimeout(function() {
                    location.reload();
                }, 1000); // Reload the page after 2 seconds
            },
            error: function() {
                alert(error);
            }
        });
    });
});
</script>
<script>
$(document).ready(function() {
    // Handle close payroll button click
    $('#closePayrollBtn, #openPayrollBtn').click(function() {
        var payroll_no = '<?php echo $payroll_no; ?>';
        var action = $(this).attr('id') === 'closePayrollBtn' ? 'close' : 'reopen';
        
        $('#passwordModal').modal('show');
    });

    // Modal password verification
    $('#passwordModal').on('shown.bs.modal', function() {
        $('#passwordInput').focus();
    });

    $('#passwordModal').on('hidden.bs.modal', function() {
        $('#passwordInput').val('');
    });

    // Verify password button click event
    $('#verifyPasswordBtn').click(function() {
        var payroll_no = '<?php echo $payroll_no; ?>';
        var action = $('#closePayrollBtn').is(':visible') ? 'close' : 'reopen';
        var password = $('#passwordInput').val();
        
        // AJAX request to verify password
        $.ajax({
            url: 'verify_password.php',
            type: 'POST',
            data: { payroll_no: payroll_no, password: password },
            success: function(response) {
                if (response === 'true') {
                    // Password is correct, update payroll status
                    $.ajax({
                        url: action === 'close' ? 'update_payroll.php' : 'update_payroll_reopen.php',
                        type: 'POST',
                        data: { payroll_no: payroll_no },
                        success: function(response) {
							//alert(payroll_no);
                            $('#passwordModal').modal('hide');
                            $('#payrollModal').modal('hide');
							console.log(response); 
							location.reload(); // Reload the page
                        },
                        error: function() {
                            alert('Error updating status. Please try again.');
                        }
                    });
                } else {
                    // Password is incorrect
                    alert('Invalid password. Please try again.');
                }
            },
            error: function() {
                alert('Error verifying password. Please try again.');
            }
        });
    });
});
</script>
