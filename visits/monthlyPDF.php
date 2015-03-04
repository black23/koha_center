<?php
# This file is part of Koha.
#
# Copyright (C) 2014  MartinKravec
#
# Koha is free software; you can redistribute it and/or modify it
# under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 3 of the License, or
# (at your option) any later version.
#
# Koha is distributed in the hope that it will be useful, but
# WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Koha; if not, see <http://www.gnu.org/licenses>.
	
    session_start();

	require_once "../system/config.php";
	
	$department = $_GET["department"];
	$from = $_GET["from"];
    $to = $_GET["to"];
    $departmentText = $_GET["departmentText"];
    
    $total = $_GET["total"];
    $registrations = $_GET["registrations"];
    $borrows = $_GET["borrows"];
    $reservations = $_GET["reservations"];
    $returns = $_GET["returns"];
    $paids = $_GET["paids"];
    $prolongs = $_GET["prolongs"];
    
    $allOthers = $reservations+$returns+$paids+$prolongs;
    
    $newFrom = new DateTime($from);
    $dayFrom = $newFrom->format('d.m');
    
    $newTo = new DateTime($to);
    $dayTo = $newTo->format('d.m');

    /*********************************/
    
    require_once('../system/tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('KOHA');
$pdf->SetTitle('KOHA');
$pdf->SetSubject('KOHA');
$pdf->SetKeywords('KOHA');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 12, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = "
<h3>$text->visits_summary ($dayFrom - $dayTo) - $departmentText</h3>
   <table>
   <tr><td><b>$text->registrace</b>:</td><td align='right'>$registrations</td><td align='right'>$registrations</td></tr>
   <tr><td><b>$text->borrowed</b>:</td><td align='right'>$borrows</td><td align='right'>$borrows</td></tr>
   <tr><td><b>$text->reservations</b>:</td><td align='right'>$reservations</td><td align='right'>*********</td></tr>
   <tr><td><b>$text->returned</b>:</td><td align='right'>$returns</td><td align='right' bgcolor='#cccccc'>$allOthers</td></tr>
   <tr><td><b>$text->paid</b>:</td><td align='right'>$paids</td><td align='right'>*********</td></tr>
   <tr><td><b>$text->prolonged_offline</b>:</td><td align='right'>$prolongs</td><td align='right'>*********</td></tr>
   </table>
   <br><br><br><br>
   <b>$text->total_unique_visits</b>: $total
   <br><br><br><br>
   $text->signature<br>
   ..........................................................
";

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('monthly.pdf', 'I');
