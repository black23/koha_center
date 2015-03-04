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
    
    use Tracy\Debugger;
	
	$department = $_GET["department"];
	$date = $_GET["date"];
    $departmentText = $_GET["departmentText"];
	
	$type = "payment";
    
    $myArray = Array();
    
    if ($department == "allDepartments") {
        
            require_once __DIR__."/../system/visits/DatabaseHandler.class.php";

            $databaseHandler = new DatabaseHandler($db);
            $branches = $databaseHandler->getBranches();
            
            $allDepartmentsStr = "'";
            foreach ($branches as $arr) {
                 array_push($myArray,  $arr['branchcode']);
            }

        }
        else {
            array_push($myArray,  $department);
        }
	
	try {
		
		$db->beginTransaction();
        
        $query = "SELECT `a`.`timestamp`, `b`.`borrowernumber`, `b`.`firstname`, `b`.`surname` "
				."FROM `statistics` `s` "
                ."JOIN `accountlines` `a` "
                ."ON `a`.`accountlines_id` = `s`.`other` "
                ."LEFT OUTER JOIN `borrowers` `b` "
                ."ON `b`.`borrowernumber` = `a`.`borrowernumber` "
				."WHERE `s`.`branch` IS NULL AND DATE(`s`.`datetime`) = :date AND `s`.`type` = :type AND `a`.`accounttype` IN ('".implode('\',\'', $cat_reservations)."') "	
                ."GROUP BY `b`.`borrowernumber` "
                ."ORDER BY `s`.`datetime` ASC";	
			
		$stmt = $db->prepare($query);
		$stmt->bindValue(':date', $date, PDO::PARAM_STR);
		$stmt->bindValue(':type', $type, PDO::PARAM_STR);
		$stmt->execute();

		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 
		$db->commit();
                		
	} catch(PDOException $ex) {
		
		Debugger::log($ex->getMessage());
		
	}
    
    $newDate = new DateTime($date);
    $day = $newDate->format('d.m');

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
<h3>$text->reserved_online ($day)</h3>
<table>
        <thead>
            <tr>
                <th><b>$text->name</b></th>
                <th><b>$text->surname</b></th>
                <th><b>$text->time</b></th>
            </tr>
        </thead>
        <tbody>
        ";
        foreach($results as $key => $val){
        $datetime = new DateTime($results[$key]["timestamp"]);
        $time = $datetime->format("H:i:s");
        $results[$key]["timestamp"] = $time;
        $html .= "
            <tr>
                <td>".$results[$key]['firstname']."</td>
                <td>".$results[$key]['surname']."</td>
                <td>".$time."</td>
            </tr>";
                    }
                    $html .= "
        </tbody>
   </table>
   <br><br><br><br>
   $text->signature<br>
   ..........................................................
";

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('reservationsonline.pdf', 'I');
