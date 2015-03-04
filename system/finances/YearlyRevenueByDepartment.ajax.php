<?php
# This file is part of Koha.
#
# Copyright (C) 2014  xkravec
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

	require_once __DIR__."/../config.php";
    
    use Tracy\Debugger;

	$department = $_POST["department"];
	$from = $_POST["from"];
    $to = $_POST["to"];
	
	$type = "payment";
	
    if ($department == "allDepartments") {
        
            require_once __DIR__."/DatabaseHandler.class.php";

            $databaseHandler = new DatabaseHandler($db);
            $branches = $databaseHandler->getBranches();
            $myArray = Array();
            
            $allDepartmentsStr = "'";
            foreach ($branches as $arr) {
                 array_push($myArray,  $arr['branchcode']);
            }

        }
        else {
            $myArray = Array();
            array_push($myArray,  $department);
        }
    
        $query = "SELECT round(Sum(ABS(`s`.`value`)),0) as `value`, `a`.`accounttype` "
				."FROM `statistics` `s` "
				."JOIN `accountlines` `a` "
				."ON `a`.`accountlines_id` = `s`.`other` "
                ."LEFT OUTER JOIN `branches` `b` "
                ."ON `s`.`branch` = `b`.`branchcode` "
                ."LEFT OUTER JOIN `accounttypes` `t` "
                ."ON `t`.`code` = `a`.`accounttype` "
				."WHERE `s`.`branch` IN ('".implode('\',\'', $myArray)."') AND DATE(`s`.`datetime`) BETWEEN :from AND :to AND `s`.`type` = :type "
                ."GROUP BY `s`.`branch`, `a`.`accounttype`";
        
	try {
		
		$db->beginTransaction();

		$stmt = $db->prepare($query);
		$stmt->bindValue(':from', str_replace(":", "", $from), PDO::PARAM_STR);
        $stmt->bindValue(':to', str_replace(":", "", $to), PDO::PARAM_STR);
		$stmt->bindValue(':type', $type, PDO::PARAM_STR);
		$stmt->execute();

		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 
		$db->commit();
		
	} catch(PDOException $ex) {
		
		Debugger::log($ex->getMessage());
		
	}
	
	$total = 0;
	$registrations = 0;
	$fines = 0;
	$prints = 0;
    $res = 0;
	$other = 0;
	
	foreach ($results as $arr) {
		
		if(in_array($arr['accounttype'], $cat_registrations))       { $registrations += $arr['value']; }
		elseif(in_array($arr['accounttype'], $cat_fines))           { $fines += $arr['value']; }
		elseif(in_array($arr['accounttype'], $cat_reservations))    { $res += $arr['value']; }
        elseif(in_array($arr['accounttype'], $cat_prints))          { $prints += $arr['value']; }
		elseif(in_array($arr['accounttype'], $cat_other))           { $other += $arr['value']; }
        else                                                        { Debugger::log("Account type not categorized: ". $arr['accounttype']); }
		
		$total += $arr['value'];
		
	}
	
	$data = [
		"total" 		=> $total,
		"registrations" => $registrations,
		"fines" 		=> $fines,
		"prints" 		=> $prints,
		"other" 		=> $other,
        "res"           => $res,
	];
	
	echo json_encode($data);

	
