
<link rel="stylesheet" href="cpms/css/coe.css">
<link rel="stylesheet" type="text/css" href="cpms/css/bootstrap.min.css"> 
<html>
    <body>
        <style>
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
strong{
        background-color: black !important;
	color: white !important;
	-webkit-print-color-adjust: exact; 
	font-size: 14px;
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
    .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
        }

        .footer {
            padding: 5px;
            background-color: #ffff; /* optional for visibility */
        }

        .footer div {
            margin-top: 5px;
        }

        .text-center {
            text-align: center;
            font-size: 12px;
        }

        .footer hr {
            border: none;
            background-color: #ffff; /* optional for visibility */
            border-top: 1px solid black;
            margin: 0 0 5px 0;
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
    /* Table styling */
    .assistance-table {
        width: 100%;
        border-collapse: collapse;
        height: 600px; /* total table height */
    }

    .assistance-table thead th {
        
        padding: 8px;
        background-color: #f2f2f2;
        text-align: left;
    }

    .assistance-table tbody td {
        border-bottom: 1px solid #000; /* thicker bottom border for header */
        padding: 8px;
        text-align: left;
    }

    .assistance-table tbody .table-row {
        height: 35%; /* each row height as percentage of table height */
    }
        </style>
		<?php
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '4096M'); // Increase to 256MB or more
		include "../dbconnect.php";
		error_reporting(0);
		session_start();
		$emm=$_SESSION['userid'];
		$user_type = $_SESSION['user_type'];
		$head = "";
		$sql2 = "SELECT head FROM lib_users as a
				INNER JOIN lib_area as b ON a.areaassigned = b.id 
				where empid='$emm'";
		$result2 = mysqli_query($conn, $sql2);	
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$head = $row2['head'];
		}
		$row['age'] =0;
		
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
		$page_no = $_SESSION['pageNumber'];
		$isLastPage = $_SESSION['isLastPage'];
		$benePerPage =50;

					if ($page_no > 0) {
						$pagectr = (($page_no - 1) * 50) + 1;
					} else {
						$pagectr = 1; // Default value if $page_no is invalid
					}
					
			$sql = "SELECT * FROM aics_clean_list WHERE exportStatus is null
					 LIMIT " . (($page_no - 1) * $benePerPage) . ", $benePerPage";
			$result = mysqli_query($conn, $sql);
			while ($row = mysqli_fetch_assoc($result)) {
			$fname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['first_name']);
			$mname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['middle_name']);
			$lname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['last_name']);    
			$ename = $row['extension_name'];   
			$charging = strtoupper($row['charging']);
			$amount = $row['amount'];
			$monthly_salary = $row['monthly_salary'];
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
        
    <div class="fluid-container print-page" id="gisv2_print" style="font-size:12px; padding:0%;margin-left:5%;margin-top:5%;padding-right:5%;">
     <!--HEADER-->
     <div class="row" >
                <div class="col-6">
                    <img src="cpms/images/dswd_olog.png" alt="" width="230px" height="60px">
                    <img src="cpms/images/AICS.png" alt="" width="70px" height="60px">
                    <img src="cpms/images/BP.png" alt="" width="70px" height="60px">
                </div>
            </div>
            <center>
                <p class="header" style="margin-top: 20px; margin-left: -14px; margin-right: -15px; font-size: 18px;"><b>GENERAL INTAKE SHEET</b> </p>
            </center>
            <div class="row" style="margin-top: -18px; padding: 0%;">
                <div class="col-2 text-center" style="border: 1px solid black; background-color: #ffff; font-size:14px; padding: 2%;">
                    <div class="row" style="font-size: 14px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="<?php if(strtoupper($row['charging'])=='AICS') { echo "&#x2714";}?>">
                        </div>
                        <div class="col-sm-4 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;AICS</b>
                        </div>
                    </div>
                    <div class="row" style="font-size: 14px; padding:0; margin-top: 1px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="<?php if(strtoupper($row['charging'])=='AKAP') { echo "&#x2714";}?>">
                        </div>
                        <div class="col-sm-4 center"  style="padding-top: 3px;">
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
                    <div class="row" style="font-size: 14px; padding:0; margin-top: 1px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 30px; font-size: 14px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm "  style="padding-top: 3px;">
                            <b class="text-center" style="margin-left:-60">Malasakit Center</b>
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
                <div class="col-12 " style="border: 1px solid black; font-size:14px;line-height: 13px;"> <br>
                CLIENTS NAME
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-4">
                            <input disabled class="text-center" style="width: 100%; height:100%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($lname);?>">
                        </div>
                        <div class="col-4">
                            <input disabled class="text-center" style="width: 100%; height:100%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($fname);?>">
                        </div>
                        <div class="col-3">
                            <input disabled class="text-center" style="width: 100%; height:100%; font-size: 12px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($mname);?>">
                        </div>
                        <div class="col-1">
                            <input disabled class="text-center" style="width: 100%; height:100%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($ename);?>">
                        </div>
                     </div>
                     
                    <div class="row" style="margin-top: 3px;">
                        <div class="col-4">
                            <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Apelyido</b> (Last Name)</p>
                        </div>
                        <div class="col-4">
                            <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Unang Pangalan</b> (First Name)</p>
                        </div>
                        <div class="col-3">
                            <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Gitnang Pangalan</b> (Middle Name)</p>
                        </div>
                        <div class="col-1">
                            <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Ext.</b> (Sr.,Jr.)</p>
                        </div>
                    </div>

                  
            <div class="form-group row align-items-center">
  <label class="col-auto col-form-label font-weight-bold">BENEFICIARY'S NAME</label>
  
  <div class="col-auto d-flex align-items-center">
    <input type="checkbox" class="form-control mr-2" style="width: 20px; height: 10px;">
    <span>same as above</span>
  </div>
</div>
            <!--BENEFICIARY INFO-->
                    <div class="row" style="margin-top:5px; ">
                        <div class="col-4">
                            <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-4">
                            <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-3">
                            <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-1">
                            <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value="">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 3px;">
                        <div class="col-4">
                            <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Apelyido</b> (Last Name)</p>
                        </div>
                        <div class="col-4">
                            <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Unang Pangalan</b> (First Name)</p>
                        </div>
                        <div class="col-3">
                            <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Gitnang Pangalan</b> (Middle Name)</p>
                        </div>
                        <div class="col-1">
                            <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Ext.</b> (Sr.,Jr.)</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 " style="font-size:14px;line-height: 13px;"> 
                            <div class="mb-2 d-flex align-items-end">
                                <div style="flex-grow: 1;">
                                    <div class="d-flex align-items-center mb-2">
                                        <span>PURPOSE OF ASSISTANCE:</span>
                                        <input type="text" style="width: 70%; height: 20px; " />
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2 d-flex align-items-end">
                                <div style="flex-grow: 1;">
                                    <div class="d-flex align-items-center mb-2">
                                        <span>DIAGNOSIS/CAUSE OF DEATH (If funeral):</span>
                                        <input type="text" style="width: 70%; height: 20px; " />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
            
                    <div class="row" style="margin-top: 10px;">
                &emsp;MODE OF ASSISTANCE :&emsp;
            
                <div class="row" style="font-size: 11px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 12px; font-size: 11px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value='&#x2714;'>
                        </div>
                        <div class="col-sm-9 center"  style="padding-top: 1px;">
                            <b class="text-center">&nbsp;Outright Cash</b>
                        </div>
                    </div>
                    &emsp;
                <div class="row" style="font-size: 11px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 13px; font-size: 11px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-9 center"  style="padding-top: 1px;">
                            <b class="text-center" style="width: 18px; font-size: 11px;">&nbsp;Guarantee Letter</b>
                        </div>
                    </div>
                    &emsp;
                <div class="row" style="font-size: 11px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 16px; font-size: 11px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-10 center"  style="padding-top: 1px;">
                            <b class="text-center" style="width: 18px; font-size: 11px;">&nbsp;Material Assistance</b>
                        </div>
                    </div>
                    &emsp;
                <div class="row" style="font-size: 11px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 16px; font-size: 11px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-10 center"  style="padding-top: 1px;">
                            <b class="text-center"  style="width: 18px; font-size: 10px;">&nbsp;Pychological Support</b>
                        </div>
                    </div>
                    &emsp;
                <div class="row" style="font-size: 11px; padding:0; margin-top: -5px">
                        <div class="col-sm-1 center">
                            <input disabled class="text-center" style="width: 13px; font-size: 11px; padding:0; text-indent: 1px; border: 1px solid black;" type="text" value="">
                        </div>
                        <div class="col-sm-9 center"  style="padding-top: 3px;">
                            <b class="text-center">&nbsp;Referral Service</b>
                        </div>
                    </div>
            </div>
            <div class="mb-2 d-flex align-items-end">
                        <div style="flex-grow: 1;">
                        <div class="d-flex align-items-center mb-2">
                        <span>AMOUNT NEEDED: &emsp; PhP &emsp;</span>
                        <input type="text" style="width: 82%; height: 20px; border-bottom: 1px solid black;" value ="<?php echo numtowords($row['amount']);?> " />
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
                </div>
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
            <div class="section-title mt-4">III. SEVERITY OF THE CRISIS</div>
            <!-- Leave blank for now as per image -->
            &emsp;&emsp;&emsp;<label class="form-check-label" for="insurance"><b>How long does the patient suffer from the disease?</b></label>
            <div class="mb-2 mt-2">
                
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Recently diagnosed (3 Mos & below) </label>
                </div>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">3 months to a year</label>
                </div>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">chronic life long</label>
                </div>
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
                </div>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Death of a family member</label>
                </div>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Catastropic Event (fire, earthquake, flooding, etc.)</label>
                </div>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Disablement</label>
                </div>
                <div class="form-check">
                &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="emergencyFund">
                    <label class="form-check-label" for="emergencyFund">Loss of Livelihood</label>
                </div>
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
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="family"><label class="form-check-label" for="family">Family</label></div>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="relatives"><label class="form-check-label" for="relatives">Relatives</label></div>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="friends"><label class="form-check-label" for="friends">Friend/s</label></div>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="employer"><label class="form-check-label" for="employer">Employer</label></div>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="church"><label class="form-check-label" for="church">Church/Community Organization</label></div>
            </div>

            <!-- Section V -->
            <div class="section-title mt-4">V. EXTERNAL RESOURCES TAPPED BY THE FAMILY</div>
            <div class="mb-2 mt-2">
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="philhealth"><label class="form-check-label" for="philhealth">Philhealth</label></div>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="healthcard"><label class="form-check-label" for="healthcard">Health Card</label></div>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="guarantee"><label class="form-check-label" for="guarantee">Guarantee Letter from other agencies</label></div>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="mss"><label class="form-check-label" for="mss">MSS Discount</label></div>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="senior"><label class="form-check-label" for="senior">Senior Citizen Discount</label></div>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="pwd"><label class="form-check-label" for="pwd">PWD Discount</label>
                    <input type="text">
                </div>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="others"><label class="form-check-label" for="others">Others, specify</label>
                    <input type="text">
                </div>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="pwd"><label class="form-check-label" for="pwd">Not Applicable</label>
                    <input type="text">
                </div>
            </div>
            <div class="section-title mt-4">VI. SELF HELP AND CLIENT EFFORTS</div>
            <div class="mb-2 mt-2">
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="philhealth"><label class="form-check-label" for="philhealth">Successfully sought employment opportunities <br>or explored additional Income sources</label></div>
                <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="healthcard"><label class="form-check-label" for="healthcard">Successfully  reached out to relevant organizations <br> or agencies for financial assistance or support</label></div>
            </div>
            <div class="section-title mt-4">VII. VULNERABILITY AND RISK FACTORS</div>
                <div class="mb-2 mt-2">
                    <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="philhealth"><label class="form-check-label" for="philhealth">There are elderly/ Child in need/ PWD/ <br>Pregnant in the household</label></div>
                    <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="healthcard"><label class="form-check-label" for="healthcard">A member is physically or <br>mentally incapacitated to work</label></div>
                    <div class="form-check">&emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="guarantee"><label class="form-check-label" for="guarantee">Inability to secure stable employment</label></div>
                </div>
            </div>
        </div>
    </div>
            <div class="footer row">
                <div class="col-12">
                    <div style="border-bottom: solid 1px black;"></div>
                    <p class="text-center">Page 1 of 2<br>
                    DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
                    Website: http://fo11.dswd.gov.ph Tel Nos.: (082)_227 1964 / (082) 227 8746 / (082)_227 1435 Telefax: (082) 226 2857</p>
                </div>
            </div>
            <div style="page-break-after: always;margin-bottom:5%;"></div>    <!-- newpage-->
    
            </div>        
    <div class="fluid-container" id="gisvpage2_print" style="font-size:12px; padding:0%;margin-left:5%;margin-top:5%;padding-right:5%;">
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
  <table class="source-income1" style="width: 40%;">
    <tr>
      <td></td>
    </tr>
  </table>

  <!-- Right Table (With Data) -->
  <table class="source-income" style="width: 60%;margin-top:-5% ">
  <tr>
      <td colspan="2"><br></td>
    </tr>
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
<div class="section-title mt-4">IX. PROBLEM PRESENTED</div><br><br>
<div class="section-title mt-4">X. SOCIAL WORKER'S ASSESSMENT</div><br><br><br>
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
       
            </div>
              <!-- Signatures -->
              <div class="container" style="display: flex; justify-content: space-between; gap: 40px;">
  <!-- Interviewed by -->
  <div class="col6" style="text-align: center; width: 50%;"><br>
    <label style="font-size:16px"><b>Interviewed by:</b></label><br><br>
    <hr style="width: 60%; margin: 5px auto;">
    <p class="mb-0">Social Worker</p>
    <small>(Signature over Printed Name)</small>
    <br>
      License Number:
      <input type="text" style="height: 20px; border: none; border-bottom: 1px solid black; width: 150px;">
    </label>
  </div>

  <!-- Reviewed & Approved by -->
  <div class="col6" style="text-align: center; width: 50%;"><br>
    <label  style="font-size:16px"><b>Reviewed & Approved by:</b></label><br><?php echo strtoupper($head);?>
    <hr style="width: 60%; margin: 5px auto;">
    <p class="mb-0">Approving Authority</p>
    <small>(Signature over Printed Name)</small>
  </div>
</div>

</div>
            <br><label style="font-size: 16px;">Control No:<b> <?php echo $row['control_number'];?></b></label>
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
                </div>
            </div>
         </div>
    </div>
</div>



<div style="page-break-after: always;margin-bottom:3%;"></div>    <!-- newpage-->

<div class="fluid-container" id="coev2_print" style="font-size:12px; padding:0%;margin-left:5%;padding-right:5%;">
        <br>
        <!-- HEADER -->
<div class="row">
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
                <b style="font-size: 30px">INFORMATION SHEET</b>
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6"><br>
                    </div>
                    <div class="col-3"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-8">
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
        <!--Second Row--><br>
        <p class="header" style="margin-top: -20px; margin-left: -15px; margin-right: -15px; font-size: 15px;"><strong>&emsp; IMPORMASYON NG KINATAWAN/KLIYENTE </strong>(Authorized Representative's/Client's Identifying Information)</p><br>
        <div class="row" style="margin-top: -8px;">
                <div class="col-4">
                    <input disabled class="text-center" style="width: 100%; height:100%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($lname);?>">
                </div>
                <div class="col-4">
                    <input disabled class="text-center" style="width: 100%; height:100%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($fname);?>">
                </div>
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:100%; font-size: 12px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($mname);?>">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:100%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($ename);?>">
                </div>
            </div>
            <div class="row" style="margin-top: -5px;">
                <div class="col-4">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Apelyido</b> (Last Name)</p>
                </div>
                <div class="col-4">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Unang Pangalan</b> (First Name)</p>
                </div>
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Gitnang Pangalan</b> (Middle Name)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Ext.</b> (Sr.,Jr.)</p>
                </div>
            </div>
            <div class="row" style="margin-top: -8px;">
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['purok']);?>">
                </div>
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['barangay']);?>">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['city_municipality']);?>">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['province']);?>">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="Region XI">
                </div>
            </div>
            <div class="row" style="margin-top: -5px;">
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>House No./Street/Purok</b> (Ex. Purok 12)</p>
                </div>
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Barangay</b> (Ex. 23-C)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Municipality</b> (Ex. Davao City)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Province/District</b> (Ex. Dist. I)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Region</b> (XI)</p>
                </div>
            </div>
            <div class="row" style="margin-top: -8px;">
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['contact_number']);?>">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['birth_day'].'/'.$row['birth_month'].'/'.$row['birth_year']);?>">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo $age;?>">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['sex']);?>">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['civil_status']);?>">
                </div>
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['occupation']);?>">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo number_format($row['monthly_salary'],2);?>">
                </div>
            </div>
            <div class="row" style="height: 25px; margin-top: -5px;">
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 8px; "><b>Numero ng Telepono</b> (Mobile No.)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Kapanganakan</b> (Birthdate)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Edad</b> (Age)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Kasarian</b></p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Civil Status</b> (Katayuang Sibil)</p>
                </div>
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Trabaho</b> (Occupation)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 7px; "><b>Buwanang Kita</b></p>
                </div>
            </div><br>
            <p class="header" style="margin-top: -20px; margin-left: -15px; margin-right: -15px; font-size: 15px;"><strong>&emsp; IMPORMASYON NG BENEPISYARYO </strong> (Beneficiary's Identifying Information)</p>
            <div class="row" style="margin-top:-8px; ">
                <div class="col-4">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value="">
                </div>
                <div class="col-4">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value="">
                </div>
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value="">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value="">
                </div>
            </div>
            <div class="row" style="margin-top: -5px;">
                <div class="col-4">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Apelyido</b> (Last Name)</p>
                </div>
                <div class="col-4">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Unang Pangalan</b> (First Name)</p>
                </div>
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Gitnang Pangalan</b> (Middle Name)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Ext.</b> (Sr.,Jr.)</p>
                </div>
            </div>
            <div class="row" style="margin-top: -8px;">
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
            </div>
            <div class="row" style="margin-top: -5px;">
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>House No./Street/Purok</b> (Ex. Purok 12)</p>
                </div>
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Barangay</b> (Ex. 23-C)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Municipality</b> (Ex. Davao City)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Province/District</b> (Ex. Dist. I)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Region</b> (XI)</p>
                </div>
                </div>
            <div class="row" style="margin-top: -8px;">
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-1">
                    <input disabled class="text-center salary_monthly" style="width: 100%; height:60%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
            </div>
            <div class="row" style="margin-top: -5px; margin-bottom: -15px">
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 8px; "><b>Numero ng Telepono</b> (Mobile No.)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Kapanganakan</b> (Birthdate)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Edad</b> (Age)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Kasarian</b></p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Civil Status</b> (Katayuang Sibil)</p>
                </div>
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 10px; "><b>Trabaho</b> (Occupation)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:60%; font-size: 7px; "><b>Buwanang Kita</b></p>
                </div>
            </div>
    <table class="table table-bordered" width="100%">
      <tr>
        <td style="width: 30%;">
          Ikaw ba ay nakakuha na ng tulong mula sa DSWD?<br>
          <span class="note">(Have you received any assistance from the DSWD?)</span>
          <div class="checkboxes">
            <label><input type="checkbox" name="assistance" value="Hindi"> Hindi</label><br>
            <label><input type="checkbox" name="assistance" value="Oo"> Oo</label>
          </div>
        </td>
        <td style="width: 30%;">
          Natanggap na tulong sa DSWD<br>
          <span class="note">(Assistance received from DSWD)<br>Gamitin ang likurang bahagi ng papel kung kinakailangan</span>
          <div class="row-input"><input type="text" name="assistance1"></div>
          <div class="row-input"><input type="text" name="assistance2"></div>
          <div class="row-input"><input type="text" name="assistance3"></div>
          <div class="row-input"><input type="text" name="assistance4"></div>
          <div class="row-input"><input type="text" name="assistance5"></div>
        </td>
        <td style="width: 40%;">
          Petsa ng tulong<br>
          <span class="note">(Date of assistance received)</span>
          <div class="row-input"><input type="text" name="date1"></div>
          <div class="row-input"><input type="text" name="date2"></div>
          <div class="row-input"><input type="text" name="date3"></div>
          <div class="row-input"><input type="text" name="date4"></div>
          <div class="row-input"><input type="text" name="date5"></div>
        </td>
      </tr>
    </table>
  </form><br>
  <p class="header" style="margin-top: -20px; margin-left: -15px; margin-right: -15px; font-size: 15px;"><strong>&emsp;KOMPOSISYON NG PAMILYA </strong> (Family Composition)</p>
  <table class="table bordered" width="100%">
                <thead>
                    <th></th>
                    <th>Buong Pangalan</th>
                    <th>Relasyon sa Benepisyaryo</th>
                    <th>Edad</th>
                    <th>Hanapbuhay</th>
                    <th>Buwanang Kita</th>        
            </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
            </tbody>    
            </table>
            <p class="header" style="margin-top: -20px; margin-left: -15px; margin-right: -15px; font-size: 15px;"><strong>&emsp;DEKLARASYON NG PAHINTULOT </strong> (Consent Form)																				</p>

        <!--Assistance-->

<div class="consent-body" style="text-align: justify;">
  &emsp;Ako ay nagdedeklara sa ilalim ng parusa ng pagsisinungaling (perjury), na ang lahat ng impormasyon sa aplikasyon na ito ay totoo at tama batay sa aking personal na kaalaman at mga autentikong rekord na isinimute sa Department of Social Welfare and Development (DSWD). Anumang mali o mapanlinlang na impormasyon na ibinigay, o paggawa ng pekeng/pinagwaling na mga dokumento ay magiging sanhi ng nararapat na hakbang na legal laban sa akin at awtomatikong magpawalang-bisa sa anumang tulong na ibibigay kaugnay ng aplikasyon na ito.
  <br><br>
  &emsp;Ako ay sumasang-ayon na ang lahat ng personal na datos (ayon sa depinisyon sa ilalim ng Republic Act 10173 o Data Privacy Law ng 2012 at mga patnubay nito) at impormasyon o mga rekord ng mga transaksyon sa account sa DSWD ay maaaring iproseso, i-profile, o ibahagi sa mga humihiling na partido o para sa layunin ng anumang hukuman, proseso ng batas, pagsusuri, inquiry, audit, o imbestigasyon ng anumang awtoridad.
</div>

<div class="row mt-5" style="display: flex; justify-content: space-between; align-items: flex-start; margin-top: 40px;">
  
  <!-- Signature Section -->
  <div class="col-md-9" style="width: 100%;">
    <div class="" style="text-align: center; margin-top: 25px;">
      __<u><?php echo strtoupper($fname.' '.$mname.' '.$lname.' '.$ename);?></u>____<br>
      <b>Lagda sa ibabaw ng Buong Pangalan ng Kinatawan/Kliyente</b><br>
      <small>(Signature over Printed Name of the Authorized Representative/Client)</small>
    </div>
  </div>

  <!-- Thumbmark Section -->
  <div class="col-md-2" style="text-align: center; border: 1px solid #000; height: 150px; width: 60px;margin-right: 60px;">
    <div style="margin-top: 130px;">Thumbmark</div>
  </div>
</div>

</div>
       
  <br>
            <div class="footer row">
                <div class="col-12">
                    <div style="border-bottom: solid 1px black;"></div>
                    <p class="text-center">Page 1 of 2<br>
                    DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
                    Website: http://fo11.dswd.gov.ph Tel Nos.: (082)_227 1964 / (082) 227 8746 / (082)_227 1435 Telefax: (082) 226 2857</p>
                </div>
            </div>

        <div style="page-break-after: always;margin-bottom:5%;"></div>    <!-- newpage-->
        <div class="fluid-container print-page" id="gisv2_print" style="font-size:12px; padding:0%;margin-left:5%;margin-top:5%;padding-right:5%;">
        <p class="header" style="margin-top: 5%; margin-left: 5px; margin-right: 5px; font-size: 15px;"><strong>&emsp;NATANGGAP NA TULONG MULA SA DSWD </strong>(Assistance Received from DSWD)																																										</p>
       
        <table class="assistance-table">
    <thead>
        <tr>
            <th></th>
            <th>Natanggap na tulong (Assistance received)</th>
            <th>Petsa ng tulong (Date of assistance received)</th>
        </tr>
    </thead>
    <tbody>
        <tr class="table-row"><td>6</td><td></td><td></td></tr>
        <tr class="table-row"><td>7</td><td></td><td></td></tr>
        <tr class="table-row"><td>8</td><td></td><td></td></tr>
        <tr class="table-row"><td>9</td><td></td><td></td></tr>
        <tr class="table-row"><td>10</td><td></td><td></td></tr>
        <tr class="table-row"><td>11</td><td></td><td></td></tr>
        <tr class="table-row"><td>12</td><td></td><td></td></tr>
        <tr class="table-row"><td>13</td><td></td><td></td></tr>
        <tr class="table-row"><td>14</td><td></td><td></td></tr>
        <tr class="table-row"><td>15</td><td></td><td></td></tr>
        <tr class="table-row"><td>16</td><td></td><td></td></tr>
        <tr class="table-row"><td>17</td><td></td><td></td></tr>
        <tr class="table-row"><td>18</td><td></td><td></td></tr>
        <tr class="table-row"><td>19</td><td></td><td></td></tr>
        <tr class="table-row"><td>20</td><td></td><td></td></tr>
    </tbody>
</table>
            <br>
            <p class="header" style="margin-top: -20px; margin-left: -15px; margin-right: -15px; font-size: 15px;"><strong>&emsp;KOMPOSISYON NG PAMILYA </strong> (Family Composition)</p>
            <table class="assistance-table">
    <thead>
        <tr>
            <th></th>
            <th>Buong Pangalan</th>
            <th>Relasyon sa Benepisyaryo</th>
            <th>Edad</th>
            <th>Hanapbuhay</th>
            <th>Buwanang Kita</th>  
        </tr>
    </thead>
    <tbody class="tbodys">
        <tr class="table-row"><td>6</td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="table-row"><td>7</td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="table-row"><td>8</td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="table-row"><td>9</td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="table-row"><td>10</td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="table-row"><td>11</td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="table-row"><td>12</td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="table-row"><td>13</td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="table-row"><td>14</td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="table-row"><td>15</td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="table-row"><td>16</td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="table-row"><td>17</td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="table-row"><td>18</td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="table-row"><td>19</td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="table-row"><td>20</td><td></td><td></td><td></td><td></td><td></td></tr>
    </tbody>
</table>
            </div>
            <div class="footer row">
                <div class="col-12">
                    <div style="border-bottom: solid 1px black;"></div>
                    <p class="text-center">Page 2 of 2<br>
                    DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
                    Website: http://fo11.dswd.gov.ph Tel Nos.: (082)_227 1964 / (082) 227 8746 / (082)_227 1435 Telefax: (082) 226 2857</p>
                </div>
            </div>
                </div>

                <div style="page-break-after: always;margin-bottom:5%;"></div>    <!-- newpage-->
                <div class="fluid-container print-page" id="coev2_print" style="font-size:12px; padding:0%;margin-left:5%;margin-top:5%;padding-right:5%;">    
          
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
                            <input disabled class="text-center" style="width: 30px; padding:0; border: 1px solid black; border-radius: 30px;" type="text" value="">
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
            This is to certify that, <input disabled class="text-center" style=" border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 18px; width: 62%;" value="<?php echo strtoupper($fname.' '.$mname.' '.$lname.' '.$ename);?>" />, 
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
    <input type="text" class="form-control" style="width: 150px; height: 20px; padding: 0 5px;" />
  </div>

  <!-- First Line -->
  <p style="font-size: 14px;">    
  &emsp;&emsp;&emsp;The client is hereby recommended to receive
  </p>
  <div class="mb-2">
  <div class="d-flex align-items-center mb-2">
  <input type="text" style="width: 85%; height: 10px; border-bottom: 1px solid black;" />
  <span>assistance for</span>
</div>
    <div class="left" style="font-size: 10px;">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Type of assistance: medical, burial, others</div>
  </div>

  <!-- Second Line -->
  <div class="mb-2">
  <div class="d-flex align-items-center mb-2">
  <input type="text" style="width: 83%; height: 10px; border-bottom: 1px solid black;" />
  <span>in the amount of</span>
</div>
    <div class="left" style="font-size: 10px;">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Purpose of assistance</div>
  </div>

  <!-- Amount Line -->
  <div class="d-flex justify-content-between align-items-end mb-2">
    <div style="width: 80%;">
    <div class="d-flex align-items-center mb-2">
    <input type="text" style="width: 100%; height: 10px; border-bottom: 1px solid black;" />
  <span>&nbsp;PhP</span>
</div>
      <div class="text-center" style="font-size: 10px;">Amount in words</div>
    </div>
    <div style="width: 20%;">
    <input type="text" style="width: 100%; height: 10px; border-bottom: 1px solid black;" />
      <div class="left" style="font-size: 10px;">&emsp;Amount in figures</div>
    </div>
  </div>

  <!-- Payable To -->
  <div class="mb-2 d-flex align-items-end">
    <div style="flex-grow: 1;">
    <div class="d-flex align-items-center mb-2">
    <span>payable to</span>
    <input type="text" style="width: 87%; height: 10px; border-bottom: 1px solid black;" />
</div>
      <div class="text-center" style="font-size: 10px;">Name of Service provider</div>
    </div>
  </div>

  <!-- Address -->
  <div>
  <input type="text" style="width: 100%; height: 10px; border-bottom: 1px solid black;" />
    <div class="text-center" style="font-size: 10px;">Address of Service provider</div>
  </div>
</div>

</div>
 <!-- Signatures -->
 <div class="row mt-3">
    <div class="col-md-6 text-center" style="border-left: 0; border-right: 0;border-bottom: 0;">
      <label class="fw-bold">Prepared and certified by:</label>
      <hr style="width: 80%;">
      <p class="mb-0">Social Worker</p>
      <small>(Signature over Printed Name)</small>
      <br>
      <label>License Number: 
      <input type="text" style="height: 10px; border-bottom: 1px solid black;" /></label>
    </div>
    <div class="col-md-6 text-center" style="border-left: 0; border-right: 0;border-bottom: 0;">
      <p class="fw-bold">Approved by:</p>
      <hr style="width: 80%;"><?php echo strtoupper($head);?>
      <p class="mb-0">Approving Authority</p>
      <small>(Signature over Printed Name)</small>
    </div>
  </div>
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
		<div class="row" style="font-size: 12px; margin-top: -5px">
			<div class="col-12 center">
				I acknowledged receipt of assistance in the amount of
				<input disabled class="text-center" type="text" style="font-size:15px;width:48%;height:15px;margin-top:-15px; border-bottom:1px solid black;" value="<?php echo numtowords($row['amount']);?> ONLY" />
				PHP <input disabled class="text-center" style="border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 10px; width: 15%;" value="<?php echo number_format($row['amount'],2);?>" />. <br>
				
				</div>
			</div>
        </div><br>
        <!-- Signatory -->
        <div class="row" style="font-size: 10px;">
            <div class="col">
                
            </div>
			<div class="col text-center"> 
                <b>Received by:</b>
            </div>
            <div class="col">
            </div>
        </div><br>
        <div class="row" style="font-size: 10px; line-height: 10px;">
            <div class="col text-center">
                <input disabled class="text-center" style="width: 100%; max-width: 750px; font-size: 14px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($fname.' '.$mname.' '.$lname.' '.$ename);?>" >
                <!-- <b>RDO/SDO</b> -->
				<br><div style="margin: 5px 0 -5px 0;">Client</div><br>
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
	</div>
    
    <div style="page-break-after: always;margin-bottom:5%;"></div>    <!-- newpage-->
                <div class="fluid-container print-page" id="coev2_print" style="font-size:12px; padding:0%;margin-left:5%;margin-top:5%;padding-right:5%;">    
          
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
                            <input disabled class="text-center" style="width: 30px; padding:0; border: 1px solid black; border-radius: 30px;" type="text" value="">
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
            This is to certify that, <input disabled class="text-center" style=" border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 18px; width: 62%;" value="<?php echo strtoupper($fname.' '.$mname.' '.$lname.' '.$ename);?>" />, 
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
    <input type="text" class="form-control" style="width: 150px; height: 20px; padding: 0 5px;" />
  </div>

  <!-- First Line -->
  <p style="font-size: 14px;">    
  &emsp;&emsp;&emsp;The client is hereby recommended to receive
  </p>
  <div class="mb-2">
  <div class="d-flex align-items-center mb-2">
  <input type="text" style="width: 85%; height: 10px; border-bottom: 1px solid black;" />
  <span>assistance for</span>
</div>
    <div class="left" style="font-size: 10px;">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Type of assistance: medical, burial, others</div>
  </div>

  <!-- Second Line -->
  <div class="mb-2">
  <div class="d-flex align-items-center mb-2">
  <input type="text" style="width: 83%; height: 10px; border-bottom: 1px solid black;" />
  <span>in the amount of</span>
</div>
    <div class="left" style="font-size: 10px;">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Purpose of assistance</div>
  </div>

  <!-- Amount Line -->
  <div class="d-flex justify-content-between align-items-end mb-2">
    <div style="width: 80%;">
    <div class="d-flex align-items-center mb-2">
    <input type="text" style="width: 100%; height: 10px; border-bottom: 1px solid black;" />
  <span>&nbsp;PhP</span>
</div>
      <div class="text-center" style="font-size: 10px;">Amount in words</div>
    </div>
    <div style="width: 20%;">
    <input type="text" style="width: 100%; height: 10px; border-bottom: 1px solid black;" />
      <div class="left" style="font-size: 10px;">&emsp;Amount in figures</div>
    </div>
  </div>

  <!-- Payable To -->
  <div class="mb-2 d-flex align-items-end">
    <div style="flex-grow: 1;">
    <div class="d-flex align-items-center mb-2">
    <span>payable to</span>
    <input type="text" style="width: 87%; height: 10px; border-bottom: 1px solid black;" />
</div>
      <div class="text-center" style="font-size: 10px;">Name of Service provider</div>
    </div>
  </div>

  <!-- Address -->
  <div>
  <input type="text" style="width: 100%; height: 10px; border-bottom: 1px solid black;" />
    <div class="text-center" style="font-size: 10px;">Address of Service provider</div>
  </div>
</div>

</div>
 <!-- Signatures -->
 <div class="row mt-3">
    <div class="col-md-6 text-center" style="border-left: 0; border-right: 0;border-bottom: 0;">
      <label class="fw-bold">Prepared and certified by:</label>
      <hr style="width: 80%;">
      <p class="mb-0">Social Worker</p>
      <small>(Signature over Printed Name)</small>
      <br>
      <label>License Number: 
      <input type="text" style="height: 10px; border-bottom: 1px solid black;" /></label>
    </div>
    <div class="col-md-6 text-center" style="border-left: 0; border-right: 0;border-bottom: 0;">
      <p class="fw-bold">Approved by:</p>
      <hr style="width: 80%;"><?php echo strtoupper($head);?>
      <p class="mb-0">Approving Authority</p>
      <small>(Signature over Printed Name)</small>
    </div>
  </div>
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
		<div class="row" style="font-size: 12px; margin-top: -5px">
			<div class="col-12 center">
				I acknowledged receipt of assistance in the amount of
				<input disabled class="text-center" type="text" style="font-size:15px;width:48%;height:15px;margin-top:-15px; border-bottom:1px solid black;" value="<?php echo numtowords($row['amount']);?> ONLY" />
				PHP <input disabled class="text-center" style="border: none; border-bottom: 1px solid black; padding:0px; margin:0px; height: 10px; width: 15%;" value="<?php echo number_format($row['amount'],2);?>" />. <br>
				
				</div>
			</div>
        </div><br>
        <!-- Signatory -->
        <div class="row" style="font-size: 10px;">
            <div class="col">
                
            </div>
			<div class="col text-center"> 
                <b>Received by:</b>
                    </div>
                    <div class="col">
                    </div>
                </div><br>
                <div class="row" style="font-size: 10px; line-height: 10px;">
                    <div class="col text-center">
                        <input disabled class="text-center" style="width: 100%; max-width: 750px; font-size: 14px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($fname.' '.$mname.' '.$lname.' '.$ename);?>" >
                        <!-- <b>RDO/SDO</b> -->
                        <br><div style="margin: 5px 0 -5px 0;">Client</div><br>
                        <span style="font-size: 10px;">(Signature Over Printed Name)</span>
                    </div>
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
	</div>
		<?php }?>
    </body>
</html>