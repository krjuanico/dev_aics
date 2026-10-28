<!doctype html>
<?php

    header('Content-Type: text/html; charset=UTF-8');
session_start();
error_reporting(0);
include "php/db.php";
include "dbconnect.php";
	$date_val = date("Y-m-d");	
	$date_yesterday = date("Y-m-d",strtotime("yesterday"));
	$emm=$_SESSION['userid'];
	$user_type = $_SESSION['user_type']; // get the user type from the database or session
	if($emm==''||$emm==NULL){
		echo "<script>window.open('index.php','_self')</script>";
	}else{
		
	$sql = "SELECT  distinct e.empfname,e.empmname,e.emplname,e.empext,a.empid
			FROM tbl_employment a 
			inner join employee_info e using (empnum)
			where a.empid= '$emm'";
	$result = $con->query($sql);
	$row = $result->fetch_assoc();
	
	$payroll_no= $_GET['payroll_no'];
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8" />
	<link rel="icon" type="image/png" href="images/icons/dswd_icon.jpg">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>DSWD FO XI - ECT Payroll System</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>
	
    <link href='css/googleapis.css' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
	
	  <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

	<!-- Include jQuery first -->
	<script src="js/jquery-3.6.0.min.js"></script>

	<!-- Include Bootstrap JS only once -->
	<script src="js/bootstrap.bundle.min.js"></script>

	<!-- DataTables JS -->
	<script type="text/javascript" charset="utf8" src="js/jquery.dataTables.min.js"></script>

	<script src="js/jquery.js"></script>
	<style>
		body {
		  font-size: 16px;
		}
		.modal {
		  z-index: 1060; /* increase the z-index to be higher than the page's content */
		}
		/* Custom CSS to force modal to be extra large */
		.modal-xl {
			max-width: 70%;  /* Adjust the width as per your requirement */
			width: 70%;      /* Set width to 90% of the screen */
		}
		tr.success {
			background-color: #dff0d8; /* green */
		}

		tr.danger {
			background-color: #f2dede; /* red */
		}
	</style>
	
</head>
<body>

<div class="wrapper">
   <div class="sidebar" data-color="blue" data-image="assets/img/sidebar-6.jpg">
    <!--   you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple" -->
	<div class="sidebar-wrapper">
             <div class="logo">
			
                <a href="#" class="simple-text">
                      <img src="images/icons/dswd_logo_white_2.png" style="width:82%; height:82%; margin-left:-3%" alt="..."/>
                    </a>
            </div>
			
			<ul class="nav">
				
				<?php if($user_type=='Team Leader'){ ?>
				<li>
					<a href="rdv.php?userid=<?php echo $emm;?>"><i class="pe-7s-file"></i><span><b>RDV</span></b> </a>
				</li>
				<li>
					<a href="validation.php?userid=<?php echo $emm;?>"><i class="pe-7s-folder"></i><span><b>Masterlist</span></b> </a>
				</li>
				<li class="active">
					<a href="servedBenes.php?userid=<?php echo $emm;?>"><i class="pe-7s-folder"></i><span><b>Served List</span></b> </a>
				</li>
				<li>
					<a href="tagger.php?userid=<?php echo $emm;?>"><i class="pe-7s-folder"></i><span><b>Tagger</span></b> </a>
				</li>
				<li>
					<a href="disqualified.php?userid=<?php echo $emm;?>"><i class="pe-7s-folder"></i><span><b>Disqualified</span></b> </a>
				</li>
				<li>
					<a href="correction.php?userid=<?php echo $emm;?>"><i class="pe-7s-folder"></i><span><b>Corrections</span></b> </a>
				</li>
				<?php }?>
			</ul>
    </div>
</div>


		<!-- Modal -->
			<div id="payrollModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="payrollModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="payrollModalLabel">Client Details</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div id="payroll-details"></div>
						</div>
					</div>
				</div>
			</div>
		
			<!-- Modal -->
			<div id="successModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title" id="successModalLabel">Success</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div class="modal-body">
					<p id="successMessage"></p>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				  </div>
				</div>
			  </div>
			</div>
			<!--<div class="modal fade" id="assignNewSDO" tabindex="-1" role="dialog" aria-labelledby="assignNewSDO" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="assignNewSDO">Assign New SDO</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <label for="new_sdo_id">Select New SDO:</label>
                                        <select class="form-control" id="new_sdo_id" name="new_sdo_id">
                                            <?php
												$sql3 = "SELECT  distinct e.empfname,e.empmname,e.emplname,e.empext,a.empid
														FROM tbl_employment a 
														inner join employee_info e using (empnum)
														where a.empid= '$emm' order by empid asc";
												$result3 = mysqli_query($con, $sql3);
												while ($row3 = mysqli_fetch_assoc($result3)) { ?>
													<option value="<?php echo $row3['empid']; ?>" ><?php echo $row3['emplname'].', '.$row3['empfname'].' '.$row3['empmname']; ?></option>
											<?php }?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="assignNewSDOBtn">Assign New SDO</button>
                            </div>
                        </div>
                    </div>
                </div>-->
    <div class="main-panel">
		<nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><?php echo strtoupper($row["emplname"]).", ".strtoupper($row["empfname"])." ".strtoupper($row["empmname"]); ?> </a>
                </div>
                <div class="collapse navbar-collapse">
                     <ul class="nav navbar-nav navbar-right">
                        
                        <li>
                            <a href="logout.php">
                                <p>Log out</p>
                            </a>
                        </li>
						<li class="separator hidden-lg hidden-md"></li>
                    </ul>
                </div>
            </div>
        </nav>
		<div class="content" style="width:100%">
		<input hidden id="payrollNo" value="<?php echo $payroll_no;?>"></input>
			<div class="content" style="width:99%">
				<div class="row" > <div class="form-inline">
					<div class="col-md-12" style="float: right;">
						<button class="btn btn-primary" id="generate_rep">Export File</button>
						 <select class='status-dropdown form-control' id='pageNumberSelect'>
						  <option value=''>Select Page</option>
						  <?php
							$sql2 = "SELECT id FROM aics_clean_list";
							$result2 = $conn->query($sql2);
							$totalBene = mysqli_num_rows($result2); // Get total number of beneficiaries
							$benePerPage = 50; // Number of beneficiaries per page
							$totalPages = ceil($totalBene / $benePerPage); // Calculate total pages              
							for ($i = 1; $i <= $totalPages; $i++) {
							  echo "<option value='$i'>Page $i</option>";
							}
						  ?>
						</select>&emsp;&emsp;
						<button type="button" class="btn btn-primary" id="syncDataButton">Synced Served List (Online) </button>
						<div class="mt-3"><br>
							<div class="progress" style="height: 20px; display: none;" id="progressContainer">
								<div 
									class="progress-bar progress-bar-striped progress-bar-animated" 
									role="progressbar" 
									style="width: 0%;" 
									id="progressBar">
								</div>
							</div>
						</div>
						<div id="syncResult" class="mt-3"></div>
					</div>
					
					</div>
				</div>
				<hr>
			</div>
			
				
        <div class="table-responsive">  
           <table id="payrollTable" style="width:100%" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No.</th>
            <th hidden>Control No.</th>
            <th>Control No.</th>
            <th>Beneficiary Name</th>
            <th>Date of Birth</th>
            <th>Barangay</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="payrollTableBody">
        <!-- Rows will be populated dynamically -->
    </tbody>
</table>
        </div>  
		
		</div>
	</div>
</div>
</body>


    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
	
    <!--   Core JS Files   -->
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>

</html>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('payrollTableBody');

    // Fetch data from the API
    fetch('fetch_payroll_data.php')
        .then(response => response.json())
        .then(data => {
            tableBody.innerHTML = ''; // Clear table body
            if (data.length > 0) {
                let ctr = 1;
                data.forEach(row => {
                    const newRow = `
                        <tr>
                            <td>${ctr}</td>
                            <td hidden>${row.id}</td>
                            <td>${row.control_number || ''}</td>
                            <td>${row.full_name.toUpperCase()}</td>
                            <td>${row.dob}</td>
                            <td>${row.city_municipality || ''} ${row.barangay || ''}</td>
                            <td align="center">
                                <button class="btn btn-primary view-payroll" data-payroll-no="${row.control_number}" data-backdrop="false">View</button>
                            </td>
                        </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', newRow);
                    ctr++;
                });
            } else {
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center">No payroll records found</td></tr>`;
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center">Error loading payroll records</td></tr>`;
        });
});

</script>

<script>
        document.getElementById("syncDataButton").onclick = function () {
            // Display the progress bar
            const progressBar = document.getElementById('progressBar');
            const progressContainer = document.getElementById('progressContainer');
            progressContainer.style.display = 'block';
            progressBar.style.width = '0%';

            // Start updating progress
            let progress = 0;

            // Simulate progress every 100ms (for demo purposes)
            const interval = setInterval(() => {
                progress += 10; // Increment progress
                progressBar.style.width = progress + '%';
                progressBar.innerText = progress + '%';

                if (progress >= 100) {
                    clearInterval(interval); // Stop the interval when progress reaches 100%
                }
            }, 100);

            // Use AJAX to call sync.php
            fetch('syncServedList.php', {
                method: 'POST'
            })
            .then(response => response.text())
            .then(data => {
                // Stop progress bar at 100%
                clearInterval(interval);
                progressBar.style.width = '100%';
                progressBar.innerText = '100%';

                // Display the response
                document.getElementById('syncResult').innerHTML = `
                    <div class="alert alert-success">${data}</div>
                `;

                // Hide the progress bar after 2 seconds
                setTimeout(() => {
                    progressContainer.style.display = 'none';
                }, 2000);
            })
            .catch(error => {
                clearInterval(interval);
                progressBar.style.width = '100%';
                progressBar.classList.add('bg-danger');
                progressBar.innerText = 'Error';

                // Display the error message
                document.getElementById('syncResult').innerHTML = `
                    <div class="alert alert-danger">Error: ${error}</div>
                `;
            });
        };
    </script>
<script>
$('#exportfile').click(function() {
    var reportType = $('#export_rep').val();
    var payrollNo = $('#payroll_no_tl').val();
    $.ajax({
        type: 'POST',
        url: 'generate_report.php',
        data: { report_type: reportType},
        success: function(response) {
            var blob = new Blob([response], { type: 'text/csv' });
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'report_' + new Date().getTime() + '.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        },
        error: function() {
            alert('Error generating report. Please try again.');
        }
    });
});
</script>
<script>
$('#generate_rep').click(function() {
    var reportType = 'GIS' ;//$('#export_rep').val(); // Get the selected report type
	const selectElement = document.getElementById("pageNumberSelect");
	const pageNumber = selectElement.value; // Gets the value of the selected option
    var totalPages = '<?php echo $totalPages ?>';
    var isLastPage = (pageNumber == totalPages) ? 1 : 0;
    $.ajax({
        type: 'POST',
        url: 'set_report_session.php', // Unified session handler
        data: { report_type: reportType,pageNumber:pageNumber,isLastPage:isLastPage }, // Send data
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

$(document).ready(function() {
    // Event delegation: Attach the event handler to the document, but delegate the event to elements with the 'view-payroll' class
    $(document).on('click', '.view-payroll', function() {
        var bene_id = $(this).data('payroll-no');

        // Log the payroll number for debugging
        console.log('Beneficiary ID: ' + bene_id);

        // AJAX request to fetch payroll details
        $.ajax({
            url: 'fetch_bene_details.php',  // PHP file to fetch details
            type: 'POST',
            data: { bene_id: bene_id },
            success: function(response) {
                console.log(response); // Log the response to ensure data is fetched
                $('#payroll-details').html(response);
                $('#payrollModal').modal('show'); // Show the modal
            },
            error: function() {
                alert("Failed to fetch bene details.");
            }
        });
    });

    // Rebind the click event after the modal is closed (optional if you want to reset modal content)
    $('#payrollModal').on('hidden.bs.modal', function () {
        $('#payroll-details').html(''); // Clear modal content when hidden
    });
});
    // Initialize DataTable
    $('#payrollTable').DataTable({
        "aLengthMenu": [[10, 20, 30, -1], [10, 20, 30, "All"]],
        "iDisplayLength": 10
    });

</script>
	<?php }?>