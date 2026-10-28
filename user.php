<!doctype html>
<?php
session_start();
error_reporting(0);
    include "dbconnect.php";
	
	$emm=$_SESSION['userid'];
	$user_type = $_SESSION['user_type'];
	
	if($emm==''||$emm==NULL){
		echo "<script>window.open('index.php','_self')</script>";
	}else{
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
					<li class="active">
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
<div class="modal fade" id="assignUserModal" tabindex="-1" role="dialog" aria-labelledby="assignUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignUserModalLabel">Assign User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" style="width:100%">		   
                    <tr>  
                        <td width="30%"><label  style="font-size: 20px;font-family:Arial Narrow;">Employee ID</label></td>  
                        <td width="70%" > <input type="text" class="form-control" name="employeeId" id="employeeId" value='' /></input>
                        </td>   
                    </tr> 
                    <tr>  
                        <td width="30%"><label  style="font-size: 20px;font-family:Arial Narrow;">Password</label></td>  
                        <td width="70%" ><input type="text" class="form-control" name="password" id="password" value='' /></input>
                        </td>  
                    </tr> 	
                    <tr>  
                        <td width="30%"><label  style="font-size: 20px;font-family:Arial Narrow;">Usertype</label></td>  
                        <td width="70%" ><select class="form-control selectpicker" name="user_type" id="user_type">
                                <option value="" disabled selected>----------</option>
                                <option value="RDV" >RDV</option>
                                <option value="GO" >Grievance Officer</option>
                                <option value="Tagger" >Tagger</option>
                            </select>
                        </td>  
                    </tr> 		
                    <tr>  
                        <td width="30%"><label  style="font-size: 20px;font-family:Arial Narrow;">Area of Assignment</label></td>  
                        <td width="70%" ><select class="form-control selectpicker" name="areaassigned" id="areaassigned">
                                <option value="" disabled selected>----------</option>
                                <option value="1" >Davao City</option>
                                <option value="2" >Davao Del Sur</option>
                                <option value="3" >Davao Del Norte</option>
                                <option value="4" >Davao De Oro</option>
                                <option value="5" >Davao Occidental</option>
                                <option value="6" >Davao Oriental</option>
                            </select>
                        </td>  
                    </tr> 	
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary update-status-btn">Assign User</button>
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

         <div class="main-panel" style="width:100%">
			<div class="content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<button type="button" class="btn btn-primary add-btn">
								Add New User
							</button>
						</div>
					</div>
					<div class="table-responsive">  
					<br>
					<br>
					<table id="userTable" class="table table-bordered" style="width:100%">		   
						<thead>
							<tr>
								<th>Employee ID</th>
								<th>User Type</th>
								<th>Area Assigned</th>
								<th>Head</th>
								<th>Update</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT * FROM lib_users as a
									LEFT JOIN lib_area as b ON a.areaassigned = b.id ";
							$result = mysqli_query($conn, $sql);
							while ($row = mysqli_fetch_assoc($result)) {
								echo "<tr>";
								echo "<td width='20%'>" . $row['empid'] . "</td>";
								echo "<td width='20%'>" . $row['user_type'] . "</td>";
								echo "<td width='30%'>" . $row['acronym'] . "</td>";
								echo "<td width='20%'>" . $row['head'] . "</td>";
								echo "<td width='10%'><button class='btn btn-info update-btn' data-empid='" . $row['empid'] . "' data-user_type='" . $row['user_type'] . "' data-password='" . $row['password'] . "'>Update</button></td>";
								echo "</tr>";
								
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div> 

</body>

    <!--   Core JS Files   -->
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css">
	
	
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Charts Plugin -->
	<script src="assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>
<script>
    $(document).ready(function() {
        $('#userTable').DataTable({
            "columnDefs": [
                { "width": "30%", "targets": 0 },
                { "width": "40%", "targets": 1 },
                { "width": "30%", "targets": 2 }
            ]
        });
    });
</script>
</html>
<script>
$(document).ready(function() {
    // Use event delegation for the update button
    $(document).on('click', '.update-btn', function() {
        var empid = $(this).data('empid');
        var userType = $(this).data('user_type');
        var password = $(this).data('password');
        var areaassigned = $('#areaassigned').val();

        // Set the values of the select elements in the modal
        $('#employeeId').val(empid);
        $('#user_type').val(userType);
        $('#password').val(password);
        // Open the modal
        $('#assignUserModal').modal('show');
    });

    // Handle update status button click
    $(document).on('click', '.update-status-btn', function() {
        var empid = $('#employeeId').val();
        var userType = $('#user_type').val();
        var password = $('#password').val();
        var areaassigned = $('#areaassigned').val();

        if (!empid || !password || !userType || !areaassigned) {
            alert("Employee ID or Password cannot be empty.");
            return; // Exit if any of the required fields are missing
        } else {
            // AJAX request to update the details in the database
            $.ajax({
                url: 'save_user.php',  // The PHP file that handles the update
                type: 'POST',
                data: {
                    empid: empid,
                    password: password,
                    userType: userType,
                    areaassigned: areaassigned
                },
                success: function(response) {
                    alert('Status and details updated successfully!');
                    location.reload();
                    // Reload the payrollTable without reloading the whole page
                    $('#payrollTable').DataTable().ajax.reload(null, false);  // Reload DataTable, keeping the page position
                },
                error: function() {
                    alert('Error updating status and details. Please try again.');
                }
            });
        }
    });

    // Use event delegation for the add button
    $(document).on('click', '.add-btn', function() {
        $('#assignUserModal').modal('show');
    });
});

$(document).ready(function() {
	$('.add-btn').click(function() {
		$('#assignUserModal').modal('show');
	});
});
</script>
	<?php }?>