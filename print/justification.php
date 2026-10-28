<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Justification Letter</title>
<link rel="stylesheet" href="cpms/css/coe.css">
<link rel="stylesheet" type="text/css" href="cpms/css/bootstrap.min.css"> 

<html>
    <body>
        <style>
            @page {
                size: A4;
                margin: 20mm 15mm 5mm 15mm;            /* controls the printed page margin */
            }
            * { box-sizing: border-box; }

            .header{
                background-color: black !important;
                color: white;
                -webkit-print-color-adjust: exact;
                font-size: 12px;
            }
            #gisv2_print input{ text-transform:uppercase; }

            .letter-container {
                width: 100%;
                margin: 0 auto;          /* centered, no overflow */
                padding: 20px 30px;
                display: flex;
                flex-direction: column;
            }
            .letter-header {
                text-align: center;
                font-size: 18px;
                margin: 20px 0;
            }
            .letter-body {
                font-size: 16px;
                line-height: 1.6;
            }
            .letter-footer {
                margin-top: auto;        /* pushes footer to the bottom */
                text-align: center;
                font-size: 11px;
            }
            .letter-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .letter-top p { margin: 0; text-align: center; font-family: arial, sans-serif; }
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
<div class="letter-container">
    <div class="letter-top">
        <img src="cpms/images/logo.png" alt="DSWD" width="240" height="70">
        <div style="color:#000; margin-top:40px">
            <p style="font-size:25px; font-weight:bold;">CRISIS INTERVENTION SECTION</p>
            <p style="font-size:17px;">Cor. Suazo St. R. Magsaysay Ave. Davao City</p>
            <p style="font-size:14px;">DSWD-PMB-GF-13 | REV 03 | 14 MAY 2024</p>
        </div>
    </div>

    <div class="letter-header">
        <h3 style="margin-top:40px"><b>JUSTIFICATION LETTER</b></h3>
    </div>

    <div class="letter-body">
        <p style="text-align: justify; margin-top:40px">&emsp;&emsp;This is to justify that the client
            <?php echo strtoupper($fname.' '.$mname.' '.$lname.' '.$ename);?>,
            <?php echo $age;?> years old, residing at
            <?php echo strtoupper($row['barangay'].', '.$row['city_municipality']);?>,
            was interviewed and assessed by the undersigned and found eligible to receive Food Assistance due to difficulty in sustaining their daily basic needs.
            This justification is made to facilitate the provision of assistance through the Assistance to Individuals In Crisis Situation (AICS) Program, aimed at supporting individuals and families in vulnerable situations.
            <br>Done this _____ day of ___________ 2025.
        </p>

        <div style="margin-top: 120px;">
            <label>________________________________</label><br>
            <label>DSWD Social Worker</label>
        </div>
    </div>

    <div class="letter-footer" style="margin-top:770px">
        <div style="border-bottom:solid 1px black;"></div>
        <b>PAGE 1 of 2</b><br>
        DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
        Website: http://fo11.dswd.gov.ph Tel Nos.: (082) 227 1964 / (082) 227 8746 / (082) 227 1435 Telefax: (082) 226 2857
    </div>
</div>


	
		<?php }?>
    </body>
</html>