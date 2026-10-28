<!doctype html>
<?php
	header('Content-Type: text/html; charset=UTF-8');
	session_start();
	error_reporting(0);
	include "dbconnect.php";
	$date_val = date("Y-m-d");	
	$date_yesterday = date("Y-m-d",strtotime("yesterday"));
	$emm=$_SESSION['userid'];
	$user_type = $_SESSION['user_type']; // get the user type from the database or session
	if($emm==''||$emm==NULL){
		echo "<script>window.open('index.php','_self')</script>";
	}else{
        // ✅ Get charging from first unexported beneficiary
        $sql2 = "SELECT charging FROM aics_clean_list WHERE exportStatus IS NULL LIMIT 1";
        $result2 = mysqli_query($conn, $sql2);	
        if ($row2 = mysqli_fetch_assoc($result2)) {
            $charging = strtoupper($row2['charging']);
        }
    
        // ✅ Get payroll_no of the logged-in user (from session)
        $empid = $_SESSION['userid'] ?? ''; 
    
        if (!empty($empid)) {
            $sql3 = "SELECT payroll_no FROM lib_users WHERE empid = '$empid' LIMIT 1";
            $result3 = mysqli_query($conn, $sql3);
    
            if ($row3 = mysqli_fetch_assoc($result3)) {
                $payrollNo = $row3['payroll_no'];
            } else {
                $payrollNo = null; // no record found
            }
        } else {
            $payrollNo = null; // no session user
        }

?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8" />
	<link rel="icon" type="image/png" href="images/icons/dswd_icon.jpg">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>DSWD FO XI - AICSys</title>

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
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
				<?php if ($user_type == 'RDV' || $user_type == 'Team Leader') { ?>
					<li>
						<a href="rdv.php?userid=<?php echo $emm; ?>">
							<i class="pe-7s-file"></i><span><b>Import File</b></span>
						</a>
					</li>
					<li>
						<a href="disqualified.php?userid=<?php echo $emm; ?>">
							<i class="pe-7s-folder"></i><span><b>Disqualified</b></span>
						</a>
					</li>
					<li>
						<a href="correction.php?userid=<?php echo $emm; ?>">
							<i class="pe-7s-folder"></i><span><b>Corrections</b></span>
						</a>
					</li>
					<li>
						<a href="user.php?userid=<?php echo $emm; ?>">
							<i class="pe-7s-user"></i><span><b>Users</b></span>
						</a>
					</li>
				<?php } ?>

				<?php if ($user_type == 'GO' || $user_type == 'Team Leader') { ?>
					<li class="active">
						<a href="validation.php?userid=<?php echo $emm; ?>">
							<i class="pe-7s-folder"></i><span><b>Masterlist</b></span>
						</a>
					</li>
				<?php } ?>

				<?php if ($user_type == 'Tagger' || $user_type == 'Team Leader') { ?>
					<li>
						<a href="tagger.php?userid=<?php echo $emm; ?>">
							<i class="pe-7s-folder"></i><span><b>Tagger</b></span>
						</a>
					</li>
				<?php } ?>
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
                    <a class="navbar-brand" href="#"><?php echo strtoupper($user_type); ?> </a>
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
					<?php if ($user_type == 'RDV' || $user_type == 'Team Leader') { ?>
							<button type="button" class="btn btn-primary" id="generate_rep">
							<i class="fas fa-file-download"></i>&nbsp;Download File
						    </button>
						 <select class='status-dropdown form-control' id='pageNumberSelect'>
						  <option value=''>Select Page</option>
						  <?php
							$sql2 = "SELECT id FROM aics_clean_list WHERE 
								(status IS NULL OR status = 'Correction') 
								AND exportStatus IS NULL";
							$result2 = $conn->query($sql2);
							$totalBene = mysqli_num_rows($result2); // Get total number of beneficiaries
							$benePerPage = 50; // Number of beneficiaries per page
							$totalPages = ceil($totalBene / $benePerPage); // Calculate total pages              
							for ($i = 1; $i <= $totalPages; $i++) {
							  echo "<option value='$i'>Page $i</option>";
							}
						  ?>
						</select>&emsp;&emsp;
						<button style="display:none" type="button" class="btn btn-info" id="exportDataButton">&nbsp;Export Served Benes</button>
						<script>
							document.getElementById("exportDataButton").addEventListener("click", function() {
								// Redirect to the PHP export script to download CSV
								window.open("exportservedbenes.php", "_blank");
							});
						</script>
						<button style="display:none" type="button" class="btn btn-warning" id="exportNoShowButton">Export No Show Benes</button>
						<script>
							document.getElementById("exportNoShowButton").addEventListener("click", function() {
								// Redirect to the PHP export script to download CSV
								window.open("exportnoshowbenes.php", "_blank");
							});
						</script> 
						<button type="button" class="btn btn-primary" id="syncDataButton1">
							<i class="fas fa-file-export"></i> Export All Data
						</button><?php }?>

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
		<!-- Progress Bar Container -->
		<div id="progressContainer" style="display: none; margin-top: 20px;">
		  <div class="progress">
			<div
			  id="progressBar"
			  class="progress-bar progress-bar-striped progress-bar-animated"
			  role="progressbar"
			  style="width: 0%;"
			>
			  Loading...
			</div>
		  </div>
		</div>
        <table id="payrollTable" class="table table-bordered table-striped" style="width:100%">
      <thead>
          <tr>
              <th>No.</th>
              <th>Control No.</th>
              <th>Beneficiary Name</th>
              <th>Date of Birth</th>
              <th>Barangay</th>
              <th>Status</th>
              <th>Actions</th>
          </tr>
      </thead>
  </table>
        </div>  
		
		</div>
	</div>
	<!-- Modal -->
<div class="modal fade" id="confirmSyncModal" tabindex="-1" aria-labelledby="confirmSyncModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmSyncModalLabel" style="color:red">Reminders before Export</h5>
            </div>
            <div class="modal-body">
						<div class="mt-3">
						  <ol>
							<li>The data will be deleted after export.</li>
							<li>Upon successful export, an Excel file will be generated.</li>
							<li>You can only export the file once.</li>
							<li>An XLSX file will be created and generated.</li>
						  </ol>
						</div>
            </div>
			
            <div class="modal-footer">
				<input type="text" class="form-control" id="venue" placeholder="Add Venue" /><br>
				<button type="button" class="btn btn-info" id="exportClaimedBenes">Proceed Exporting</button>
			</div>

			<script>
			document.getElementById("exportClaimedBenes").addEventListener("click", function() {
				// Get venue input value
				let venueValue = document.getElementById("venue").value;
				$('#confirmSyncModal').modal('hide'); // Hide the modal
				// Redirect to export script with venue as a query parameter
				window.open("exportClaimedBenes.php?venue=" + venueValue, "_blank");
			});
			</script>

        </div>
    </div>
</div>
</div>
</body>


    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css">

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
$(document).ready(function() {
    let table = $('#payrollTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "fetch_beneficiaries.php",
            "type": "POST"
        }
    });

    // View modal
    $(document).on('click', '.view-payroll', function() {
        var bene_id = $(this).data('payroll-no');
        $.post("fetch_bene_details.php", {bene_id: bene_id}, function(response){
            $('#payroll-details').html(response);
            $('#payrollModal').modal('show');
        });
    });
});
</script>
<script>
 document.getElementById("syncDataButton1").onclick = function () {
 $('#confirmSyncModal').modal('show'); // Show the modal
 }
 </script>
 <script>document.getElementById("syncDataButton").onclick = function () {
    $('#confirmSyncModal').modal('hide'); // Hide the modal

    // Get venue input
    const venue = document.getElementById('venue').value.trim();

    // Validate venue input
    if (!venue) {
        alert("Please enter a venue before syncing.");
        return;
    }

    // Display the progress bar
    const progressBar = document.getElementById('progressBar');
    const progressContainer = document.getElementById('progressContainer');
    
    progressContainer.style.display = 'block';
    progressBar.style.width = '0%';
    progressBar.innerText = '0%';

    // Start updating progress
    let progress = 0;
    const interval = setInterval(() => {
        progress += 10;
        progressBar.style.width = progress + '%';
        progressBar.innerText = progress + '%';

        if (progress >= 100) {
            clearInterval(interval);
        }
    }, 100);

    // Use AJAX to call sync.php and send the venue as a parameter
    fetch(`sync.php?venue=${encodeURIComponent(venue)}`, {
        method: 'POST'
    })
    .then(response => response.text())
    .then(data => {
        clearInterval(interval);
        progressBar.style.width = '100%';
        progressBar.innerText = '100%';

	alert(data);
        document.getElementById('syncResult').innerHTML = `
            <div class="alert alert-success">${data}</div>
        `;

        setTimeout(() => {
            progressContainer.style.display = 'none';
        }, 2000);
    })
    .catch(error => {
		
        clearInterval(interval);
        progressBar.style.width = '100%';
        progressBar.classList.add('bg-danger');
        progressBar.innerText = 'Error';

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
$('#generate_rep1').click(function() {
    var reportType = 'GIS' ;//$('#export_rep').val(); // Get the selected report type
    const selectElement = document.getElementById("pageNumberSelect");
    const pageNumber = selectElement.value; // Gets the value of the selected option
    var totalPages = '<?php echo $totalPages ?>';
    var isLastPage = (pageNumber == totalPages) ? 1 : 0;
    $.ajax({
        type: 'POST',
        url: 'set_report_session1.php', // Unified session handler
        data: { report_type: reportType,pageNumber:pageNumber,isLastPage:isLastPage }, // Send data
        success: function() {
            // Redirect to the session-based report generator
            window.location.href = 'set_report_session1.php';
        },
        error: function() {
            alert('Error generating report. Please try again.');
        }
    });
});
</script>


<!-- <script>

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

</script> -->
	<?php }?>