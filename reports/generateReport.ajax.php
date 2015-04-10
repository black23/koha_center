<?php

    session_start();

    if (!headers_sent()) {
	header('Content-Type: text/html; charset=utf-8');
    }
	
    require_once "../system/config.php";
    require_once "StatisticalReport.class.php";
    
    use Tracy\Debugger;
    
    //displayErrors(true);
    
    $from = $_POST["from"];
    $to = $_POST["to"];
    
    $StatisticalReport = new StatisticalReport($db, $from, $to, $categoryCode, $doc_types, $ccodes, $cat_prints);
        
    $results = $StatisticalReport->getReportAsArray();
    
    echo json_encode($results);
