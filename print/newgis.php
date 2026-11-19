
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
            padding: 5px;
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
        .col-md-6 {
        border: 1px solid #ccc;
        padding: 0px;
        width: 2000px;
        background-color: #fff;
    }  
    .col6 {
        padding: 0px;
        background-color: #fff;
    }  
    .col-md-12 {
        border: 1px solid #ccc;
        padding: 0px;
        background-color: #fff;
    }

    .section-title {
    background-color: #333333; /* Dark gray, almost black */
    color: #ffffff; /* White text */
    padding: 8px 12px;
    font-weight: bold;
    text-transform: uppercase;
    width: 100%;
    display: block;
    margin-bottom: 10px;
    border-radius: 0;
    border: 1px solid #000; /* Optional: for the defined outline */
}
.form-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: -1px;
    }

    .form-table td, .form-table th {
      vertical-align: top;
      padding: 10px;
    }

    .form-table input[type="text"] {
      border: none;
      border-bottom: 1px solid #000;
      width: 100px;
    }

    .checkbox-group {
      margin-bottom: 5px;
    }

    .sub-title {
      font-weight: bold;
      font-style: italic;
      margin-bottom: 5px;
    }

    .source-income {
      margin-top: 10px;
    }

    .source-income td {
      padding: 4px 8px;
    }

    .bold-label {
      font-weight: bold;
    }

    .amount-input {
      width: 80px;
      border: none;
      border-bottom: 1px solid #000;
    }

    .full-width {
      width: 100%;
      padding: 10px;
    }
        @media print {
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
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '4096M'); // Increase to 256MB or more
		include "../dbconnect.php";
		session_start();
		error_reporting(0);
		$head = "";
		$emm=$_SESSION['userid'];
		$user_type = $_SESSION['user_type'];
		
			$beneId = $_GET['beneId'];
			
		$sql2 = "SELECT head FROM lib_users as a
				INNER JOIN lib_area as b ON a.areaassigned = b.id 
				where empid='$emm'";
		$result2 = mysqli_query($conn, $sql2);	
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$head = $row2['head'];
		}
		$row['age'] =0;
		// Retrieve the payroll number
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
		?>

        <div class="fluid-container" id="gisv2_print" style="font-size:12px; padding:0%;margin-left:5%;margin-top:5%;padding-right:5%;">
            
            <!--HEADER-->
            <div class="row" >
                <div class="col-6">
                    <img src="cpms/images/dswd_olog.png" alt="" width="230px" height="60px">
                    <img src="cpms/images/AICS.png" alt="" width="70px" height="60px">
                    <img src="cpms/images/BP.png" alt="" width="70px" height="60px">
                </div>
            </div><center>
            <p class="header" style="margin-top: 20px; margin-left: -14px; margin-right: -15px; font-size: 18px;"><b>GENERAL INTAKE SHEET</b> </p>
            </center>
            
            <div class="row" style="margin-top: -18px; padding: 0%;">
                <div class="col-2 text-center" style="border: 1px solid black; background-color: #ffff; font-size:14px; padding: 2%;">
                    <!--
                    <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-4 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;AICS</b>
                        </div>
                    </div>
                    <div class="row" style="font-size: 14px; padding:0; margin-top: 1px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value='&#x2714;'>
                        </div>
                        <div class="col-sm-4 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;AKAP</b>
                        </div>
                    </div> -->
                   <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" 
                                style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" 
                                type="text" 
                                value="<?php echo ($charging == 'AICS') ? '✔' : ''; ?>">
                        </div>
                        <div class="col-sm-4 center" style="padding-top: 3px;">
                            <b class="text-center">&nbsp;AICS</b>
                        </div>
                    </div>

                    <div class="row" style="font-size: 14px; padding:0; margin-top: 1px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" 
                                style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" 
                                type="text" 
                                value="<?php echo ($charging == 'AKAP') ? '✔' : ''; ?>">
                        </div>  
                            <div class="col-sm-4 center" style="padding-top: 3px;">
                            <b class="text-center">&nbsp;AKAP</b>
                        </div>
                    </div>
                    
                    <div class="row" style="font-size: 14px; padding:0; margin-top: 1px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-4 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;Others:________</b>
                        </div>
                    </div>
                </div>
                <div class="col-2 text-center" style="border: 1px solid black; background-color: #ffff; font-size:14px; padding: 2%;">
                    <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black; border-radius: 30px;" type="text" value="">
                        </div>
                        <div class="col-sm-4 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;New</b>
                        </div>
                    </div>
                    <div class="row" style="font-size: 14px; padding:0; margin-top: 1px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black; border-radius: 30px;" type="text" value="">
                        </div>
                        <div class="col-sm-4 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;Returning</b>
                        </div>
                    </div>
                </div>
                <div class="col-3 text-center" style="border: 1px solid black; background-color: #ffff; font-size:14px; padding: 2%;">
                <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-4 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;Onsite</b>
                        </div>
                    </div>
                <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-4 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;Malasakit</b>
                        </div>
                    </div>
                    <div class="row" style="font-size: 14px; padding:0; margin-top: 1px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="&#x2714;">
                        </div>
                        <div class="col-sm-4 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;Offsite</b>
                        </div>
                    </div>
                </div>
                <div class="col-5 text-center" style="border: 1px solid black; background-color: #ffff; font-size:14px; padding: 2%;">
                <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black; border-radius: 30px;" type="text" value="">
                        </div>
                        <div class="col-sm-4 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;Walk-in</b>
                        </div>
                    </div>
                    <div class="row" style="font-size: 14px; padding:0; margin-top: 1px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black; border-radius: 30px;" type="text" value="">
                        </div>
                        <div class="col-sm-4 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;Referral</b>
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
                </div>
            </div>

            <div class="row">
                <div class="col-12 " style="border: 1px solid black; font-size:14px;line-height: 12px;"> <br>
                         
CLIENTS NAME
            <div class="row" style="margin-top: 10px;">
                <div class="col-4">
                    <input disabled class="text-center" style="width: 100%; height:100%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="20px" value="<?php echo strtoupper($lname);?>">
                </div>
                <div class="col-4">
                    <input disabled class="text-center" style="width: 100%; height:100%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="20px" value="<?php echo strtoupper($fname);?>">
                </div>
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:100%; font-size: 12px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="20px" value="<?php echo strtoupper($mname);?>">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:100%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="20px" value="<?php echo strtoupper($ename);?>">
                </div>
            </div>
            <div class="row" style="margin-top: 1px;">
                <div class="col-4">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Apelyido</b> (Last Name)</p>
                </div>
                <div class="col-4">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Unang Pangalan</b> (First Name)</p>
                </div>
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Gitnang Pangalan</b> (Middle Name)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Ext.</b> (Sr.,Jr.)</p>
                </div>
            </div>
            
            <div class="form-group row align-items-center">
  <label class="col-auto col-form-label font-weight-bold">BENEFICIARY'S NAME</label>
  
  <div class="col-auto d-flex align-items-center">
    <input type="checkbox" class="form-control mr-2" style="width: 10px; height: 10px;">
    <span>same as above</span>
  </div>
</div>
            <!--BENEFICIARY INFO-->
            

            <div class="row" style="margin-top:5px; ">
                <div class="col-4">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value="">
                </div>
                <div class="col-4">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value="">
                </div>
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value="">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value="">
                </div>
            </div>
            <div class="row" style="margin-top: 1px;">
                <div class="col-4">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Apelyido</b> (Last Name)</p>
                </div>
                <div class="col-4">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Unang Pangalan</b> (First Name)</p>
                </div>
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Gitnang Pangalan</b> (Middle Name)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Ext.</b> (Sr.,Jr.)</p>
                </div>
            </div>
            
            </div>
            
            </div>

            <div class="row">
                <div class="col-12 " style="border: 1px solid black; font-size:14px;line-height: 12px;">
            <div class="row" style="margin-top: 10px;">
                <div class="col-12">
                    <div class="mb-2 d-flex align-items-end">
                        <div style="flex-grow: 1;">
                        <div class="d-flex align-items-center mb-2">
                        <span>PURPOSE OF ASSISTANCE:</span>
                        <input type="text" style="width: 70%; height: 20px; border-bottom: 1px solid black;" />
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-12">
                    <div class="mb-2 d-flex align-items-end">
                        <div style="flex-grow: 1;">
                        <div class="d-flex align-items-center mb-2">
                        <span>DIAGNOSIS/CAUSE OF DEATH (If funeral):</span>
                        <input type="text" style="width: 70%; height: 20px; border-bottom: 1px solid black;" />
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                &emsp;MODE OF ASSISTANCE :&emsp;
            
                <div class="row" style="font-size: 11px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 16px; font-size: 12px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value='&#x2714;'>
                        </div>
                        <div class="col-sm-9 center"  style="padding-top: 1px;">
                            <b class="text-center">&nbsp;Outright Cash</b>
                        </div>
                    </div>
                    &emsp;
                <div class="row" style="font-size: 11px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 16px; font-size: 12px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-9 center"  style="padding-top: 1px;">
                            <b class="text-center" style="width: 18px; font-size: 12px;">&nbsp;Guarantee Letter</b>
                        </div>
                    </div>
                    &emsp;
                <div class="row" style="font-size: 11px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 16px; font-size: 12px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-10 center"  style="padding-top: 1px;">
                            <b class="text-center" style="width: 18px; font-size: 12px;">&nbsp;Material Assistance</b>
                        </div>
                    </div>
                    &emsp;
                <div class="row" style="font-size: 11px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 16px; font-size: 12px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-10 center"  style="padding-top: 1px;">
                            <b class="text-center"  style="width: 18px; font-size: 10px;">&nbsp;Pychological Support</b>
                        </div>
                    </div>
                    &emsp;
                <div class="row" style="font-size: 11px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 16px; font-size: 12px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-9 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;Referral Service</b>
                        </div>
                    </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col-12">
                    <div class="mb-2 d-flex align-items-end">
                        <div style="flex-grow: 1;">
                        <div class="d-flex align-items-center mb-2">
                        <span>AMOUNT NEEDED: &emsp; PhP &emsp;</span>
                        <input type="text" style="width: 82%; height: 20px; border-bottom: 1px solid black;" value ="<?php echo numtowords($row['amount']);?>  only" />
                    </div>
                </div>
            </div>
            </div>
            </div>


            <div class="fluid-container">
  <div style="display: flex; justify-content: space-between;">
    <div style="width: 50%; border: 1px solid #000; padding: 0px 0;margin-left:-16px">
         <!-- Section I -->
         <div class="section-title">I. INCOME AND FINANCIAL RESOURCES</div>
            <div class="mb-2 mt-2">
                <p><b>&emsp;&emsp;&emsp;Occupation/s of family member</b></p>
                <p>&emsp;&emsp;&emsp;&emsp;Employed <br><i>&emsp;&emsp;&emsp;&emsp;(indicate number of members working)</i> 
                    <input type="text">
                </p>
                <p>&emsp;&emsp;&emsp;&emsp;Seasonal Employee <br><i>&emsp;&emsp;&emsp;&emsp;(indicate number of members working)</i> 
                    <input type="text">
                </p>
                <p><b>&emsp;&emsp;&emsp;Combined family income </b>
                    <input type="text">
                </p>
                <div class="form-check">
                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="insurance">
                    <label class="form-check-label" for="insurance"><b>Insurance coverage</b></label>
                </div><br>
                <div class="form-check">
                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="savings">
                    <label class="form-check-label" for="savings"><b>Savings</b></label>
                </div>
            </div>

            <!-- Section II -->
            <div class="section-title mt-4">II. BUDGET AND EXPENSES</div>
            <div class="mb-2 mt-2">
                
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Monthly expenses of the family<br><small>(Utility bills, Maintenance and Medication, Mortgage/Rent, Debt, and Others)</small> </label>
                </div><br>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Availability of emergency fund</label>
                </div>
            </div>

            <!-- Section III -->
            <div class="section-title mt-4">III. SEVERITY OF THE CRISIS</div><br>
            <!-- Leave blank for now as per image -->
            &emsp;&emsp;&emsp;<label class="form-check-label" for="insurance"><b>How long does the patient suffer from the disease?</b></label><br>
            <div class="mb-2 mt-2">
                
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Recently diagnosed (3 Mos & below) </label>
                </div><br>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">3 months to a year</label>
                </div><br>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">chronic life long</label>
                </div><br>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">not applicable</label>
                </div>
            </div>
            <br>
            &emsp;&emsp;&emsp;<label class="form-check-label" for="insurance"><b>In the past three (3) months, did the family experience ate least <br>once crisis?</b></label><br>
            <div class="mb-2 mt-2">
                
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Yes </label>
                &emsp;&emsp;&emsp;
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">No </label>
                </div>
            </div>
            <br>
            &emsp;&emsp;&emsp;<label class="form-check-label" for="insurance"><b>If yes, which among the following crisis did the family experience<br>in the past three (3) months (check all that apply):</b></label><br>
            <div class="mb-2 mt-2">
                
            <div class="form-check">
            &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Hospitalization</label>
                </div><br>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Death of a family member</label>
                </div><br>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Catastropic Event (fire, earthquake, flooding, etc.)</label>
                </div><br>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Disablement</label>
                </div><br>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Loss of Livelihood</label>
                </div><br>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Others, specify ____________</label>
                </div>
            </div>
    </div>
    <div style="width: 50%; border: 1px solid #000; padding: 0px 0;margin-right:-16px">
        
            <!-- Section IV -->
            <div class="section-title">IV. AVAILABILITY OF SUPPORT SYSTEMS</div>
            <div class="mb-2 mt-2">
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="family"><label class="form-check-label" for="family">Family</label></div><br>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="relatives"><label class="form-check-label" for="relatives">Relatives</label></div><br>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="friends"><label class="form-check-label" for="friends">Friend/s</label></div><br>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="employer"><label class="form-check-label" for="employer">Employer</label></div><br>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="church"><label class="form-check-label" for="church">Church/Community Organization</label></div>
            </div>

            <!-- Section V -->
            <div class="section-title mt-4">V. EXTERNAL RESOURCES TAPPED BY THE FAMILY</div>
            <div class="mb-2 mt-2">
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="philhealth"><label class="form-check-label" for="philhealth">Philhealth</label></div><br>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="healthcard"><label class="form-check-label" for="healthcard">Health Card</label></div><br>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="guarantee"><label class="form-check-label" for="guarantee">Guarantee Letter from other agencies</label></div><br>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="mss"><label class="form-check-label" for="mss">MSS Discount</label></div><br>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="senior"><label class="form-check-label" for="senior">Senior Citizen Discount</label></div><br>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="pwd"><label class="form-check-label" for="pwd">PWD Discount</label>
                    <input type="text">
                </div><br>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="others"><label class="form-check-label" for="others">Others, specify</label>
                    <input type="text">
                </div><br>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="pwd"><label class="form-check-label" for="pwd">Not Applicable</label>
                    <input type="text">
                </div>
            </div>
            <div class="section-title mt-4">VI. SELF HELP AND CLIENT EFFORTS</div>
            <div class="mb-2 mt-2">
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="philhealth"><label class="form-check-label" for="philhealth">Successfully sought employment opportunities <br>or explored additional Income sources</label></div><br>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="healthcard"><label class="form-check-label" for="healthcard">Successfully  reached out to relevant organizations <br> or agencies for financial assistance or support</label></div>
            </div>
        <div class="section-title mt-4">VII. VULNERABILITY AND RISK FACTORS</div>
            <div class="mb-2 mt-2">
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="philhealth"><label class="form-check-label" for="philhealth">There are elderly/ Child in need/ PWD/ <br>Pregnant in the household</label></div><br>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="healthcard"><label class="form-check-label" for="healthcard">A member is physically or <br>mentally incapacitated to work</label></div><br>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="guarantee"><label class="form-check-label" for="guarantee">Inability to secure stable employment</label></div>
            </div>
            </div>
    </div>
  </div>
</div>

            <!--Client INFO-->
            
            </div><br><br><label style="font-size: 16px;">Control No:<b> <?php echo $row['control_number'];?></b></label><br>
            <div class="footer row">
                <div class="col-12">
                    <div style="border-bottom: solid 1px black;"></div>
                    <p class="text-center">Page 1 of 2<br>
                    DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
                    Website: http://fo11.dswd.gov.ph Tel Nos.: (082)_227 1964 / (082) 227 8746 / (082)_227 1435 Telefax: (082) 226 2857</p>
                </div>
            </div>
            <!--Container-->
        </div>
            </div>
            </div>
            </div>
		
		



<div class="fluid-container"><br><br><br>
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-12"  >
            <!-- Section I -->
            <div class="section-title">VIII. CLIEANT SECTOR</div>
            <div class="mb-2 mt-2">
            <table class="form-table" >
    <tr>
      <!-- Target Sector -->
      <td>
  <div class="sub-title">Target Sector:</div>
  <table style="width: 100%; border: none;">
  <tr>
    <td style="vertical-align: top; width: 50%;">
      <div class="checkbox-group"><input type="checkbox" <?php if($row['category']=='FHONA') { echo "checked";}?>> FHONA</div>
      <div class="checkbox-group"><input type="checkbox" <?php if($row['category']=='WEDC') { echo "checked";}?>> WEDC</div>
      <div class="checkbox-group"><input type="checkbox" <?php if($row['category']=='PWD') { echo "checked";}?>> PWD</div>
      <div class="checkbox-group"><input type="checkbox" <?php if($row['category']=='CNSP') { echo "checked";}?>> CNSP</div>
    </td>
    <td style="vertical-align: top; width: 50%;">
      <div class="checkbox-group"><input type="checkbox" <?php if($row['category']=='SC') { echo "checked";}?>> SC</div>
      <div class="checkbox-group"><input type="checkbox" <?php if($row['category']=='YNSP') { echo "checked";}?>> YNSP</div>
      <div class="checkbox-group"><input type="checkbox" <?php if($row['category']=='PLHIV') { echo "checked";}?>> PLHIV</div>
    </td>
  </tr>
</table>

<!-- Specify Sub-Category -->
<td>
  <div class="sub-title">Specify Sub-Category:</div>
  <table style="width: 100%;">
    <tr>
      <td style="vertical-align: top; width: 50%;">
      <div class="checkbox-group"><input type="checkbox" <?php if($row['subcategory']=='SOLO PARENT') { echo "checked";}?>> Solo Parent</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['subcategory']=='INDIGENOUS PEOPLE') { echo "checked";}?>> Indigenous People</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['subcategory']=='STREET DWELLERS') { echo "checked";}?>> Street Dwellers</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['subcategory']=='KIA/WIA') { echo "checked";}?>> KIA/WIA</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['subcategory']=='4PS DSWD BENEFICIARY') { echo "checked";}?>> 4PS Beneficiary</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['subcategory']=='STATELESS PERSON') { echo "checked";}?>> Stateless Person</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['subcategory']=='ASYLUM SEEKERS') { echo "checked";}?>> Asylum Seekers</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['subcategory']=='REFUGEES') { echo "checked";}?>> Refugees</div>
      </td>
      <td style="vertical-align: top; width: 50%;">
        <div class="checkbox-group"><input type="checkbox"> Recovering Person Who <br>&emsp;&nbsp;Used Drugs</div>
        <div class="checkbox-group"><input type="checkbox"> Minimum Wage Earner</div>
        <div class="checkbox-group">
          <input type="checkbox"> Below Minimum Wage <br>&emsp;Earner 
          <span style="font-size: 12px;">(specify approximate monthly income)</span><br>
          &emsp;&nbsp;Php <input type="text">
        </div>
        <div class="checkbox-group"><input type="checkbox"> No Regular Income</div>
        <div class="checkbox-group">
          <input type="checkbox"> Others: <input type="text" style="width: 100px;">
        </div>
      </td>
    </tr>
  </table>
</td>

<!-- Type of Disability -->
<td>
  <div class="sub-title">Type of Disability:</div>
  <table style="width: 100%;border:none;">
    <tr>
      <td style="vertical-align: top; width: 50%;">
        <div class="checkbox-group"><input type="checkbox" <?php if($row['type_of_disability']=='Speech Impairment') { echo "checked";}?>> Speech Impairment</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['type_of_disability']=='Learning Disability') { echo "checked";}?>> Learning Disability</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['type_of_disability']=='Psychosocial Disability') { echo "checked";}?>> Psychosocial Disability</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['type_of_disability']=='Deaf/Hard-of-Hearing') { echo "checked";}?>> Deaf/Hard-of-Hearing</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['type_of_disability']=='Cancer') { echo "checked";}?>> Cancer</div>
      </td>
      <td style="vertical-align: top; width: 50%;">
        <div class="checkbox-group"><input type="checkbox" <?php if($row['type_of_disability']=='Mental Disability') { echo "checked";}?>> Mental Disability</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['type_of_disability']=='Visual Disability') { echo "checked";}?>> Visual Disability</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['type_of_disability']=='Intellectual Disability') { echo "checked";}?>> Intellectual Disability</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['type_of_disability']=='Physical Disability') { echo "checked";}?>> Physical Disability</div>
        <div class="checkbox-group"><input type="checkbox" <?php if($row['type_of_disability']=='Rare Disease') { echo "checked";}?>> Rare Disease</div>
      </td>
    </tr>
  </table>
</td>
            </table>
<!-- Source of Income Section -->
<div class="full-width" style="display: flex; justify-content: space-between;">
  
  <!-- Left Table (Empty) -->
  <table class="source-income1" style="width: 40%; ">
    <tr>
      <td></td>
    </tr>
  </table>

  <!-- Right Table (With Data) -->
  <table class="source-income" style="width: 60%;">
    <tr>
      <td><input type="checkbox"> Salaries/Wages from Employment</td>
      <td>Php <input class="amount-input" type="text"></td>
    </tr>
    <tr>
      <td><input type="checkbox"> Entrepreneurial income/profits</td>
      <td>Php <input class="amount-input" type="text"></td>
    </tr>
    <tr>
      <td><input type="checkbox"> Cash assistance from domestic source</td>
      <td>Php <input class="amount-input" type="text"></td>
    </tr>
    <tr>
      <td><input type="checkbox"> Cash assistance from abroad</td>
      <td>Php <input class="amount-input" type="text"></td>
    </tr>
    <tr>
      <td><input type="checkbox"> Transfers from the government (e.g. 4Ps)</td>
      <td>Php <input class="amount-input" type="text"></td>
    </tr>
    <tr>
      <td><input type="checkbox"> Pension</td>
      <td>Php <input class="amount-input" type="text"></td>
    </tr>
    <tr>
      <td><input type="checkbox"> Other income</td>
      <td>Php <input class="amount-input" type="text"></td>
    </tr>
    <tr>
      <td class="bold-label">&emsp;&nbsp; &emsp;&nbsp; &emsp;Total income in the past 6 months</td>
      <td>Php <input class="amount-input" type="text"></td>
    </tr>
  </table>
</div>



            <!-- Section II -->
            <div class="section-title mt-4">IX. PROBLEM PRESENTED</div><br><br><br>
            </div>

            <!-- Section III -->
            <div class="section-title mt-4">X. SOCIAL WORKER'S ASSESSMENT</div><br><br><br><br>
              <!-- Section III -->
              <div class="section-title mt-4">
                <div class="row">
                <div class="col-4">
                   &emsp;<input class="form-check-input" type="checkbox" id="family"><label class="form-check-label" for="family">FINANCIAL ASSISTANCE
                </div>
                <div class="col-3">
                <input class="form-check-input" type="checkbox" id="family"><label class="form-check-label" for="family">
                MATERIAL ASSISTANCE
                </div>
                <div class="col-3">
                <input class="form-check-input" type="checkbox" id="family"><label class="form-check-label" for="family">PYSCHOLOGICAL SUPPORT
                </div>
                <div class="col-2">
                <input class="form-check-input" type="checkbox" id="family"><label class="form-check-label" for="family">REFFERAL:
                </div>
                </div>   
              </div>


              <div class="row">
                <div class="col-4">
                <table style="width: 100%;">
                <tr>
                    <td style="vertical-align: top; width: 50%;">
                    <div style="margin-bottom:8px">&nbsp;<input type="checkbox"  <?php if($row['type_of_assistance']=='MEDICAL ASSISTANCE') { echo "checked";}?>>&nbsp;Medical</div>
                    <div style="margin-bottom:8px">&nbsp;<input type="checkbox" <?php if($row['type_of_assistance']=='FUNERAL ASSISTANCE') { echo "checked";}?>>&nbsp;Funeral</div>
                    <div style="margin-bottom:8px">&nbsp;<input type="checkbox" <?php if($row['type_of_assistance']=='TRANSPORTATION ASSISTANCE') { echo "checked";}?>>&nbsp;Transportation</div>
                    <div style="margin-bottom:8px">&nbsp;<input type="checkbox" <?php if($row['type_of_assistance']=='EDUCATIONAL ASSISTANCE') { echo "checked";}?>>&nbsp;Educational</div>
                    </td>
                    <td style="vertical-align: top; width: 50%;">
                    <div style="margin-bottom:8px"><input type="checkbox" <?php if($row['type_of_assistance']=='FOOD ASSISTANCE') { echo "checked";}?>>&nbsp;&nbsp;Food Assistance</div>
                    <div style="margin-bottom:8px"><input type="checkbox" <?php if($row['type_of_assistance']=='CASH RELIEF ASSISTANCE') { echo "checked";}?>>&nbsp;Cash Relief Assistance</div>
                    </td>
                </tr>
                </table>

                </div>
                <div class="col-3">
                <tr>
                    <td style="vertical-align: top; width: 100%;">
                    <div style="margin-bottom:8px"><input type="checkbox">&nbsp;&nbsp;Family Food Packs</div>
                    <div style="margin-bottom:8px"><input type="checkbox">&nbsp;&nbsp;Other Food Items</div>
                    <div style="margin-bottom:8px"><input type="checkbox">&nbsp;&nbsp;Hygiene & Sleeping Kits</div>
                    <div style="margin-bottom:8px"><input type="checkbox">&nbsp;&nbsp;Assistive Devices & Techniquies</div>
                    <div style="margin-bottom:8px"><input type="checkbox">&nbsp;&nbsp;Food Rice</div>
                    </td>
                </tr>
                </div>
                <div class="col-3">
                <tr>
                    <td style="vertical-align: top; width: 100%;">
                    <div style="margin-bottom:8px"><input type="checkbox">&nbsp;&nbsp;Psychological First Aid (PFA)</div>
                    <div style="margin-bottom:8px"><input type="checkbox">&nbsp;&nbsp;Social Work Counseling</div>
                    </td>
                </tr>
                </div>
                <div class="col-2">
                <tr>
                    <td style="vertical-align: top; width: 100%;">
                    <div>________________</div>
                    <div>________________</div>
                    <div>________________</div>
                    <div>________________</div>
                    <div>________________</div>
                    </td>
                    
                </tr>
                </div>
                </div>   
              <div class="section-title mt-4">
              <div class="row"> 
                <div class="col-1">
                   &emsp;
                </div>
                <div class="col-5">
                   &emsp;Purpose of Assistance
                </div>
                <div class="col-3">
                Amount
                </div>
                <div class="col-3">
                Fund Source
                </div>
                </div>   
            </div>
            <table class="table bordered" width="100%">
                    <tbody>
                        <tr>
                            <td width="5%">1</td>
                            <td width="45%">&emsp;</td>
                            <td>&emsp;</td>
                            <td>&emsp;</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>&emsp;</td>
                            <td>&emsp;</td>
                            <td>&emsp;</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>&emsp;</td>
                            <td>&emsp;</td>
                            <td>&emsp;</td>
                        </tr>
                    </tbody>
                </table>
 
            
            </div>
            <!--Client INFO-->
       
            </div><br><br>
              <!-- Signatures -->
              <div class="container" style="display: flex; justify-content: space-between; gap: 40px;">
  <!-- Interviewed by -->
  <div class="col6" style="text-align: center; width: 50%;">
    <label style="font-size:12px"><b>Interviewed by:</b></label>
    <hr style="width: 80%; margin: 5px auto;">
    <p class="mb-0">Social Worker</p>
    <small>(Signature over Printed Name)</small>
    <br>
    <label>
      License Number:
      <input type="text" style="height: 20px; border: none; border-bottom: 1px solid black; width: 150px;">
    </label>
  </div>

  <!-- Reviewed & Approved by -->
  <div class="col6" style="text-align: center; width: 50%;">
    <p class="fw-bold"><b>Reviewed & Approved by:</b></p><?php echo strtoupper($head);?>
    <hr style="width: 80%; margin: 5px auto;">
    <p class="mb-0">Approving Authority</p>
    <small>(Signature over Printed Name)</small>
  </div>
</div>

            <br><label style="font-size: 16px;">Control No:<b> <?php echo $row['control_number'];?></b></label><br>
            <div class="footer row">
                <div class="col-12">
                    <div style="border-bottom: solid 1px black;"></div>
                    <p class="text-center">Page 2 of 2<br>
                    DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
                    Website: http://fo11.dswd.gov.ph Tel Nos.: (082)_227 1964 / (082) 227 8746 / (082)_227 1435 Telefax: (082) 226 2857</p>
                </div>
            </div>
            <!--Container-->
        </div>

		<?php }?>
    </body>
</html>