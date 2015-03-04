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
	
    $date = $_POST["date"];
    
	try {
		
		$db->beginTransaction();
        
        $query = "SELECT `s`.`datetime`, `b`.`borrowernumber`, `b`.`firstname`, `b`.`surname`, count(`b`.`borrowernumber`) AS `count` "
				."FROM `statistics` `s` "
                ."LEFT JOIN `borrowers` `b` "
                ."ON `b`.`borrowernumber` = `s`.`borrowernumber` "
				."WHERE `s`.`branch` IS NULL AND DATE(`s`.`datetime`) = 'date' AND `s`.`type` = 'renew' "
                ."GROUP BY `b`.`borrowernumber` "
                ."ORDER BY `s`.`datetime` ASC";
			
		$stmt = $db->prepare($query);
		$stmt->execute();

		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 
		$db->commit();
                		
	} catch(PDOException $ex) {
		
		Debugger::log($ex->getMessage());
		
	}
    
    $newFrom = new DateTime($date);
    $day = $newFrom->format('d.m');
    
    
    foreach($results as $key => $val){
        $datetime = new DateTime($results[$key]["datetime"]);
        $time = $datetime->format("H:i:s");
        $results[$key]["datetime"] = $time;
    }

	$data = [
		"day"             => $day,
		"rows"            => $results,
        "number_of_users" => count($results),
	];
	
	echo json_encode($data);

	
