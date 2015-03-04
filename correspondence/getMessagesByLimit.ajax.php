<?php

    session_start();

    if (!headers_sent()) {
	header('Content-Type: text/html; charset=utf-8');
    }
	
    require_once "../system/config.php";
    require_once "../system/correspondence/sms.class.php";
    $Sms = new Sms($db, $SMS_username, $SMS_password);
        
    use Tracy\Debugger;
    
    $type = $_POST['type'];
    $from = $_POST['from'];
    $offset = $_POST['offset'];
    $receiver = $_POST['receiver'];
    $sender = $_POST['sender'];
    $staff = $_POST['staff'];
    $status = $_POST['status'];
    
    $rows = $Sms->getMessagesByLimit($type, $from, $offset, $receiver, $sender, $staff, $status);
/*
    $result = [
        "receiver" => $rows[0],
        "datetime" => $datetime,
        "sms_id" => $sms_id,
        "status" => $status,
        "sender" => $sender,
    ];*/
    
    echo json_encode($rows);
