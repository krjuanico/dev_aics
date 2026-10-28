<?php
require('fpdf186/fpdf.php');
require('html2fpdf/html2fpdf.php'); // Include the HTML extension

// Create instance of FPDF
$pdf = new PDF_HTML();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Add a Title
$pdf->Cell(0, 10, 'General Intake Sheet', 0, 1, 'C'); // Centered title
$pdf->Ln(10); // Line break

// HTML Content
$html = '
<div style="font-family: Arial, sans-serif; font-size: 12px;">
    <p><b>Name:</b> John Doe</p>
    <p><b>Position:</b> Software Developer</p>
    <p><b>Email:</b> john.doe@example.com</p>
</div>';

// Render HTML
$pdf->WriteHTML($html);

// Output the PDF
$pdf->Output('D', 'PDS.pdf'); // 'D' forces download
?>
