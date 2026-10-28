<!doctype html>
<?php
session_start();
error_reporting(0);
    include "php/db.php";
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

	<title>DSWD FO XI - ECT Payroll System</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />

    <link href='css/googleapis.css' rel='stylesheet' type='text/css'>
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
			text-align: center; /* Center the text inside the bar */
		}
        /* Basic styles for modal */
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


</script> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

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
					<li class="active">
						<a href="rdv.php?userid=<?php echo $emm; ?>">
							<i class="pe-7s-file"></i><span><b>RDV</b></span>
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
					<li>
						<a href="tagger.php?userid=<?php echo $emm; ?>">
							<i class="pe-7s-folder"></i><span><b>Tagger</b></span>
						</a>
					</li>
				<?php } ?>
			</ul>

    </div>
</div>
<div class="modal" id="modal" tabindex="-1" aria-labelledby="confirmSyncModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmSyncModalLabel" style="color:red">Reminders Import Served File first!</h5>
            </div>
            <div class="modal-body">
				<form id="uploadForm" enctype="multipart/form-data">
					<input type="text" class="form-control" id="codetosync" name="codetosync" /><br>
					<button type="button" class="btn btn-primary" onclick="runImport()">Import Clean List Now</button>
				</form>

				<!-- Progress Bar -->
				<div id="progressContainer1" style="display: none; margin-top: 20px;">
					<div class="progress1">
						<div id="progressBar1" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
							<span id="progressPercentage1" style="font-weight: bold;">0%</span>
						</div>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="closeModal" data-bs-dismiss="modal">Cancel</button>
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

<div class="content" style="width:100%"><br><br>
    <div class="container" style="width:50%">
        <center>
            <h4 style="color: red;">Reminders Before Importing Clean List</h4>
            <ul style="text-align: left; display: inline-block;">
                <li>Ensure the file is in <b>CSV format</b> before uploading.</li>
                <li>Check that all required columns are present in the file.</li>
                <li>Remove any duplicate or incorrect data to avoid errors.</li>
                <li>Do not close the page while the process is running.</li>
            </ul>
            <br>
            <input type="text" class="form-control" id="codetosync" name="codetosync" /><br>
            <div id="fileError" style="color: red; display: none;">Please upload a valid CSV file.</div>
			<button type="button" class="btn btn-primary" onclick="runImport()">Import Clean List Now</button>
        </center>

        <!-- Progress Bar with Percentage -->
        <div id="progressContainer" style="display: none; margin-top: 20px;">
            <div class="progress">
                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    <span id="progressPercentage" style="font-weight: bold;">0%</span>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.getElementById('fileInput').addEventListener('change', function () {
    const file = this.files[0]; // Get the selected file
    const fileError = document.getElementById('fileError');
    const rdvButton = document.getElementById('rdvButton');
    
    if (file) {
        // Check the file type
        if (file.type === "text/csv" || file.name.endsWith('.csv')) {
            fileError.style.display = 'none'; // Hide error message
            rdvButton.disabled = false; // Enable the button
        } else {
            fileError.style.display = 'block'; // Show error message
            fileError.textContent = 'Please upload a valid CSV file.';
            this.value = ''; // Clear the input
            rdvButton.disabled = true; // Disable the button
        }
    } else {
        rdvButton.disabled = true; // Disable the button if no file is selected
    }
});


function runRDV() {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressPercentage = document.getElementById('progressPercentage');
    const runRDVButton = document.querySelector('input[name="rdvrun"]'); // Get button element

    if (file) {
        // Disable the button to prevent multiple clicks
        runRDVButton.disabled = true;
        runRDVButton.value = "Processing...";

        // Show the progress bar
        progressContainer.style.display = 'block';
        progressBar.style.width = '0%';
        progressBar.setAttribute('aria-valuenow', '0');
        progressPercentage.textContent = '0%';

        // Simulate progress
        let progress = 0;
        const interval = setInterval(() => {
            if (progress < 90) {
                progress += 10;
                progressBar.style.width = progress + '%';
                progressBar.setAttribute('aria-valuenow', progress);
                progressPercentage.textContent = progress + '%'; // Update percentage text
            } else {
                clearInterval(interval);
            }
        }, 500); // Update progress every 500ms

        const formData = new FormData();
        formData.append('file', file);

        fetch('runfullrdv.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json(); // Parse JSON response
        })
        .then(data => {
            clearInterval(interval); // Stop simulated progress
            progressBar.style.width = '100%'; // Complete the progress bar
            progressBar.setAttribute('aria-valuenow', '100');
            progressPercentage.textContent = '100%'; // Set percentage to 100
            progressBar.classList.remove('progress-bar-animated'); // Stop animation

            // Enable the button after completion
            runRDVButton.disabled = false;
            runRDVButton.value = "Run RDV";

            if (data.success) {
                alert(data.message); // Success message
                window.open('rdv.php', '_self');
            } else {
                alert("RDV Done");
                window.open('validation.php', '_self');
            }
        })
        .catch(error => {
            clearInterval(interval); // Stop simulated progress
            progressContainer.style.display = 'none'; // Hide progress bar
            console.error('Error:', error);

            // Enable the button in case of failure
            runRDVButton.disabled = false;
            runRDVButton.value = "Run RDV";

            window.open('validation.php', '_self');
        });
    } else {
        alert('Please select a file first!');
    }
}

    function runImport() {
    let fileInput = document.getElementById("ImportfileInput");
    let file = fileInput.files[0];

    if (!file || !file.name.endsWith(".csv")) {
        document.getElementById("fileError").style.display = "block";
        return;
    }
	 let closeModalBtn = document.getElementById("closeModal");
                closeModalBtn.style.display = "inline-block";
                closeModalBtn.disabled = false;
                closeModalBtn.classList.remove("btn-primary");
                closeModalBtn.classList.add("btn-info");
                closeModalBtn.innerText = "PAGHULAT!";
				
    document.getElementById("fileError").style.display = "none";

    let formData = new FormData();
    formData.append("csvFile", file);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "upload.php", true);

    // Progress Bar Update
    xhr.upload.onprogress = function (e) {
        if (e.lengthComputable) {
            let percentComplete = (e.loaded / e.total) * 100;
            document.getElementById("progressBar1").style.width = percentComplete + "%";
            document.getElementById("progressPercentage1").innerText = Math.round(percentComplete) + "%";
        }
    };

    xhr.onload = function () {
        if (xhr.status == 200) {
			//alert(xhr.status);
            let response = xhr.responseText.trim();
			//alert(response);
            if (response === "file_exists") {
                alert("Error: This file has already been uploaded!");
            } else if (response === "success") {
               
            } else {
               document.getElementById("progressBar1").style.width = "100%";
                document.getElementById("progressPercentage1").innerText = "100%";
                alert("Import Done");

                // Enable and update the "Done" button
                let closeModalBtn = document.getElementById("closeModal");
                closeModalBtn.style.display = "inline-block";
                closeModalBtn.disabled = false;
                closeModalBtn.classList.remove("btn-danger");
                closeModalBtn.classList.add("btn-primary");
                closeModalBtn.innerText = "Done";
            }
        } else {
            alert("Upload Failed! Server error.");
        }
    };

    xhr.send(formData);
}


</script>

</div>
</div>


</body>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Charts Plugin -->
	<script src="assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>
	

</html>
 <script>
  $(document).ready(function() {
    var max_fields      = 100; //maximum input boxes allowed
    var wrapper         = $(".input_party_wrap2"); //Fields wrapper
    var add_button      = $(".add_party_button2"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="row"><br>'+
									'<div class="col-md-3">'+
										'<input type="text" name="fname[]" class="form-control" placeholder="First Name" required>'+
									'</div>'+
									'<div class="col-md-3">'+								
										 '<input type="text" name="mname[]" class="form-control" placeholder="Middle Name" >'+									
									'</div>'+
									'<div class="col-md-3">'+								
										 '<input type="text" name="lname[]" class="form-control" placeholder="Last Name" required>'+									
									'</div>'+
									'<div class="col-md-2">'+								
										 '<select class="form-control"  name="ename[]" id="ename" class="selectpicker"  >'+
											'<option disabled selected>Ext. Name</option>'+
											'<option value=""> None</option>'+
											'<option value="Jr">Jr</option>'+
											'<option value="Sr">Sr</option>'+
											'<option value="I">I</option>'+
											'<option value="II">II</option>'+
											'<option value="III">III</option>'+
											'<option value="IV">IV</option>'+
										'</select>'+								
									'</div>'+
									 '<button style="width:40px" class="btn btn-danger  remove_party2"  >-</button>'+
								 '</div>'); //add input box
						//alert($('input[name="mytext1[]"]').length);      
	  }
    });

    $(wrapper).on("click",".remove_party2", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});
  </script>
	<?php }?>

