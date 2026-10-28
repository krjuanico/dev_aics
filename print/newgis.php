<?php
// Must stay at the very top: no HTML, spaces or blank lines before this tag,
// otherwise session_start() fails with "headers already sent".
error_reporting(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '4096M');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../dbconnect.php";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>General Intake Sheet</title>
<link rel="stylesheet" href="cpms/css/coe.css">
<link rel="stylesheet" type="text/css" href="cpms/css/bootstrap.min.css"> 

<html>
    <body>
        <style>
            html, body { margin: 0; padding: 0; }
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
            font-size: 11px;
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
    background-color: #000;
    color: #ffffff; /* White text */
    padding: 8px 12px;
    font-size: 15px;
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

    /* ===== Header option boxes (Program / Mode of implementation / Mode of admission) ===== */
    .hdr-wrap {
      display: flex;
      align-items: stretch;
      border: 1px solid #000;
      background: #fff;
      margin-top: -18px;
      margin-left: -14px;
      margin-right: -15px;
    }
    .hdr-sec {
      padding: 4px 8px 6px 8px;
      border-right: 1px solid #000;
    }
    .hdr-sec:last-child { border-right: none; }
    .hdr-title {
      font-size: 12px;
      line-height: 14px;
      margin-bottom: 2px;
    }
    .opt {
      display: flex;
      align-items: center;
      gap: 5px;
      margin-bottom: 3px;
      white-space: nowrap;
      font-size: 12px;
      font-weight: bold;
      line-height: 14px;
    }
    .opt.plain { font-weight: normal; }
    .opt .box {
      flex: 0 0 16px;
      width: 16px;
      height: 16px;
      border: 1px solid #000;
      padding: 0;
      text-align: center;
      font-size: 12px;
      line-height: 14px;
    }
    .opt .box.round {
      flex: 0 0 18px;
      width: 18px;
      height: 18px;
      border-radius: 50%;
    }
    .date-row { display: flex; }
    .date-row input {
      height: 20px;
      border: 1px solid #000;
      padding: 0;
      margin: 0;
    }
    .date-row input + input { margin-left: -1px; }
    .date-labels { display: flex; font-size: 11px; font-weight: bold; font-style: italic; text-align: center; }

    /* ===== Diagnosis / Mode of assistance / Amount needed ===== */
    .info-block {
      padding: 6px 8px 8px 8px;
      font-size: 12px;
      line-height: 16px;
    }
    .info-row {
      display: flex;
      align-items: flex-end;
      gap: 4px;
      margin-bottom: 6px;
    }
    .info-row .lbl {
      font-weight: bold;
      white-space: nowrap;
    }
    .info-row input.line {
      flex: 1;
      min-width: 0;
      height: 18px;
      padding: 0 4px;
      margin: 0;
      border: none;
      border-bottom: 1px solid #000;
      background: transparent;
      font-size: 12px;
      box-shadow: none;
    }
    /* ===== Page 2 ===== */
    .p2-box { border:1px solid #000; background:#fff; font-size:12px; line-height:15px; }
    .p2-bar, .p2-head > div, .p2-table th {
    background:#000; color:#fff; font-weight:bold;
    -webkit-print-color-adjust:exact; print-color-adjust:exact;
    }
    .p2-bar { text-transform:uppercase; padding:3px 8px; }
    .p2-sub { font-weight:bold; font-style:italic; margin:6px 0 3px; }
    .p2-grid { display:grid; grid-template-columns:1.2fr 1.5fr 1.2fr .9fr 1fr; }
    .p2-head > div { display:flex; align-items:center; padding:3px 6px; font-size:11px; border-right:2px solid #fff; }
    .p2-head > div:last-child { border-right:none; }
    .p2-head .box { background:#fff; }
    .p2-cell { padding:5px 8px; }
    .p2-line { border-bottom:1px solid #000; height:16px; margin-bottom:4px; }
    .p2-table { width:100%; border-collapse:collapse; }
    .p2-table th { text-align:center; padding:3px; }
    .p2-table td { border:1px solid #000; height:22px; padding:0 4px; }
    .p2-table td.n { width:4%; text-align:center; }

    @page {
        size: A4 portrait;   /* 210 x 297mm */
        margin: 7mm;         /* 0 = no paper margin */
    }

    @media print {
        html, body { margin: 0; padding: 0; }

        /* A4 is shorter than 8.5x13in, so shrink each page a little to fit.
        Raise or lower these values until each page fits on one sheet. */
        #gisv2_print { zoom: 1; }
        .pg2        { zoom: 1; }

        .print-page { page-break-before: always; }
    }

        </style>
		<?php
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
			$fname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['first_name']);
			$mname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['middle_name']);
			$lname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['last_name']);   
			$ename = $row['extension_name'];   
			
			$amount = $row['amount'];
			$dob = $row['birth_day'].'/'.$row['birth_month'].'/'.$row['birth_year'];

			// Program / charging: normalized once, used by the Program checkboxes below.
			// ($isOther was never defined before, and error_reporting(0) hid the notice.)
			$charging    = strtoupper(trim((string)$row['charging']));
			$isAics      = ($charging === 'AICS');
			$isFfs       = ($charging === 'FFS');
			$isWiSupport = in_array($charging, ['WI-SUPPORT', 'WI SUPPORT', 'WISUPPORT'], true);
			$isOther     = ($charging !== '' && !$isAics && !$isFfs && !$isWiSupport);

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

        <div class="fluid-container" id="gisv2_print" style="font-size:12px; margin:0 20px 0 20px">
            
            <!--HEADER-->
            <div class="row" >
                <div class="col-6">
                    <img src="cpms/images/logo.png" alt="" width="240px" height="70px">

                </div>
            </div><center>
            <p class="header" style="margin-top: 20px; margin-left: -14px; margin-right: -15px; font-size: 20px;"><b>GENERAL INTAKE SHEET</b> </p>
            </center>
            
            <!-- OPTION BOXES: Program / Mode of implementation / Mode of admission -->
            <div class="hdr-wrap">

                <!-- Program -->
                <div class="hdr-sec" style="flex:0 0 auto; min-width:200px;">
                    <div class="hdr-title">Program:</div>
                    <div class="opt"><input disabled class="box" type="text" value="<?php echo $isAics ? '✔' : ''; ?>"> AICS</div>
                    <div class="opt"><input disabled class="box" type="text" value="<?php echo $isFfs ? '✔' : ''; ?>"> FFS</div>
                    <div class="opt"><input disabled class="box" type="text" value="<?php echo $isWiSupport ? '✔' : ''; ?>"> Wi-Support</div>
                    <div class="opt"><input disabled class="box" type="text" value="<?php echo $isOther ? '✔' : ''; ?>"> Others:</div>
                    <div style="border-bottom:1px solid #000; width:160px; min-height:14px; margin-left:2px; font-size:11px; line-height:14px; font-weight:bold; text-transform:uppercase;"><?php echo $isOther ? htmlspecialchars($charging) : ''; ?></div>
                </div>

                <!-- Mode of implementation -->
                <div class="hdr-sec" style="flex:0 0 auto; min-width:250px;">
                    <div class="hdr-title">Mode of implementation:</div>
                    <div class="opt"><input disabled class="box" type="text" value=""> Onsite</div>
                    <div class="opt"><input disabled class="box" type="text" value=""> Malasakit Center</div>
                    <div class="opt"><input disabled class="box" type="text" value=""> Offsite</div>
                </div>

                <!-- Mode of admission -->
                <div class="hdr-sec" style="flex:1 1 auto;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                        <div>
                            <div class="hdr-title">Mode of admission:</div>
                            <div style="display:flex; gap:14px;">
                                <div class="opt plain"><input disabled class="box" type="text" value=""> Walk-in</div>
                                <div class="opt plain"><input disabled class="box" type="text" value=""> Referral</div>
                            </div>
                        </div>
                        <div style="text-align:left; margin-top:12px;">
                            <div style="display:flex; align-items:center; gap:4px; font-size:13px;">
                                <span>Date :</span>
                                <div>
                                    <div class="date-row">
                                        <input disabled type="text" style="width:44px;">
                                        <input disabled type="text" style="width:44px;">
                                        <input disabled type="text" style="width:66px;">
                                    </div>
                                    <div class="date-labels">
                                        <div style="width:44px;">MM</div>
                                        <div style="width:44px;">DD</div>
                                        <div style="width:66px;">YYYY</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-left:40px; margin-top:6px;">
                        <div class="opt plain"><input disabled class="box round" type="text" value=""> New</div>
                        <div class="opt plain"><input disabled class="box round" type="text" value=""> Returning</div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12 " style="border: 1px solid black; font-size:14px;line-height: 12px;"> <br>
                         
CLIENT'S NAME
            <div class="row" style="margin-top: 10px;">
                <div class="col-4">
                    <input disabled class="text-center" style="width: 100%; height:100%; font-weight: bold; font-size: 15px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="20px" value="<?php echo strtoupper($lname);?>">
                </div>
                <div class="col-4">
                    <input disabled class="text-center" style="width: 100%; height:100%; font-weight: bold; font-size: 15px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="20px" value="<?php echo strtoupper($fname);?>">
                </div>
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:100%; font-weight: bold; font-size: 15px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="20px" value="<?php echo strtoupper($mname);?>">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:100%; font-weight: bold; font-size: 15px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="20px" value="<?php echo strtoupper($ename);?>">
                </div>
            </div>
            <div class="row" style="margin-top: 1px;">
                <div class="col-4">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; ">Last Name</p>
                </div>
                <div class="col-4">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; ">First Name</p>
                </div>
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; ">Middle Name</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Ext.</b> <i>(Sr.,Jr.)</i></p>
                </div>
            </div>
            
            <div class="form-group row align-items-center">
  <label class="col-auto col-form-label font-weight-bold">BENEFICIARY'S NAME</label>
  
  <div class="col-auto d-flex align-items-center">
    <input type="checkbox" class="form-control mr-2" style="width: 10px; height: 10px;">
    <span style="font-weight: bold; font-size: 11px;">SAME AS ABOVE</span>
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
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; ">Last Name</p>
                </div>
                <div class="col-4">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; ">First Name</p>
                </div>
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; ">Middle Name</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Ext.</b> <i>(Sr.,Jr.)</i></p>
                </div>
            </div>
            
            </div>
            
            </div>

            <div class="row">
                <div class="col-12 " style="border: 1px solid black; font-size:14px;line-height: 12px;">

                    <!-- Diagnosis / Mode of assistance / Amount needed -->
                    <div class="info-block">

                        <!-- Diagnosis -->
                        <div class="info-row">
                            <span class="lbl">DIAGNOSIS/CAUSE OF DEATH <i>(if applicable)</i>:</span>
                            <input type="text" class="line">
                        </div>

                        <!-- Mode of assistance -->
                        <div style="display:flex; align-items:center; flex-wrap:wrap; gap:4px 20px; margin-bottom:6px;">
                            <span style="font-weight:bold; white-space:nowrap;">MODE OF ASSISTANCE :</span>
                            <div class="opt" style="margin:0;"><input disabled class="box" type="text" value="&#x2714;"> Outright Cash</div>
                            <div class="opt" style="margin:0;"><input disabled class="box" type="text" value=""> Guarantee Letter</div>
                        </div>

                        <!-- Amount needed -->
                        <div class="info-row" style="margin-bottom:0;">
                            <span class="lbl">AMOUNT NEEDED <i>(if applicable)</i>: PhP</span>
                            <input type="text" class="line" value="<?php echo numtowords($row['amount']);?> only">
                        </div>

                    </div>


            <div class="fluid-container">
    <div style="display: flex; justify-content: space-between;">
        <div style="width: 50%; border: 1px solid #000; padding: 0px 0;margin-left:-16px">
         <!-- Section I -->
         <div class="section-title">I. INCOME AND FINANCIAL RESOURCES</div>
            <div class="mb-2 mt-2" style="padding:0 16px 8px 24px; font-size:14px; line-height:16px;">

            <div style="font-weight:bold; margin-bottom:4px;">Income status of family member</div>

            <!-- With fixed income -->
            <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-left:16px; margin-bottom:6px;">
                <div>
                    With fixed income<br>
                    <i style="font-size:11px;">(indicate number of members working)</i>
                </div>
                <input type="text" style="width:150px; height:18px; border:none; border-bottom:1px solid #000;">
            </div>

            <!-- With non-fixed income -->
            <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-left:16px; margin-bottom:10px;">
                <div>
                    With non-fixed income<br>
                    <i style="font-size:11px;">(indicate number of members working)</i>
                </div>
                <input type="text" style="width:150px; height:18px; border:none; border-bottom:1px solid #000;">
            </div>

            <!-- Other Financial Resources -->
            <div style="margin-left:60px; margin-bottom:10px;">
                <div style="margin-bottom:3px;">Other Financial Resources:</div>
                <div class="opt" style="font-size:14px;"><input disabled class="box" type="text" value=""> Insurance coverage</div>
                <div class="opt" style="font-size:14px;"><input disabled class="box" type="text" value=""> Savings</div>
                <div class="opt" style="font-size:14px;"><input disabled class="box" type="text" value=""> Pension</div>
            </div>

            <!-- Estimated combined monthly income -->
            <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-left:16px;">
                <div style="font-weight:bold;">Estimated combined monthly<br>income and resources</div>
                <div style="display:flex; align-items:flex-end; gap:6px;">
                    <span>PhP</span>
                    <input type="text" style="width:150px; height:18px; border:none; border-bottom:1px solid #000;">
                </div>
            </div>

        </div>

            <!-- Section II -->
        <div class="section-title mt-4">II. BUDGET AND EXPENSES</div>
        <div class="mb-2 mt-2" style="padding:0 16px 8px 24px; font-size:14px; line-height:16px;">

            <!-- Estimated monthly expenses -->
            <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-left:16px; margin-bottom:10px;">
                <div>
                    <div style="font-weight:bold;">Estimated monthly expenses of the family</div>
                    <i style="font-size:10px; line-height:12px; display:block; max-width:260px;">(Utility bills, Maintenance and Medication, Mortgage/Rent, Debt, and Others)</i>
                </div>
                <div style="display:flex; align-items:flex-end; gap:6px;">
                    <span>PhP</span>
                    <input type="text" style="width:110px; height:18px; border:none; border-bottom:1px solid #000;">
                </div>
            </div>

            <!-- Availability of emergency fund -->
            <div style="display:flex; align-items:center; justify-content:space-between; margin-left:16px;">
                <div style="font-weight:bold;">Availability of emergency fund</div>
                <div style="display:flex; align-items:center; gap:16px;">
                    <div class="opt plain" style="margin:0; font-size:14px;"><input disabled class="box" type="text" value=""> Yes</div>
                    <div class="opt plain" style="margin:0; font-size:14px;"><input disabled class="box" type="text" value=""> No</div>
                </div>
            </div>

        </div>

            <!-- Section III -->
        <div class="section-title mt-4">III. SEVERITY OF THE CRISIS</div>
        <div style="padding:0 16px 8px 24px; font-size:14px; line-height:17px;">

            <!-- Q1 -->
            <div style="font-weight:bold; margin-bottom:4px;">
                In the past three (3) months, did the family experience at least one crisis?
            </div>
            <div style="display:flex; gap:60px; margin-bottom:12px;">
                <div class="opt plain" style="margin:0; font-size:14px;"><input disabled class="box" type="text" value=""> Yes</div>
                <div class="opt plain" style="margin:0; font-size:14px;"><input disabled class="box" type="text" value=""> No</div>
            </div>

            <!-- Q2 -->
            <div style="font-weight:bold; margin-bottom:4px;">
                If yes, which among the following crises did the family experience in the past three (3) months (check all that apply):
            </div>
            <div style="margin-bottom:12px;">
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> Hospitalization</div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> Death of a family member</div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> Catastrophic Event (fire, earthquake, flooding, etc.)</div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> Disablement</div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> Loss of Livelihood</div>
                <div class="opt plain" style="font-size:14px;">
                    <input disabled class="box" type="text" value=""> Others, specify
                    <input type="text" style="width:170px; height:18px; border:none; border-bottom:1px solid #000;">
                </div>
            </div>

            <!-- Q3 -->
            <div style="font-weight:bold; margin-bottom:4px;">
                If applicable, how long is the patient experiencing the illness?
            </div>
            <div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> Recently diagnosed (3mos &amp; below)</div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> 3 months to a year</div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> chronic or lifelong</div>
            </div>

        </div>
        </div>
    <div style="width: 50%; border: 1px solid #000; padding: 0px 0;margin-right:-16px">
        
            <!-- Section IV -->
            <div class="section-title">IV. AVAILABILITY OF FINANCIAL SUPPORT</div>
            <div style="padding:0 16px 8px 40px; font-size:14px; line-height:16px;">
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> Relatives</div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> Friend/s</div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> Employer</div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> Church/Community Organization</div>
            </div>

            <!-- Section V -->
            <div class="section-title" style="margin-top:12px;">V. EXTERNAL RESOURCES TAPPED</div>
            <div style="padding:0 16px 8px 40px; font-size:14px; line-height:16px;">
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> Philhealth</div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> Health Card</div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> Guarantee Letter from other agencies</div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> MSS Discount</div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> Senior Citizen/ PWD Discount</div>
                <div class="opt plain" style="font-size:14px;">
                    <input disabled class="box" type="text" value=""> Others, specify
                    <input type="text" style="width:150px; height:18px; border:none; border-bottom:1px solid #000;">
                </div>
                <div class="opt plain" style="font-size:14px;"><input disabled class="box" type="text" value=""> None</div>
            </div>

            <!-- Section VI -->
            <div class="section-title" style="margin-top:12px;">VI. SELF HELP AND CLIENT EFFORTS</div>
            <div style="padding:4px 16px 8px 40px; font-size:14px; line-height:17px;">
                <div class="opt plain" style="font-size:14px; white-space:normal; margin-bottom:14px;">
                    <input disabled class="box" type="text" value="">
                    <span>Successfully sought employment opportunities<br>or explored additional income sources</span>
                </div>
                <div class="opt plain" style="font-size:14px; white-space:normal;">
                    <input disabled class="box" type="text" value="">
                    <span>Successfully reached out to relevant organizations<br>or agencies for financial assistance or support</span>
                </div>
            </div>

            <!-- Section VII -->
            <div class="section-title" style="margin-top:12px;">VII. VULNERABILITY AND RISK FACTORS</div>
            <div style="padding:4px 16px 8px 40px; font-size:14px; line-height:17px;">
                <div class="opt plain" style="font-size:14px; white-space:normal; margin-bottom:14px;">
                    <input disabled class="box" type="text" value="">
                    <span>There are elderly/ Child in need/ PWD/ Pregnant in<br>the household</span>
                </div>
                <div class="opt plain" style="font-size:14px; white-space:normal; margin-bottom:14px;">
                    <input disabled class="box" type="text" value="">
                    <span>A member is physically or mentally incapacitated to<br>work</span>
                </div>
                <div class="opt plain" style="font-size:14px; white-space:normal;">
                    <input disabled class="box" type="text" value="">
                    <span>Inability to secure stable employment</span>
                </div>
            </div>
    </div>
</div>
            <!--Client INFO-->
            
            <br><br><label style="font-size: 16px; margin-top: 40px;">Control No:<b> <?php echo $row['control_number'];?></b></label><br>
            <div class="footer row">
                <div class="col-12">
                    <div style="border-bottom: solid 1px black;"></div>
                    <p class="text-center" style="font-size: 10px;">Page 1 of 2<br>
                    DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
                    Website: http://fo11.dswd.gov.ph Tel Nos.: (082)_227 1964 / (082) 227 8746 / (082)_227 1435 Telefax: (082) 226 2857</p>
                </div>
            </div>
            <!--Container-->
        </div>
        </div>
    </div>
</div>
<div class="fluid-container pg2" style="font-size:12px; page-break-before:always;">
        <?php $chk = function($a, $b) { return ($a == $b) ? '✔' : ''; }; ?>

        <div class="p2-box">

            <!-- VIII. CLIENT SECTOR -->
            <div class="p2-bar">VIII. Client Sector</div>
            <div style="padding:4px 12px 10px;">

                <div class="p2-sub">Client Category:</div>
                <div style="display:flex; flex-wrap:wrap; gap:4px 26px;">
                    <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['category'],'FHONA'); ?>"> FHONA</div>
                    <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['category'],'WEDC'); ?>"> WEDC</div>
                    <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['category'],'CNSP'); ?>"> CNSP</div>
                    <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['category'],'YNSP'); ?>"> YNSP</div>
                    <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['category'],'SC'); ?>"> SC</div>
                    <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['category'],'PWD'); ?>"> PWD</div>
                    <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['category'],'PLHIV'); ?>"> PLHIV</div>
                </div>

                <div style="display:flex; gap:30px; margin-top:6px;">

                    <!-- Sub-category -->
                    <div style="flex:1 1 55%;">
                        <div class="p2-sub">Specify Sub-Category:</div>
                        <div style="display:flex; gap:26px;">
                            <div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['subcategory'],'SOLO PARENT'); ?>"> Solo Parent</div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['subcategory'],'INDIGENOUS PEOPLE'); ?>"> Indigenous People</div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['subcategory'],'STREET DWELLERS'); ?>"> Street Dwellers</div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['subcategory'],'KIA/WIA'); ?>"> KIA/WIA</div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['subcategory'],'4PS DSWD BENEFICIARY'); ?>"> 4PS Beneficiary</div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['subcategory'],'STATELESS PERSON'); ?>"> Stateless Person</div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['subcategory'],'ASYLUM SEEKERS'); ?>"> Asylum Seekers</div>
                            </div>
                            <div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['subcategory'],'REFUGEES'); ?>"> Refugees</div>
                                <div class="opt plain"><input disabled class="box" type="text" value=""> Overseas Filipino Worker</div>
                                <div class="opt plain"><input disabled class="box" type="text" value=""> Recovering Person Who Used Drugs</div>
                                <div class="opt plain"><input disabled class="box" type="text" value=""> Minimum Wage Earner</div>
                                <div class="opt plain"><input disabled class="box" type="text" value=""> No Regular Income</div>
                                <div class="opt plain"><input disabled class="box" type="text" value=""> Below Minimum Wage Earner</div>
                                <div class="opt plain"><input disabled class="box" type="text" value=""> Others: <span style="display:inline-block; width:90px; height:14px; border-bottom:1px solid #000;"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Type of disability -->
                    <div style="flex:1 1 45%;">
                        <div class="p2-sub">Type of Disability:</div>
                        <div style="display:flex; gap:26px;">
                            <div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_disability'],'Speech Impairment'); ?>"> Speech Impairment</div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_disability'],'Learning Disability'); ?>"> Learning Disability</div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_disability'],'Psychosocial Disability'); ?>"> Psychosocial Disability</div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_disability'],'Deaf/Hard-of-Hearing'); ?>"> Deaf/Hard-of-Hearing</div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_disability'],'Cancer'); ?>"> Cancer</div>
                            </div>
                            <div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_disability'],'Mental Disability'); ?>"> Mental Disability</div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_disability'],'Visual Disability'); ?>"> Visual Disability</div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_disability'],'Intellectual Disability'); ?>"> Intellectual Disability</div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_disability'],'Physical Disability'); ?>"> Physical Disability</div>
                                <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_disability'],'Rare Disease'); ?>"> Rare Disease</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- IX. PROBLEM PRESENTED -->
            <div class="p2-bar">IX. Problem Presented</div>
            <div style="height:280px;"></div>

            <!-- X. SOCIAL WORKER'S ASSESSMENT -->
            <div class="p2-bar">X. Social Worker's Assessment</div>
            <div style="height:350px;"></div>

            <!-- Assistance header bar -->
            <div class="p2-grid p2-head">
                <div><div class="opt" style="margin:0; white-space:normal;"><input disabled class="box" type="text" value=""><span>FINANCIAL ASSISTANCE</span></div></div>
                <div><div class="opt" style="margin:0; white-space:normal;"><input disabled class="box" type="text" value=""><span>MATERIAL ASSISTANCE</span></div></div>
                <div><div class="opt" style="margin:0; white-space:normal;"><input disabled class="box" type="text" value=""><span>PSYCHOSOCIAL SUPPORT</span></div></div>
                <div><div class="opt" style="margin:0; white-space:normal;"><input disabled class="box" type="text" value=""><span>FREE FUNERAL SERVICE</span></div></div>
                <div><div class="opt" style="margin:0; white-space:normal;"><input disabled class="box" type="text" value=""><span>REFERRAL:</span></div></div>
            </div>

            <!-- Assistance options -->
            <div class="p2-grid">
                <div class="p2-cell">
                    <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_assistance'],'MEDICAL ASSISTANCE'); ?>"> Medical</div>
                    <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_assistance'],'FUNERAL ASSISTANCE'); ?>"> Funeral</div>
                    <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_assistance'],'TRANSPORTATION ASSISTANCE'); ?>"> Transportation</div>
                    <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_assistance'],'EDUCATIONAL ASSISTANCE'); ?>"> Educational</div>
                    <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_assistance'],'FOOD ASSISTANCE'); ?>"> Food</div>
                    <div class="opt plain"><input disabled class="box" type="text" value="<?php echo $chk($row['type_of_assistance'],'CASH RELIEF ASSISTANCE'); ?>"> Cash Relief</div>
                </div>
                <div class="p2-cell">
                    <div class="opt plain"><input disabled class="box" type="text" value=""> Family Food Packs</div>
                    <div class="opt plain"><input disabled class="box" type="text" value=""> Other Food Items</div>
                    <div class="opt plain"><input disabled class="box" type="text" value=""> Hygiene &amp; Sleeping Kits</div>
                    <div class="opt plain"><input disabled class="box" type="text" value=""> Assistive Devices &amp; Technologies</div>
                    <div class="opt plain"><input disabled class="box" type="text" value=""> Rice</div>
                </div>
                <div class="p2-cell">
                    <div class="opt plain" style="white-space:normal; align-items:flex-start;"><input disabled class="box" type="text" value=""><span>Psychosocial First Aid (PFA)</span></div>
                    <div class="opt plain" style="white-space:normal; align-items:flex-start;"><input disabled class="box" type="text" value=""><span>Social Work Counseling</span></div>
                </div>
                <div class="p2-cell">
                    <div class="opt plain"><input disabled class="box" type="text" value=""> Burial</div>
                    <div class="opt plain"><input disabled class="box" type="text" value=""> Cremation</div>
                </div>
                <div class="p2-cell">
                    <div class="p2-line"></div>
                    <div class="p2-line"></div>
                    <div class="p2-line"></div>
                    <div class="p2-line"></div>
                    <div class="p2-line"></div>
                </div>
            </div>

            <!-- Purpose / Amount / Fund Source -->
            <table class="p2-table">
                <thead>
                    <tr>
                        <th colspan="2">Purpose of Assistance</th>
                        <th style="width:20%;">Amount</th>
                        <th style="width:20%;">Fund Source</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="n">1</td><td></td><td></td><td></td></tr>
                    <tr><td class="n">2</td><td></td><td></td><td></td></tr>
                    <tr><td class="n">3</td><td></td><td></td><td></td></tr>
                    <tr><td class="n">4</td><td></td><td></td><td></td></tr>
                </tbody>
            </table>

            <!-- Signatures -->
            <div style="display:flex; justify-content:space-around; padding:14px 20px 12px;">
                <div style="width:40%; text-align:center;">
                    <b>Interviewed by:</b>
                    <div style="height:60px;"></div>
                    <div style="border-top:1px solid #000; padding-top:2px;">Social Worker</div>
                    <small style="display:block;"><i>(Signature over Printed Name)</i></small>
                    <small style="display:block; margin-top:2px;">License no. _________________</small>
                </div>
                <div style="width:40%; text-align:center;">
                    <b>Reviewed &amp; Approved by:</b>
                    <div style="height:60px; display:flex; align-items:flex-end; justify-content:center; font-weight:bold;"><?php echo strtoupper($head); ?></div>
                    <div style="border-top:1px solid #000; padding-top:2px;">Approving Authority</div>
                    <small style="display:block;"><i>(Signature over Printed Name)</i></small>
                </div>
            </div>
                    <br><label style="font-size: 16px; margin-left:10px">Control No:<b> <?php echo $row['control_number'];?></b></label><br>
        <div class="footer row">
            <div class="col-12">
                <div style="border-bottom: solid 1px black;"></div>
                <p class="text-center" style="font-size: 10px;">Page 2 of 2<br>
                DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
                Website: http://fo11.dswd.gov.ph Tel Nos.: (082)_227 1964 / (082) 227 8746 / (082)_227 1435 Telefax: (082) 226 2857</p>
            </div>
        </div>

        </div>  
            <!--Container-->
        </div>

		<?php }?>
    </body>
</html>