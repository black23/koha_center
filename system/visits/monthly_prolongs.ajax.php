<?php
# This file is part of Koha.
#
# Copyright (C) 2014  MartinKravec
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

	require_once "../config.php";
    
    use Tracy\Debugger;
	
	$department = $_POST["department"];
	$from = $_POST["from"];
    $to = $_POST["to"];
    
    $myArray = Array();
    
    if ($department == "allDepartments") {
        
            require_once __DIR__."/DatabaseHandler.class.php";

            $databaseHandler = new DatabaseHandler($db);
            $branches = $databaseHandler->getBranches();
            
            $allDepartmentsStr = "'";
            foreach ($branches as $arr) {
                 array_push($myArray,  $arr['branchcode']);
            }

        }
        else {
            array_push($myArray,  $department);
        }
	
	try {
		
		$db->beginTransaction();
        
        $query = "SELECT `s`.`datetime`, `b`.`borrowernumber`, `b`.`firstname`, `b`.`surname`, count(`b`.`borrowernumber`) AS `count` "
				."FROM `statistics` `s` "
                ."LEFT JOIN `borrowers` `b` "
                ."ON `b`.`borrowernumber` = `s`.`borrowernumber` "
				."WHERE `s`.`branch` IN ('".implode('\',\'', $myArray)."') AND DATE(`s`.`datetime`) BETWEEN '$from' AND '$to' AND `s`.`type` = 'renew' "
                ."GROUP BY `b`.`borrowernumber` "
                ."ORDER BY `s`.`datetime` ASC";
			
		$stmt = $db->prepare($query);
		$stmt->execute();

		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 
		$db->commit();
                		
	} catch(PDOException $ex) {
		
		Debugger::log($ex->getMessage());
		
	}
    
    $newFrom = new DateTime($from);
    $dayFrom = $newFrom->format('d.m');
    
    $newTo = new DateTime($to);
    $dayTo = $newTo->format('d.m');
    
    foreach($results as $key => $val){
        $datetime = new DateTime($results[$key]["datetime"]);
        $time = $datetime->format("H:i:s");
        $results[$key]["datetime"] = $time;
    }

	$data = [
		"dayFrom"             => $dayFrom,
        "dayTo"             => $dayTo,
		"rows"            => $results,
        "number_of_users" => count($results),
	];
	
	echo json_encode($data);

	
