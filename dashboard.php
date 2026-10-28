<!doctype html>
<?php
session_start();
//error_reporting(0);
    include "php/db.php";
    include "dbconnect.php";
	
	$emm=$_SESSION['userid'];
	$user_type = $_SESSION['user_type']; // get the user type from the database or session
	if($emm==''||$emm==NULL){
		echo "<script>window.open('index.php','_self')</script>";
	}else{
	$sql2 = "SELECT team_leader,payroll_no from lib_users where empid='$emm'";
	$result2 = $conn->query($sql2);
	$row2 = $result2->fetch_assoc();
	$team_leader=$row2['team_leader'];
	$payroll_no=$row2['payroll_no'];
	
	
	$sql1= "select empnum from tbl_employment where empid='$emm'";
	$result1 = $con->query($sql1);
	$row1 = $result1->fetch_assoc();
	$id1=$row1['empnum'];
	
	// Query to get the count of male, female, and null (unspecified) gender beneficiaries
	$sql_sex_distribution = "
		SELECT 
			CASE 
				WHEN bene_sex IS NULL THEN 'Unspecified' 
				WHEN bene_sex = 'Male' THEN 'Male' 
				WHEN bene_sex = 'Female' THEN 'Female' 
			END AS gender, 
			COUNT(*) as count
		FROM tbl_bene WHERE status in ('Validated','Claimed') and team_leader='$emm'
		GROUP BY gender
	";
	$result_sex_distribution = $conn->query($sql_sex_distribution);

	$sex_labels = [];
	$sex_counts = [];

	while ($row = $result_sex_distribution->fetch_assoc()) {
		$sex_labels[] = $row['gender'];
		$sex_counts[] = $row['count'];
	}
	
	// Fetch data for pie chart (status distribution)
	$sql_status = "SELECT status, COUNT(*) as count FROM tbl_bene 
		WHERE team_leader='$emm' GROUP BY status";
	$result_status = $conn->query($sql_status);

	$status_counts = [
		'Claimed' => 0,
		'Null' => 0,
		'Disqualified' => 0
	];

	while ($row = $result_status->fetch_assoc()) {
		if ($row['status'] == 'Claimed') {
			$status_counts['Claimed'] = $row['count'];
		} elseif ($row['status'] == 'Disqualified') {
			$status_counts['Disqualified'] = $row['count'];
		} elseif ($row['status'] == NULL) {
			$status_counts['Null'] = $row['count'];
		}
	}

	// Fetch data for bar chart (validated count per validator)
	$sql_validator = "SELECT validated_by, COUNT(*) as count 
					  FROM tbl_bene a
					  WHERE status in ('Validated','Claimed') and team_leader='$emm'
					  GROUP BY validated_by";
	$result_validator = $conn->query($sql_validator);

	$validators = [];
	$validated_counts = [];

	while ($row = $result_validator->fetch_assoc()) {
		$validators[] = $row['validated_by'];
		$validated_counts[] = $row['count'];
	}

	// Query to get the count of records per hour for each status
	$sql_trend = "
		SELECT 
			HOUR(date_validated) AS hour, 
			SUM(CASE WHEN status = 'Claimed' THEN 1 ELSE 0 END) AS claimed,
			SUM(CASE WHEN status = 'Replacement' THEN 1 ELSE 0 END) AS replacement,
			SUM(CASE WHEN status = 'Disqualified' THEN 1 ELSE 0 END) AS disqualified
		FROM tbl_bene 
		WHERE status IN ('Claimed', 'Replacement', 'Disqualified') and team_leader='$emm'
		GROUP BY HOUR(date_validated)
		ORDER BY hour ASC
	";
	$result_trend = $conn->query($sql_trend);

	$hours = [];
	$validated_per_hour = [];
	$replacement_per_hour = [];
	$disqualified_per_hour = [];

	while ($row = $result_trend->fetch_assoc()) {
		$hours[] = $row['hour'];
		$validated_per_hour[] = $row['validated'];
		$replacement_per_hour[] = $row['replacement'];
		$disqualified_per_hour[] = $row['disqualified'];
	}
	
	// Query to get the count of validated beneficiaries in each age group
	$sql_age_distribution = "
		SELECT 
			CASE 
				WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 20 THEN '18-20'
				WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 21 AND 30 THEN '21-30'
				WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 31 AND 40 THEN '31-40'
				WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 41 AND 50 THEN '41-50'
				WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 51 AND 60 THEN '51-60'
				WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 61 AND 70 THEN '61-70'
				WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 71 AND 80 THEN '71-80'
				WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 81 AND 90 THEN '81-90'
				ELSE '91-100'
			END AS age_group, 
			COUNT(*) AS count
		FROM tbl_bene
		WHERE status in ('Validated','Claimed') and team_leader='$emm'
		GROUP BY age_group
	";
	$result_age_distribution = $conn->query($sql_age_distribution);

	$age_groups = [];
	$age_counts = [];

	while ($row = $result_age_distribution->fetch_assoc()) {
		$age_groups[] = $row['age_group'];
		$age_counts[] = $row['count'];
	}


	$sql1= "select empnum from tbl_employment where empid='$emm'";
	$result1 = $con->query($sql1);
	$row1 = $result1->fetch_assoc();
	$id1=$row1['empnum'];
	$sql = "SELECT  distinct e.empfname,e.empmname,e.emplname,e.empext
			FROM tbl_employment a 
			inner join employee_info e using (empnum)
			where a.empnum= '$id1'";
	$result = $con->query($sql);
	$row = $result->fetch_assoc();


// SQL query to fetch payroll list from the database
$sql = "SELECT *
        FROM tbl_payroll_list as a
		INNER JOIN lib_fund_source as b on fund_source=b.id
		INNER JOIN lib_sdo as c on sdo=c.sdo_id
		INNER JOIN lib_program as d on program=d.id
		WHERE team_leader='$team_leader' AND payroll_no<>0 ";  // Adjust the table name to your actual payroll table
$result = $conn->query($sql);
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
    <!--     Fonts and icons     -->
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
	<!-- Chart.js Library -->
    <script src="js/chart.js"></script>	
	<style>
		body {
		  font-size: 16px;
		}
		.modal {
		  z-index: 1060; /* increase the z-index to be higher than the page's content */
		}
		/* Custom CSS to force modal to be extra large */
		.modal-xl {
			max-width: 90%;  /* Adjust the width as per your requirement */
			width: 90%;      /* Set width to 90% of the screen */
		}
		.notification {
			display: flex;
			flex-direction: column;
			align-items: center;
		}

		.notification .badge {
			background-color: salmon;
			color: white;
			padding: 5px 10px;
			border-radius: 50%;
			margin-bottom: 5px; /* Space between badge and name */
			font-size: 12px;
		}
        .card {
            padding: 20px;
            margin-bottom: 20px;
        }
		#validatorBarChart {
			width: 100%;   /* Set the width to 100% of the parent */
			height: 10%;   /* Set the height to 50% of the parent */
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
				
                <li class="active">
					<a href="dashboard.php?userid=<?php echo $emm;?>"><i class="pe-7s-file"></i><span><b>Dashboard</span></b> </a>
				</li>
				<li>
					<a href="export_excel.php?userid=<?php echo $emm;?>"><i class="pe-7s-file"></i><span><b>Import CSV File</span></b> </a>
				</li>
				<li>
					<a href="validation.php?userid=<?php echo $emm;?>"><i class="pe-7s-folder"></i><span><b>Masterlist</span></b> </a>
				</li>
				<li>
					<a href="payroll_list.php?userid=<?php echo $emm;?>"><i class="pe-7s-folder"></i><span><b>List of Payroll</span></b> </a>
				</li>
				
				<!--
                <li>
					<a href="export_excel.php?userid=<?php echo $emm;?>"><i class="pe-7s-file"></i><span><b>Import CSV File</span></b> </a>
				</li>
				 <li hidden>
					<a href="teamLeaderAssignment.php?userid=<?php echo $emm;?>"><i class="pe-7s-file"></i><span><b>File Assignment</span></b> </a>
				</li>
					<input hidden id="user_id" value="<?php echo $emm;?>"/>
				<li hidden>
					<a href="tagger.php?userid=<?php echo $emm;?>"><i class="pe-7s-folder"></i><span><b>Tagging</span></b> </a>
				</li>
				<li hidden>
					<a href="payroll_list.php?userid=<?php echo $emm;?>"><i class="pe-7s-folder"></i><span><b>List of Payroll</span></b> </a>
				</li>
				 <li hidden>
					<a href="#subPages6" data-toggle="collapse" class="collapsed"><i class="pe-7s-user"></i><span><b>Team Leader</span></b> </a>
					<div id="subPages6" class="collapse ">
						
					</div>
				</li>-->
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
							<div id="payroll-details"></div>
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
			<div class="content" style="width:99%">
				<div class="row" >
					<div class="col-md-2" style="float: right;">
						<select class="form-control" id="export_rep">
							<option value="Correction">List of Corrections</option>
							<option value="Replacement">List of Replacement</option>
							<option value="Claimed">List of Claimed</option>
							<option value="Validated">List of Validated</option>
							<option hidden value="TagClaimed">Tag as Claimed</option>
						</select>
					</div>
					
					<div class="col-md-2" style="float: right;">
						<select class="form-control" id="payroll_no_tl" name="payroll_no_tl">
							<option disabled selected>Select Payroll No.</option>
								<?php
									$sql3 = "SELECT payroll_no FROM tbl_payroll_list where team_leader='$team_leader'";
									$result3 = mysqli_query($conn, $sql3);
									while ($row3 = mysqli_fetch_assoc($result3)) { ?>
										<option value="<?php echo $row3['payroll_no']; ?>" ><?php echo $row3['payroll_no']; ?></option>
								<?php }?>
						</select>
					</div>
					<div class="col-md-1" style="float: right;">
						<button class="btn btn-primary" id="generate_rep">Export File</button>
					</div>
				</div>
				<hr>
			</div>
			<div class="container-fluid">
                <div class="row">
                    <!-- Pie Chart for Status Distribution -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Status Distribution</h4>
                            </div>
                            <div class="content">
                                <canvas id="statusPieChart"></canvas>
                            </div>
                        </div>
                    </div>
					
					<!-- Donut Chart for Gender Distribution -->
					<div class="col-md-4">
						<div class="card">
							<div class="header">
								<h4 class="title">Gender Distribution</h4>
							</div>
							<div class="content">
								<canvas id="sexDonutChart"></canvas>
							</div>
						</div>
					</div>
					 <div class="col-md-4">
						<div class="card">
							<div class="header">
								<h4 class="title">Age Distribution of Validated Beneficiaries</h4>
							</div>
							<div class="content">
								<canvas id="ageDistributionChart" width="400" height="200"></canvas>
							</div>
						</div>
					</div>

				 <!-- Bar Chart for Validated per Validator -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Validated Records per Validator</h4>
                            </div>
                            <div class="content">
                                <canvas id="validatorBarChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Line Chart for Trends (Validated per Hour) -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Validated Records per Hour</h4>
                            </div>
                            <div class="content">
                                <canvas id="trendLineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>
</body>


	
    <!--   Core JS Files   -->
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>

</html>

<!-- Pie Chart Script -->
<script>
$('#generate_rep').click(function() {
    var reportType = $('#export_rep').val();
    var payrollNo = $('#payroll_no_tl').val();
    $.ajax({
        type: 'POST',
        url: 'generate_report.php',
        data: { report_type: reportType,payrollNo:payrollNo },
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
var ctxPie = document.getElementById('statusPieChart').getContext('2d');
var statusPieChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: ['Claimed', 'Null', 'Disqualified'],
        datasets: [{
            data: [
                <?php echo $status_counts['Claimed']; ?>, 
                <?php echo $status_counts['Null']; ?>, 
                <?php echo $status_counts['Disqualified']; ?>
            ],
            backgroundColor: ['#2682f1', '#FDDA0D', '#ca2907']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});
</script>

<!-- Bar Chart Script -->
<script>
var ctxBar = document.getElementById('validatorBarChart').getContext('2d');
var validatorBarChart = new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($validators); ?>,
        datasets: [{
            label: 'Validated Records',
            data: <?php echo json_encode($validated_counts); ?>,
            backgroundColor: '#2196F3'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
<!-- Line Chart Script (Trends - Records per Hour) -->
<script>
var ctxLine = document.getElementById('trendLineChart').getContext('2d');
var trendLineChart = new Chart(ctxLine, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($hours); ?>,
        datasets: [{
            label: 'Validated',
            data: <?php echo json_encode($validated_per_hour); ?>,
            borderColor: '#42A5F5',
            backgroundColor: 'rgba(66, 165, 245, 0.2)',
            fill: true
        }, {
            label: 'Replacement',
            data: <?php echo json_encode($replacement_per_hour); ?>,
            borderColor: '#FF9800',
            backgroundColor: 'rgba(255, 152, 0, 0.2)',
            fill: true
        }, {
            label: 'Disqualified',
            data: <?php echo json_encode($disqualified_per_hour); ?>,
            borderColor: '#F44336',
            backgroundColor: 'rgba(244, 67, 54, 0.2)',
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            },
            x: {
                title: {
                    display: true,
                    text: 'Hour of the Day'
                }
            }
        }
    }
});
</script>
<!-- Donut Chart Script -->
<script>
var ctxDonut = document.getElementById('sexDonutChart').getContext('2d');
var sexDonutChart = new Chart(ctxDonut, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($sex_labels); ?>, // Male, Female, Unspecified
        datasets: [{
            data: <?php echo json_encode($sex_counts); ?>, // Counts per gender (including null as Unspecified)
            backgroundColor: ['#ca2907', '#2682f1', '#ece241'] // Colors for Male, Female, and Unspecified (gray)
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        cutout: '50%' // This creates the 'donut' effect by cutting out the middle
    }
});
</script>
<!-- Bar Chart Script for Age Distribution -->
<script>
var ctxAge = document.getElementById('ageDistributionChart').getContext('2d');
var ageDistributionChart = new Chart(ctxAge, {
    type: 'bar', // Use 'bar' type
    data: {
        labels: <?php echo json_encode($age_groups); ?>, // Age groups
        datasets: [{
            label: 'Number of Validated Beneficiaries',
            data: <?php echo json_encode($age_counts); ?>, // Counts for each age group
            backgroundColor: '#2196F3' // Color for the bars
        }]
    },
    options: {
        indexAxis: 'y', // This option makes the bar chart horizontal
        responsive: true,
        scales: {
            x: { // X-axis now represents the number of beneficiaries
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Beneficiaries'
                }
            },
            y: { // Y-axis now represents the age groups
                title: {
                    display: true,
                    text: 'Age Group'
                }
            }
        },
        plugins: {
            legend: {
                display: false // Hide the legend since there's only one dataset
            }
        }
    }
});
</script>
	<?php }?>