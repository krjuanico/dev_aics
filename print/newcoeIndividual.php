
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
    @media print {
    .row {
      display: flex !important;
      flex-wrap: nowrap !important;
    }

    .col-md-6, .col-6 {
      width: 50% !important;
      float: left !important;
    }

    .col-3 {
      width: 25% !important;
      float: left !important;
    }

    .col-9 {
      width: 75% !important;
      float: left !important;
    }

    .col-12 {
      width: 100% !important;
      float: left !important;
    }

    /* Prevent Bootstrap from making all columns full-width on print */
    [class*="col-"] {
      display: block !important;
    }
  }
  @media print {
  * {
    background-color: transparent !important;
    color: black !important;
  }

    @page {
        margin-top: 2%;
    }

    .print-page {
        page-break-before: always;
        padding-top: 2%;
    }
    @page {
    size: 8.5in 13in;
    margin: 2%;
  } 
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
			$charging = strtoupper($row['charging']);
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
    <div class="container" id="coev2_print"  style="font-size:12px; padding:0%;margin-left:5%;padding-right:5%;">
        <br>
        <!-- HEADER -->
<div class="row" style="margin-top:1%">
    <div class="col-6">
        <img src="cpms/images/dswd_olog.png" alt="" width="230px" height="60px">
        <img src="cpms/images/AICS.png" alt="" width="70px" height="60px">
        <img src="cpms/images/BP.png" alt="" width="70px" height="60px">
    </div>
    <div class="col-6 ml-md-auto" style="color: #000000;">
        <div class="d-flex justify-content-center align-items-center mb-2">
            <input type="checkbox" class="form-check-input me-2" /><p class="mb-0" style="font-size: 18px; font-family: Arial, sans-serif; margin-left:130">Central Office</p>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <input type="checkbox" class="form-check-input me-2"  checked/><p class="mb-0" style="font-size: 18px; font-family: Arial, sans-serif;margin-left:165">Field Office <u>&nbsp;&nbsp;&nbsp;XI&nbsp;&nbsp;&nbsp;</u></p>
        </div>
    </div>
</div>
<br>
        <div class="row">
            <div class="col-12" style="text-align: center;">
                <b style="font-size: 30px">CERTIFICATE OF ELIGIBILITY</b>
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6"><br>
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
                        
                    <div style="display: flex; gap: 4px;">
                        <div style="text-align: center;">
                            <input disabled type="text" style="width: 50px; font-size: 14px; padding:0; text-indent: 8px; border:1px solid black;" />
                            <div style="font-size: 10px;"><i>MM</i></div>
                        </div>
                        <div style="text-align: center;">
                            <input disabled type="text" style="width: 50px; font-size: 14px; padding:0; text-indent: 8px; border:1px solid black;" />
                            <div style="font-size: 10px;"><i>DD</i></div>
                        </div>
                        <div style="text-align: center;">
                            <input disabled type="text" style="width: 70px; font-size: 14px; padding:0; text-indent: 8px; border:1px solid black;" />
                            <div style="font-size: 10px;"><i>YYYY</i></div>
                        </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--Second Row-->
        <div class="row" style="margin-bottom: 15px;">
             <div class="col-3">
                    <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                        <!--<div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-5 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;AICS</b>
                        </div> -->
                        <!-- AICS row -->
                        <div class="col-sm-1 center">
                            <!-- Show check if charging is AICS -->
                            <input disabled class="text-center" 
                                style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" 
                                type="text" 
                                value="<?php echo (strtoupper($row['charging'])=='AICS') ? '✔' : ''; ?>">
                        </div>
                        <div class="col-sm-5 center" style="padding-top: 3px;">
                            <b class="text-center">&nbsp;AICS</b>
                        </div>
                        <div class="col-sm-1 center">
                        <input disabled class="text-center" style="width: 30px; padding:0; border: 1px solid black; border-radius: 30px;" type="text" value=''>
                    </div>
                    <div class="col-sm-2 center"  style="padding-top: 3px;">
                        <b class="text-center">&nbsp;New</b>
                    </div>
                    </div>
                </div>
            <div class="col-6">
            <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-5 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;Onsite</b>
                        </div>
                        <div class="col-sm-1 center">
                        <input disabled class="text-center" style="width: 30px; padding:0; border: 1px solid black; border-radius: 30px;" type="text" value=''>
                    </div>
                    <div class="col-sm-3 center"  style="padding-top: 3px;">
                        <b class="text-center">&nbsp;Walk-in</b>
                    </div>
                    </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px;">
             <div class="col-3">
                    <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                        
                        <!-- AKAP row -->
                        <div class="col-sm-1 center">
                            <!-- Show check if charging is AKAP -->
                            <input disabled class="text-center" 
                                style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" 
                                type="text" 
                                value="<?php echo (strtoupper($row['charging'])=='AKAP') ? '✔' : ''; ?>">
                        </div>
                        <div class="col-sm-5 center" style="padding-top: 3px;">
                            <b class="text-center">&nbsp;AKAP</b>
                        </div>
                

                        <div class="col-sm-1 center">
                        <input disabled class="text-center" style="width: 30px; padding:0; border: 1px solid black; border-radius: 30px;" type="text" value=''>
                    </div>
                    <div class="col-sm-2 center"  style="padding-top: 3px;">
                        <b class="text-center">&nbsp;Returning</b>
                    </div>
                    </div>
                </div>
            <div class="col-6">
            <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-5 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;Malasakit Center</b>
                        </div>
                        <div class="col-sm-1 center">
                        <input disabled class="text-center" style="width: 30px; padding:0; border: 1px solid black; border-radius: 30px;" type="text" value=''>
                    </div>
                    <div class="col-sm-3 center"  style="padding-top: 3px;">
                        <b class="text-center">&nbsp;Referral</b>
                    </div>
                    </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px;">
             <div class="col-3">
                    <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-6 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;Others:_____</b>
                        </div>
                    </div>
                </div>
            <div class="col-9">
            <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="&#x2714;">
                        </div>
                        <div class="col-sm-4 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;Offsite</b>
                        </div>
                        <div class="col-sm-3 center">
                        <b class="right">Birthdate:</b>
                    </div>
                    <div class="col-sm-4">
                       
                    <div style="display: flex; gap: 4px;">
                        <div style="text-align: center;">
                            <input disabled type="text" style="width: 50px; font-size: 14px; padding:0; text-indent: 8px; border:1px solid black;" value=" <?php echo $row['birth_month'];?>"/>
                            <div style="font-size: 10px;"><i>MM</i></div>
                        </div>
                        <div style="text-align: center;">
                            <input disabled type="text" style="width: 50px; font-size: 14px; padding:0; text-indent: 8px; border:1px solid black;" value=" <?php echo $row['birth_day'];?>"/>
                            <div style="font-size: 10px;"><i>DD</i></div>
                        </div>
                        <div style="text-align: center;">
                            <input disabled type="text" style="width: 70px; font-size: 14px; padding:0; text-indent: 8px; border:1px solid black;" value=" <?php echo $row['birth_year'];?>"/>
                            <div style="font-size: 10px;"><i>YYYY</i></div>
                        </div>
                        </div>
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
				<div class="text-center col-2"><div contenteditable="true" style="word-wrap: break-word; word-break: break-word;width: 350px; font-size: 14px;line-height:12px;">First Name Middle Name Last Name Extension Name</div></div> 
				<div class="col-3"></div>
				<div class="text-center col-1"><div contenteditable="true" style="margin-left:-5px; word-wrap: break-word; word-break: break-word;width: 60px; font-size: 14px;line-height:12px;"> Sex</div></div> 
				<div class="text-center col-1"><div  contenteditable="true" style="margin-left:-5px; word-wrap: break-word; word-break: break-word;width: 60px; font-size: 14px;line-height:12px;"> Age</div></div>
            </div>
			and presently residing at <input disabled class="text-center" style=" border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 18px;  width: 80%;" 
			value="<?php echo $row['purok'].' '.$row['barangay'].', '.$row['city_municipality'].', '.$row['province'];?>" /><br>
            <a style="margin-left: 43%;">Complete Address</a><br> 
            has been found eligible for assistance after assessment and validation conducted, for his/herself or through the representation of his/her <br> 
            <input disabled class="text-center" style=" border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 15px; width: 45%;" value="SELF" />, 
                <input disabled class="text-center" style=" border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 15px; width: 50%;"
                value="" />.<br>
            <div class="row">
				<div class="col-1"></div>
				<div class="text-center col-4"><div contenteditable="true" style="word-wrap: break-word; word-break: break-word;width: 315px; font-size: 14px;line-height:12px;">Relationship of Representative to the Beneficiary</div></div> 
				<div class="col-1"></div>
				<div class="text-center col-4"><div contenteditable="true" style="word-wrap: break-word; word-break: break-word;width: 415px; font-size: 14px;line-height:12px;">First Name Middle Name Last Name Extension Name</div></div>
            </div>
        </div>
        <!-- Checker of Documents Presented -->
        <div class="row">
            <div class="col " style="border: 1px solid black;margin-top: 3px; padding: 0px 0px 4px 0px;">
                <p class="text-center" style="font-size: 15px; border: 1px solid black; background-color: #bfbfbf; font-size:15px; padding:0%; margin-bottom:2%;"><b>Records of the case such as the following are confidentially filed at the Crisis Intervention Program (CIP)</b></p>
                <div class="row" style="font-size: 15px;margin:0px 2px 0px 2px">
                     <div class="col" style="padding-top: 0px; margin-top: -8px;">
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;border:1px solid black;" value="&#x2714;" />&emsp;General Intake Sheet<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;margin-bottom:7px;border:1px solid black;" value="" />&emsp;Justification<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="&#x2714;" />&emsp;Valid I.D. Presented:<br>
                        <p class="text-center" style="width: 100%;height:15px; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;">PRESENTED</p>
                        <!-- <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;4PS DSWD I.D.<br> -->
                    </div>
                    <div class="col" style="padding-top: 0px; margin-top: -8px;">
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;border:1px solid black;" value="" />&emsp;Medical Certificate/Abstract<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Prescriptions<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Statement of Account<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Treatment Protocol<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Quotation/Chargeslip<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;border:1px solid black;" value="" />&emsp;Discharge Summary<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;border:1px solid black;" value="" />&emsp;Social Case Study Report<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;border:1px solid black;" value="" />&emsp;Case Summary Report
                    </div>
                    <div class="col" style="padding-top: 0px; margin-top: -8px;">
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Laboratory Request<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Promissory Note/ <br>&emsp;&emsp;Certificate of Balance<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Funeral Contract<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Transfer Permit<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Death Certificate<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;border:1px solid black;" value="" />&emsp;Death Summary<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Referral Letter
                    </div>
                    <div class="col" style="padding-top: 0px; margin-top: -8px;">
                    <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Contract of Employment<br>
                    <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Certificate of Employment<br>
                    <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Certificate of Attestation<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Income Tax Return<br>
                        <input disabled class="text-center" type="text" style="font-size:18px;width:18px;height:18px;margin-top:1px;border:1px solid black;" value="" />&emsp;Others:<br>
                        <input disabled class="text-center" style="width: 100%;height:15px; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black; margin-bottom: 0%;" type="text" height="30px" value="">
                    </div>
                </div>
            </div>
        </div>


        <div class="fluid-container">
  <div class="row">
    <!-- Outright Cash -->
    <div class="col-md-6 border p-3" >
    <p class="text-center" style="font-size: 15px; border: 1px solid black; background-color: #bfbfbf; font-size:18px; padding:0%; margin-bottom:2%;"><b><i>If Outright Cash</i></b></p>
     
  <!-- First Line --><br>
  <p style="font-size: 14px;">    
  &emsp;&emsp;&emsp;The client is hereby recommended to receive
  </p>
  <div class="mb-2">
  <div class="d-flex align-items-center mb-2">
  <input type="text" style="width: 80%; height: 15px; border-bottom: 1px solid black;" value="<?php echo $row['type_of_assistance'];?>" />
  <span>assistance for</span>
</div>
    <div class="left" style="font-size: 10px;">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Type of assistance: food, medical, cash relief, transportation, others</div>
  </div>

  <!-- Second Line -->
  <div class="mb-2">
  <div class="d-flex align-items-center mb-2">
  <input type="text" style="width: 80%; height: 15px; border-bottom: 1px solid black;" />
  <span>in the amount of</span>
</div>
    <div class="text-center" style="font-size: 10px;">Purpose of assistance</div>
  </div>

  <!-- Amount Line -->
  <div class="d-flex justify-content-between align-items-end mb-2">
    <div style="width: 100%;">
    <div class="d-flex align-items-center mb-2">
    <input class="text-center" type="text" style="width: 100%; height: 15px; border-bottom: 1px solid black;" value="<?php echo numtowords($row['amount']);?> ONLY"/>
</div>
      <div class="text-center" style="font-size: 10px;">Amount in words</div>
    </div>
  </div>
  
  <!-- Payable To -->
  <div class="mb-2 d-flex align-items-end">
    <div style="flex-grow: 1;">
    <div class="d-flex align-items-center mb-2">
    <span>Php</span>
    <input class="text-center" type="text" style="width: 20%; height: 15px; border-bottom: 1px solid black;" value="<?php echo number_format($row['amount'],2);?>" />
</div>
      <div class="left" style="font-size: 10px;">&emsp;&emsp;&emsp;Amount in figures</div>
    </div>
  </div>
    </div>

<!-- Guarantee Letter -->
<div class="col-md-6 border p-3" style="border: 1px solid #000;">
  <p class="text-center" style="font-size: 18px; background-color: #bfbfbf; margin-bottom: 10px; border: 1px solid black;">
    <b><i>If Guarantee Letter</i></b>
  </p>

  <!-- GL Number -->
  <div class="d-flex justify-content-end align-items-center mb-2">
    <label class="me-2"><b>GL No.</b></label>
    <input type="text" class="form-control" style="width: 150px; height: 24px; padding: 0 5px;" />
  </div>

  <!-- First Line -->
  <p style="font-size: 14px;">    
  &emsp;&emsp;&emsp;The client is hereby recommended to receive
  </p>
  <div class="mb-2">
  <div class="d-flex align-items-center mb-2">
  <input type="text" style="width: 85%; height: 15px; border-bottom: 1px solid black;" />
  <span>assistance for</span>
</div>
    <div class="left" style="font-size: 10px;">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Type of assistance: medical, burial, others</div>
  </div>

  <!-- Second Line -->
  <div class="mb-2">
  <div class="d-flex align-items-center mb-2">
  <input type="text" style="width: 83%; height: 15px; border-bottom: 1px solid black;" />
  <span>in the amount of</span>
</div>
    <div class="left" style="font-size: 10px;">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Purpose of assistance</div>
  </div>

  <!-- Amount Line -->
  <div class="d-flex justify-content-between align-items-end mb-2">
    <div style="width: 80%;">
    <div class="d-flex align-items-center mb-2">
    <input type="text" style="width: 100%; height: 15px; border-bottom: 1px solid black;" />
  <span>&nbsp;PhP</span>
</div>
      <div class="text-center" style="font-size: 10px;">Amount in words</div>
    </div>
    <div style="width: 20%;">
    <input type="text" style="width: 100%; height: 15px; border-bottom: 1px solid black;" />
      <div class="left" style="font-size: 10px;">&emsp;Amount in figures</div>
    </div>
  </div>

  <!-- Payable To -->
  <div class="mb-2 d-flex align-items-end">
    <div style="flex-grow: 1;">
    <div class="d-flex align-items-center mb-2">
    <span>payable to</span>
    <input type="text" style="width: 87%; height: 15px; border-bottom: 1px solid black;" />
</div>
      <div class="text-center" style="font-size: 10px;">Name of Service provider</div>
    </div>
  </div>

  <!-- Address -->
  <div>
  <input type="text" style="width: 100%; height: 15px; border-bottom: 1px solid black;" />
    <div class="text-center" style="font-size: 10px;">Address of Service provider</div>
  </div>
</div>


 
</div>
 <!-- Signatures -->
 <div class="row mt-5">
    <div class="col-md-6 text-center">
      <label class="fw-bold">Prepared and certified by:</label>
      <hr style="width: 80%;">
      <p class="mb-0">Social Worker</p>
      <small>(Signature over Printed Name)</small>
      <br>
      <label>License Number: 
      <input type="text" style="height: 15px; border-bottom: 1px solid black;" /></label>
    </div>
    <div class="col-md-6 text-center">
      <p class="fw-bold">Approved by:</p>
      <hr style="width: 80%;"><?php echo strtoupper($head);?>
      <p class="mb-0">Approving Authority</p>
      <small>(Signature over Printed Name)</small>
    </div>
  </div>
<br>
        <!-- Acknowledgement -->
        <p class="text-center" style="font-size: 15px; border: 1px solid black; background-color: #bfbfbf; font-size:15px; padding:0%; margin-bottom:2%;"><b>ACKNOWLEDGEMENT RECEIPT</b></p>
        <div class="row">
            <div class="col-12">
                <div class="row right" style="font-size: 15px; margin-top: -5px">
                    <div class="col-sm-2 center">
                        <p class="right">Date:</p>
                    </div>
                    <div class="col-sm-10">
                        <div style="display: flex; gap: 4px;">
                        <div style="text-align: center;">
                            <input disabled type="text" style="width: 50px; font-size: 14px; padding:0; text-indent: 8px; border:1px solid black;" />
                            <div style="font-size: 10px;"><i>MM</i></div>
                        </div>
                        <div style="text-align: center;">
                            <input disabled type="text" style="width: 50px; font-size: 14px; padding:0; text-indent: 8px; border:1px solid black;" />
                            <div style="font-size: 10px;"><i>DD</i></div>
                        </div>
                        <div style="text-align: center;">
                            <input disabled type="text" style="width: 70px; font-size: 14px; padding:0; text-indent: 8px; border:1px solid black;" />
                            <div style="font-size: 10px;"><i>YYYY</i></div>
                        </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
		<div class="row" style="font-size: 15px; margin-top: -5px">
			<div class="col-12 center">
				I acknowledged receipt of assistance in the amount of
				<input disabled class="text-center" type="text" style="font-size:18px;width:48%;height:18px;margin-top:6px; border-bottom:1px solid black;" value="<?php echo numtowords($row['amount']);?> ONLY" />
				PHP <input disabled class="text-center" style="border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 15px; width: 15%;" value="<?php echo number_format($row['amount'],2);?>" />. <br>
				
				</div>
			</div>
        </div><br>
        <!-- Signatory -->
        <div class="row" style="font-size: 15px;">
            <div class="col">
                
            </div>
			<div class="col text-center"> 
                <b>Received by:</b>
            </div>
            <div class="col">
            </div>
        </div><br>
        <div class="row" style="font-size: 15px; line-height: 15px;">

            <div class="col text-center">
                <input disabled class="text-center" style="width: 75%; font-size: 15px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($first_name.' '.$middle_name.' '.$last_name.' '.$extension_name);?>" >
                <!-- <b>RDO/SDO</b> -->
				<br>Client<br>
                <span style="font-size: 10px;">(Signature Over Printed Name)</span>
            </div>
        
        </div>

       
        <div class="footer row">
            <div class="col-12">
                <div style="border-bottom: solid 1px black;"></div>
                <p class="text-center">Page 1 of 1<br>
                DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
                Website: http://fo11.dswd.gov.ph Tel Nos.: (082)_227 1964 / (082) 227 8746 / (082)_227 1435 Telefax: (082) 226 2857</p>
            </div>
        </div>
                        <!--Container-->
	</div>
			<?php }?>
</body>

</html>