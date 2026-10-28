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

function numtowords($num, $c = 1) {
    $ZERO  = 'zero';
    $MINUS = 'minus';

    $lowName = array(
        "", "ONE", "TWO", "THREE", "FOUR", "FIVE",
        "SIX", "SEVEN", "EIGHT", "NINE", "TEN",
        "ELEVEN", "TWELVE", "THIRTEEN", "FOURTEEN", "FIFTEEN",
        "SIXTEEN", "SEVENTEEN", "EIGHTEEN", "NINETEEN"
    );
    $tys = array("", "", "TWENTY", "THIRTY", "FORTY", "FIFTY", "SIXTY", "SEVENTY", "EIGHTY", "NINETY");
    $groupName = array("", "HUNDRED", "THOUSAND", "MILLION", "BILLION", "TRILLION", "QUADRILLION", "QUINTILLION");
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

/**
 * Returns a font size (px) for text that has to fit one line.
 * $capacity = roughly how many characters fit at the normal size ($base).
 * Longer text gets a proportionally smaller font, never below $min.
 * Runs on the server, so it works even if JavaScript does not run.
 */
function fitsize($text, $capacity, $base = 13, $min = 6) {
    $len = function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);
    if ($len <= $capacity) return $base;
    return max($min, round($base * $capacity / $len, 1));
}

/* ---------- Information Sheet layout helpers ---------- */

// One labeled field (value on top of an underline, Tagalog/English label below)
function is_fld($flex, $val, $tl, $en) {
    $val = htmlspecialchars(mb_strtoupper(trim((string)$val), 'UTF-8'));
    return '<div class="is-fld" style="flex:' . $flex . ' 1 0;"><div class="val"><span class="fit-text">' . $val . '</span></div>'
         . '<div class="lbl"><b>' . $tl . '</b><br>' . $en . '</div></div>';
}

// Identity block used by both the Client and the Beneficiary sections
function is_identity($d, $withRel = false) {
    $h  = '<div class="is-ident">';
    $h .= '<div class="is-frow">'
        . is_fld(4, $d['ln'], 'Apelyido', '(Last Name)')
        . is_fld(4, $d['fn'], 'Unang Pangalan', '(First Name)')
        . is_fld(3, $d['mn'], 'Gitnang Pangalan', '(Middle Name)')
        . is_fld(1.3, $d['ext'], 'Ext.', '(Sr.,Jr.,III)') . '</div>';
    $h .= '<div class="is-frow">'
        . is_fld(3, $d['street'], 'Numero ng Bahay/Kalye', '(Street Address)')
        . is_fld(3, $d['brgy'], 'Barangay', '(Ex. Batasan Hills)')
        . is_fld(3, $d['city'], 'Lungsod/Bayan', '(City/Municipality)')
        . is_fld(3, $d['prov'], 'Lalawigan/Distrito', '(Province/District)')
        . is_fld(2, $d['region'], 'Rehiyon', '(Region)') . '</div>';
    $h .= '<div class="is-frow">'
        . is_fld(2.5, $d['mobile'], 'Numero ng Mobile', '(Mobile No.)')
        . is_fld(2.8, $d['bdate'], 'Petsa ng Kapanganakan', '(Birthdate) MM/DD/YYYY')
        . is_fld(1, $d['age'], 'Edad', '(Age)')
        . is_fld(1.6, $d['sex'], 'Kasarian', '(Sex)')
        . is_fld(3, $d['gender'], 'Pagkakakilanlan ng Kasarian', '(Gender Identity)')
        . is_fld(2.3, $d['civil'], 'Katayuang Sibil', '(Civil Status)') . '</div>';
    $h .= '<div class="is-frow">'
        . is_fld(3, $d['occ'], 'Hanapbuhay', '(Occupation)')
        . is_fld(3, $d['income'], 'Buwanang Kita', '(Monthly Income)')
        . ($withRel ? is_fld(5, $d['rel'], 'Relasyon sa Benepisyaryo', '(Relationship to the Beneficiary)')
                    : '<div style="flex:5 1 0;"></div>') . '</div>';
    return $h . '</div>';
}

// GIS page 2: prints a check mark when two values match
$chk = function($a, $b) { return ($a == $b) ? '✔' : ''; };

/* ---------- Which records to print ---------- */
$page_no = max(1, (int)$_SESSION['pageNumber']);
$benePerPage = 50;

$sql = "SELECT * FROM aics_clean_list WHERE exportStatus is null
        LIMIT " . (($page_no - 1) * $benePerPage) . ", $benePerPage";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>AICS Forms - Page <?php echo $page_no; ?></title>
<link rel="stylesheet" href="cpms/css/coe.css">
<link rel="stylesheet" type="text/css" href="cpms/css/bootstrap.min.css">
<style>
    /* All three forms are A4. Each form has its own named page so its own margin applies. */
    @page gis       { size: A4 portrait; margin: 5mm; }
    @page coe       { size: A4 portrait; margin: 7mm; }
    @page infosheet { size: A4 portrait; margin: 0; }
    .gis-wrap { page: gis; }
    .coe-wrap { page: coe; }
    .is-wrap  { page: infosheet; }

    html, body { margin: 0; padding: 0; }
    @media print { html, body { margin: 0; padding: 0; } }

    /* =====================================================================
       Shared option boxes (GIS + COE)
       ===================================================================== */
    .gis-wrap .opt, .coe-wrap .opt {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 3px;
        white-space: nowrap;
        font-size: 12px;
        font-weight: bold;
        line-height: 14px;
    }
    .gis-wrap .opt.plain, .coe-wrap .opt.plain { font-weight: normal; }
    .gis-wrap .opt .box, .coe-wrap .opt .box {
        flex: 0 0 16px;
        width: 16px;
        height: 16px;
        border: 1px solid #000;
        padding: 0;
        text-align: center;
        font-size: 12px;
        line-height: 14px;
    }
    .gis-wrap .opt .box.round, .coe-wrap .opt .box.round {
        flex: 0 0 18px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
    }

    /* =====================================================================
       GENERAL INTAKE SHEET (.gis-wrap)
       ===================================================================== */
    .gis-wrap input { text-transform: uppercase; }
    .gis-wrap .header {
        background-color: black !important;
        color: white;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        font-size: 12px;
    }
    .gis-wrap .section-title {
        background-color: #000;
        color: #ffffff;
        padding: 8px 12px;
        font-size: 15px;
        font-weight: bold;
        text-transform: uppercase;
        width: 100%;
        display: block;
        margin-bottom: 10px;
        border-radius: 0;
        border: 1px solid #000;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .gis-wrap .hdr-wrap {
        display: flex;
        align-items: stretch;
        border: 1px solid #000;
        background: #fff;
        margin-top: -18px;
        margin-left: -14px;
        margin-right: -15px;
    }
    .gis-wrap .hdr-sec { padding: 4px 8px 6px 8px; border-right: 1px solid #000; }
    .gis-wrap .hdr-sec:last-child { border-right: none; }
    .gis-wrap .hdr-title { font-size: 12px; line-height: 14px; margin-bottom: 2px; }
    .gis-wrap .date-row { display: flex; }
    .gis-wrap .date-row input { height: 20px; border: 1px solid #000; padding: 0; margin: 0; }
    .gis-wrap .date-row input + input { margin-left: -1px; }
    .gis-wrap .date-labels { display: flex; font-size: 11px; font-weight: bold; font-style: italic; text-align: center; }

    .gis-wrap .info-block { padding: 6px 8px 8px 8px; font-size: 12px; line-height: 16px; }
    .gis-wrap .info-row { display: flex; align-items: flex-end; gap: 4px; margin-bottom: 6px; }
    .gis-wrap .info-row .lbl { font-weight: bold; white-space: nowrap; }
    .gis-wrap .info-row input.line {
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

    /* GIS page 2 */
    .gis-wrap .p2-box { border: 1px solid #000; background: #fff; font-size: 12px; line-height: 15px; }
    .gis-wrap .p2-bar, .gis-wrap .p2-head > div, .gis-wrap .p2-table th {
        background: #000; color: #fff; font-weight: bold;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .gis-wrap .p2-bar { text-transform: uppercase; padding: 3px 8px; }
    .gis-wrap .p2-sub { font-weight: bold; font-style: italic; margin: 6px 0 3px; }
    .gis-wrap .p2-grid { display: grid; grid-template-columns: 1.2fr 1.5fr 1.2fr .9fr 1fr; }
    .gis-wrap .p2-head > div { display: flex; align-items: center; padding: 3px 6px; font-size: 11px; border-right: 2px solid #fff; }
    .gis-wrap .p2-head > div:last-child { border-right: none; }
    .gis-wrap .p2-head .box { background: #fff; }
    .gis-wrap .p2-cell { padding: 5px 8px; }
    .gis-wrap .p2-line { border-bottom: 1px solid #000; height: 16px; margin-bottom: 4px; }
    .gis-wrap .p2-table { width: 100%; border-collapse: collapse; }
    .gis-wrap .p2-table th { text-align: center; padding: 3px; }
    .gis-wrap .p2-table td { border: 1px solid #000; height: 22px; padding: 0 4px; }
    .gis-wrap .p2-table td.n { width: 4%; text-align: center; }

    /* =====================================================================
       CERTIFICATE OF ELIGIBILITY (.coe-wrap)
       ===================================================================== */
    .coe-wrap { font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #000; padding: 0; }
    /* Each COE copy starts on its own A4 page (2 copies are printed per record) */
    .coe-wrap { break-after: page; page-break-after: always; }
    .coe-wrap .copy-label { text-align: right; font-size: 9px; font-style: italic; margin-bottom: 2px; }
    .coe-wrap input:disabled, .coe-wrap input[readonly] {
        color: #000; -webkit-text-fill-color: #000; opacity: 1;
    }
    .coe-wrap .opt .box { margin: 0; background: #fff; }
    .coe-wrap .ce-title { font-weight: bold; font-size: 11px; margin-bottom: 2px; }
    .coe-wrap .ul {
        display: inline-block;
        border-bottom: 1px solid #000;
        min-width: 70px;
        height: 14px;
        line-height: 13px;
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
    }
    .coe-wrap .date-row { display: flex; }
    .coe-wrap .date-row input { width: 44px; height: 20px; border: 1px solid #000; padding: 0; margin: 0; text-align: center; }
    .coe-wrap .date-row input:last-child { width: 66px; }
    .coe-wrap .date-row input + input { margin-left: -1px; }
    .coe-wrap .date-labels { display: flex; font-size: 9px; font-style: italic; text-align: center; }
    .coe-wrap .date-labels div { width: 44px; }
    .coe-wrap .date-labels div:last-child { width: 66px; }

    .coe-wrap .ce-line { display: flex; align-items: flex-start; gap: 6px; margin-bottom: 8px; font-size: 13px; }
    .coe-wrap .ce-line > span { line-height: 20px; white-space: nowrap; }
    .coe-wrap .fld { display: flex; flex-direction: column; flex: 1; min-width: 0; }
    .coe-wrap .fld input.val, .coe-wrap input.val {
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
    .coe-wrap .fld .cap { font-size: 9px; font-style: italic; text-align: center; line-height: 11px; margin-top: 1px; }

    .coe-wrap .ce-box { border: 1px solid #000; background: #fff; }
    .coe-wrap .ce-bar {
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
    .coe-wrap .ce-box > .ce-bar { border: none; border-bottom: 1px solid #000; }
    .coe-wrap .ce-boxhead {
        text-align: center;
        font-weight: bold;
        font-size: 11px;
        line-height: 13px;
        padding: 3px 10px;
        border-bottom: 1px solid #000;
    }
    .coe-wrap .ce-records .opt { font-size: 11px; font-weight: normal; line-height: 13px; white-space: normal; align-items: flex-start; }

    /* =====================================================================
       INFORMATION SHEET (.is-wrap)
       ===================================================================== */
    .is-wrap { font-family: Arial, sans-serif; font-size: 12px; color: #000; }
    .is-wrap, .is-wrap * { box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }

    .is-sheet {
        margin: 0;
        padding: 9mm;                 /* white space around the border */
        height: 296mm;                /* fits one A4 page, avoids blank extra page */
        display: flex;
        flex-direction: column;
        page-break-after: always;
        break-after: page;
    }
    .is-sheet:last-child { page-break-after: auto; break-after: auto; }
    @media screen { .is-sheet { margin: 0 auto 24px; width: 210mm; } }

    .is-frame { flex: 1 1 auto; display: flex; flex-direction: column; border: 1px solid #000; }
    .is-frame > * { flex: 0 0 auto; }

    .is-top { display: flex; justify-content: space-between; align-items: flex-start; }
    .is-title { text-align: center; font-size: 26px; font-weight: bold; margin: 20px 0 10px; }

    .is-pcn-date { display: flex; justify-content: space-between; align-items: flex-start; margin: 20px 10px 10px; }
    .is-pcn { display: flex; align-items: center; font-weight: bold; font-size: 11px; }
    .is-pcn .boxes { display: flex; margin-left: 6px; border: 1px solid #000; }
    .is-pcn .boxes span { width: 19px; height: 20px; border-right: 1px solid #000; }
    .is-pcn .boxes span:last-child { border-right: none; }
    .is-datebox { display: flex; align-items: flex-start; font-weight: bold; font-size: 11px; margin-right: 10px; }
    .is-datebox .parts { display: flex; margin-left: 6px; }
    .is-datebox .part { text-align: center; margin-left: -1px; }
    .is-datebox .part .b { border: 1px solid #000; height: 20px; }
    .is-datebox .part i { font-size: 8px; font-weight: normal; }

    .is-bar { background: #000 !important; color: #fff !important; font-size: 11px; padding: 3px 8px;
              margin: 10px 0 4px; display: flex; justify-content: space-between; align-items: center; }
    .is-bar strong { background: transparent !important; color: #fff !important; font-size: 11px; }
    .is-bar em { color: #fff; }
    .is-bar .same { font-size: 10px; font-weight: bold; display: flex; align-items: center; }
    .is-bar .same input { margin: 0 6px 0 0; width: 12px; height: 12px; }

    .is-frame > .is-ident { flex: 1 1 auto; display: flex; flex-direction: column;
                            justify-content: space-evenly; padding-bottom: 4px; }
    .is-frow { display: flex; gap: 6px; padding: 0 4px; }
    .is-fld { min-width: 0; }
    .is-fld .val { display: flex; align-items: flex-end; justify-content: center; min-height: 50px;
                   border-bottom: 1px solid #000; padding: 0 2px 2px; text-align: center;
                   font-size: 12px; font-weight: bold; white-space: nowrap; overflow: hidden; }
    .is-fld .lbl { text-align: center; font-size: 8.5px; line-height: 1.15; margin-top: 2px; }

    .is-off { display: flex; align-items: center; gap: 6px; margin: 0 0 6px; font-size: 10px;
              line-height: 1.2; font-weight: bold; white-space: nowrap; }
    .is-box { display: inline-block; flex: 0 0 16px; width: 16px; height: 16px; border: 1px solid #000;
              text-align: center; font-size: 13px; line-height: 14px; }
    .is-ul { display: inline-block; min-width: 90px; border-bottom: 1px solid #000; text-align: center; font-weight: bold; }

    .is-assist { display: flex; gap: 18px; padding: 4px 4px 10px; font-size: 11px; }
    .is-assist .left { flex: 0 0 38%; margin: 20px 0 0 7px; }
    .is-assist .right { flex: 1; margin: 20px 0 0 7px; }
    .is-assist b { font-weight: normal; font-size: 12px; }
    .is-assist .note { font-style: italic; font-size: 9.5px; color: #444; }
    .is-assist label { display: flex; align-items: center; gap: 8px; margin: 0 0 4px; font-weight: normal; }
    .is-assist input[type="checkbox"] { appearance: none; -webkit-appearance: none; flex: 0 0 14px; width: 14px;
              height: 14px; margin: 0; border: 1.5px solid #222; border-radius: 2px; background: #fff; }
    .is-assist input[type="checkbox"]:checked {
      background: #fff url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><path d='M3 8.5l3.2 3.2L13 4.8' fill='none' stroke='black' stroke-width='2'/></svg>") center / 12px no-repeat; }
    .is-assist .yn { margin-top: 28px; }
    .is-assist .yn label { margin: 0 0 6px 10px; gap: 14px; }
    .is-assist .options { margin-top: 12px; }
    .is-assist .options label { align-items: flex-start; line-height: 1.25; margin-top: 15px; }
    .is-assist .options label input { margin-top: 1px; }
    .is-assist .others-line { flex: 0 0 95px; align-self: flex-end; border-bottom: 1px solid #444; min-height: 12px; }

    .is-footer { border-top: 1px solid #000; text-align: center; font-size: 10px; padding: 4px 0; margin-top: auto; }

    .is-p2 .is-bar { margin-top: 0; }
    table.is-fam { width: 100%; border-collapse: collapse; table-layout: fixed; }
    table.is-fam th { font-size: 10px; font-weight: bold; text-align: center; line-height: 2; padding: 6px 4px 4px;
                      border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; }
    table.is-fam th i { display: block; font-weight: normal; font-size: 8.5px; }
    table.is-fam td { height: 0.4in; border: 1px solid #000; font-size: 11px; padding: 2px 4px; vertical-align: middle; }
    table.is-fam td.no { font-size: 9px; vertical-align: top; padding: 2px 3px; }
    table.is-fam td.num { text-align: right; }
    .is-total { display: flex; justify-content: flex-end; border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; }
    .is-total .lbl-total { flex: 1 1 auto; text-align: right; font-weight: bold; font-style: italic; font-size: 11px; padding: 5px 6px; align-self: center; }
    .is-total .amt-total { flex: 0 0 14%; border-left: 1px solid #000; min-height: 26px; }
    .is-frame > .is-consent-wrap { flex: 1 1 auto; display: flex; flex-direction: column; }
    .is-consent { margin: 5%; font-size: 11.5px; line-height: 1.45; text-align: justify; }
    .is-consent p { margin: 0 0 14px; }
    .is-sign-row { display: flex; justify-content: space-between; align-items: flex-end; margin: auto 0 12px; padding: 0 6px; }
    .is-sign-block { width: 3.2in; text-align: center; margin-left: 0.5in; }
    .is-sign-block .line { border-top: 1px solid #000; padding-top: 3px; }
    .is-sign-block .l1 { font-size: 10px; font-weight: bold; }
    .is-sign-block .l2 { font-size: 9px; font-style: italic; }
    .is-thumb { width: 1.30in; text-align: center; margin-right: 5%; }
    .is-thumb .box { height: 1.45in; border: 1px solid #000; }
    .is-thumb .cap { font-size: 10px; margin-top: 2px; }
    .is-thumb .cap2 { font-size: 7.5px; font-style: italic; }

    /* Auto-fit text: font size is reduced by the script when the value is too long */
    .fit-text { display: inline-block; white-space: nowrap; }
    /* Used by the script when a value is too long even at the minimum single-line size */
    .fit-text.wrap { white-space: normal; text-align: center; line-height: 1.15; word-break: break-word; }
    .sig-name { white-space: nowrap; }
    /* Printed full name above the signature line (always one line, auto-fits) */
    .is-sign-block .printed { text-align: center; font-size: 11px; font-weight: bold; padding-bottom: 2px; }
    /* COE inputs marked "fit": font size set per field (PHP inline style + JS refinement) */
    .coe-wrap input.val.fit { text-overflow: clip; white-space: nowrap; }
</style>
<script>
/*
 * Fits the text inside .fit-text into its container:
 *  1) shrink on ONE line down to minSingle px (keeps it readable)
 *  2) if it still doesn't fit, wrap to up to maxLines lines and keep shrinking
 *     (never below minPx) until everything is visible.
 *  data-single="1" = always ONE line (signatory names): just keep shrinking.
 */
function fitText(el, minSingle, minPx, maxLines) {
    el.classList.remove('wrap');
    el.style.maxWidth = '';
    el.style.fontSize = '';
    var size = parseFloat(getComputedStyle(el).fontSize);
    var maxW = el.parentElement.clientWidth - 6;

    if (el.hasAttribute('data-single')) {
        while (el.offsetWidth > maxW && size > 4) {
            size -= 0.25;
            el.style.fontSize = size + 'px';
        }
        return;
    }

    while (el.offsetWidth > maxW && size > minSingle) {
        size -= 0.5;
        el.style.fontSize = size + 'px';
    }
    if (el.offsetWidth <= maxW) return;

    el.classList.add('wrap');
    el.style.maxWidth = maxW + 'px';
    while (el.offsetHeight > size * 1.15 * maxLines + 1 && size > minPx) {
        size -= 0.5;
        el.style.fontSize = size + 'px';
    }
}
function fitAll() {
    document.querySelectorAll('.fit-text').forEach(function (el) {
        fitText(el, 9, 6.5, 2);
    });
}
window.addEventListener('load', fitAll);
window.addEventListener('beforeprint', fitAll);
</script>
</head>
<body>
<?php
while ($row = mysqli_fetch_assoc($result)) {

    /* ---------- Shared data for all three forms ---------- */
    $fname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['first_name']);
    $mname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['middle_name']);
    $lname = str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $row['last_name']);
    $ename = $row['extension_name'];
    $amount = $row['amount'];
    $charging = strtoupper(trim((string)$row['charging']));

    // Age (reset every record so a bad birthdate never reuses the previous age)
    $age = '';
    $dob = str_replace('-', '/', $row['birth_day'].'/'.$row['birth_month'].'/'.$row['birth_year']);
    $dobDate = DateTime::createFromFormat('d/m/Y', $dob);
    if ($dobDate !== false) {
        $age = (new DateTime())->diff($dobDate)->y;
    }

    // Name is printed as: LAST, FIRST MIDDLE EXT
    $full_name = strtoupper(trim($lname.', '.trim($fname.' '.$mname.' '.$ename)));
    $address   = trim($row['purok'].' '.$row['barangay'].', '.$row['city_municipality'].', '.$row['province']);

    // COE program checks (the COE has no Wi-Support box, so anything else counts as "Others")
    $isAics  = ($charging == 'AICS');
    $isFfs   = ($charging == 'FFS');
    $isOther = ($charging != '' && !$isAics && !$isFfs);

    // GIS program checks (the GIS has a Wi-Support box, so it is excluded from "Others").
    // $gisOther was missing before, and error_reporting(0) hid the undefined-variable notice.
    $isWiSupport = in_array($charging, ['WI-SUPPORT', 'WI SUPPORT', 'WISUPPORT'], true);
    $gisOther    = ($charging !== '' && !$isAics && !$isFfs && !$isWiSupport);

    // COE: font sizes for long text (capacity = approx. characters that fit at 13px)
    $amount_words = numtowords($row['amount']).' ONLY';
    $fs_name_cert = fitsize($full_name, 56);   // "This is to certify that," line
    $fs_name_recv = fitsize($full_name, 46);   // "Received by" line (narrower)
    $fs_address   = fitsize($address, 60);
    $fs_words     = fitsize($amount_words, 58);

    // Information Sheet data (birthdate shown as MM/DD/YYYY)
    $bdateDisplay = ($row['birth_month'] !== '' && $row['birth_day'] !== '' && $row['birth_year'] !== '')
        ? $row['birth_month'].'/'.$row['birth_day'].'/'.$row['birth_year'] : '';
    $income = ($row['monthly_salary'] !== '' && $row['monthly_salary'] !== null)
        ? number_format((float)$row['monthly_salary'], 2) : '';

    $client = array(
        'ln' => $lname, 'fn' => $fname, 'mn' => $mname, 'ext' => $ename,
        'street' => $row['purok'], 'brgy' => $row['barangay'], 'city' => $row['city_municipality'],
        'prov' => $row['province'], 'region' => 'Region XI', 'mobile' => $row['contact_number'],
        'bdate' => $bdateDisplay, 'age' => $age, 'sex' => $row['sex'],
        'gender' => '',   // no gender identity column in aics_clean_list
        'civil' => $row['civil_status'], 'occ' => $row['occupation'], 'income' => $income, 'rel' => ''
    );
    $beneficiary = array_fill_keys(array_keys($client), '');
?>
<!-- ======================= GENERAL INTAKE SHEET ======================= -->
<div class="gis-wrap">

<!-- GIS PAGE 1 -->
<div class="fluid-container" style="font-size:12px; margin:0 20px 0 20px">

  <!--HEADER-->
  <div class="row">
    <div class="col-6">
      <img src="cpms/images/logo.png" alt="" width="240px" height="70px">
    </div>
  </div>
  <center>
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
      <div class="opt"><input disabled class="box" type="text" value="<?php echo $gisOther ? '✔' : ''; ?>"> Others:</div>
      <div style="border-bottom:1px solid #000; width:160px; min-height:14px; margin-left:2px; font-size:11px; line-height:14px; font-weight:bold; text-transform:uppercase;"><?php echo $gisOther ? htmlspecialchars($charging) : ''; ?></div>
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
        <div class="col-4"><p class="text-center" style="width: 100%; height:80%; font-size: 10px; ">Last Name</p></div>
        <div class="col-4"><p class="text-center" style="width: 100%; height:80%; font-size: 10px; ">First Name</p></div>
        <div class="col-3"><p class="text-center" style="width: 100%; height:80%; font-size: 10px; ">Middle Name</p></div>
        <div class="col-1"><p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Ext.</b> <i>(Sr.,Jr.)</i></p></div>
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
        <div class="col-4"><input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value=""></div>
        <div class="col-4"><input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value=""></div>
        <div class="col-3"><input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value=""></div>
        <div class="col-1"><input disabled class="text-center" style="width: 100%; height:80%; font-size: 13px; padding:0; border:none; border-bottom: 1px solid black;" type="text" value=""></div>
      </div>
      <div class="row" style="margin-top: 1px;">
        <div class="col-4"><p class="text-center" style="width: 100%; height:80%; font-size: 10px; ">Last Name</p></div>
        <div class="col-4"><p class="text-center" style="width: 100%; height:80%; font-size: 10px; ">First Name</p></div>
        <div class="col-3"><p class="text-center" style="width: 100%; height:80%; font-size: 10px; ">Middle Name</p></div>
        <div class="col-1"><p class="text-center" style="width: 100%; height:80%; font-size: 10px; "><b>Ext.</b> <i>(Sr.,Jr.)</i></p></div>
      </div>

    </div>
  </div>

  <div class="row">
    <div class="col-12 " style="border: 1px solid black; font-size:14px;line-height: 12px;">

      <!-- Diagnosis / Mode of assistance / Amount needed -->
      <div class="info-block">

        <div class="info-row">
          <span class="lbl">DIAGNOSIS/CAUSE OF DEATH <i>(if applicable)</i>:</span>
          <input type="text" class="line">
        </div>

        <div style="display:flex; align-items:center; flex-wrap:wrap; gap:4px 20px; margin-bottom:6px;">
          <span style="font-weight:bold; white-space:nowrap;">MODE OF ASSISTANCE :</span>
          <div class="opt" style="margin:0;"><input disabled class="box" type="text" value="&#x2714;"> Outright Cash</div>
          <div class="opt" style="margin:0;"><input disabled class="box" type="text" value=""> Guarantee Letter</div>
        </div>

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

              <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-left:16px; margin-bottom:6px;">
                <div>With fixed income<br><i style="font-size:11px;">(indicate number of members working)</i></div>
                <input type="text" style="width:150px; height:18px; border:none; border-bottom:1px solid #000;">
              </div>

              <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-left:16px; margin-bottom:10px;">
                <div>With non-fixed income<br><i style="font-size:11px;">(indicate number of members working)</i></div>
                <input type="text" style="width:150px; height:18px; border:none; border-bottom:1px solid #000;">
              </div>

              <div style="margin-left:60px; margin-bottom:10px;">
                <div style="margin-bottom:3px;">Other Financial Resources:</div>
                <div class="opt" style="font-size:14px;"><input disabled class="box" type="text" value=""> Insurance coverage</div>
                <div class="opt" style="font-size:14px;"><input disabled class="box" type="text" value=""> Savings</div>
                <div class="opt" style="font-size:14px;"><input disabled class="box" type="text" value=""> Pension</div>
              </div>

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

              <div style="font-weight:bold; margin-bottom:4px;">
                In the past three (3) months, did the family experience at least one crisis?
              </div>
              <div style="display:flex; gap:60px; margin-bottom:12px;">
                <div class="opt plain" style="margin:0; font-size:14px;"><input disabled class="box" type="text" value=""> Yes</div>
                <div class="opt plain" style="margin:0; font-size:14px;"><input disabled class="box" type="text" value=""> No</div>
              </div>

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

<!-- GIS PAGE 2 -->
<div class="fluid-container pg2" style="font-size:12px; page-break-before:always;">

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
        <div class="sig-name" style="height:60px; display:flex; align-items:flex-end; justify-content:center; font-weight:bold;"><span class="fit-text" data-single="1"><?php echo strtoupper($head); ?></span></div>
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

  </div><!-- /.p2-box -->
</div><!-- /GIS page 2 -->

</div><!-- /.gis-wrap -->

<!-- ======================= INFORMATION SHEET ======================= -->
<div class="is-wrap">

<!-- IS PAGE 1 -->
<div class="is-sheet">
  <div class="is-frame">

    <div class="is-top">
      <div class="logos">
        <img src="cpms/images/logo.png" alt="DSWD" width="240" height="70" style="margin-left:20px; margin-top:4px;">
      </div>
      <div style="padding-top:15px; padding-right:10px;">
        <div class="is-off"><span class="is-box"></span> Central Office</div>
        <div class="is-off"><span class="is-box">&#x2714;</span> Field Office <span class="is-ul">XI</span></div>
      </div>
    </div>

    <div class="is-title">INFORMATION SHEET</div>

    <div class="is-pcn-date">
      <div class="is-pcn">PCN:
        <div class="boxes"><?php for ($i = 0; $i < 16; $i++) { echo '<span></span>'; } ?></div>
      </div>
      <div class="is-datebox">Date
        <div class="parts">
          <div class="part"><div class="b" style="width:42px;"></div><i>MM</i></div>
          <div class="part"><div class="b" style="width:42px;"></div><i>DD</i></div>
          <div class="part"><div class="b" style="width:64px;"></div><i>YYYY</i></div>
        </div>
      </div>
    </div>

    <div class="is-bar"><span><strong>IMPORMASYON NG ITINALAGANG KINATAWAN/KLIYENTE</strong>
      <em>(Authorized Representative's/Client's Identifying Information)</em></span></div>
    <?php echo is_identity($client, true); ?>

    <div class="is-bar"><span><strong>IMPORMASYON NG BENEPISYARYO</strong>
      <em>(Beneficiary's Identifying Information)</em></span>
      <span class="same"><input type="checkbox"> KATULAD SA DETALYE NG KLIYENTE</span></div>
    <?php echo is_identity($beneficiary, false); ?>

    <div class="is-bar"><span><strong>NATANGGAP NA TULONG MULA SA DSWD</strong>
      <em>(Assistance Received from DSWD)</em></span></div>
    <div class="is-assist">
      <div class="left">
        <b>Ikaw ba ay nakakuha na ng tulong mula sa DSWD?</b><br>
        <span class="note">(Have you received any assistance from the DSWD?)</span>
        <div class="yn">
          <label><input type="checkbox"> Hindi</label>
          <label><input type="checkbox"> Oo</label>
        </div>
      </div>
      <div class="right">
        <b>Natanggap na tulong sa DSWD</b><br>
        <span class="note">(Assistance received from DSWD)</span>
        <div class="options">
          <label><input type="checkbox"> Assistance to Individuals in Crisis Situations (AICS)</label>
          <label><input type="checkbox"> Walang Gutom Program (WGP)</label>
          <label><input type="checkbox"> Pantawid Pamilyang Pilipino Program (4Ps)</label>
          <label><input type="checkbox"> Sustainable Livelihood Program (SLP)</label>
          <label><input type="checkbox"> Kapit-Bisig Laban sa Kahirapan-Comprehensive and Integrated Delivery of Social Services (KALAHI CIDDS)</label>
          <label><input type="checkbox"> Others: <span class="others-line"></span></label>
        </div>
      </div>
    </div>

    <div class="is-footer" style="margin-top:220px;">
      <b>PAGE 1 of 2</b><br>
      DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
      Website: http://fo11.dswd.gov.ph Tel Nos.: (082) 227 1964 / (082) 227 8746 / (082) 227 1435 Telefax: (082) 226 2857
    </div>

  </div>
</div>

<!-- IS PAGE 2 -->
<div class="is-sheet is-p2">
  <div class="is-frame">

    <div class="is-bar"><span><strong>KOMPOSISYON NG PAMILYA</strong> <em>(Family Composition)</em></span></div>

    <table class="is-fam">
      <colgroup>
        <col style="width:3.5%;"><col style="width:36%;"><col style="width:22%;">
        <col style="width:7%;"><col style="width:17%;"><col style="width:14.5%;">
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
        <tr><td class="no"><?php echo $i; ?></td><td></td><td></td><td></td><td></td><td class="num"></td></tr>
        <?php } ?>
      </tbody>
    </table>
    <div class="is-total">
      <div class="lbl-total">TOTAL FAMILY INCOME</div>
      <div class="amt-total"></div>
    </div>

    <div class="is-bar"><span><strong>DEKLARASYON NG PAHINTULOT</strong> <em>(Consent Form)</em></span></div>

    <div class="is-consent-wrap">
      <div class="is-consent">
        <p>Ako ay nagdedeklara sa ilalim ng parusa ng pagsisinungaling (perjury), na ang lahat ng impormasyon sa aplikasyon na ito ay totoo at tama batay sa aking personal na kaalaman at mga autentikong rekord na isinumite sa Department of Social Welfare and Development (DSWD). Anumang mali o mapanlinlang na impormasyon na ibinigay, o paggawa ng pekeng/pinagwaglit na mga dokumento ay magiging sanhi ng nararapat na hakbang na legal laban sa akin at awtomatikong magpawalang-bisa sa anumang tulong na ibibigay kaugnay ng aplikasyon na ito.</p>
        <p>Ako ay sumasang-ayon na ang lahat ng personal na datos (ayon sa depinisyon sa ilalim ng Republic Act 10173 o Data Privacy Act of 2012 at mga patnubay nito) at impormasyon o mga rekord ng mga transaksyon sa account sa DSWD ay maaaring iproseso, i-profile, at, kung kinakailangan at pinahihintulutan ng batas at patakaran, ilathala ang aking pangalan sa opisyal na website ng DSWD, alinsunod sa probisyon ng RA 12314 or the General Appropriations Act, para sa transparency at accountability, o ibahagi sa mga humihiling na partido o para sa layunin ng anumang hukuman, proseso ng batas, pagsusuri, inquiry, audit, o imbestigasyon ng anumang awtoridad.</p>
      </div>

      <div class="is-sign-row" style="margin-top:50px;" >
        <div class="is-sign-block">
          <div class="printed"><span class="fit-text" data-single="1"><?php echo htmlspecialchars($full_name); ?></span></div>
          <div class="line">
            <div class="l1">Lagda sa ibabaw ng Buong Pangalan ng Kinatawan/Kliyente</div>
            <div class="l1" style="font-weight:normal;">Last Name, First Name, Middle Name, Extension Name</div>
            <div class="l2">(Signature over Printed Name of the Authorized Representative/Client)</div>
          </div>
        </div>
        <div class="is-thumb">
          <div class="box"></div>
          <div class="cap">Thumbmark</div>
          <div class="cap2">(If unable to provide specimen signature)</div>
        </div>
      </div>
    </div>

    <div class="is-footer" style="margin-top:170px;">
      <b>PAGE 2 of 2</b><br>
      DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
      Website: http://fo11.dswd.gov.ph Tel Nos.: (082) 227 1964 / (082) 227 8746 / (082) 227 1435 Telefax: (082) 226 2857
    </div>

  </div>
</div>

</div><!-- /.is-wrap -->

<!-- ======================= CERTIFICATE OF ELIGIBILITY (2 COPIES) ======================= -->
<?php for ($copy = 1; $copy <= 2; $copy++) { ?>
<div class="coe-wrap">
<div class="print-coe" style="border:1px solid #000; padding:10px 20px;">

  <div class="copy-label"><?php echo ($copy == 1) ? 'Office Copy' : 'Client Copy'; ?></div>

  <!-- HEADER -->
  <div style="display:flex; justify-content:space-between; align-items:flex-start;">
    <div>
      <img src="cpms/images/logo.png" alt="" width="230px" height="70px">
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
      <div class="sig-name" style="height:100px; display:flex; align-items:flex-end; justify-content:center; font-weight:bold; font-size:13px;"><span class="fit-text" data-single="1"><?php echo strtoupper($head); ?></span></div>
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
      <b style="font-size:13px;">Received by:</b>
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
  <div class="footer" style="margin-top:45px;">
    <div style="border-bottom:solid 1px black;"></div>
    <p class="text-center" style="font-size:10px; line-height:1.2; margin:2px 0 0;">
      <b>Page 1 of 1</b><br>
      DSWD Field Office XI, Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000<br>
      Website: http://fo11.dswd.gov.ph Tel Nos.: (082) 227 1964 / (082) 227 8746 / (082) 227 1435 Telefax: (082) 226 2857
    </p>
  </div>

</div><!-- /.print-coe -->
</div><!-- /.coe-wrap -->
<?php } // end for: COE copies ?>

<?php } // end while: next record ?>
<script>
// Second pass for the COE: measures the real text width with a canvas and shrinks input.fit to fit.
// The PHP-computed size is already applied, so if this script does not run the page still works.
(function () {
    var MIN_SIZE = 6;
    var canvas = document.createElement('canvas');
    var ctx = canvas.getContext('2d');

    function fitInput(el) {
        var prev = el.style.getPropertyValue('font-size');
        el.style.removeProperty('font-size');
        var cs = window.getComputedStyle(el);
        var base = parseFloat(cs.fontSize);
        var avail = el.clientWidth - parseFloat(cs.paddingLeft) - parseFloat(cs.paddingRight);

        if (!avail || avail <= 0 || !el.value) {
            if (prev) el.style.setProperty('font-size', prev, 'important');
            return;
        }

        ctx.font = cs.fontStyle + ' ' + cs.fontWeight + ' ' + base + 'px ' + cs.fontFamily;
        var width = ctx.measureText(el.value.toUpperCase()).width;

        if (width > avail) {
            var size = Math.floor((base * avail / width) * 10) / 10 - 0.2;
            el.style.setProperty('font-size', Math.max(MIN_SIZE, size) + 'px', 'important');
        } else if (prev) {
            el.style.setProperty('font-size', prev, 'important');
        }
    }

    function fitAllInputs() {
        var inputs = document.querySelectorAll('input.fit');
        for (var i = 0; i < inputs.length; i++) fitInput(inputs[i]);
    }

    fitAllInputs();
    document.addEventListener('DOMContentLoaded', fitAllInputs);
    window.addEventListener('load', fitAllInputs);
    window.addEventListener('beforeprint', fitAllInputs);
    window.addEventListener('resize', fitAllInputs);
})();
</script>
</body>
</html>