<?php

    if (!headers_sent()) {
            header('Content-Type: text/html; charset=utf-8');
    }

require_once(__DIR__.'/../smsconnect/connect.php');

use Tracy\Debugger;

/**
 * @TODO downloadInbox2DB() merge queries in loops for better performance
 */
class Sms{
    
    private $username;
    private $password;
    private $db;
    
    public function __construct($db, $SMS_username, $SMS_password){
        $this->username = $SMS_username;
        $this->password = $SMS_password;
        $this->db       = $db;
    }

    public function getCredit()
    {
        $sms = new CSMSConnect();
        $sms->Create($this->username, $this->password, CSMSConnect::AUTH_HASH);
        
        $XMLResult = $sms->credit();
        $sms->Logout();
        
        $array = $this->XML2Array($XMLResult);
        
        return $array['credit'];
    }
    
    private function XML2Array($xmlstring)
    {
        $xml = simplexml_load_string($xmlstring, null, LIBXML_NOCDATA);
        $json = json_encode($xml); 
        $array = json_decode($json, TRUE);
        
        return $array;
    }
    
    public function getInboxAsXML()
    {
        $sms = new CSMSConnect();
        $sms->Create($this->username, $this->password, CSMSConnect::AUTH_HASH); // inicializace a prihlaseni login, heslo, typ zabezpeceni

        $XMLResult = $sms->Inbox();
        $sms->Logout();
        
        $array = $this->XML2Array($XMLResult);
        
        return $array;
        
    }
    
    private function getInbox()
    {
        $sms = new CSMSConnect();
        $sms->Create($this->username, $this->password, CSMSConnect::AUTH_HASH); // inicializace a prihlaseni login, heslo, typ zabezpeceni

        $XMLResult = $sms->Inbox();
        $sms->Logout();
        
        $array = $this->XML2Array($XMLResult);
        
        return $array;
        
    }
    
    public function downloadInbox2DB()
    {
        
        $array = $this->getInbox();
        //echo count($array['inbox']['delivery_sms']);
        
        // Received START
        if((isset($array['inbox']['delivery_sms'])) AND (count($array['inbox']['delivery_sms']) != 0) ){
            
            if(count($array['inbox']['delivery_sms']['item']) == count($array['inbox']['delivery_sms']['item'], COUNT_RECURSIVE)){

                $phone = $array['inbox']['delivery_sms']['item']['number'];
                $type = "incoming";
                $message = $array['inbox']['delivery_sms']['item']['message'];

                $str = $array['inbox']['delivery_sms']['item']['time'];
                $time = substr($str, 0, 4)."-".substr($str, 4, 2)."-".substr($str, 6, 2)." ".substr($str, 9, 2).":".substr($str, 11, 2).":".substr($str, 13, 2);

                $status = 2;
                
                try {



                    $query = "INSERT INTO `cen_smshistory` (`phone`, `type`, `message`, `time`, `system_sms_id`, `status`) VALUES (:phone, :type, :message, :time, :system_sms_id, :status)";

                    $stmt = $this->db->prepare($query);
                    $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
                    $stmt->bindValue(':type', $type, PDO::PARAM_STR);
                    $stmt->bindValue(':message', $message, PDO::PARAM_STR);
                    $stmt->bindValue(':time', $time, PDO::PARAM_STR);
                    $stmt->bindValue(':system_sms_id', null, PDO::PARAM_INT);
                    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
                    $stmt->execute();

                } catch(PDOException $ex) {

                    Debugger::Log($ex->getMessage());
                    echo $ex->getMessage();

                }

            }
            else{

                foreach ($array['inbox']['delivery_sms']['item'] AS $key => $val){

                    $phone = $val['number'];
                    $type = "incoming";
                    $message = $val['message'];

                    $str = $val['time'];
                    $time = substr($str, 0, 4)."-".substr($str, 4, 2)."-".substr($str, 6, 2)." ".substr($str, 9, 2).":".substr($str, 11, 2).":".substr($str, 13, 2);

                    $status = 2;
                    
                    try {



                        $query = "INSERT INTO `cen_smshistory` (`phone`, `type`, `message`, `time`, `system_sms_id`, `status`) VALUES (:phone, :type, :message, :time, :system_sms_id, :status)";

                        $stmt = $this->db->prepare($query);
                        $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
                        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
                        $stmt->bindValue(':message', $message, PDO::PARAM_STR);
                        $stmt->bindValue(':time', $time, PDO::PARAM_STR);
                        $stmt->bindValue(':system_sms_id', null, PDO::PARAM_INT);
                        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
                        $stmt->execute();

                    } catch(PDOException $ex) {

                        Debugger::Log($ex->getMessage());
                        echo $ex->getMessage();

                    }

                }

            }
            
        }

                

        // Received END
        
        // Delivered START
        
        
        
        if((isset($array['inbox']['delivery_report'])) AND (count($array['inbox']['delivery_report']) !== 0)){
           
            if(count($array['inbox']['delivery_report']['item']) == count($array['inbox']['delivery_report']['item'], COUNT_RECURSIVE)){
                
                $systemSMSID = $array['inbox']['delivery_report']['item']['idsms'];
                $status = $array['inbox']['delivery_report']['item']['status'];

                try {

                    $query = "UPDATE `cen_smshistory` SET `status`=:status WHERE `system_sms_id`=:system_sms_id";

                    $stmt = $this->db->prepare($query);
                    $stmt->bindValue(':system_sms_id', $systemSMSID, PDO::PARAM_INT);
                    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
                    $stmt->execute();

                } catch(PDOException $ex) {

                    Debugger::Log($ex->getMessage());
                    echo $ex->getMessage();

                }
           
            }
            else {

                foreach ($array['inbox']['delivery_report']['item'] AS $key => $val){

                    $systemSMSID = $val['idsms'];
                    $status = $val['status'];

                    try {

                        $query = "UPDATE `cen_smshistory` SET `status`=:status WHERE `system_sms_id`=:system_sms_id";

                        $stmt = $this->db->prepare($query);
                        $stmt->bindValue(':system_sms_id', $systemSMSID, PDO::PARAM_INT);
                        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
                        $stmt->execute();

                    } catch(PDOException $ex) {

                        Debugger::Log($ex->getMessage());
                        echo $ex->getMessage();

                    }

                }

            }
        
        }
        // Delivered END
        
        $this->deleteInbox();
         
    }
    
    private function deleteInbox()
    {
        $sms = new CSMSConnect();
        $sms->Create($this->username, $this->password, CSMSConnect::AUTH_HASH);
        
        $sms->deleteInbox();
        $sms->Logout();
    }
    
    /**
     * 
     * @param string $type [incoming/outgoing]
     * @param type $limit
     * @return type
     */
    public function getMessages($type, $limit)
    {
        
        $messages = array();
        $count = 0;
        
        try {

            $query = "SELECT * FROM `cen_smshistory` WHERE type=:type ORDER BY `time` DESC LIMIT :limit";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':type', $type, PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $messages = $results;

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());

        }
        
        try {

            $query = "SELECT COUNT(*) AS `count` FROM `cen_smshistory` WHERE type=:type";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':type', $type, PDO::PARAM_STR);
            $stmt->execute();

            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $res[0]['count'];
            
        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());

        }
        
        
        $array = array('messages' => $messages, 
                       'count' => $count
                );
        return $array;
        
    }
    
    public function sendMultipleMessages($receivers, $message, $time, $deliveryReport)
    {
        $sms = new CSMSConnect();
        $sms->Create($this->username, $this->password, CSMSConnect::AUTH_HASH);

        foreach($receivers AS $key => $val){
            $sms->Add_SMS($val, $message, $time, "", $deliveryReport);
        }
        
        $XMLResponse = $sms->SendAllSMS();
        
        $XMLResponseArray = $this->XML2Array($XMLResponse);
        
        $errors = array();
        foreach($XMLResponseArray['result_count']['item'] AS $key => $val){
            
            $error = $val['err'];
            $number = $val['number'];
            $smsID = isset($val['sms_id']) ? $val['sms_id'] : NULL;
            
            if($error != 0){
                $errorArray = array( 'err' => $error, 
                                    'phone' => $number
                );

                array_push($errors, $errorArray);

                unset($errorArray);
            }
            else{ // no error
                $phone = $number;
        
                $type = "outgoing";
                $str = $time;
                $dbTtime = substr($str, 0, 4)."-".substr($str, 4, 2)."-".substr($str, 6, 2)." ".substr($str, 9, 2).":".substr($str, 11, 2).":".substr($str, 13, 2);
                $systemSMSID = $smsID;

                $senderId = $_SESSION["userID"];
                
                try {

                    $query = "INSERT INTO `cen_smshistory` (`phone`, `type`, `message`, `time`, `system_sms_id`, `status`, `sender_id`) VALUES (:phone, :type, :message, :time, :system_sms_id, :status, :sender_id)";

                    $stmt = $this->db->prepare($query);
                    $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
                    $stmt->bindValue(':type', $type, PDO::PARAM_STR);
                    $stmt->bindValue(':message', $message, PDO::PARAM_STR);
                    $stmt->bindValue(':time', $dbTtime, PDO::PARAM_STR);
                    $stmt->bindValue(':system_sms_id', $systemSMSID, PDO::PARAM_INT);
                    $stmt->bindValue(':status', null, PDO::PARAM_INT);
                    $stmt->bindValue(':sender_id', $senderId, PDO::PARAM_INT);
                    $stmt->execute();

                } catch(PDOException $ex) {

                    Debugger::Log($ex->getMessage());

                }
            }
            
        }
        
        if(count($errors) > 0){
            return $errors;
        }
        else{
            return 0;
        }
        
        $sms->Logout();
    }
    
    public function sendMessage($number, $message, $time="", $sender="", $delivery="")
    {
        $sms = new CSMSConnect();
        $sms->Create($this->username, $this->password, CSMSConnect::AUTH_HASH);
        
        $XMLResponse = $sms->Send_SMS($number, $message, $time, $sender, $delivery);

        $sms->Logout();
        
        $phone = $number;
        $type = "outgoing";
        $str = $time;
        $dbTtime = substr($str, 0, 4)."-".substr($str, 4, 2)."-".substr($str, 6, 2)." ".substr($str, 9, 2).":".substr($str, 11, 2).":".substr($str, 13, 2);
        
        
        $XMLResponseArray = $this->XML2Array($XMLResponse);
        
        $systemSMSID = $XMLResponseArray['sms_id'];
        
        $senderId = $_SESSION["userID"];
        
        try {



            $query = "INSERT INTO `cen_smshistory` (`phone`, `type`, `message`, `time`, `system_sms_id`, `status`, `sender_id`) VALUES (:phone, :type, :message, :time, :system_sms_id, :status, :sender_id)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindValue(':type', $type, PDO::PARAM_STR);
            $stmt->bindValue(':message', $message, PDO::PARAM_STR);
            $stmt->bindValue(':time', $dbTtime, PDO::PARAM_STR);
            $stmt->bindValue(':system_sms_id', $systemSMSID, PDO::PARAM_INT);
            $stmt->bindValue(':status', null, PDO::PARAM_INT);
            $stmt->bindValue(':sender_id', $senderId, PDO::PARAM_INT);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);



        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());

        }
        
    }
    
    private function preparePhoneNumeber($phoneNumber)
    {
        return preg_replace('/\s/', '', $phoneNumber); // removes all whitespaces
    }
    
    public function getBorrowers()
    {
        
        try {
		
            //$this->db->beginTransaction();
        
            $query = "SELECT COALESCE(`firstname`, 'NoName') AS `firstname`, COALESCE(`surname`, 'NoSurname') AS `surname`, COALESCE(NULLIF(`smsalertnumber`,''), NULLIF(`phone`,''), NULLIF(`phonepro`,'')) AS `phone` "
                    ."FROM `borrowers` "
                    ."WHERE (`smsalertnumber` IS NOT NULL AND `smsalertnumber` != '') OR (`phone` IS NOT NULL AND `phone` != '') OR (`phonepro` IS NOT NULL AND `phonepro` != '')";
			
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 
            //$this->db->commit();
                		
	} catch(PDOException $ex) {
		
            Debugger::Log($ex->getMessage());
		
	}
        
        return $results;
        
    }
    
    public function getStaff()
    {
        
        try {
		
            //$this->db->beginTransaction();
        
            $query = "SELECT COALESCE(`b`.`firstname`, 'NoName') AS `firstname`, COALESCE(`b`.`surname`, 'NoSurname') AS `surname`, COALESCE(NULLIF(`b`.`smsalertnumber`,''), NULLIF(`b`.`phone`,''), NULLIF(`b`.`phonepro`,'')) AS `phone` "
                    ."FROM `borrowers` `b` "
                    ."LEFT JOIN `cen_smshistory` `c` "
                    ."ON `b`.`borrowernumber`=`c`.`sender_id` "
                    ."WHERE `c`.`sender_id` IS NOT NULL AND ((`b`.`smsalertnumber` IS NOT NULL AND `b`.`smsalertnumber` != '') OR (`b`.`phone` IS NOT NULL AND `b`.`phone` != '') OR (`b`.`phonepro` IS NOT NULL AND `b`.`phonepro` != '')) "
                    ."GROUP BY `c`.`sender_id`";
			
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 
            //$this->db->commit();
                		
	} catch(PDOException $ex) {
		
            Debugger::Log($ex->getMessage());
		
	}
        
        return $results;
        
    }
    
    public function borrowersAsString()
    {
        
        $borrowers = $this->getBorrowers();
        
        $string = "";
        foreach($borrowers AS $key => $val){
                $phone = $val['phone'];
               /* $phone = str_replace(" ", "", $val['phone']);
                $phone = str_replace("-", "", $phone);
                $phone = str_replace("(", "", $phone);
                $phone = str_replace(")", "", $phone);*/
                $string .= "'".$val['firstname']." ".$val['surname']." ".$phone."', ";
        }
        
        $string = substr($string, 0, -2);
        
        return $string;
        
    }
    
    public function staffAsString()
    {
        
        $staff = $this->getStaff();
        
        $string = "";
        foreach($staff AS $key => $val){
            $phone = $val['phone'];
                /*$phone = str_replace(" ", "", $val['phone']);
                $phone = str_replace("-", "", $phone);
                $phone = str_replace("(", "", $phone);
                $phone = str_replace(")", "", $phone);*/
                $string .= "'".$val['firstname']." ".$val['surname']." ".$phone."', ";
        }
        
        $string = substr($string, 0, -2);
        
        return $string;
        
    }
    
    public function getBorrowersAsAssociativeArray()
    {
        
        try {
		
            //$this->db->beginTransaction();
        
            $query = "SELECT COALESCE(`firstname`, 'NoName') AS `firstname`, COALESCE(`surname`, 'NoSurname') AS `surname`, COALESCE(NULLIF(`smsalertnumber`,''), NULLIF(`phone`,''), NULLIF(`phonepro`,'')) AS `phone` "
                    ."FROM `borrowers` "
                    ."WHERE (`smsalertnumber` IS NOT NULL AND `smsalertnumber` != '') OR (`phone` IS NOT NULL AND `phone` != '') OR (`phonepro` IS NOT NULL AND `phonepro` != '')";
			
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 
            //$this->db->commit();
                		
	} catch(PDOException $ex) {
		
            Debugger::Log($ex->getMessage());
		
	}
        
        $array = array();
        foreach($results as $val){
            
            $phone = str_replace(" ", "", $val['phone']);
            $phone = str_replace("-", "", $phone);
            $phone = str_replace("(", "", $phone);
            $phone = str_replace(")", "", $phone);
            
            $phone = substr($phone, -9);
            $array[$phone] = array('firstname'=>$val['firstname'], 'surname'=>$val['surname']);
        }
        
        return $array;
        
    }
    
    public function getBorrowerAsArray($p_string)
    {
        try {
		
            //$this->db->beginTransaction();

            $query = "SELECT COALESCE(`firstname`, 'NoName') AS `firstname`, COALESCE(`surname`, 'NoSurname') AS `surname`, COALESCE(NULLIF(`smsalertnumber`,''), NULLIF(`phone`,''), NULLIF(`phonepro`,'')) AS `phone` "
                    ."FROM `borrowers` "
                    ."WHERE ((`smsalertnumber` IS NOT NULL AND `smsalertnumber` != '') OR (`phone` IS NOT NULL AND `phone` != '') OR (`phonepro` IS NOT NULL AND `phonepro` != '') ) AND ( (`firstname` LIKE :p_string) OR (`surname` LIKE :p_string) OR (`phone` LIKE :p_string) OR (`phonepro` LIKE :p_string) OR (`smsalertnumber` LIKE :p_string) ) "
                    ."LIMIT 10";
			
            $stmt = $this->db->prepare($query);
            $stmt->execute(array(':p_string'=> '%'.$p_string.'%'));

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 
            //$this->db->commit();
                		
	} catch(PDOException $ex) {
		
            Debugger::Log($ex->getMessage());
		
	}

        $array = array();
        $i = 0;
        foreach($results as $val){
            $i++;
            $phone = str_replace(" ", "", $val['phone']);
            $phone = str_replace("-", "", $phone);
            $phone = str_replace("(", "", $phone);
            $phone = str_replace(")", "", $phone);
            
            $phone = substr($phone, -9);
            array_push($array, array('id'=>$phone, 'name'=>$val['firstname'].' '.$val['surname'].' '.$phone));
        }

        return $array;
        
    }
    
    public function getMessage($id)
    {
        
         try {
		
            //$this->db->beginTransaction();
        
            $query = "SELECT `c`.`message`, `c`.`status`, CONCAT(NULLIF(`b`.`firstname`, ''), ' ', NULLIF(`b`.`surname`, '')) as `solver`, `c`.`type` "
                    ."FROM `cen_smshistory` `c` "
                    ."LEFT JOIN `borrowers` `b` "
                    ."ON `b`.`borrowernumber`=`c`.`solver_id` "
                    ."WHERE `sms_id`=:id";
			
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 
            //$this->db->commit();
                		
	} catch(PDOException $ex) {
		
            Debugger::Log($ex->getMessage());
		
	}
        
        return $results;
        
    }
    
    public function getBorrowerByID($id)
    {
        
         try {
		
            //$this->db->beginTransaction();
        
            $query = "SELECT COALESCE(`firstname`, 'NoName') AS `firstname`, COALESCE(`surname`, 'NoSurname') AS `surname` "
                    ."FROM `borrowers` "
                    ."WHERE `borrowernumber`=:id";
			
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 
            //$this->db->commit();
                		
	} catch(PDOException $ex) {
		
            Debugger::Log($ex->getMessage());
		
	}
        
        $result = $results[0]['firstname']." ".$results[0]['surname'];
        
        return $result;
        
    }
    
    private function getStaffIDByPhone($phone)
    {
        
         try {
		
            //$this->db->beginTransaction();
        
            $query = "SELECT `borrowernumber`"
                    ."FROM `borrowers` "
                    ."WHERE `smsalertnumber`=:phone OR `phone`=:phone OR `phonepro`=:phone";
			
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 
            //$this->db->commit();
                		
	} catch(PDOException $ex) {
		
            Debugger::Log($ex->getMessage());
		
	}
        
        $result = $results[0]['borrowernumber'];
        
        return $result;
        
    }
    
    /**
     * 
     * @param type $type
     * @param type $from
     * @param type $offset
     * @param type $receiver
     * @param int $sender
     * @param type $staff
     * @param type $status
     * @return array
     */
    public function getMessagesByLimit($type, $from, $offset, $receiver="", $sender="", $staff="", $status="")
    {
        
        $messages = array();
        $count = 0;
        $senderNumber = "";
        $receiverNumber = "";
        $stuffID = "";
        $query = "";
        
        if($sender !== ""){
            $senderNumberE = explode(' ', $sender, 3);
            $senderNumber = trim($senderNumberE[2]);
           /* echo "<pre>";
            echo("SenderNumber: ". $senderNumber);
            echo "</pre>";*/
        }
        
        if($receiver !== ""){
            $receiverNumberE = explode(' ', $receiver, 3);
            $receiverNumber = trim($receiverNumberE[2]); 
           /* echo "<pre>";
            echo("ReceiverNumber: ". $receiverNumber."|Receiver: ".$receiver);
            echo "</pre>";*/
        }
         
        if($staff !== ""){
            $staffNumberE = explode(' ', $staff, 3);
            $staffNumber = trim($staffNumberE[2]);
            $staffID = $this->getStaffIDByPhone($staffNumber);
           /* echo "<pre>";
            echo("StuffID: ".$staffID);
            echo "</pre>";*/
        }
  
        
        //return true;

        
        if($type == "incoming"){
            $senderWhere = ($sender != "") ?  "AND `phone` LIKE '%$senderNumber'" : "";
            $statusWhere = ($status != "") ?  "AND `status`=$status" : "";
            $query = "SELECT * FROM `cen_smshistory` WHERE type=:type $senderWhere $statusWhere ORDER BY `time` DESC LIMIT $from, $offset";
            $queryCount = "SELECT COUNT(*) as `count` FROM `cen_smshistory` WHERE type=:type $senderWhere $statusWhere";
            //print_r($query);
        }
        else{ // outgoing
            $receiverWhere = ($receiver != "") ?  "AND `phone` LIKE '%$receiverNumber'" : "";
            $staffWhere = ($staff != "") ?  "AND `sender_id`=$staffID" : "";
            $statusWhere = ($status != "") ?  "AND `status`=$status" : "";
            $query = "SELECT * FROM `cen_smshistory` WHERE type=:type $receiverWhere $staffWhere $statusWhere ORDER BY `time` DESC LIMIT $from, $offset";
            $queryCount = "SELECT COUNT(*) as `count` FROM `cen_smshistory` WHERE type=:type $receiverWhere $staffWhere $statusWhere";
             //print_r($query);
        }

        try {

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':type', $type, PDO::PARAM_STR);
            //$stmt->bindValue(':from', $from, PDO::PARAM_INT);
            //$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $messages = $results;

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());

        }
        $count = 0;
         try {

            $stmt2 = $this->db->prepare($queryCount);
            $stmt2->bindValue(':type', $type, PDO::PARAM_STR);
            //$stmt->bindValue(':from', $from, PDO::PARAM_INT);
            //$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt2->execute();

            $resultsCount = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            $count = $resultsCount[0]['count'];

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());

        }
        
        $arrays = array();
        
        foreach($messages as $message){
        
            $datetime = new DateTime($message['time']);
            $time = $datetime->format("d.m. H:i");

            $borrowers = $this->getBorrowersAsAssociativeArray();

            $phone = str_replace(" ", "", $message['phone']);
            $phone = str_replace("-", "", $phone);
            $phone = str_replace("(", "", $phone);
            $phone = str_replace(")", "", $phone);

            $phone = substr($phone, -9);
            $nameAndSurname = $borrowers[$phone]['firstname']." ".$borrowers[$phone]['surname'];

            $sender = "";
            if(is_null($message['sender_id'])){
                $sender = 0;
            }
            else{
                $sender = $this->getBorrowerByID($message['sender_id']);
            }
            
            $array = array('receiver' => $nameAndSurname, 
                           'datetime' => $time,
                            'sms_id' => $message['sms_id'],
                            'status' => $message['status'],
                            'sender' => $sender,
                            'count'=>$count,
                            //'query'=>$query,
                    );
            
            array_push($arrays, $array);
        
        }
        /*
        echo "<pre>";
        print_r($arrays);
        echo "</pre>";
        
        echo "<pre>";
        print_r($messages);
        echo "</pre>";
        */
        return $arrays;
        //print_r($arrays);
       // print_r($query);
        
        
    }
    
    public function solveMessage($smsID, $status, $solverID)
    {
        
        try {

            $query = "UPDATE `cen_smshistory` SET `solver_id`=:solver_id, `status`=:status WHERE `sms_id`=:sms_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':solver_id', $solverID, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status, PDO::PARAM_INT);
            $stmt->bindValue(':sms_id', $smsID, PDO::PARAM_INT);
            $stmt->execute();

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());

        }
        
    }
        
}
