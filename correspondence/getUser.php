<?php

    session_start();

    header('Content-Type: application/json; charset=utf-8');
	
    require_once "../system/config.php";
    require_once "../system/correspondence/sms.class.php";
    
    use Tracy\Debugger;
    
    $Sms = new Sms($db, $SMS_username, $SMS_password);
    
    $result = $Sms->getBorrowerAsArray($_GET["q"]);
 
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
