<!doctype html>
<?php
session_start();
include "dbconnect.php";
$emm = $_SESSION['userid'];
$user_type = $_SESSION['user_type'];

if ($emm == '' || $emm == NULL) {
    echo "<script>window.open('index.php','_self')</script>";
    exit();
}
?>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="images/icons/dswd_icon.jpg">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>DSWD FO XI - ECT Payroll System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Animation library for notifications -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>
    <!-- Light Bootstrap Table core CSS -->
    <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>
    <link href="assets/css/demo.css" rel="stylesheet" />
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
    <style>
        body {
            font-size: 16px;
        }

        .progress {
            height: 30px;
        }

        .progress-bar {
            font-size: 16px;
            line-height: 30px;
            text-align: center;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            position: relative;
            margin: 15% auto;
            padding: 20px;
            background: #fff;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
    </style>
</head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<body>
<div class="wrapper">
    <div class="sidebar" data-color="blue" data-image="assets/img/sidebar-6.jpg">
        <div class="sidebar-wrapper">
            <div class="logo">
                <a href="#" class="simple-text">
                    <img src="images/icons/dswd_logo_white_2.png" style="width:82%; height:82%; margin-left:-3%" alt="..."/>
                </a>
            </div>
            <ul class="nav">
                <?php if ($user_type == 'RDV' || $user_type == 'Team Leader') { ?>
                    <li class="active">
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
                        <a href="listOfPayroll.php?userid=<?php echo $emm; ?>">
                            <i class="pe-7s-folder"></i><span><b>Payroll List</b></span>
                        </a>
                    </li>
                    <li>
                        <a href="user.php?userid=<?php echo $emm; ?>">
                            <i class="pe-7s-user"></i><span><b>Users</b></span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($user_type == 'GO' || $user_type == 'Team Leader') { ?>
                    <li>
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
                    <a class="navbar-brand" href="#">Import File</a>
                </div>
            </div>
        </nav>

		<div class="content" style="width:100%">
        <div class="table-responsive">  
            <table id="payrollTable" style="width:100%" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Program</th>
                        <th>Province</th>
                        <th>Payroll Date</th>
                        <th>Amount</th>
                        <th>City/Municipality</th>
                        <th>Fund Source</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
				<?php
				$sdofname="";
				// SQL query to fetch payroll list from the database
				$sql1 = "SELECT * FROM tbl_payroll_list ";  // Adjust the table name to your actual payroll table
				$result1 = $conn->query($sql1);
				while ($result1->num_rows > 0) {
					if ($row1 = $result1->fetch_assoc()) {
						$payroll_no 		= $row1['payroll_no'];
						$program 		= $row1['program'];
						$project_title 		= $row1['project_title'];
						$file_name 	= $row1['file_name'];
						$payroll_status 		= $row1['payroll_status'];
						

							echo "<tr class='$row_class'>";
							echo "<td>" . $row1['payroll_no'] . "</td>";
							echo "<td>" . $program . "</td>";
							echo "<td>" . $project_title . "</td>";
							echo "<td>" . $file_name . "</td>";
							echo "<td align='center'><button class='btn btn-primary view-payroll' data-payroll-no='" . $payroll_no . "' data-backdrop='false'>View</button></td>";
							echo "</tr>";
						}
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
		alert(payroll_no);
        
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