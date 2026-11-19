
<link rel="stylesheet" href="cpms/css/coe.css">
<link rel="stylesheet" type="text/css" href="cpms/css/bootstrap.min.css"> 

<html>
    <body>
        <style>
            .header{
                background-color: black !important;
                color: white;
                -webkit-print-color-adjust: exact; 
                font-size: 12px;
            }
            #gisv2_print input{
                text-transform:uppercase;
            }
        .letter-container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
        }
        .letter-header {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
        .letter-body {
            font-size: 16px;
            line-height: 1.6;
        }
        .letter-footer {
            margin-top: 20px;
            text-align: right;
            font-size: 16px;
        }
        </style>
		<?php
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '4096M'); // Increase to 256MB or more
		error_reporting(0);
		session_start();
		$row['age'] =0;
		include "../dbconnect.php";
			// Ensure session variable exists

			$beneId = $_GET['beneId'];
			$sql = "SELECT * FROM aics_clean_list
				WHERE control_number='$beneId'";
			$result = mysqli_query($conn, $sql);	
			while ($row = mysqli_fetch_assoc($result)) {
			$fname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['first_name']);
			$mname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['middle_name']);
			$lname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['last_name']);  
			$ename = $row['extension_name'];   
			
			$amount = $row['amount'];
			
			$dob = $row['birth_day'].'/'.$row['birth_month'].'/'.$row['birth_year'];
			
			$dob = str_replace('-', '/', $dob); // Replace dashes with slashes
				$dobDate = DateTime::createFromFormat('d/m/Y', $dob);

				// Check if the conversion was successful
				if ($dobDate === false) {
					//echo "Invalid date format!";
				} else {
					// Get the current date
					$currentDate = new DateTime();

					// Calculate the difference between the current date and the DOB
					$ageInterval = $currentDate->diff($dobDate);

					// Get the age in years

					$age = $ageInterval->y;
				}	



			
			$sixDigitCode = '';
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Alphanumeric characters
			for ($i = 0; $i < 6; $i++) {
				$sixDigitCode .= $characters[rand(0, strlen($characters) - 1)];
			}


		?>
<div class="letter-container" style="margin: 20px;">
    <div class="row" style="margin-top: 5%;">
        <div class="col-6">
            <img src="cpms/images/dswd_olog.png" alt="" width="230px" height="60px" style="margin-bottom: 10px;">
            <img src="cpms/images/AICS.png" alt="" width="70px" height="60px" style="margin-left: 10px; margin-bottom: 10px;">
            <img src="cpms/images/BP.png" alt="" width="70px" height="60px" style="margin-left: 10px; margin-bottom: 10px;">
        </div>
        <div class="col-6 ml-md-auto" style="color: #000000;">
            <p class="text-center" style="font-size: 30px; font-family: arial, sans-serif; font-weight: bold; margin-top: 10px;">CRISIS INTERVENTION SECTION</p>
            <p class="text-center" style="font-size: 17px; font-family: arial, sans-serif; margin-top: -20px;">Cor. Suazo St. R. Magsaysay Ave. Davao City</p>
            <p class="text-center" style="font-size: 14px; font-family: arial, sans-serif; margin-top: -20px;">DSWD-PMB-GF-13 | REV 03 | 14 MAY 2024</p>
        </div>
    </div>
    <br><br><br><br>
    <div class="letter-header" style="margin-top: 20px; margin-bottom: 20px;">
        <h3 style="text-align: center;">Justification Letter</h3><br>
    </div>
    <div style="margin: 20px 20px;">
        <p>&emsp;&emsp;This is to justify that the client <?php echo strtoupper($fname.' '.$mname.' '.$lname.' '.$ename);?>, <?php echo $age;?> years old, residing at <?php echo strtoupper($row['barangay'].', '.$row['city_municipality']);?>, was interviewed and assessed by the undersigned and found eligible to receive Food Assistance due to difficulty in sustaining their daily basic needs.
            This justification is made to facilitate the provision of assistance through the Assistance to Individuals In Crisis Situation (AICS) Program, aimed at supporting individuals and families in vulnerable situations.
<br>Done this _____ day of ___________ 2025.
        </p>
    </div>
    <br><br>
    <div style="text-align: right; margin-right: 20px;">
        <label>________________________________</label><br>
        <label>DSWD Social Worker</label>&emsp;&emsp;
    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <div class="footer row" style="margin-top: 50px;">
        <div class="col-10">
            <div style="border-bottom: solid 1px black; margin-bottom: 10px;"></div>
            <p class="text-center">Page 1 of 1<br>
                DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
                Website: http://fo11.dswd.gov.ph Tel Nos.: (082)_227 1964 / (082) 227 8746 / (082)_227 1435 Telefax: (082) 226 2857
            </p>
        </div>
        <div class="col-2">
            <img src="cpms/images/dswd-ISO.png" alt="" width="110px" height="60px" style="margin-left: 10px;">
        </div>
    </div>
</div>


	
		<?php }?>
    </body>
</html>