<?php

    session_start();

    if (!headers_sent()) {
	header('Content-Type: text/html; charset=utf-8');
    }
	
    require_once "../system/config.php";
    require_once "../system/correspondence/sms.class.php";
    $Sms = new Sms($db, $SMS_username, $SMS_password);
        
    use Tracy\Debugger;
    
    
    $receivers = $_POST['receivers'];
    $message = $_POST['message'];
    $time = $_POST['time'];
    
    if($time == ""){
        

        $time = date("d. m. Y h:i:s", time() + 30);


    }
    
    $deliveryReport = $_POST['deliveryReport'];
    
    $prevodni_tabulka = Array(
        'ä'=>'a',
        'Ä'=>'A',
        'á'=>'a',
        'Á'=>'A',
        'à'=>'a',
        'À'=>'A',
        'ã'=>'a',
        'Ã'=>'A',
        'â'=>'a',
        'Â'=>'A',
        'č'=>'c',
        'Č'=>'C',
        'ć'=>'c',
        'Ć'=>'C',
        'ď'=>'d',
        'Ď'=>'D',
        'ě'=>'e',
        'Ě'=>'E',
        'é'=>'e',
        'É'=>'E',
        'ë'=>'e',
        'Ë'=>'E',
        'è'=>'e',
        'È'=>'E',
        'ê'=>'e',
        'Ê'=>'E',
        'í'=>'i',
        'Í'=>'I',
        'ï'=>'i',
        'Ï'=>'I',
        'ì'=>'i',
        'Ì'=>'I',
        'î'=>'i',
        'Î'=>'I',
        'ľ'=>'l',
        'Ľ'=>'L',
        'ĺ'=>'l',
        'Ĺ'=>'L',
        'ń'=>'n',
        'Ń'=>'N',
        'ň'=>'n',
        'Ň'=>'N',
        'ñ'=>'n',
        'Ñ'=>'N',
        'ó'=>'o',
        'Ó'=>'O',
        'ö'=>'o',
        'Ö'=>'O',
        'ô'=>'o',
        'Ô'=>'O',
        'ò'=>'o',
        'Ò'=>'O',
        'õ'=>'o',
        'Õ'=>'O',
        'ő'=>'o',
        'Ő'=>'O',
        'ř'=>'r',
        'Ř'=>'R',
        'ŕ'=>'r',
        'Ŕ'=>'R',
        'š'=>'s',
        'Š'=>'S',
        'ś'=>'s',
        'Ś'=>'S',
        'ť'=>'t',
        'Ť'=>'T',
        'ú'=>'u',
        'Ú'=>'U',
        'ů'=>'u',
        'Ů'=>'U',
        'ü'=>'u',
        'Ü'=>'U',
        'ù'=>'u',
        'Ù'=>'U',
        'ũ'=>'u',
        'Ũ'=>'U',
        'û'=>'u',
        'Û'=>'U',
        'ý'=>'y',
        'Ý'=>'Y',
        'ž'=>'z',
        'Ž'=>'Z',
        'ź'=>'z',
        'Ź'=>'Z'
      );
 
    $message = strip_tags(strtr(trim($message), $prevodni_tabulka));
    
    $time = str_replace(".", "", $time);
    $time = str_replace(":", "", $time);
    $timeArray = preg_split('/\s+/', trim($time));
    $time = $timeArray[2].$timeArray[1].$timeArray[0]."T".$timeArray[3];
    
    
    
    $receivers = str_replace("@", "", $receivers);
    $receivers = str_replace("&64;", "", $receivers);
    $receivers = str_replace("(", "", $receivers);
    $receivers = str_replace(")", "", $receivers);
    $receivers = str_replace("-", "", $receivers);
    $receivers = str_replace(",", "", $receivers);
    
    $data   = preg_split('/\s+/', trim($receivers));
    
    $data = array_chunk($data, 3);
    
    foreach($data AS $key => $val){
        $count = count($val);
        if(($count % 3) != 0){
            $result = [
                "status" => "error",
                "message" => "Unexpected char or string in receivers",
            ];
        }
    }
    
    if(isset($result) AND ($result['status'] == 'error')){
        echo json_encode($result);
        return false;
    }
    else{
        /* Just for debug */
        //print_r($data);
        //print_r($message);
        //print_r($deliveryReport);
        /////////////////////////////////////


        if(count($data) > 1){
            $res = $Sms->sendMultipleMessages($data, $message, $time, $deliveryReport);
            if($res != 0){
                $errorMessage = "$text->sms_error_occured: ";
                foreach($res AS $key => $val){
                    $errNo = "sms_error_".$val['err'];
                    $errorMessage .= $val['phone']." - error ".$text->$errNo.", ";
                }
                $errorMessage .= "'";
                
                $result = [
                    "status" => "error",
                    "message" => "$errorMessage",
                ];
                
                if(isset($result) AND ($result['status'] == 'error')){
                    echo json_encode($result);
                    return false;
                }
    
            }
        }
        else{ // count($data) == 1
            $Sms->sendMessage($data[0]['2'], $message, $time, "", $deliveryReport);
        }
        
        $result = [
            "status" => "ok",
            "message" => "ok",
        ];
        echo json_encode($result);
    }
	