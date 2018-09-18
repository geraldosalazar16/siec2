<?php
require('fpdf.php');

require('makefont/makefont.php');

//MakeFont('calibrii.ttf','cp1252');

$pdf = new FPDF();
$pdf->AddFont('Calibri','I','calibrii.php');
$pdf->AddPage();
$pdf->SetFont('Calibri','I',35);
$pdf->Write(10,'Enjoy new fonts with FPDF!');
$pdf->Output();

?>