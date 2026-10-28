<?php
// Must stay at the very top: no HTML, spaces or blank lines before this tag,
// otherwise session_start() fails with "headers already sent".
error_reporting(0);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../dbconnect.php";

$head = "";
$emm = mysqli_real_escape_string($conn, $_SESSION['userid']);
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

/**
 * Returns a font size (px) for text that has to fit one line.
 * $capacity = roughly how many characters fit at the normal size ($base).
 * Longer text gets a proportionally smaller font, never below $min.
 * This runs on the server, so it works even if JavaScript does not run.
 */
function fitsize($text, $capacity, $base = 13, $min = 6) {
    $len = function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);
    if ($len <= $capacity) return $base;
    return max($min, round($base * $capacity / $len, 1));
}

// Retrieve the beneficiary record
$beneId = mysqli_real_escape_string($conn, $_GET['beneId']);
$sql = "SELECT * FROM aics_clean_list
        WHERE control_number='$beneId'";
$result = mysqli_query($conn, $sql);
$age = '';

if ($row = mysqli_fetch_assoc($result)) {
    $first_name  = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['first_name']);
    $middle_name = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['middle_name']);
    $last_name   = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['last_name']);
    $extension_name = $row['extension_name'];
    $amount = $row['amount'];

    $dob = $row['birth_day'].'/'.$row['birth_month'].'/'.$row['birth_year'];
    $charging = strtoupper($row['charging']);
    $dob = str_replace('-', '/', $dob); // Replace dashes with slashes
    $dobDate = DateTime::createFromFormat('d/m/Y', $dob);

    if ($dobDate !== false) {
        $currentDate = new DateTime();
        $ageInterval = $currentDate->diff($dobDate);
        $age = $ageInterval->y;
    }

    // Program checks
    $isAics  = ($charging == 'AICS');
    $isFfs   = ($charging == 'FFS');
    $isOther = ($charging != '' && !$isAics && !$isFfs);

    // Name is printed as: LAST, FIRST MIDDLE EXT
    $full_name = strtoupper(trim($last_name.', '.trim($first_name.' '.$middle_name.' '.$extension_name)));
    $address   = trim($row['purok'].' '.$row['barangay'].', '.$row['city_municipality'].', '.$row['province']);
    $amount_words = numtowords($row['amount']).' ONLY';

    // Font sizes for long text (capacity = approx. characters that fit at 13px)
    $fs_name_cert = fitsize($full_name, 56);   // "This is to certify that," line
    $fs_name_recv = fitsize($full_name, 46);   // "Received by" line (narrower)
    $fs_address   = fitsize($address, 60);
    $fs_words     = fitsize($amount_words, 58);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Certificate of Eligibility</title>
<link rel="stylesheet" href="cpms/css/coe.css">
<link rel="stylesheet" type="text/css" href="cpms/css/bootstrap.min.css"> 
<html>
<body>
<style>
    #coev2_print {
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
        color: #000;
        padding: 0;
    }

    /* Disabled inputs must print black, not gray */
    #coev2_print input:disabled,
    #coev2_print input[readonly] {
        color: #000;
        -webkit-text-fill-color: #000;
        opacity: 1;
    }

    /* ===== Checkbox / radio options ===== */
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
        margin: 0;
        text-align: center;
        font-size: 12px;
        line-height: 14px;
        background: #fff;
    }
    .opt .box.round {
        flex: 0 0 18px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
    }
    .ce-title { font-weight: bold; font-size: 11px; margin-bottom: 2px; }
    .ul {
        display: inline-block;
        border-bottom: 1px solid #000;
        min-width: 70px;
        height: 14px;
        line-height: 13px;
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
    }

    /* ===== Date boxes ===== */
    .date-row { display: flex; }
    .date-row input {
        width: 44px;
        height: 20px;
        border: 1px solid #000;
        padding: 0;
        margin: 0;
        text-align: center;
    }
    .date-row input:last-child { width: 66px; }
    .date-row input + input { margin-left: -1px; }
    .date-labels { display: flex; font-size: 9px; font-style: italic; text-align: center; }
    .date-labels div { width: 44px; }
    .date-labels div:last-child { width: 66px; }

    /* ===== Fill-in lines with a caption underneath ===== */
    .ce-line {
        display: flex;
        align-items: flex-start;
        gap: 6px;
        margin-bottom: 8px;
        font-size: 13px;
    }
    .ce-line > span { line-height: 20px; white-space: nowrap; }
    .fld { display: flex; flex-direction: column; flex: 1; min-width: 0; }
    .fld input.val, input.val {
        width: 100%;
        height: 20px;
        padding: 0 4px;
        margin: 0;
        border: none;
        border-bottom: 1px solid #000;
        border-radius: 0;
        background: transparent;
        box-shadow: none;
        font: inherit;
        font-size: 13px;
        font-weight: bold;
        line-height: 20px;
        text-align: center;
        text-transform: uppercase;
    }
    /* Inputs marked "fit": the font size is set per field (PHP inline style + JS refinement) */
    input.val.fit {
        text-overflow: clip;
        white-space: nowrap;
    }
    .fld .cap {
        font-size: 9px;
        font-style: italic;
        text-align: center;
        line-height: 11px;
        margin-top: 1px;
    }

    /* ===== Boxes and gray bars ===== */
    .ce-box { border: 1px solid #000; background: #fff; }
    .ce-bar {
        background: #bfbfbf !important;
        border: 1px solid #000;
        text-align: center;
        font-weight: bold;
        font-size: 12px;
        line-height: 16px;
        padding: 2px 0;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .ce-box > .ce-bar { border: none; border-bottom: 1px solid #000; }
    .ce-boxhead {
        text-align: center;
        font-weight: bold;
        font-size: 11px;
        line-height: 13px;
        padding: 3px 10px;
        border-bottom: 1px solid #000;
    }
    .ce-records .opt {
        font-size: 11px;
        font-weight: normal;
        line-height: 13px;
        white-space: normal;
        align-items: flex-start;
    }

    @page {
        size: A4 portrait;
        margin: 7mm;      
    }

    @media print {
        html, body { margin: 0; padding: 0; }

        .print-page { page-break-before: always; }
    }
</style>

<div id="coev2_print">

    <div class="print-coe" style="border:1px solid #000; padding:10px 20px;">

    <!-- HEADER -->
    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
        <div>
            <img src="cpms/images/logo.png" alt="" width="230px" height="70px" >
        </div>
        <div style="padding-top:4px;">
            <div class="opt plain" style="font-size:10px; line-height:1.2; font-weight:bold;"><input disabled class="box" type="text" value=""> Central Office</div>
            <div class="opt plain" style="font-size:10px; line-height:1.2; font-weight:bold;"><input disabled class="box" type="text" value="&#x2714;"> Field Office <span class="ul" style="min-width:90px;">XI</span></div>
        </div>
    </div>

    <!-- TITLE -->
    <div style="text-align:center; font-size:26px; font-weight:bold; margin:30px 0 10px;">CERTIFICATE OF ELIGIBILITY</div>

    <!-- QN / DATE -->
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px;">
        <div style="display:flex; align-items:center; gap:6px; font-size:12px;">
            <b>QN:</b>
            <input readonly type="text" style="width:130px; height:20px; border:1px solid #000; padding:0 4px;" value="<?php echo htmlspecialchars($row['id']); ?>">
        </div>
        <div style="display:flex; align-items:flex-start; gap:6px; font-size:12px;">
            <b style="line-height:20px;">Date:</b>
            <div>
                <div class="date-row">
                    <input disabled type="text"><input disabled type="text"><input disabled type="text">
                </div>
                <div class="date-labels"><div>MM</div><div>DD</div><div>YYYY</div></div>
            </div>
        </div>
    </div>

    <!-- PROGRAM / MODE OF IMPLEMENTATION / MODE OF ADMISSION -->
    <div style="display:flex; gap:34px; margin:4px 0 10px 70px; align-items:flex-start;">

        <div>
            <div class="ce-title">Program:</div>
            <div class="opt"><input disabled class="box" type="text" value="<?php echo $isAics ? '✔' : ''; ?>"> AICS</div>
            <div class="opt"><input disabled class="box" type="text" value="<?php echo $isFfs ? '✔' : ''; ?>"> FFS</div>
            <div class="opt"><input disabled class="box" type="text" value="<?php echo $isOther ? '✔' : ''; ?>"> Others: <span class="ul" style="min-width:60px;"><?php echo $isOther ? htmlspecialchars($charging) : ''; ?></span></div>
        </div>

        <div>
            <div class="ce-title">Mode of Implementation:</div>
            <div class="opt plain"><input disabled class="box" type="text" value=""> Onsite</div>
            <div class="opt plain"><input disabled class="box" type="text" value=""> Malasakit Center</div>
            <div class="opt plain"><input disabled class="box" type="text" value=""> Offsite</div>
        </div>

        <div>
            <div class="ce-title">Mode of Admission:</div>
            <div style="display:flex; gap:16px;">
                <div class="opt plain"><input disabled class="box" type="text" value=""> Walk-in</div>
                <div class="opt plain"><input disabled class="box" type="text" value=""> Referral</div>
            </div>
            <div style="margin-top:2px;">
                <div class="opt plain"><input disabled class="box round" type="text" value=""> New</div>
                <div class="opt plain"><input disabled class="box round" type="text" value=""> Returning</div>
            </div>
        </div>

    </div>

    <!-- CERTIFICATION TEXT -->
    <div class="ce-line">
        <span>This is to certify that,</span>
        <div class="fld">
            <input readonly type="text" class="val fit" style="font-size:<?php echo $fs_name_cert; ?>px !important;" value="<?php echo htmlspecialchars($full_name); ?>">
            <div class="cap">Last Name, First Name, Middle Name, Extension Name</div>
        </div>
        <span>,</span>
        <div class="fld" style="flex:0 0 70px;">
            <input readonly type="text" class="val" value="<?php echo htmlspecialchars(strtoupper($row['sex'])); ?>">
            <div class="cap">Sex</div>
        </div>
        <span>,</span>
        <div class="fld" style="flex:0 0 70px;">
            <input readonly type="text" class="val" value="<?php echo htmlspecialchars($age); ?>">
            <div class="cap">Age</div>
        </div>
    </div>

    <div class="ce-line">
        <span>and presently residing at</span>
        <div class="fld">
            <input readonly type="text" class="val fit" style="font-size:<?php echo $fs_address; ?>px !important;" value="<?php echo htmlspecialchars($address); ?>">
            <div class="cap">Complete Address</div>
        </div>
    </div>

    <div style="font-size:13px; line-height:18px; margin-bottom:4px;">
        has been found eligible for assistance after assessment and validation conducted, for him/herself or in representation of his/her
    </div>

    <div class="ce-line">
        <div class="fld" style="flex:0 0 42%;">
            <input readonly type="text" class="val" value="SELF">
            <div class="cap">Relationship of the Beneficiary to the Client/Representative</div>
        </div>
        <span>,</span>
        <div class="fld">
            <input type="text" class="val fit" value="">
            <div class="cap">Last Name, First Name, Middle Name, Extension Name</div>
        </div>
        <span>.</span>
    </div>

    <!-- RECORDS OF THE CASE -->
    <div class="ce-box ce-records" style="margin-top:4px;">
        <div class="ce-boxhead">Records of the case, such as the following, are submitted and/or filed to the Crisis Intervention Program (CIP) and/or the appropriate office, as applicable</div>
        <div style="display:flex;">

            <!-- Crisis Intervention Program -->
            <div style="flex:0 0 27%; border-right:1px solid #000; padding:4px 8px;">
                <div class="ce-title">Crisis Intervention Program:</div>
                <div class="opt"><input disabled class="box" type="text" value="&#x2714;"> <span>General Intake Sheet</span></div>
                <div class="opt"><input disabled class="box" type="text" value=""> <span>Information Sheet</span></div>
                <div class="opt"><input disabled class="box" type="text" value=""> <span>Social Case Study Report/ Case Summary Report</span></div>
                <div class="opt"><input disabled class="box" type="text" value=""> <span>Referral Letter</span></div>
                <div class="opt"><input disabled class="box" type="text" value="&#x2714;"> <span>Photocopy of valid I.D.</span></div>
                <div class="ul" style="display:block; width:80%; margin:2px 0 2px 21px; font-size:11px;"></div>
            </div>

            <!-- Other Offices -->
            <div style="flex:1; padding:4px 8px;">
                <div class="ce-title">Other Offices:</div>
                <div style="display:flex; gap:12px;">

                    <div style="flex:1.15;">
                        <div class="opt"><input disabled class="box" type="text" value=""> <span>Medical Certificate/ Abstract/ Discharge Summary/ Certificate of Confinement/ Death Summary/ Alagang Pinoy Tagubilin</span></div>
                        <div class="opt"><input disabled class="box" type="text" value=""> <span>Prescriptions</span></div>
                        <div class="opt"><input disabled class="box" type="text" value=""> <span>Laboratory Request</span></div>
                        <div class="opt"><input disabled class="box" type="text" value=""> <span>Treatment Protocol</span></div>
                    </div>

                    <div style="flex:1;">
                        <div class="opt"><input disabled class="box" type="text" value=""> <span>Statement of Account</span></div>
                        <div class="opt"><input disabled class="box" type="text" value=""> <span>Promissory Note / Certificate of Balance</span></div>
                        <div class="opt"><input disabled class="box" type="text" value=""> <span>Funeral Contract</span></div>
                        <div class="opt"><input disabled class="box" type="text" value=""> <span>Death Certificate</span></div>
                        <div class="opt"><input disabled class="box" type="text" value=""> <span>Transfer Permit</span></div>
                        <div class="opt"><input disabled class="box" type="text" value=""> <span>Quotation/Charge Slip</span></div>
                    </div>

                    <div style="flex:.8;">
                        <div class="opt"><input disabled class="box" type="text" value="&#x2714;"> <span>Justification</span></div>
                        <div class="opt"><input disabled class="box" type="text" value=""> <span>Photocopy of valid I.D.</span></div>
                        <div class="opt"><input disabled class="box" type="text" value=""> <span>Others</span> <span class="ul" style="min-width:50px;"></span></div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- MODE OF RELEASE -->
    <div class="ce-box" style="margin-top:8px;">
        <div class="ce-bar">MODE OF RELEASE</div>
        <div style="padding:8px 10px 2px;">

            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
                <div style="display:flex; gap:40px;">
                    <div class="opt" style="margin:0; font-size:13px;"><input disabled class="box" type="text" value="&#x2714;"> Outright Cash</div>
                    <div class="opt" style="margin:0; font-size:13px;"><input disabled class="box" type="text" value=""> Guarantee Letter</div>
                </div>
                <div style="display:flex; align-items:flex-end; gap:6px; font-size:13px;">
                    <b>GL No.</b>
                    <input type="text" class="val" style="width:140px;">
                </div>
            </div>

            <div class="ce-line">
                <span>The client is hereby recommended to receive</span>
                <div class="fld">
                    <input type="text" class="val fit" value="<?php echo htmlspecialchars($row['type_of_assistance']); ?>">
                    <div class="cap">Type of assistance: medical, burial, other(s)</div>
                </div>
                <span>assistance for</span>
                <div class="fld">
                    <input type="text" class="val fit">
                    <div class="cap">Purpose of assistance</div>
                </div>
            </div>

            <div class="ce-line">
                <span>in the amount of</span>
                <div class="fld">
                    <input readonly type="text" class="val fit" style="font-size:<?php echo $fs_words; ?>px !important;" value="<?php echo $amount_words; ?>">
                    <div class="cap">Amount in words</div>
                </div>
                <span>PhP</span>
                <div class="fld" style="flex:0 0 130px;">
                    <input readonly type="text" class="val" value="<?php echo number_format($row['amount'], 2); ?>">
                    <div class="cap">Amount in figures</div>
                </div>
            </div>

            <div class="ce-line">
                <span>payable to <small>(if guarantee letter)</small></span>
                <div class="fld">
                    <input type="text" class="val fit">
                    <div class="cap">Name of Service provider</div>
                </div>
            </div>

            <div class="ce-line">
                <div class="fld">
                    <input type="text" class="val fit">
                    <div class="cap">Address of Service provider</div>
                </div>
            </div>

        </div>
    </div>

    <!-- SIGNATURES -->
    <div style="display:flex; justify-content:space-around; margin-top:14px;">
        <div style="width:40%; text-align:center;">
            <b style="font-size:13px;">Prepared and Certified by:</b>
            <div style="height:100px;"></div>
            <div style="border-top:1px solid #000; padding-top:2px; width:85%; margin:0 auto;">Social Worker</div>
            <small style="display:block; font-size:9px;"><i>(Signature over Printed Name)</i></small>
            <div style="font-size:10px; margin-top:2px;">License Number: <input type="text" class="val" style="width:110px; height:14px; font-size:10px; line-height:14px;"></div>
        </div>
        <div style="width:40%; text-align:center;">
            <b style="font-size:13px;">Approved by:</b>
            <div style="height:100px; display:flex; align-items:flex-end; justify-content:center; font-weight:bold; font-size:13px;"><?php echo strtoupper($head); ?></div>
            <div style="border-top:1px solid #000; padding-top:2px; width:85%; margin:0 auto;">Approving Authority</div>
            <small style="display:block; font-size:9px;"><i>(Signature over Printed Name)</i></small>
        </div>
    </div>

    <!-- ACKNOWLEDGMENT -->
    <div class="ce-bar" style="margin-top:12px;">ACKNOWLEDGMENT OF ASSISTANCE</div>

    <div style="display:flex; justify-content:flex-end; margin:6px 0 4px;">
        <div style="display:flex; align-items:flex-start; gap:6px; font-size:12px;">
            <b style="line-height:20px;">Date:</b>
            <div>
                <div class="date-row">
                    <input disabled type="text"><input disabled type="text"><input disabled type="text">
                </div>
                <div class="date-labels"><div>MM</div><div>DD</div><div>YYYY</div></div>
            </div>
        </div>
    </div>

    <div class="ce-line">
        <span>I acknowledge receipt of assistance in the amount of</span>
        <div class="fld">
            <input readonly type="text" class="val fit" style="font-size:<?php echo $fs_words; ?>px !important;" value="<?php echo $amount_words; ?>">
            <div class="cap">Amount in words</div>
        </div>
        <span>PhP</span>
        <div class="fld" style="flex:0 0 130px;">
            <input readonly type="text" class="val" value="<?php echo number_format($row['amount'], 2); ?>">
            <div class="cap">Amount in figures</div>
        </div>
    </div>

    <!-- RECEIVED BY + THUMBMARK -->
    <div style="display:flex; align-items:flex-end; margin-top:4px;">
        <div style="flex:1;"></div>
        <div style="flex:2; text-align:center;">
            <b style="font-size:13px; top:100px;">Received by:</b>
            <div style="height:50px;"></div>
            <div class="fld">
                <input readonly type="text" class="val fit" style="font-size:<?php echo $fs_name_recv; ?>px !important;" value="<?php echo htmlspecialchars($full_name); ?>">
                <div class="cap">Last Name, First Name, Middle Name, Extension Name</div>
            </div>
            <small style="display:block; font-size:9px;"><i>(Signature over Printed Name)</i></small>
        </div>
        <div style="flex:1; display:flex; justify-content:flex-end;">
            <div style="text-align:center; width:130px;">
                <div style="height:150px; border:1px solid #000;"></div>
                <div style="font-size:8px; line-height:10px;">Thumbmark<br>(if unable to provide specimen signature)</div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer" style="margin-top:65px;">
        <div style="border-bottom:solid 1px black;"></div>
        <p class="text-center" style="font-size:10px; line-height:1.2; margin:2px 0 0;">
            <b>Page 1 of 1</b><br>
            DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
            Website: http://fo11.dswd.gov.ph Tel Nos.: (082) 227 1964 / (082) 227 8746 / (082) 227 1435 Telefax: (082) 226 2857
        </p>
    </div>

</div><!--Container-->

<script>
// Second pass: measures the real text width with a canvas and shrinks input.fit to fit.
// The PHP-computed size is already applied, so if this script does not run the page still works.
(function () {
    var MIN_SIZE = 6;
    var canvas = document.createElement('canvas');
    var ctx = canvas.getContext('2d');

    function fitInput(el) {
        var prev = el.style.getPropertyValue('font-size');
        el.style.removeProperty('font-size');         // back to the CSS size to measure
        var cs = window.getComputedStyle(el);
        var base = parseFloat(cs.fontSize);
        var avail = el.clientWidth - parseFloat(cs.paddingLeft) - parseFloat(cs.paddingRight);

        if (!avail || avail <= 0 || !el.value) {      // hidden or empty: keep what PHP set
            if (prev) el.style.setProperty('font-size', prev, 'important');
            return;
        }

        ctx.font = cs.fontStyle + ' ' + cs.fontWeight + ' ' + base + 'px ' + cs.fontFamily;
        var width = ctx.measureText(el.value.toUpperCase()).width;

        if (width > avail) {
            var size = Math.floor((base * avail / width) * 10) / 10 - 0.2; // small safety margin
            // 'important' so stylesheet rules cannot override it
            el.style.setProperty('font-size', Math.max(MIN_SIZE, size) + 'px', 'important');
        } else if (prev) {
            el.style.setProperty('font-size', prev, 'important');
        }
    }

    function fitAll() {
        var inputs = document.querySelectorAll('input.fit');
        for (var i = 0; i < inputs.length; i++) fitInput(inputs[i]);
    }

    fitAll();
    document.addEventListener('DOMContentLoaded', fitAll);
    window.addEventListener('load', fitAll);
    window.addEventListener('beforeprint', fitAll);
    window.addEventListener('resize', fitAll);
})();
</script>
<?php } ?>
</body>
</html>