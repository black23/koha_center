<?php

    session_start();

    if (!headers_sent()) {
	header('Content-Type: text/html; charset=utf-8');
    }
	
    require_once "../system/config.php";
    require_once "../system/correspondence/sms.class.php";
    $Sms = new Sms($db, $SMS_username, $SMS_password);
        
    use Tracy\Debugger;
    
    $id = (int) $_POST['id'];
    
    $message = $Sms->getMessage($id);

    $result = [
        "message" => $message[0]['message'],
        "solver" => $message[0]['solver'],
        "type" => $message[0]['type'],
        "status" => $message[0]['status'],
    ];
    
    echo json_encode($result);
