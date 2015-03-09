<?php

    session_start();

    if (!headers_sent()) {
	header('Content-Type: text/html; charset=utf-8');
    }
	
    require_once "../system/config.php";
    require_once "StatisticalReport.class.php";
    
    use Tracy\Debugger;
    
    $from = $_POST["from"];
    $to = $_POST["to"];
    
    $StatisticalReport = new StatisticalReport($db, $from, $to);
        
    $results = $StatisticalReport->getReportAsArray();
    
    echo json_encode($results);
