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

	require_once "../config.php";
	
	$department = $_POST["department"];
	$from = $_POST["from"];
    $to = $_POST["to"];
	
	$type = "payment";
	
	try {
		
		$db->beginTransaction();
        
        $query = "SELECT `a`.`timestamp`, `b`.`borrowernumber`, `b`.`firstname`, `b`.`surname`, round(Sum(ABS(`s`.`value`)),0) as `value` "
				."FROM `statistics` `s` "
                ."JOIN `accountlines` `a` "
                ."ON `a`.`accountlines_id` = `s`.`other` "
                ."LEFT OUTER JOIN `borrowers` `b` "
                ."ON `b`.`borrowernumber` = `a`.`borrowernumber` "
				."WHERE `s`.`branch` = :department AND DATE(`s`.`datetime`) BETWEEN :from AND :to AND `s`.`type` = :type AND `a`.`accounttype` IN ('".implode('\',\'', $cat_reservations)."') "	
                ."GROUP BY `b`.`borrowernumber` "
                ."ORDER BY `s`.`datetime` ASC";			
			
		$stmt = $db->prepare($query);
		$stmt->bindValue(':department', $department, PDO::PARAM_STR);
		$stmt->bindValue(':from', str_replace(":", "", $from), PDO::PARAM_STR);
        $stmt->bindValue(':to', str_replace(":", "", $to), PDO::PARAM_STR);
		$stmt->bindValue(':type', $type, PDO::PARAM_STR);
		$stmt->execute();

		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 
		$db->commit();
                		
	} catch(PDOException $ex) {
		
		error_log($ex->getMessage(), 3, "../../log/error.log") or logConsole("Error: ", $ex->getMessage());
		
	}
    
    $newFrom = new DateTime($from);
    $dayFrom = $newFrom->format('d.m');
    
    $newTo = new DateTime($to);
    $dayTo = $newTo->format('d.m');
    
    foreach($results as $key => $val){
        $datetime = new DateTime($results[$key]["timestamp"]);
        $time = $datetime->format("d.m.Y H:i:s");
        $results[$key]["timestamp"] = $time;
    }

	$data = [
		"dayFrom"             => $dayFrom,
        "dayTo"             => $dayTo,
		"rows"            => $results,
        "number_of_users" => count($results),
	];
	
	echo json_encode($data);

	
