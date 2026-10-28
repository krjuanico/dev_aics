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
?>
<html lang="en">
<head>
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
	
	<!-- Include jQuery first -->
	<script src="js/jquery-3.6.0.min.js"></script>

	<!-- Include Bootstrap JS only once -->
	<script src="js/bootstrap.bundle.min.js"></script>

	<!-- DataTables JS -->
	<script type="text/javascript" charset="utf8" src="js/jquery.dataTables.min.js"></script>

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
					<li>
						<a href="validation.php?userid=<?php echo $emm; ?>">
							<i class="pe-7s-folder"></i><span><b>Masterlist</b></span>
						</a>
					</li>
				<?php } ?>

				<?php if ($user_type == 'Tagger' || $user_type == 'Team Leader') { ?>
					<li class="active">
						<a href="tagger.php?userid=<?php echo $emm; ?>">
							<i class="pe-7s-folder"></i><span><b>Tagger</b></span>
						</a>
					</li>
				<?php } ?>
			</ul>
    </div>
</div>


			<!-- Modal -->
			<div id="claimModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="payrollModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="payrollModalLabel">Client Details</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<input type="hidden" class="form-control" id="bene-id-input" value="">
							<div id="payroll-details">
								<!-- Payroll details content will be dynamically loaded here -->
								<p><span id="bene-id-display"></span></p>
								<p><span id="bene-name-display"></span></p>
							</div>
							
						</div>
						<div class="modal-footer">
						   <button class="btn btn-primary" id="claimButton">Update</button>
						</div>
					</div>
				</div>
			</div>
			<div id="unclaimModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="unclaimModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="unclaimModalLabel">Client Details</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<input type="hidden" class="form-control" id="bene-id-input1" value="">
							<div id="payroll-details1">
								<!-- Payroll details content will be dynamically loaded here -->
								<p>Beneficiary ID: <span id="bene-id-display1"></span></p>
							</div>
							<div class="form-group mt-3">
								<label for="password-input1">Enter TL Password:</label>
								<input type="password" id="password-input1" class="form-control" placeholder="Enter Team Leader Password">
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" id="unclaimButton">Update</button>
							<button class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        <div class="table-responsive">  
		<table id="payrollTable" class="table table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
						<th>Control No.</th>
                        <th>Beneficiary Name</th>
                        <th>Date of Birth</th>
                        <th>Barangay</th>
                        <th>Status</th>
                        <th>Payroll ID.</th>
                        <th>Date/Time Updated</th>
                        <th>Updated By</th>
                        <th>Actions</th>
                        </tr>
                    </thead>
                </table>
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
    // ✅ Initialize DataTable with server-side processing
    let table = $('#payrollTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": { "url": "fetch_bene_payroll.php", "type": "POST" },
        "pageLength": 10,
        "lengthMenu": [10, 20, 30, 50]
    });

    // ✅ Claim modal
    $(document).on('click', '.claim-btn', function() {
        let beneId = $(this).data('id');
        let fullName = $(this).data('name');
        $('#bene-id-input').val(beneId);
        $('#bene-id-display').html(`<p>Beneficairy ID: ${beneId}</p>`);
        $('#bene-name-display').html(`<p>Full Name: ${fullName}</p>`);
        $('#claimModal').modal('show');
    });
   $('#claimButton').off('click').on('click', function () {
    let beneId = $('#bene-id-input').val();
    let $btn   = $(this);

    // disable button to prevent double clicks
    $btn.prop('disabled', true).text('Updating...');

    $.post("update_status_claim.php", { bene_id: beneId })
        .done(function (resp) {
            alert("Updated: Payroll ID " + resp);
            $('#claimModal').modal('hide');
            table.ajax.reload();
        })
        .fail(function () {
            alert('Error updating payroll.');
        })
        .always(function () {
            // re-enable button
            $btn.prop('disabled', false).text('Update');
        });
});

    // ✅ Unclaim modal
    $(document).on('click', '.unclaim-btn', function() {
        let beneId = $(this).data('id');
        let fullName = $(this).data('name');
        $('#bene-id-input1').val(beneId);
        $('#unclaim-details').html(`<p>Full Name: ${fullName}</p>`);
        $('#unclaimModal').modal('show');
    });
    $('#unclaimButton').click(function(){
        let pwd = $('#password-input1').val();
        let beneId = $('#bene-id-input1').val();
        $.get("getTeamLeaderPassword.php", {password: pwd, beneId: beneId}, function(resp){
            if(resp === 'Ok'){
                alert("Status updated");
                $('#unclaimModal').modal('hide');
                table.ajax.reload();
            } else {
                alert(resp);
            }
			
			$('#unclaimModal').modal('hide');
			table.ajax.reload();
        });
    });
});
</script>


	<?php }?>