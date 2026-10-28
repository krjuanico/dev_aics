<?php
error_reporting(0);
session_start();
include "../dbconnect.php";

$head      = "";
$emm       = $_SESSION['userid'];
$user_type = $_SESSION['user_type'];

$sql2 = "SELECT head FROM lib_users as a
         INNER JOIN lib_area as b ON a.areaassigned = b.id
         WHERE empid='" . mysqli_real_escape_string($conn, $emm) . "'";
$result2 = mysqli_query($conn, $sql2);
if ($row2 = mysqli_fetch_assoc($result2)) {
    $head = $row2['head'];
}

function numtowords($num, $c = 1) {
    $ZERO  = 'zero';
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

    $divisor = array(100, 10, 1000, 1000, 1000, 1000, 1000, 1000);

    $num   = str_replace(",", "", $num);
    $num   = number_format($num, 2, '.', '');
    $cents = substr($num, strlen($num) - 2, strlen($num) - 1);
    $num   = (int)$num;

    if ($num == 0) return $ZERO;

    $negative = ($num < 0);
    if ($negative) $num = -$num;

    $s = "";

    for ($i = 0; $num > 0; $i++) {
        $remdr = (int)($num % $divisor[$i]);
        $num   = (int)($num / $divisor[$i]);

        if ($remdr == 0) continue;

        $t = "";
        if ($remdr < 20) {
            $t = $lowName[$remdr];
        } elseif ($remdr < 100) {
            $units = (int)$remdr % 10;
            $tens  = (int)$remdr / 10;
            $t     = $tys[$tens];
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

/* ---------- Layout helpers ---------- */

// One labeled field (value on top of an underline, Tagalog/English label below).
// The value is wrapped in .fit-text so the script can shrink it when it is too long.
function fld($flex, $val, $tl, $en, $extraClass = '') {
    $val = htmlspecialchars(mb_strtoupper(trim((string)$val), 'UTF-8'));
    return '<div class="fld ' . $extraClass . '" style="flex:' . $flex . ' 1 0;">'
         . '<div class="val"><span class="fit-text">' . $val . '</span></div>'
         . '<div class="lbl"><b>' . $tl . '</b><br>' . $en . '</div>'
         . '</div>';
}

// Identity block used by both the Client and the Beneficiary sections.
// The rows are wrapped in .ident so they spread out evenly over the free space on the page.
function renderIdentity($d, $withRelationship = false) {
    $html  = '<div class="ident">';

    $html .= '<div class="frow">';
    $html .= fld(4, $d['ln'],  'Apelyido', '(Last Name)');
    $html .= fld(4, $d['fn'],  'Unang Pangalan', '(First Name)');
    $html .= fld(3, $d['mn'],  'Gitnang Pangalan', '(Middle Name)');
    $html .= fld(1.3, $d['ext'], 'Ext.', '(Sr.,Jr.,III)');
    $html .= '</div>';

    $html .= '<div class="frow">';
    $html .= fld(3, $d['street'], 'Numero ng Bahay/Kalye', '(Street Address)');
    $html .= fld(3, $d['brgy'],   'Barangay', '(Ex. Batasan Hills)');
    $html .= fld(3, $d['city'],   'Lungsod/Bayan', '(City/Municipality)');
    $html .= fld(3, $d['prov'],   'Lalawigan/Distrito', '(Province/District)');
    $html .= fld(2, $d['region'], 'Rehiyon', '(Region)');
    $html .= '</div>';

    $html .= '<div class="frow">';
    $html .= fld(2.5, $d['mobile'], 'Numero ng Mobile', '(Mobile No.)');
    $html .= fld(2.8, $d['bdate'],  'Petsa ng Kapanganakan', '(Birthdate) MM/DD/YYYY');
    $html .= fld(1,   $d['age'],    'Edad', '(Age)');
    $html .= fld(1.6, $d['sex'],    'Kasarian', '(Sex)');
    $html .= fld(3,   $d['gender'], 'Pagkakakilanlan ng Kasarian', '(Gender Identity)');
    $html .= fld(2.3, $d['civil'],  'Katayuang Sibil', '(Civil Status)');
    $html .= '</div>';

    $html .= '<div class="frow">';
    $html .= fld(3, $d['occ'],    'Hanapbuhay', '(Occupation)');
    $html .= fld(3, $d['income'], 'Buwanang Kita', '(Monthly Income)');
    if ($withRelationship) {
        $html .= fld(5, $d['rel'], 'Relasyon sa Benepisyaryo', '(Relationship to the Beneficiary)');
    } else {
        $html .= '<div style="flex:5 1 0;"></div>';
    }
    $html .= '</div>';

    $html .= '</div>';

    return $html;
}

/* ---------- Data ---------- */

$beneId = mysqli_real_escape_string($conn, $_GET['beneId']);
$sql = "SELECT * FROM aics_clean_list WHERE control_number='$beneId'";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {

    $fname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['first_name']);
    $mname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['middle_name']);
    $lname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['last_name']);
    $ename = $row['extension_name'];
    $amount = $row['amount'];

    // Age (birth_day / birth_month / birth_year columns)
    $age = '';
    $dob = $row['birth_day'] . '/' . $row['birth_month'] . '/' . $row['birth_year'];
    $dob = str_replace('-', '/', $dob);
    $dobDate = DateTime::createFromFormat('d/m/Y', $dob);
    if ($dobDate !== false) {
        $currentDate = new DateTime();
        $age = $currentDate->diff($dobDate)->y;
    }

    // Birthdate displayed as MM/DD/YYYY (new format)
    $bdateDisplay = '';
    if ($row['birth_month'] !== '' && $row['birth_day'] !== '' && $row['birth_year'] !== '') {
        $bdateDisplay = $row['birth_month'] . '/' . $row['birth_day'] . '/' . $row['birth_year'];
    }

    $income = ($row['monthly_salary'] !== '' && $row['monthly_salary'] !== null)
        ? number_format((float)$row['monthly_salary'], 2)
        : '';

    $client = array(
        'ln'     => $lname,
        'fn'     => $fname,
        'mn'     => $mname,
        'ext'    => $ename,
        'street' => $row['purok'],
        'brgy'   => $row['barangay'],
        'city'   => $row['city_municipality'],
        'prov'   => $row['province'],
        'region' => 'Region XI',
        'mobile' => $row['contact_number'],
        'bdate'  => $bdateDisplay,
        'age'    => $age,
        'sex'    => $row['sex'],
        'gender' => '',   // no gender identity column in aics_clean_list
        'civil'  => $row['civil_status'],
        'occ'    => $row['occupation'],
        'income' => $income,
        'rel'    => ''
    );

    $beneficiary = array(
        'ln' => '', 'fn' => '', 'mn' => '', 'ext' => '',
        'street' => '', 'brgy' => '', 'city' => '', 'prov' => '', 'region' => '',
        'mobile' => '', 'bdate' => '', 'age' => '', 'sex' => '', 'gender' => '',
        'civil' => '', 'occ' => '', 'income' => '', 'rel' => ''
    );
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Information Sheet</title>
<link rel="stylesheet" href="cpms/css/coe.css">
<link rel="stylesheet" type="text/css" href="cpms/css/bootstrap.min.css">
<style>
    * {
        box-sizing: border-box;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    html, body { margin: 0; padding: 0; }

    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #000;
    }

    /* ---------- A4 page ---------- */
    @page {
        size: A4 portrait;
        margin: 0;
    }

    /* One .sheet = one full A4 page (210 x 297 mm) */
    .sheet,
    #coev2_print {
        margin: 0;
        padding: 9mm !important;      /* white space around the border */
        height: 296mm;                /* fits one A4 page, avoids blank extra page */
        display: flex;
        flex-direction: column;
        page-break-after: always;
        break-after: page;
    }
    .sheet:last-of-type {
        page-break-after: auto;
        break-after: auto;
    }
    .sheet + .sheet { margin-top: 0 !important; }

    @media screen {
        .sheet { margin: 0 auto 24px !important; width: 210mm; }
    }

    /* The bordered frame fills the whole sheet */
    .print-coe {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        width: auto !important;
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
        border: 1px solid #000 !important;
    }
    .print-coe > * { flex: 0 0 auto; }

    /* Header */
    .top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .top .logos img { vertical-align: middle; }

    .title {
        text-align: center;
        font-size: 26px;
        font-weight: bold;
        margin: 6px 0 10px;
    }

    /* PCN / Date */
    .pcn-date {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }
    .pcn { display: flex; align-items: center; font-weight: bold; font-size: 11px; }
    .pcn .boxes { display: flex; margin-left: 6px; border: 1px solid #000; }
    .pcn .boxes span {
        width: 19px; height: 20px;
        border-right: 1px solid #000;
    }
    .pcn .boxes span:last-child { border-right: none; }

    .datebox { display: flex; align-items: flex-start; font-weight: bold; font-size: 11px; }
    .datebox .parts { display: flex; margin-left: 6px; }
    .datebox .part { text-align: center; margin-left: -1px; }
    .datebox .part .b { border: 1px solid #000; height: 20px; }
    .datebox .part i { font-size: 8px; font-weight: normal; }

    /* Section header bar */
    .header {
        background-color: #000 !important;
        color: #fff !important;
        font-size: 11px;
        padding: 3px 8px;
        margin: 10px 0 4px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header strong { color: #fff !important; background: transparent !important; font-size: 11px; }
    .header em { color: #fff; }
    .header .same { font-size: 10px; font-weight: bold; display: flex; align-items: center; }
    .header .same input { margin: 0 6px 0 0; width: 12px; height: 12px; }

    /* Fields */
    .frow {
        display: flex;
        gap: 6px;
        margin-top: 8px;
        padding: 0 4px;
    }
    /* Identity rows share the free height of the page evenly */
    .print-coe > .ident {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        padding-bottom: 4px;
    }
    .ident .frow { margin-top: 0; }

    .fld { min-width: 0; }
    .fld .val {
        display: flex;
        align-items: flex-end;      /* value sits right above the line */
        justify-content: center;    /* keep horizontal centering */
        min-height: 50px;
        border-bottom: 1px solid #000;
        padding: 0 2px 2px;         /* 2px gap between text and line */
        text-align: center;
        font-size: 12px;
        font-weight: bold;
        white-space: nowrap;
        overflow: hidden;
    }
    .fld .lbl {
        text-align: center;
        font-size: 8.5px;
        line-height: 1.15;
        margin-top: 2px;
    }

    /* Auto-fit text: font size is reduced by the script when the value is too long */
    .fit-text {
        display: inline-block;
        white-space: nowrap;
    }
    /* Used by the script when a value is too long even at the minimum single-line size */
    .fit-text.wrap {
        white-space: normal;
        text-align: center;
        line-height: 1.15;
        word-break: break-word;
    }

    /* Assistance section */
    .assist {
        display: flex;
        gap: 18px;
        padding: 6px 4px;
        font-size: 12px;
    }
    .print-coe > .assist { flex: 1.2 1 auto; }
    .assist .left  { flex: 1 1 0; display: flex; flex-direction: column; }
    .assist .right { flex: 1.2 1 0; display: flex; flex-direction: column; }
    .assist .note  { font-style: italic; font-size: 10px; color: #333; }
    .assist label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 8px 0;
        font-weight: normal;
    }
    .assist label input[type="checkbox"] { width: 16px; height: 16px; margin: 0; flex: 0 0 auto; }
    .assist .others-line {
        flex: 1;
        border-bottom: 1px solid #000;
        min-height: 16px;
    }
    .assist .options {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        margin-top: 6px;
    }
    .assist .options label { margin: 4px 0; }
    .assist .yn {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        margin-top: 24px;
    }
    .assist .yn label { margin: 22px 0 22px 14px; }

    /* ---------- PAGE 2 ---------- */

    .page2 .header { margin-top: 0; }
    .page2 .header.next { margin-top: 0; }

    /* Family composition table */
    table.fam {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin-top: 0;
    }
    table.fam th {
        font-size: 10px;
        font-weight: bold;
        text-align: center;
        line-height: 2;
        padding: 6px 4px 4px;
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        border-bottom: 1px solid #000;
    }
    table.fam th i { display: block; font-weight: normal; font-size: 8.5px; }
    table.fam td {
        height: 0.4in;
        border: 1px solid #000;
        font-size: 11px;
        padding: 2px 4px;
        vertical-align: middle;
    }
    table.fam td.no {
        font-size: 9px;
        vertical-align: top;
        padding: 2px 3px;
    }
    table.fam td.num { text-align: right; }

    .total-row {
        display: flex;
        justify-content: flex-end;
        align-items: stretch;
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        border-bottom: 1px solid #000;
    }
    .total-row .lbl-total {
        flex: 1 1 auto;
        text-align: right;
        font-weight: bold;
        font-style: italic;
        font-size: 11px;
        padding: 5px 6px;
        align-self: center;
    }
    .total-row .amt-total {
        flex: 0 0 14%;
        border-left: 1px solid #000;
        min-height: 26px;
    }

    /* Consent form: takes the remaining height, signature block pinned to the bottom */
    .print-coe > .consent-wrap {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
    }
    .consent {
        padding: 10px 6px 0;
        font-size: 11.5px;
        line-height: 1.45;
        text-align: justify;
    }
    .consent p { margin: 0 0 14px; }

    .sign-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: auto;
        margin-bottom: 12px;
        padding: 0 6px;
    }
    .sign-block {
        width: 3.2in;
        text-align: center;
        margin-left: 0.5in;
    }
    /* Printed full name above the signature line (auto-fits if too long) */
    .sign-block .printed {
        text-align: center;
        font-size: 11px;
        font-weight: bold;
        padding-bottom: 2px;
    }
    .sign-block .line { border-top: 1px solid #000; padding-top: 3px; }
    .sign-block .l1 { font-size: 10px; font-weight: bold; }
    .sign-block .l2 { font-size: 9px; font-style: italic; }
    .thumb { width: 1.30in; text-align: center; margin-right:5%; }
    .thumb .box { height: 1.45in; border: 1px solid #000; }
    .thumb .cap { font-size: 10px; margin-top: 2px; }
    .thumb .cap2 { font-size: 7.5px; font-style: italic; }

    /* Footer (sits at the bottom of the frame) */
    .page-footer {
        border-top: 1px solid #000;
        text-align: center;
        font-size: 9px;
        padding: 4px 0 4px;
        margin-top: 8px;
    }
    .page-footer b { font-size: 9px; }

    /* Central Office / Field Office options */
    .off-opt {
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 0 0 6px;
        font-size: 13px;
        font-weight: normal;
        white-space: nowrap;
    }
    .off-box {
        display: inline-block;
        flex: 0 0 16px;
        width: 16px;
        height: 16px;
        border: 1px solid #000;
        text-align: center;
        font-size: 13px;
        line-height: 14px;
        color: #000;
    }
    .off-ul {
        display: inline-block;
        min-width: 90px;
        border-bottom: 1px solid #000;
        text-align: center;
        font-weight: bold;
    }

    /* Assistance section */
    .print-coe > .assist { flex: 0 0 auto; }          /* don't stretch to fill the page */
    .assist {
        display: flex;
        gap: 18px;
        padding: 4px 4px 10px;
        font-size: 11px;
    }
    .assist .left  { flex: 0 0 38%; display: block; }
    .assist .right { flex: 1; display: block; }

    .assist b      { font-weight: normal; font-size: 12px; }
    .assist .note  { font-style: italic; font-size: 9.5px; color: #444; }

    .assist label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 4px;
        font-weight: normal;
    }
    .assist label input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        flex: 0 0 14px;
        width: 14px;
        height: 14px;
        margin: 0;
        border: 1.5px solid #222;
        border-radius: 2px;
        background: #fff;
    }
    .assist label input[type="checkbox"]:checked {
        background: #fff url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><path d='M3 8.5l3.2 3.2L13 4.8' fill='none' stroke='black' stroke-width='2'/></svg>") center / 12px no-repeat;
    }

    /* Hindi / Oo: tight, indented, pushed down from the question */
    .assist .yn { margin-top: 28px; display: block; }
    .assist .yn label { margin: 0 0 6px 10px; gap: 14px; }

    /* Right column: tight list, starts a little below the note */
    .assist .options { margin-top: 12px; display: block; }
    .assist .options label { align-items: flex-start; line-height: 1.25; }
    .assist .options label input { margin-top: 1px; }

    /* "Others:" short underline */
    .assist .others-line {
        flex: 0 0 95px;
        align-self: flex-end;
        border-bottom: 1px solid #444;
        min-height: 12px;
    }
</style>
<script>
/*
 * Fits the text inside .fit-text into its container:
 *  1) shrink on ONE line down to minSingle px (keeps it readable)
 *  2) if it still doesn't fit, wrap to up to maxLines lines and keep shrinking
 *     (never below minPx) until everything is visible.
 */
function fitText(el, minSingle, minPx, maxLines) {
    el.classList.remove('wrap');
    el.style.maxWidth = '';
    el.style.fontSize = '';                                   // reset to the CSS size first
    var size = parseFloat(getComputedStyle(el).fontSize);
    var maxW = el.parentElement.clientWidth - 6;              // allowance for padding / breathing room

    // Signatory name: ALWAYS one line, just keep shrinking until it fits (no wrapping)
    if (el.hasAttribute('data-single')) {
        while (el.offsetWidth > maxW && size > 4) {
            size -= 0.25;
            el.style.fontSize = size + 'px';
        }
        return;
    }

    // Step 1: single line
    while (el.offsetWidth > maxW && size > minSingle) {
        size -= 0.5;
        el.style.fontSize = size + 'px';
    }
    if (el.offsetWidth <= maxW) return;

    // Step 2: wrap onto extra lines
    el.classList.add('wrap');
    el.style.maxWidth = maxW + 'px';
    while (el.offsetHeight > size * 1.15 * maxLines + 1 && size > minPx) {
        size -= 0.5;
        el.style.fontSize = size + 'px';
    }
}
function fitAll() {
    document.querySelectorAll('.fit-text').forEach(function (el) {
        fitText(el, 9, 6.5, 2);   // single-line min 9px, then wrap (max 2 lines) down to 6.5px
    });
}
window.addEventListener('load', fitAll);
window.addEventListener('beforeprint', fitAll);
</script>
</head>
<body>

<!-- ======================= PAGE 1 ======================= -->
<div class="sheet" id="coev2_print">

  <div class="print-coe">

    <!-- HEADER -->
    <div class="top">
        <div class="logos">
            <img src="cpms/images/logo.png" alt="DSWD" width="240" height="70" style="margin-left:20px; margin-top:4px;">
        </div>
        <div style="padding-top:15px; padding-right:10px;">
            <div class="off-opt" style="font-size:10px; line-height:1.2; font-weight:bold;"><span class="off-box"></span> Central Office</div>
            <div class="off-opt" style="font-size:10px; line-height:1.2; font-weight:bold;"><span class="off-box">&#x2714;</span> Field Office <span class="off-ul">XI</span></div>
        </div>
    </div>

    <div class="title" style="margin-top: 20px;">INFORMATION SHEET</div>

    <!-- PCN / DATE -->
    <div class="pcn-date" style="margin-left:10px; margin-top: 20px">
        <div class="pcn">
            PCN:
            <div class="boxes">
                <?php for ($i = 0; $i < 16; $i++) { echo '<span></span>'; } ?>
            </div>
        </div>
        <div class="datebox" style="margin-right:10px;">
            Date
            <div class="parts">
                <div class="part"><div class="b" style="width:42px;"></div><i>MM</i></div>
                <div class="part"><div class="b" style="width:42px;"></div><i>DD</i></div>
                <div class="part"><div class="b" style="width:64px;"></div><i>YYYY</i></div>
            </div>
        </div>
    </div>

    <!-- AUTHORIZED REPRESENTATIVE / CLIENT -->
    <div class="header">
        <span><strong>IMPORMASYON NG ITINALAGANG KINATAWAN/KLIYENTE</strong> <em>(Authorized Representative's/Client's Identifying Information)</em></span>
    </div>
    <?php echo renderIdentity($client, true); ?>

    <!-- BENEFICIARY -->
    <div class="header">
        <span><strong>IMPORMASYON NG BENEPISYARYO</strong> <em>(Beneficiary's Identifying Information)</em></span>
        <span class="same"><input type="checkbox"> KATULAD SA DETALYE NG KLIYENTE</span>
    </div>
    <?php echo renderIdentity($beneficiary, false); ?>

    <!-- ASSISTANCE RECEIVED -->
    <div class="header">
        <span><strong>NATANGGAP NA TULONG MULA SA DSWD</strong> <em>(Assistance Received from DSWD)</em></span>
    </div>
    <div class="assist">
        <div class="left" style="margin-top: 20px; margin-left: 7px;">
            <b>Ikaw ba ay nakakuha na ng tulong mula sa DSWD?</b><br>
            <span class="note">(Have you received any assistance from the DSWD?)</span>
            <div class="yn">
                <label><input type="checkbox" name="assistance" value="Hindi"> Hindi</label>
                <label><input type="checkbox" name="assistance" value="Oo"> Oo</label>
            </div>
        </div>
        <div class="right" style="margin-top: 20px; margin-left: 7px;">
            <b>Natanggap na tulong sa DSWD</b><br>
            <span class="note">(Assistance received from DSWD)</span>
            <div class="options" >
                <label style="margin-top: 15px;"><input type="checkbox" > Assistance to Individuals in Crisis Situations (AICS)</label>
                <label style="margin-top: 15px;"><input type="checkbox"> Walang Gutom Program (WGP)</label>
                <label style="margin-top: 15px;"><input type="checkbox"> Pantawid Pamilyang Pilipino Program (4Ps)</label>
                <label style="margin-top: 15px;"><input type="checkbox"> Sustainable Livelihood Program (SLP)</label>
                <label style="margin-top: 15px;"><input type="checkbox"> Kapit-Bisig Laban sa Kahirapan-Comprehensive and Integrated Delivery of Social Services (KALAHI CIDDS)</label>
                <label style="margin-top: 15px;"><input type="checkbox"> Others: <span class="others-line"></span></label>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="page-footer" style="margin-top: 110px; font-size: 10px;">
        <b>PAGE 1 of 2</b><br>
        DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
        Website: http://fo11.dswd.gov.ph Tel Nos.: (082) 227 1964 / (082) 227 8746 / (082) 227 1435 Telefax: (082) 226 2857
    </div>

  </div>
</div>

<!-- ======================= PAGE 2 ======================= -->
<div class="sheet page2">

  <div class="print-coe">

    <!-- FAMILY COMPOSITION -->
    <div class="header">
        <span><strong>KOMPOSISYON NG PAMILYA</strong> <em>(Family Composition)</em></span>
    </div>

    <table class="fam">
        <colgroup>
            <col style="width:3.5%;">
            <col style="width:36%;">
            <col style="width:22%;">
            <col style="width:7%;">
            <col style="width:17%;">
            <col style="width:14.5%;">
        </colgroup>
        <thead>
            <tr>
                <th style="border-left:none;"></th>
                <th>Buong Pangalan<i>(Last Name, First Name, Middle Name, Extension Name)</i></th>
                <th>Relasyon sa Benepisyaryo<i>(Relationship to the Beneficiary)</i></th>
                <th>Edad<i>(Age)</i></th>
                <th>Hanapbuhay<i>(Occupation)</i></th>
                <th>Buwanang Kita<i>(Monthly Income)</i></th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 1; $i <= 15; $i++) { ?>
            <tr>
                <td class="no"><?php echo $i; ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="num"></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="total-row">
        <div class="lbl-total">TOTAL FAMILY INCOME</div>
        <div class="amt-total"></div>
    </div>

    <!-- CONSENT FORM -->
    <div class="header next">
        <span><strong>DEKLARASYON NG PAHINTULOT</strong> <em>(Consent Form)</em></span>
    </div>

    <div class="consent-wrap" >
        <div class="consent" style="margin:5%;">
            <p>
                Ako ay nagdedeklara sa ilalim ng parusa ng pagsisinungaling (perjury), na ang lahat ng impormasyon
                sa aplikasyon na ito ay totoo at tama batay sa aking personal na kaalaman at mga autentikong rekord
                 na isinumite sa Department of Social Welfare and Development (DSWD). Anumang mali o mapanlinlang na
                  impormasyon na ibinigay, o paggawa ng pekeng/pinagwaglit na mga dokumento ay magiging sanhi ng
                  nararapat na hakbang na legal laban sa akin at awtomatikong magpawalang-bisa sa anumang tulong
                  na ibibigay kaugnay ng aplikasyon na ito.
            </p>
            <p>
                Ako ay sumasang-ayon na ang lahat ng personal na datos (ayon sa depinisyon sa ilalim ng Republic Act
                10173 o Data Privacy Act of 2012 at mga patnubay nito) at impormasyon o mga rekord ng mga transaksyon
                 sa account sa DSWD ay maaaring iproseso, i-profile, at, kung kinakailangan at pinahihintulutan ng
                 batas at patakaran, ilathala ang aking pangalan sa opisyal na website ng DSWD, alinsunod sa probisyon
                  ng RA 12314 or the General Appropriations Act, para sa transparency at accountability, o ibahagi
                  sa mga humihiling na partido o para sa layunin ng anumang hukuman, proseso ng batas, pagsusuri,
                  inquiry, audit, o imbestigasyon ng anumang awtoridad.
            </p>
        </div>

        <div class="sign-row">
            <?php
            $fullName = trim(trim($lname . ', ' . $fname . ' ' . $mname . ' ' . $ename));
            $fullName = htmlspecialchars(mb_strtoupper($fullName, 'UTF-8'));
            ?>
            <div class="sign-block">
                <div class="printed"><span class="fit-text" data-single="1"><?php echo $fullName; ?></span></div>
                <div class="line">
                    <div class="l1">Lagda sa ibabaw ng Buong Pangalan ng Kinatawan/Kliyente</div>
                    <div class="l1" style="font-weight:normal;">Last Name, First Name, Middle Name, Extension Name</div>
                    <div class="l2">(Signature over Printed Name of the Authorized Representative/Client)</div>
                </div>
            </div>
            <div class="thumb">
                <div class="box"></div>
                <div class="cap">Thumbmark</div>
                <div class="cap2">(If unable to provide specimen signature)</div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="page-footer" style="margin-top: 100px; font-size: 10px;">
        <b>PAGE 2 of 2</b><br>
        DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
        Website: http://fo11.dswd.gov.ph Tel Nos.: (082) 227 1964 / (082) 227 8746 / (082) 227 1435 Telefax: (082) 226 2857
    </div>

  </div>
</div>

</body>
</html>
<?php } ?>