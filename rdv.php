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
    <!-- Modal for file import -->
    <div class="modal" id="modal" tabindex="-1" aria-labelledby="confirmSyncModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmSyncModalLabel" style="color:red">Reminders: Import Served File first!</h5>
                </div>
                <div class="modal-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <input type="file" class="form-control" id="ImportfileInput" name="csvFile" accept=".csv"/>
                        <div id="fileError" style="color: red; display: none;">Please upload a valid CSV file.</div><br>
                        <button type="button" class="btn btn-primary" id="runImportbtn" onclick="runImport()">Update Served List</button>
                    </form>
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

<div class="" style="width:100%"><br><br>
    <div class="container" style="width:60%">
        <center>
            <h4 style="color: red;">Reminders Before Importing Served List</h4>
            <ul style="text-align: left; display: inline-block;">
                <li>Ensure the file is in <b>CSV format</b> before uploading.</li>
                <li>Check that all required columns are present in the file.</li>
                <li>Remove any duplicate or incorrect data to avoid errors.</li>
                <li>Do not close the page while the process is running.</li>
            </ul>
            <br>
            <form action="import_excel.php" method="post" target="_self" enctype="multipart/form-data">
       
      <div class="table-responsive">  
	  
           <table class="table table-bordered" style="width:100%">						
				<tr>  
                     <td width="30%"><label  style="font-size: 20px;font-family:Arial Narrow;">ACTIVITY NAME</label></td>  
                     <td width="70%" ><input type="text" name="project_name"  class="form-control" placeholder="" > </input> </td> 
				 </tr> 	
				  <tr>  
                     <td width="20%"><label  style="font-size: 20px;font-family:Arial Narrow;">IMPORT FILE</label></td>  
                     <td width="80%" > <input type="file" name="file" accept=".csv" ></td> 
				 </tr>
           </table>  
      </div>  
	     <!-- Spinner (Initially Hidden) -->
		<div id="spinner" class="text-center" style="display: none;">
			<div class="spinner-border text-primary" role="status">
				<span class="sr-only">Importing...</span>
			</div>
			<p style="margin-top: 10px;">Importing, please wait...</p>
		</div>
		<div class="modal-footer" style="width:80%">  		 
			<center><input type="submit" name="submit" class="btn btn-primary" value="IMPORT LIST"><center>
		</div>
	  </form>
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
        // Show modal on page load
        window.onload = () => {
            document.getElementById('modal').style.display = 'block';
        };

        // Close modal on click
        document.getElementById('closeModal').addEventListener('click', () => {
            document.getElementById('modal').style.display = 'none';
        });

        // Function to handle file import
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

            document.getElementById('runImportbtn').disabled = true;
            document.getElementById("fileError").style.display = "none";

            let formData = new FormData();
            formData.append("csvFile", file);

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "upload.php", true);

            xhr.upload.onprogress = function (e) {
                if (e.lengthComputable) {
                    let percentComplete = (e.loaded / e.total) * 100;
                    document.getElementById("progressBar1").style.width = percentComplete + "%";
                    document.getElementById("progressPercentage1").innerText = Math.round(percentComplete) + "%";
                }
            };

            xhr.onload = function () {
                if (xhr.status == 200) {
                    let response = xhr.responseText.trim();

                    if (response === "file_exists") {
                        alert("Error: This file has already been uploaded!");
                    } else if (response === "success") {
                        alert("Import Done");
                    } else {
                        document.getElementById("progressBar1").style.width = "100%";
                        document.getElementById("progressPercentage1").innerText = "100%";
                        alert("Import Done");
                    }

                    closeModalBtn.classList.remove("btn-danger");
                    closeModalBtn.classList.add("btn-primary");
                    closeModalBtn.innerText = "Done";
                    closeModalBtn.disabled = false;
                } else {
                    alert("Upload Failed! Server error...");
                }
            };
            xhr.send(formData);
        }
    </script>

    </div>
</div>
</body>
</html>
