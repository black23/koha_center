<?php

    session_start();

    if (!headers_sent()) {
	header('Content-Type: text/html; charset=utf-8');
    }
	
    require_once "../system/config.php";
    require_once "../system/correspondence/sms.class.php";
    $Sms = new Sms($db, $SMS_username, $SMS_password);
        
    use Tracy\Debugger;
    
    $smsID = (int) $_POST['sms_id'];
    $status = (int) $_POST['status'];
    $solverID = $_SESSION['userID'];
    
    $Sms->solveMessage($smsID, $status, $solverID);
