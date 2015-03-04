<?php

    session_start();

    require_once "config.php";
    
    use Tracy\Debugger;

	$path = "$catalogue/cgi-bin/koha/ilsdi.pl?service=AuthenticatePatron&username=".$_POST['username']."&password=".$_POST['password'];

	$data = file_get_contents($path) or Debugger::log("[file_get_contents] ".$data);

	$xml = new SimpleXMLElement($data) or Debugger::log("[SimpleXMLElement] ".$data);

	$result = $xml->xpath('/AuthenticatePatron/id') or Debugger::log("[xpath] ".$data);
	
	while(list( , $node) = each($result)) {
		//echo (int) $node;
        $userID = (int) $node;
	}

    if (!isset($userID)){
        $_SESSION['sign_in_error'] = "1";
        header("Location: login.php");
    }
    else{
        
        if((int) $userID > 0){
        
            $_SESSION['userID'] = $userID;

            function wrongPermissions($userID, $db){
                
                try {
		
                    $db->beginTransaction();

                    $query = "SELECT `flags` "
                            ."FROM `borrowers` "
                            ."WHERE `borrowernumber` = :userID";		

                    $stmt = $db->prepare($query);
                    $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
                    $stmt->execute();

                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $db->commit();

                } catch(PDOException $ex) {

                    Debugger::log($ex->getMessage());

                }
                
                $super = 1; //konstanta pro superlibrarian flag
                $staff = 4; // konstanta  pro staffacces flag
                
                $userFlag = $results[0]['flags'];
                
                if(($userFlag & $super) OR ($userFlag & $staff)){ 

                        return false; //right permissions

                }
                else {
                    return true; // wrong permissions
                }
            }

            if(wrongPermissions($userID, $db)){
                $_SESSION['sign_in_error'] = "2";
                header("Location: login.php");
            }
            else{
                header("Location: ../index.php");
            }

        }
        else{
            $_SESSION['sign_in_error'] = "1";
            header("Location: login.php");
        }
        
    }
    
    