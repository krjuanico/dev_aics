
<link rel="stylesheet" href="cpms/css/coe.css">
<link rel="stylesheet" type="text/css" href="cpms/css/bootstrap.min.css"> 
<html>
<body>
<style>
	.header{
		background-color: black !important;
		color: white;
		-webkit-print-color-adjust: exact; 
		font-size: 14px;
	}

	
 </style>
 <?php
		error_reporting(0);
		session_start();
		include "../dbconnect.php";
		error_reporting(0);
		$head = "";
		$emm=$_SESSION['userid'];
		$user_type = $_SESSION['user_type'];
		$sql2 = "SELECT head FROM lib_users as a
				INNER JOIN lib_area as b ON a.areaassigned = b.id 
				where empid='$emm'";
		$result2 = mysqli_query($conn, $sql2);	
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$head = $row2['head'];
		}
        function numtowords($num, $c=1) {
            $ZERO = 'zero';
            $MINUS = 'minus';
            
            $lowName = array(
                "", "ONE", "TWO", "THREE", "FOUR", "FIVE",
                "SIX", "SEVEN", "EIGHT", "NINE", "TEN",
                "ELEVEN", "TWELVE", "THIRTEEN", "FOURTEEN", "FIFTEEN",
                "SIXTEEN", "SEVENTEEN", "EIGHTEEN", "NINETEEN"
            );
           
            $tys = array(
                "", "", "TWENTY", "THIRTY", "FORTY", "FIFTY",
                "SIXTY", "SEVENTY", "EIGHTY", "NINETY"
            );
           
            $groupName = array(
                "", "HUNDRED", "THOUSAND", "MILLION", "BILLION",
                "TRILLION", "QUADRILLION", "QUINTILLION"
            );
           
            $divisor = array(
                100, 10, 1000, 1000, 1000, 1000, 1000, 1000
            );
        
            $num = str_replace(",", "", $num);
            $num = number_format($num, 2, '.', '');
            $cents = substr($num, strlen($num) - 2, strlen($num) - 1);
            $num = (int)$num;
            
            if ($num == 0) return $ZERO;
            
            $negative = ($num < 0);
            if ($negative) $num = -$num;
        
            $s = "";
            
            for ($i = 0; $num > 0; $i++) {
                $remdr = (int)($num % $divisor[$i]);
                $num = (int)($num / $divisor[$i]);
        
                if ($remdr == 0) continue;
        
                $t = "";
                if ($remdr < 20) {
                    $t = $lowName[$remdr];
                } elseif ($remdr < 100) {
                    $units = (int) $remdr % 10;
                    $tens = (int) $remdr / 10;
                    $t = $tys[$tens];
                    if ($units != 0) {
                        $t .= "-" . $lowName[$units];
                    }
                } else {
                    $t = numtowords($remdr, 0);
                }
        
                $s = trim($t . " " . $groupName[$i] . " " . $s);
            }
        
            if ($negative) {
                $s = $MINUS . " " . $s;
            }
        
            return trim($s);
        }


		// Retrieve the payroll number
		$beneId = $_GET['beneId'];
			$sql = "SELECT * FROM aics_clean_list
				WHERE control_number='$beneId'";
		$result = mysqli_query($conn, $sql);
		if ($row = mysqli_fetch_assoc($result)) {
			$first_name = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['first_name']);
			$middle_name = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['middle_name']);
			$last_name = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['last_name']);    
			$extension_name = $row['extension_name'];   
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
				
?>
    <div class="container" id="coev2_print" style="font-size:12px; padding:0%;">
        <br>
        <!--HEADER-->
        <div class="row" style="margin-top:5%">
            <div class="col-6">
                <img src="cpms/images/dswd_olog.png" alt="" width="230px" height="60px">
                <img src="cpms/images/AICS.png" alt="" width="70px" height="60px">
                <img src="cpms/images/BP.png" alt="" width="70px" height="60px">
            </div>
            <div class="col-6 ml-md-auto" style="color: #000000; ">
                <p class="text-center" style="font-size: 30px; font-family: arial, sans-serif; font-weight: bold;">CRISIS INTERVENTION SECTION</p><br>
                <p class="text-center" style="font-size: 17px; font-family: arial, sans-serif; margin-top: -45px;">Cor. Suazo St. R. Magsaysay Ave. Davao City</p><br>
                <p class="text-center" style="font-size: 14px; font-family: arial, sans-serif; margin-top: -40px;">DSWD-PMB-GF-13 | REV 03 | 14 MAY 2024</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12" style="text-align: center;">
                <b style="font-size: 30px">CERTIFICATE OF ELIGIBILITY</b>
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        <p style="font-size: 18px; margin-top: -10px;">(Cash Outright)</p>
                    </div>
                    <div class="col-3"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="row" style="font-size: 15px; margin-top: -5px">
                    <div class="col-sm-1 center">
                        <p class="text-center">QN:</p>
                    </div>
                    <div class="col-sm-10">
                        <input disabled class="form-control input-lg border-dark" style="font-size: 15px; padding:0; text-indent: 8px; border: 1px solid black;" type="text" value="<?php echo $row['id'];?>">
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="row" style="font-size: 15px; margin-top: -5px">
                    <div class="col-sm-1 center" style="margin-right: 5px;">
                        <p class="text-center">PCN:</p>
                    </div>
                    <div class="col-sm-10">
                        <input disabled class="text-center" style="width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                        <input disabled class="text-center" style="margin-left:-5; width: 18px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value=''>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="row" style="font-size: 15px; margin-top: -5px">
                    <div class="col-sm-3 center">
                        <p class="text-center">Date:</p>
                    </div>
                    <div class="col-sm-9">
                        <input disabled class="text-center" style="width: 62px; font-size: 15px; padding:0; border: 1px solid black;" type="text" value=''><!-- Month -->
                        <input disabled class="text-center" style="width: 65px; font-size: 15px; padding:0; border: 1px solid black;" type="text" value=''><!-- Day -->
                        <input disabled class="text-center" style="width: 90px; font-size: 15px; padding:0; border: 1px solid black;" type="text" value=''><!-- Year -->
                    </div>
                </div>
            </div>
        </div>
        <!--Second Row-->
        <div class="row" style="margin-bottom: 15px;">
             <div class="col-2">
                    <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                    <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="<?php if(strtoupper($row['charging'])=='AICS') { echo "&#x2714";}?>;">
                        </div>
                        <div class="col-sm-3 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;AICS</b>
                        </div>
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="<?php if(strtoupper($row['charging'])=='AKAP') { echo "&#x2714";}?>">
                        </div>
                        <div class="col-sm-3 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;AKAP</b>
                        </div>
                    </div>
                </div>
            <div class="col-2">
                <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                    <div class="col-sm-1 center">
                        <input disabled class="text-center" style="width: 30px; padding:0; border: 1px solid black;" type="text" value=''>
                    </div>
                    <div class="col-sm-3 center"  style="padding-top: 3px;">
                        <b class="text-center">&nbsp;New</b>
                    </div>
                    <div class="col-sm-1 center">
                        <input disabled class="text-center" style="width: 30px; padding:0; border: 1px solid black;" type="text" value=''>
                    </div>
                    <div class="col-sm-4 center"  style="padding-top: 3px;">
                        <b class="text-center">&nbsp;Returning</b>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                    <div class="col-sm-1 center">
                    </div>
                    <div class="col-sm-1 center">
                        <input disabled class="text-center" style="width: 30px; padding:0; border: 1px solid black; border-radius: 30px;" type="text" value=''>
                    </div>
                    <div class="col-sm-7 center"  style="padding-top: 3px;">
                        <b class="text-center">&nbsp;On-site</b>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="row" style="font-size: 14px; margin-top: -5px">
                    <div class="col-sm-1 center">
                        <input disabled class="text-center" style="width: 30px; padding:0; border: 1px solid black;" type="text" value=''>
                    </div>
                    <div class="col-sm-4 center"  style="padding-top: 3px;">
                        <b class="text-center">&nbsp;Walk-in</b>
                    </div>
                    <div class="col-sm-1 center">
                        <input disabled class="text-center" style="width: 30px; padding:0; border: 1px solid black;" type="text" value=''>
                    </div>
                    <div class="col-sm-4 center"  style="padding-top: 3px;">
                        <b class="text-center">&nbsp;Referral</b>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="row" style="font-size: 14px; margin-top: -5px">
                    <div class="col-sm-1 center">
                        <input disabled class="text-center" style="width: 30px; padding:0; border: 1px solid black; border-radius: 30px;" type="text" value='&#x2714;'>
                    </div>
                    <div class="col-sm-4 center" style="padding-top: 3px;">
                        <b class="text-center">&nbsp;Off-site</b>
                    </div>
                    <div class="col-sm-1 center">
                        <input disabled class="text-center" style="width: 30px; padding:0; border: 1px solid black; border-radius: 30px;" type="text" value=''>
                    </div>
                    <div class="col-sm-4 center" style="padding-top: 3px;">
                        <b class="text-center">&nbsp;Malasakit</b>
                    </div>
                </div>
            </div>
            
        </div>

		
        <!--Assistance-->
        <div style="margin-top: 5px; font-size: 15px; text-transform: justify">
            This is to certify that, <input disabled class="text-center" style=" border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 18px; width: 62%;" value="<?php echo strtoupper($first_name.' '.$middle_name.' '.$last_name.' '.$extension_name);?>" />, 
            <input disabled class="text-center" style=" border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 18px; width: 8%;" value="<?php echo strtoupper($row['sex']);?>" />, 
            <input disabled class="text-center" style=" border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 18px; width: 8%;" value="<?php echo strtoupper($age);?>" /> year/s<br>
            <div class="row">
				<div class="col-4"></div>
				<div class="text-center col-2"><div contenteditable="true" style="word-wrap: break-word; word-break: break-word;width: 150px; font-size: 14px;line-height:12px;"><b>Kumpletong Pangalan</b> (Complete Name)</div></div> 
				<div class="col-3"></div>
				<div class="text-center col-1"><div contenteditable="true" style="margin-left:-5px; word-wrap: break-word; word-break: break-word;width: 60px; font-size: 14px;line-height:12px;"><b>Kasarian</b> (Sex)</div></div> 
				<div class="text-center col-1"><div  contenteditable="true" style="margin-left:-5px; word-wrap: break-word; word-break: break-word;width: 60px; font-size: 14px;line-height:12px;"><b>Edad</b> (Age)</div></div>
            </div>
			and presently residing at <input disabled class="text-center" style=" border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 18px;  width: 80%;" 
			value="<?php echo $row['purok'].' '.$row['barangay'].', '.$row['city_municipality'].', '.$row['province'];?>" /><br>
            <a style="margin-left: 43%;"><b>Kumpletong Tirahan </b>(Complete Address)</a><br> 
            has been found eligible for assistance after assessment and validation conducted, for his/herself or through the representation of his/her <br> 
            <input disabled class="text-center" style=" border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 20px; width: 45%;" value="SELF" />, 
                <input disabled class="text-center" style=" border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 20px; width: 50%;"
                value="" />.<br>
            <div class="row">
				<div class="col-1"></div>
				<div class="text-center col-4"><div contenteditable="true" style="word-wrap: break-word; word-break: break-word;width: 320px; font-size: 14px;line-height:12px;"><b>Relasyon ng Kinatawan sa Benepisyaryo</b> (Relationship of Representative to the Beneficiary)</div></div> 
				<div class="col-1"></div>
				<div class="text-center col-4"><div contenteditable="true" style="word-wrap: break-word; word-break: break-word;width: 420px; font-size: 14px;line-height:12px;"><b>Buong Pangalan ng Benepisyaryo</b> (Name of Beneficiary)</div></div>
            </div>
        </div>

        <!-- Checker of Documents Presented -->
        <div class="row">
            <div class="col " style="border: 1px solid black;margin-top: 3px; padding: 0px 0px 4px 0px;">
                <p class="text-center" style="font-size: 15px; border: 1px solid black; background-color: #bfbfbf; font-size:15px; padding:0%; margin-bottom:2%;">RECORDS OF THE CASE SUCH AS THE FOLLOWING ARE CONFIDENTIALLY FILED AT THE CRISIS INTERVENTION SECTION</p>
                <div class="row" style="font-size: 15px;margin:0px 2px 0px 2px">
                     <div class="col" style="padding-top: 0px; margin-top: -8px;">
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;border:1px solid black;" value="&#x2714;" />&emsp;General Intake Sheet<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;margin-bottom:7px;border:1px solid black;" value="&#x2714;" />&emsp;Justification<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="&#x2714;" />&emsp;Valid I.D. Presented:<br>
                        <p class="text-center" style="width: 100%;height:20px; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;">PRESENTED</p>
                        <!-- <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;4PS DSWD I.D.<br> -->
                    </div>
                    <div class="col" style="padding-top: 0px; margin-top: -8px;">
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;border:1px solid black;" value="" />&emsp;Medical Certificate/Abstract<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Prescriptions<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Statement of Account<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Treatment Protocol<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Quotation/Chargeslip<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;border:1px solid black;" value="" />&emsp;Discharge Summary
                    </div>
                    <div class="col" style="padding-top: 0px; margin-top: -8px;">
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Laboratory Request<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Promissory Note<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Funeral Contract<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Death Certificate<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;border:1px solid black;" value="" />&emsp;Death Summary<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Referral Letter
                    </div>
                    <div class="col" style="padding-top: 0px; margin-top: -8px;">
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="&#x2714;" />&emsp;Social Case Study Report<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Contract of Employment<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Certificate of Employment<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Income Tax Return<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Others:<br>
                        <input disabled class="text-center" style="width: 100%;height:20px; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black; margin-bottom: 0%;" type="text" height="30px" value="">
                    </div>
                </div>
            </div>
        </div>


        <!--Assistance-->
        <div style="margin-top: 10px; font-size: 14px; text-align:justify;">
            The client is hereby recommended to receive <input disabled class="text-center" style="border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 20px; width: 25%;" value="FINANCIAL" /> assistance for <input disabled class="text-center" style="border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 20px; width: 35%; text-transform: capitalize;" value="DAILY CONSUMPTION" /><br> 
			in the amount of <input disabled class="text-center" style="border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 20px; width: 55%;" value="<?php echo numtowords($row['amount']);?> ONLY"
            /> PHP <input disabled class="text-center money" style="border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 20px; width: 8%;" value="<?php echo number_format($row['amount'],2);?>" />CHARGABLE AGAINST: PSP 
			<input disabled class="text-center" style="border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 20px; width: 13%;" value="AICS 2025" />
        </div>
<br><br>
        <!-- Signatory -->
        <div class="row" style="font-size: 17px;">
            <div class="col">
                Conforme:
            </div>
            <div class="col">
                Prepared by:
            </div>
            <div class="col">
                Approved by:
            </div>
        </div><br><br><br>
        <div class="row" style="font-size: 15px; line-height: 15px;">
            <div class="col text-center">
                <input disabled class="text-center" style="width: 100%; font-size: 15px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value='<?php echo strtoupper($first_name.' '.$middle_name.' '.$last_name.' '.$extension_name);?>'>
                <b>Beneficiary/Representative</b>
                <p>Signature Over Printed Name</p>
            </div>
            <div class="col text-center">
                <input disabled class="text-center" style="width: 100%; font-size: 15px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value=''>
                <b>Social Worker</b>
                <p>Signature Over Printed Name</p>
            </div>
            <div class="col text-center">
                <input disabled class="text-center" style="width: 100%; font-size: 15px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value='<?php echo strtoupper($head);?>'>
                <!-- <b>CID/CIU/CIS/SWTL</b> -->
                <b>CIS HEAD</b>
                <p>Signature Over Printed Name</p>
            </div>
        </div><br>

        <!-- Acknowledgement -->
        <p class="header text-center" style="margin-top: -5px; margin-left:-15px; font-size: 15px;"><b>Acknowledgement Receipt</b></p>
        <div class="row">
			<div class="col-9"></div>
            <div class="col-3">
                <div class="row" style="font-size: 15px; margin-top: -5px">
                    <div class="col-sm-2 center">
                        <p class="text-center">Date:</p>
                    </div>
                    <div class="col-sm-10">
                        <input disabled class="" style="width: 50px; font-size: 14px; padding:0; text-indent: 8px; border:1px solid black;" type="text" height="30px" width="100px" value=''><!-- Month -->
                        <input disabled class="" style="width: 50px; font-size: 14px; padding:0; text-indent: 8px; border:1px solid black;" type="text" height="30px" width="100px" value=''><!-- Day -->
                        <input disabled class="" style="width: 70px; font-size: 14px; padding:0; text-indent: 8px; border:1px solid black;" type="text" height="30px" width="100px" value=''><!-- Year -->
                    </div>
                </div>
            </div>
        </div>
		<div class="row" style="font-size: 15px; margin-top: -5px">
			<div class="col-12 center">
				<input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:6px; border:1px solid black;" value="&#x2714;" />&emsp;Financial Assistance : 
				<input disabled class="text-center" type="text" style="font-size:18px;width:60%;height:18px;margin-top:6px; border-bottom:1px solid black;" value="<?php echo numtowords($row['amount']);?> ONLY" />
				PHP <input disabled class="text-center" style="border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 20px; width: 15%;" value="<?php echo number_format($row['amount'],2);?>" /> <br>
				<div style="font-size: 15px; margin-top:10px; margin-left:30px;">
					<div class="row">
						<div class="col-3">
							<a style=""></a>
							<input disabled style="margin-left:0; width: 20px; padding:0; text-indent: 2px;" type="radio" height="30px" width="100px" value='1 '  <?php if(strtoupper($row['type_of_assistance'])=='MEDICAL ASSISTANCE') echo "checked";?>> Medical Assistance
						</div>
						<div class="col-3">
							<a style=""></a>
							<input disabled style="margin-left:0; width: 20px; padding:0; text-indent: 2px;" type="radio" height="30px" width="100px" value='1 '  <?php if(strtoupper($row['type_of_assistance'])=='TRANSPORTATION ASSISTANCE') echo "checked";?>> Transportation Assistance
						</div>
						<div class="col-3">
							<a style=""></a>
							<input disabled style="margin-left:0; width: 20px; padding:0; text-indent: 2px;" type="radio" height="30px" width="100px" value='1 '  <?php if(strtoupper($row['type_of_assistance'])=='FOOD ASSISTANCE') echo "checked";?>> Food Assistance
						</div>
					</div>
					<div class="row">
						<br>
						<div class="col-3">
							<a style=""></a>
							<input disabled style="margin-left:0; width: 20px; padding:0; text-indent: 2px;" type="radio" height="30px" width="100px" value='1 '  <?php if(strtoupper($row['type_of_assistance'])=='FUNERAL ASSISTANCE') echo "checked";?>> Funeral Assistance
						</div>
						<div class="col-3">
							<a style=""></a>
							<input disabled style="margin-left:0; width: 20px; padding:0; text-indent: 2px;" type="radio" height="30px" width="100px" value='1 ' <?php if(strtoupper($row['type_of_assistance'])=='EDUCATIONAL ASSISTANCE') echo "checked";?>> Educational Assistance
						</div>
						<div class="col-4">
							<a style=""></a>
							<input disabled style="margin-left:0; width: 20px; padding:0; text-indent: 2px;" type="radio" height="30px" width="100px" value='1 '  <?php if(strtoupper($row['type_of_assistance'])=='CASH RELIEF ASSISTANCE') echo "checked";?>> Cash Relief Assistance
						</div>
					</div>
				</div>
			</div>
        </div><br><br>
		
        <!-- Signatory -->
        <div class="row" style="font-size: 15px;">
            <div class="col">
                Accepted by:
            </div>
			<div class="col"> 
                Paid by:
            </div>
            <div class="col">
                Witnessed by:
            </div>
        </div><br><br>
        <div class="row" style="font-size: 15px; line-height: 15px;">
            <div class="col text-center">
                <input disabled class="text-center" style="width: 100%; font-size: 15px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value='<?php echo strtoupper($first_name.' '.$middle_name.' '.$last_name.' '.$extension_name);?>'>
                <b>Beneficiary/Representative</b>
                <p>Signature Over Printed Name</p>
            </div>
            <div class="col text-center">
                <input disabled class="text-center" style="width: 100%; font-size: 15px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value=''>
                <!-- <b>RDO/SDO</b> -->
				<b>Special Disbursing Officer</b>
                <p>Signature Over Printed Name</p>
            </div>
            <div class="col text-center">
                <input disabled class="text-center" style="width: 100%; font-size: 15px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value=''>
                <!-- <b>SWO/Admin</b> -->
                <b>Social Worker</b>
                <p>Signature Over Printed Name</p>
            </div>
        </div><br>

        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
        <div class="row">
            <div class="col-6">
            </div>
            <div class="col-6">
            <p style="text-align: right; margin-top: -15px; font-size: 13px;">*E.O 163 series 2022</p>
            </div>
        </div>
        <div class="footer row">
            <div class="col-10">
                <div style="border-bottom: solid 1px black;"></div>
                <p class="text-center">Page 1 of 1<br>
                DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
                Website: http://fo11.dswd.gov.ph Tel Nos.: (082)_227 1964 / (082) 227 8746 / (082)_227 1435 Telefax: (082) 226 2857</p>
            </div>
            <div class="col-2">
                <img src="cpms/images/dswd-ISO.png" alt="" width="110px" height="60px">
            </div>
        </div>
                        <!--Container-->
	</div>
			<?php }?>
</body>

</html>