
<link rel="stylesheet" href="cpms/css/coe.css">
<link rel="stylesheet" type="text/css" href="cpms/css/bootstrap.min.css"> 
<html>
<body>
<style>
	.header ,strong{
        background-color: black !important;
	color: white !important;
	-webkit-print-color-adjust: exact; 
	font-size: 14px;
	}
    @media print {
    .row {
      display: flex !important;
      flex-wrap: nowrap !important;
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

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 10px;
    }

    th, td {
      border: 1px solid #000;
      height: 10px;
      padding: 2px;
    }

    thead th {
      background-color: #f0f0f0;
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
    margin: 0.5in;
  }
body {
      font-family: Arial, sans-serif;
      font-size: 14px;
    }
    .consent-section {
      padding: 15px;
      margin: 20px auto;
      width: 100%;
    }
    .consent-header {
      background-color: #444;
      color: #fff;
      padding: 5px 10px;
      font-weight: bold;
    }
    .consent-header em {
      font-style: italic;
      font-weight: normal;
      color: #ddd;
    }
    .consent-body {
      text-align: justify;
      line-height: 1.6;
    }
    .signature-section {
      display: flex;
      justify-content: space-between;
      margin-top: 35px;
      align-items: flex-end;
    }
    .signature-line {
      border-top: 1px solid #000;
      width: 60%;
      text-align: center;
      padding-top: 5px;
    }
    .signature-line small {
      font-style: italic;
      font-size: 12px;
    }
    .thumbmark-box {
      border: 1px solid #000;
      width: 150px;
      height: 140px;
      text-align: center;
      font-size: 12px;
      line-height: 120px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    .checkboxes label {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .checkboxes {
      margin-top: 10px;
    }

    input[type="text"] {
      width: 100%;
      border: none;
      border-bottom: 1px solid #000;
      outline: none;
    }

    .note {
      font-style: italic;
      font-size: 12px;
      color: #555;
    }

    .row-input {
      margin-bottom: 10px;
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
				
?>
    <div class="container" id="coev2_print" style="font-size:12px; padding:0%;margin-left:5%;padding-right:5%;">
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
            <input type="checkbox" class="form-check-input me-2" /><p class="mb-0" style="font-size: 18px; font-family: Arial, sans-serif;margin-left:165">Field Office <u>&nbsp;&nbsp;&nbsp;XI&nbsp;&nbsp;&nbsp;</u></p>
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
        </div><br>
        <!--Second Row--><br>
        <p class="header" style="margin-top: -20px; margin-left: -15px; margin-right: -15px; font-size: 15px;"><strong>&emsp; IMPORMASYON NG KINATAWAN/KLIYENTE </strong>(Authorized Representative's/Client's Identifying Information)</p>
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
            <div class="row" style="margin-top: -8px;">
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['purok']);?>">
                </div>
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['barangay']);?>">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['city_municipality']);?>">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['province']);?>">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="Region XI">
                </div>
            </div>
            <div class="row" style="margin-top: -5px;">
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>House No./Street/Purok</b> (Ex. Purok 12)</p>
                </div>
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Barangay</b> (Ex. 23-C)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Municipality</b> (Ex. Davao City)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Province/District</b> (Ex. Dist. I)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Region</b> (XI)</p>
                </div>
            </div>
            <div class="row" style="margin-top: -8px;">
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['contact_number']);?>">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['birth_day'].'/'.$row['birth_month'].'/'.$row['birth_year']);?>">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo $age;?>">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['sex']);?>">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['civil_status']);?>">
                </div>
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo strtoupper($row['occupation']);?>">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="<?php echo number_format($row['monthly_salary'],2);?>">
                </div>
            </div>
            <div class="row" style="height: 25px; margin-top: -5px;">
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 8px; "><b>Numero ng Telepono</b> (Mobile No.)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Kapanganakan</b> (Birthdate)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Edad</b> (Age)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Kasarian</b></p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Civil Status</b> (Katayuang Sibil)</p>
                </div>
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Trabaho</b> (Occupation)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 7px; "><b>Buwanang Kita</b></p>
                </div>
            </div><br><br>
            <p class="header" style="margin-top: -20px; margin-left: -15px; margin-right: -15px; font-size: 15px;"><strong>&emsp; IMPORMASYON NG BENEPISYARYO </strong> (Beneficiary's Identifying Information)</p>
            <div class="row" style="margin-top:-8px; ">
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
            <div class="row" style="margin-top: -5px;">
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
            <div class="row" style="margin-top: -8px;">
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
            </div>
            <div class="row" style="margin-top: -5px;">
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>House No./Street/Purok</b> (Ex. Purok 12)</p>
                </div>
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Barangay</b> (Ex. 23-C)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Municipality</b> (Ex. Davao City)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Province/District</b> (Ex. Dist. I)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Region</b> (XI)</p>
                </div>
            </div>
            <div class="row" style="margin-top: -8px;">
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-1">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-2">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-3">
                    <input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
                <div class="col-1">
                    <input disabled class="text-center salary_monthly" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" height="30px" value="">
                </div>
            </div>
            <div class="row" style="margin-top: -5px; margin-bottom: -15px">
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 8px; "><b>Numero ng Telepono</b> (Mobile No.)</p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Kapanganakan</b> (Birthdate)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Edad</b> (Age)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Kasarian</b></p>
                </div>
                <div class="col-2">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Civil Status</b> (Katayuang Sibil)</p>
                </div>
                <div class="col-3">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Trabaho</b> (Occupation)</p>
                </div>
                <div class="col-1">
                    <p class="text-center" style="width: 100%; height:80%; font-size: 7px; "><b>Buwanang Kita</b></p>
                </div>
            </div>

<br>
    <table class="table table-bordered" width="100%">
      <tr>
        <td style="width: 30%;">
          Ikaw ba ay nakakuha na ng tulong mula sa DSWD?<br>
          <span class="note">(Have you received any assistance from the DSWD?)</span>
          <div class="checkboxes">
            <label><input type="checkbox" name="assistance" value="Hindi"> Hindi</label>
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
  <p class="header" style="margin-top: -30px; margin-left: -15px; margin-right: -15px; font-size: 15px;"><strong>&emsp;KOMPOSISYON NG PAMILYA </strong> (Family Composition)</p>
  <table class="table bordered" width="100%" style="margin-top: -18px;">
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
            <br>
            <p class="header" style="margin-top: -20px; margin-left: -15px; margin-right: -15px; font-size: 15px;"><strong>&emsp;DEKLARASYON NG PAHINTULOT </strong> (Consent Form)																				</p>

        <!--Assistance-->
        <div class="consent-section" style="font-family: Arial, sans-serif; font-size: 14px; margin-top:1px;">

  <div class="consent-body" style="text-align: justify;">
    &emsp;Ako ay nagdedeklara sa ilalim ng parusa ng pagsisinungaling (perjury), na ang lahat ng impormasyon sa aplikasyon na ito ay totoo at tama batay sa aking personal na kaalaman at mga autentikong rekord na isinimute sa Department of Social Welfare and Development (DSWD). Anumang mali o mapanlinlang na impormasyon na ibinigay, o paggawa ng pekeng/pinagwaling na mga dokumento ay magiging sanhi ng nararapat na hakbang na legal laban sa akin at awtomatikong magpawalang-bisa sa anumang tulong na ibibigay kaugnay ng aplikasyon na ito.
    <br><br>
    &emsp;Ako ay sumasang-ayon na ang lahat ng personal na datos (ayon sa depinisyon sa ilalim ng Republic Act 10173 o Data Privacy Law ng 2012 at mga patnubay nito) at impormasyon o mga rekord ng mga transaksyon sa account sa DSWD ay maaaring iproseso, i-profile, o ibahagi sa mga humihiling na partido o para sa layunin ng anumang hukuman, proseso ng batas, pagsusuri, inquiry, audit, o imbestigasyon ng anumang awtoridad.
  </div>

  <div class="row mt-5" style="display: flex; justify-content: space-between; align-items: flex-start; margin-top: -10px;">
    
    <!-- Signature Section -->
    <div class="col-md-9" style="width: 100%;">
      <div class="" style="text-align: center; margin-top: 5px;">
      __<u><?php echo strtoupper($fname.' '.$mname.' '.$lname.' '.$ename);?></u>____<br>
        <b>Lagda sa ibabaw ng Buong Pangalan ng Kinatawan/Kliyente</b><br>
        <small>(Signature over Printed Name of the Authorized Representative/Client)</small>
      </div>
    </div>

    <!-- Thumbmark Section -->
    <div class="col-md-2" style="text-align: center; border: 1px solid #000; height: 180px;margin-top: -20px; width: 80px;margin-right: 80px;">
      <div style="margin-top: 150px;">Thumbmark</div>
    </div>
  </div>

</div><br>

       
        <div class="footer row">
            <div class="col-12">
                <div style="border-bottom: solid 1px black;margin-top:-40px;"></div>
                <p class="text-center">Page 1 of 2<br>
                DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
                Website: http://fo11.dswd.gov.ph Tel Nos.: (082)_227 1964 / (082) 227 8746 / (082)_227 1435 Telefax: (082) 226 2857</p>
            </div>
        </div><br><br>
        <p class="header" style="margin-top: -20px; margin-left: -15px; margin-right: -15px; font-size: 15px;"><strong>&emsp;NATANGGAP NA TULONG MULA SA DSWD </strong>(Assistance Received from DSWD)																																										</p>
        <table class="table bordered" width="100%">
                <thead>
                    <th></th>
                    <th>Natanggap na tulong (Assistance received)</th>
                    <th>Petsa ng tulong (Date of assistance received)</th>
            </thead>
                <tbody>
                    <tr>
                        <td>6</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>12</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>13</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>14</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>16</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>17</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>18</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>19</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>20</td>
                        <td></td>
                        <td></td>
                    </tr>
            </tbody>    
            </table>
            <br><br>
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
                        <td>6</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>12</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>13</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>14</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>16</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>17</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>18</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>19</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>20</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
            </tbody>    
            </table>
            <br><br>
                        <!--Container-->
	
    <center>
    <div class="footer row">
            <div class="col-12">
                <div style="border-bottom: solid 1px black;"></div>
                <p class="text-center">Page 2 of 2<br>
                DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
                Website: http://fo11.dswd.gov.ph Tel Nos.: (082)_227 1964 / (082) 227 8746 / (082)_227 1435 Telefax: (082) 226 2857</p>
            </div>
        </div></center>
			<?php }?>
</body>

</html>