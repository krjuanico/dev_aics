<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>DSWD FO XI - AICSys</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/dswd_icon.jpg"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
	<link rel="stylesheet" href="css/maxdn.css">
<!--===============================================================================================-->  
	<script src="js/javascript1.js"></script>
	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   
	<script src="js/javascript2.js"></script>
	<style>
		body {
			background-image: url('images/bg.jpg');
			background-size: cover;
			background-repeat: no-repeat;
			background-position: center;
		}
		* Basic styles for modal */
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
            width: 100%;
            max-width: 1000px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            color: #aaa;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }
	</style>
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
					
					<span class="login100-form-title p-b-48">
						<div class="logo text-center"><img src="images/icons/logo-dswd.png" style="width: 100%" alt="DSWD Logo"></div>
					</span>

					<div class="wrap-input100 validate-input" >
						<input class="input100" type="text" name="user" id="user">
						<span class="focus-input100" data-placeholder="Employee ID" ></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="pass" id="pass">
						<span class="focus-input100" data-placeholder="Password"></span>
					</div>
					
					
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn" id="openModal" type="button" name="continue"> 
								Login
							</button>
						</div>
					</div>

				

					<div class="text-center p-t-115">
						<span class="txt1">
							<a name="add_data" hidden onclick=" myFunction()" id="add_data">Forgot Password?
						</span></a>
					</div>
			</div>
		</div>
	</div>
					<!-- Modal Structure -->
					<div id="modal" class="modal">
						<div class="modal-content">
							<span class="close" id="closeModal">&times;</span>
							<center><h1 style="color:red">REMINDER!</h1></center>
							<div class="container-login100-form-btn">
							<div>
							<h3>FOR INITIAL RDV INSTRUCTIONS</h3>
							  <ul>
								<li>1. Import the updated served list first before conducting RDV.</li>
								<li>2. Leave the Control number column blank. The result of the initial RDV will provide the control number.</li>
								<li>3. Remove all empty rows including the table border.</li>
								<li class="color:red">4. <b>When running the RDV, DO NOT import the same list of names more than once. If you do, the first set of clean lists will be marked as a possible match during the second import.</b></li>
								<li>5. Remove all empty rows including the table border.</li>
								<li>6. Use only the default template for Raw File before RDV <a href="https://docs.google.com/spreadsheets/d/1JYOjFKI-6O7sxNTkVkT0VsVS-edHGWDZi_G0wVk36IM/edit?gid=0#gid=0" target="_blank">RAW List Template</a></li>
							  </ul>
							
							<h3>FOR GRIEVANCE OFFICER INSTRUCTIONS</h3>
							  <ul>
								<li>1. Import the updated served list to the system.</li>
								<li>2. The control number column MUST NOT be empty. Copy the beneficiary's control number listed in the GIS (lower left part).</li>
								<li>3. Remove all empty rows including the table border.</li>
								</ul>
								
							</div>
							<form  role="form" method="post" action="index.php">
								<input hidden class="input100" type="text" name="user1" id="user1"/>
								<input hidden class="input100" type="password1" name="pass1" id="pass1"/>
									<div class="wrap-login100-form-btn">
										<div class="login100-form-bgbtn"></div>
										<button class="login100-form-btn" id="login" type="submit" name="login"> 
											Proceed
										</button>
									</div>
									
							</form>
					</div>
						</div>
					</div>

	<script src="assets/js/main.js"></script>

</body>
</html>

<?php 

// Set the timezone
date_default_timezone_set('Asia/Manila');

// Get the current date and time
$date_today = date("Y-m-d H:i:s");
$date_today1 = date("Y-m-d");

// Include database connection files
include("dbconnect.php"); 

// Check if the form is submitted
if (isset($_POST['login'])) { 		
    $empid = mysqli_real_escape_string($conn, $_POST['user1']); 
    $user_pass = mysqli_real_escape_string($conn, $_POST['pass1']);  

    // Query to check user credentials
    $sql1 = "SELECT * FROM lib_users WHERE empid = '$empid' AND password = '$user_pass'";
    $result1 = mysqli_query($conn, $sql1);

    // Check if user exists
    if ($row1 = mysqli_fetch_assoc($result1)) { 	
        $user_type = $row1['user_type'];
        $userid = $row1['empid'];

        // Generate a secure token
        $token = bin2hex(random_bytes(32));

        // Store session variables
        $_SESSION['token'] = $token; 
        $_SESSION['userid'] = $userid;
        $_SESSION['user_type'] = $user_type;

        // Redirect based on user type
        if ($user_type == 'RDV' || $user_type == 'Team Leader') {
            echo "<script>window.location.href = 'rdv.php?userid=$userid';</script>";
        } elseif ($user_type == 'GO') {
            echo "<script>window.location.href = 'validation.php?userid=$userid';</script>";
        } elseif ($user_type == 'Tagger') {
            echo "<script>window.location.href = 'tagger.php?userid=$userid';</script>";
        } else {				
            echo "<script>alert('Invalid Usertype!'); window.location.href = 'index.php';</script>";
        }
    } else {				
        echo "<script>alert('Invalid credentials! Please try again.'); window.location.href = 'index.php';</script>";
    }
}
?>

	<script>
						// Get elements
						const modal = document.getElementById('modal');
						const openModal = document.getElementById('openModal');
						const closeModal = document.getElementById('closeModal');
						const closeModalButton = document.getElementById('closeModalButton');

						// Open modal on button click
						openModal.addEventListener('click', () => {
							
							modal.style.display = 'block';
							var pass = document.getElementById('pass').value; // Get the value of the first field
							var pass1 = document.getElementById('pass1'); // Get the second input element
							var user = document.getElementById('user').value; // Get the value of the first field
							var user1 = document.getElementById('user1'); // Get the second input element
							pass1.value = pass; // Set the value of the second input to match the first one
							user1.value = user; // Set the value of the second input to match the first one
						});

						// Close modal when 'x' is clicked
						closeModal.addEventListener('click', () => {
							modal.style.display = 'none';
						});

						// Close modal when "Close" button is clicked
						closeModalButton.addEventListener('click', () => {
							modal.style.display = 'none';
						});

						// Close modal if user clicks outside of modal content
						window.addEventListener('click', (event) => {
							if (event.target === modal) {
								modal.style.display = 'none';
							}
						});
					</script>