<!doctype html>
<?php
session_start();
error_reporting(0);
    include "dbconnect.php";
	
	$emm=$_SESSION['userid'];
	$user_type = $_SESSION['user_type']; // get the user type from the database or session
	if($emm==''||$emm==NULL){
		echo "<script>window.open('index.php','_self')</script>";
	}else{
		
	
	$sql1= "select empnum from tbl_employment where empid='$emm'";
	$result1 = $con->query($sql1);
	$row1 = $result1->fetch_assoc();
	$id1=$row1['empnum'];
	$sql = "SELECT  distinct e.empfname,e.empmname,e.emplname,e.empext,a.empid
			FROM tbl_employment a 
			inner join employee_info e using (empnum)
			where a.empid= '$emm'";
	$result = $con->query($sql);
	$row = $result->fetch_assoc();
	
	$sql2 = "SELECT team_leader from lib_users where empid='$emm'";
	$result2 = $conn->query($sql2);
	$row2 = $result2->fetch_assoc();
	$team_leader=$row2['team_leader'];
	


// SQL query to fetch payroll list from the database
$sql1 = "SELECT *
        FROM tbl_payroll_list as a
		LEFT JOIN lib_sdo as c on sdo=c.sdo_id
		LEFT JOIN lib_fund_source as d on fund_source=d.id
		";  // Adjust the table name to your actual payroll table
$result1 = $conn->query($sql1);
?>
<html lang="en">
<head>
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
		#pageNumberSelect {
		  display: inline-block;
		}
		#pageNumberSelect {
		  float: right;
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
					<a href="export_excel.php?userid=<?php echo $emm;?>"><i class="pe-7s-file"></i><span><b>Import CSV File</span></b> </a>
				</li>
				<li>
					<a href="validation.php?userid=<?php echo $emm;?>"><i class="pe-7s-folder"></i><span><b>Masterlist</span></b> </a>
				</li>
				<li class="active">
					<a href="payroll_list.php?userid=<?php echo $emm;?>"><i class="pe-7s-folder"></i><span><b>List of Payroll</span></b> </a>
				</li>
				<?php }?>
			</ul>
    </div>

</div>

	<!-- Modal -->
	<div class='modal fade' id='addDetailsModal' tabindex='-1' role='dialog' aria-labelledby='addDetailsModalLabel' aria-hidden='true'>
	  <div class='modal-dialog' role='document'>
		<div class='modal-content'>
		  <div class='modal-header'>
			<h5 class='modal-title' id='addDetailsModalLabel'>Add Details</h5>
			<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
			  <span aria-hidden='true'>&times;</span>
			</button>
		  </div>
		  <div class='modal-body'>
			<table class="table table-bordered" style="width:80%">		
			  <tr>  
				<td width="30%"><label  style="font-size: 20px;font-family:Arial Narrow;">SDO No.</label></td>  
				<td width="70%" >
				  <select class="form-control selectpicker" name="sdo" id="sdo" hidden>
					<option disabled selected>----------</option>
					<?php
					  $sqls = "SELECT * FROM lib_sdo WHERE sdo_status = 'Active'";
					  $results = mysqli_query($conn, $sqls);

					  while ($rows = mysqli_fetch_assoc($results)) {
						$sdo_empid = $rows['sdo_id'];
						$sql1s = "SELECT  distinct e.empfname,e.empmname,e.emplname,e.empext,a.empid
									FROM tbl_employment a 
									inner join employee_info e using (empnum)
									where a.empid= '$sdo_empid'";
						$result1s = mysqli_query($con, $sql1s);
						if ($row1s = mysqli_fetch_assoc($result1s)) {
						  echo '<option value="' . $sdo_empid . '">' . strtoupper($row1s['emplname'].', '.$row1s['empfname'].' '.$row1s['empmname']). '</option>';
						}
					  }
					?>
				  </select>
				</td>  
			  </tr>				
			  <tr>  
				<td width="30%"><label  style="font-size: 20px;font-family:Arial Narrow;">Project Name</label></td>  
				<td width="70%" ><textarea  name="project_name"  class="form-control" placeholder="" required> </textarea> </td> 
			  </tr> 	
			  <tr>  
				<td width="30%"><label  style="font-size: 20px;font-family:Arial Narrow;">Fund Source</label></td>  
				<td width="70%" >
				  <select class="form-control selectpicker" name="fund_source" id="fund_source" hidden>
					<option disabled selected>----------</option>
					<?php
					  $sqlf = "SELECT * FROM lib_fund_source WHERE fs_status = 'Active'";
					  $resultf = mysqli_query($conn, $sqlf);

					  while ($rowf = mysqli_fetch_assoc($resultf)) {
						echo '<option value="' . $rowf['id'] . '">' . $rowf['fs_name'] . '</option>';
					  }
					?>
				  </select>
				</td>  
			  </tr> 
			
			  <tr>  
				<td width="30%">
				  <label  style="font-size: 20px;font-family:Arial Narrow;">Program</label>
				</td>  
				<td width="70%">
				  <select class="form-control selectpicker" name="program_title" id="program_title" hidden>
					<option disabled selected>----------</option>
					<?php
					  $sqlp = "SELECT * FROM lib_program WHERE prog_status = 1";
					  $resultp = mysqli_query($conn, $sqlp);

					  while ($rowp = mysqli_fetch_assoc($resultp)) {
						echo '<option value="' . $rowp['id'] . '">' . $rowp['prog_name'] . '</option>';
					  }
					?>
				  </select>

				</td> 
			  </tr>
			  <tr>  
				<td width="30%"><label  style="font-size:  20px;font-family:Arial Narrow;">Amount</label></td>  
				<td width="70%" ><input type="number"  class="form-control" name="amount" placeholder="Amount" required> </td> 
			  </tr> 
			</table>
		  </div>
		  <div class='modal-footer'>
			<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
			<button type='button' class='btn btn-primary'>Save changes</button>
		  </div>
		</div>
	  </div>
	</div>
		<!-- Modal -->
			<div id="payrollModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="payrollModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="payrollModalLabel">Payroll Details</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div id="payroll-details" width="50%"></div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
		<!-- Modal prompt for password verification -->
		<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="passwordModalLabel">Verify Password</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<input type="password" id="passwordInput" class="form-control" placeholder="Enter your password">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" id="verifyPasswordBtn" class="btn btn-primary">Verify</button>
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
        <div class="table-responsive">  
            <table id="payrollTable" style="width:100%" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Activity Title</th>
                        <th>Program</th>
                        <th>File Name</th>
                        <th>No. of Bene</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
				<?php
				$sdofname="";
				if ($result1->num_rows > 0) {
					while ($row1 = $result1->fetch_assoc()) {
						$sdonum 			= $row1['sdo'];
						$project_title 		= $row1['project_title'];
						$program 			= $row1['program'];
						$payrollNoRow		= $row1['payroll_no'];
						$payroll_date_to 	= $row1['dateTo'];
						$payroll_date_from 	= $row1['dateFrom'];
						$city_muni 			= $row1['city_muni'];
						$file_name 			= $row1['file_name'];
						$fs_name 			= strtoupper($row1['fs_name']);
						
							$sql2 = "SELECT  distinct e.empfname,e.empmname,e.emplname,e.empext,a.empid
									FROM tbl_employment a 
									inner join employee_info e using (empnum)
									where a.empid= '$sdonum'";
							$result2 = $con->query($sql2);
							$row2 = $result2->fetch_assoc();
							$sdofname =$row2['fname'].' '.$row2['mname'].' '.$row2['lname'];
						

						$sqls = "SELECT COUNT(id) as bene_count,sum(amount) as amount FROM tbl_bene WHERE payroll_no='$payrollNoRow' ";
						$results = $conn->query($sqls);
						
						if ($result1 && $rows = $results->fetch_assoc()) {
							$status = $row['payroll_status']; // assume 'status' is the column name for the status
							$row_class = ($status == 'Active') ? 'success' : (($status == 'Closed') ? 'danger' : ''); // add CSS class based on status

							echo "<tr class='$row_class'>";
							echo "<td>" . $payrollNoRow . "</td>";
							echo "<td>" . $project_title . "</td>";
							echo "<td>" . $program . "</td>";
							echo "<td>" . $file_name . "</td>";
							echo "<td align='center'>" . $rows['bene_count'] . "</td>"; // Display the count of beneficiaries
							echo "<td align='center'><button class='btn btn-primary view-payroll' data-payroll-no='" . $payrollNoRow . "' data-backdrop='false'>View</button></td>";
							echo "</tr>";
						}
					}
				} else {
					echo "<tr><td colspan='9' class='text-center'>No payroll records found</td></tr>";
				}
				?>
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

$(document).ready(function() {

    // Event delegation: Attach the event handler to the document, but delegate the event to elements with the 'view-payroll' class
    $(document).on('click', '.view-payroll', function() {
        var payroll_no = $(this).data('payroll-no');
        // AJAX request to fetch payroll details
        $.ajax({
            url: 'fetch_payroll_details.php',  // PHP file to fetch details
            type: 'POST',
            data: { payroll_no: payroll_no },
            success: function(response) {
                console.log(response); // Log the response to ensure data is fetched
                $('#payroll-details').html(response);
                $('#payrollModal').modal('show'); // Show the modal
            },
            error: function() {
                alert("Failed to fetch payroll details.");
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