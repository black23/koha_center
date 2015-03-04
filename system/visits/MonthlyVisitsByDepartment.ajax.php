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
	
	$department = htmlspecialchars($_POST["department"]);
	$from = $_POST["from"];
    $to = $_POST["to"];
    
    $allDepartments = Array();
    
    if ($department == "allDepartments") {
        
            require_once __DIR__."/DatabaseHandler.class.php";

            $databaseHandler = new DatabaseHandler($db);
            $branches = $databaseHandler->getBranches();
            
            $allDepartmentsStr = "'";
            foreach ($branches as $arr) {
                 array_push($allDepartments,  $arr['branchcode']);
            }

        }
        else {
            array_push($allDepartments,  $department);
        }
        
        $implodedDepartments = "'".implode('\',\'', $allDepartments)."'";
    
	try {
		
		$db->beginTransaction();	
        
        $query = "SELECT "
                ."(SELECT COUNT(DISTINCT `b`.`borrowernumber`) AS `count` "
				."FROM `statistics` `s` "
                ."JOIN `accountlines` `a` "
                ."ON `a`.`accountlines_id` = `s`.`other` "
                ."LEFT OUTER JOIN `borrowers` `b` "
                ."ON `b`.`borrowernumber` = `a`.`borrowernumber` "
				."WHERE `s`.`branch` IN ($implodedDepartments) AND DATE(`s`.`datetime`) BETWEEN '$from' AND '$to' AND `s`.`type` = 'payment' AND `a`.`accounttype` IN ('".implode('\',\'', $cat_registrations)."') ) AS `registrations`, "
                ."(SELECT COUNT(DISTINCT `b`.`borrowernumber`) AS `count` "
				."FROM `statistics` `s` "
                ."JOIN `accountlines` `a` "
                ."ON `a`.`accountlines_id` = `s`.`other` "
                ."LEFT OUTER JOIN `borrowers` `b` "
                ."ON `b`.`borrowernumber` = `a`.`borrowernumber` "
				."WHERE `s`.`branch` IN ($implodedDepartments) AND DATE(`s`.`datetime`) BETWEEN '$from' AND '$to' AND `s`.`type` = 'payment' AND `a`.`accounttype` IN ('".implode('\',\'', $cat_allPayments)."') ) AS `allPayments`, "
			    ."(SELECT COUNT(DISTINCT `b`.`borrowernumber`) AS `count` "
				."FROM `statistics` `s` "
                ."JOIN `accountlines` `a` "
                ."ON `a`.`accountlines_id` = `s`.`other` "
                ."LEFT OUTER JOIN `borrowers` `b` "
                ."ON `b`.`borrowernumber` = `a`.`borrowernumber` "
				."WHERE `s`.`branch` IN ($implodedDepartments) AND DATE(`s`.`datetime`) BETWEEN '$from' AND '$to' AND `s`.`type` = 'payment' AND `a`.`accounttype` IN ('".implode('\',\'', $cat_reservations)."') ) AS `reservations`, "
                ."(SELECT COUNT(DISTINCT `b`.`borrowernumber`) AS `count` "
				."FROM `statistics` `s` "
                ."LEFT JOIN `borrowers` `b` "
                ."ON `b`.`borrowernumber` = `s`.`borrowernumber` "
				."WHERE `s`.`branch` IN ($implodedDepartments) AND DATE(`s`.`datetime`) BETWEEN '$from' AND '$to' AND `s`.`type` = 'issue' ) AS `borrows`, "
                ."(SELECT COUNT(DISTINCT `b`.`borrowernumber`) AS `count` "
				."FROM `statistics` `s` "
                ."LEFT JOIN `borrowers` `b` "
                ."ON `b`.`borrowernumber` = `s`.`borrowernumber` "
				."WHERE `s`.`branch` IN ($implodedDepartments) AND DATE(`s`.`datetime`) BETWEEN '$from' AND '$to' AND `s`.`type` = 'renew' ) AS `prolongs`, "
                ."(SELECT COUNT(DISTINCT `b`.`borrowernumber`) AS `count` "
				."FROM `statistics` `s` "
                ."LEFT JOIN `borrowers` `b` "
                ."ON `b`.`borrowernumber` = `s`.`borrowernumber` "
				."WHERE `s`.`branch` IN ($implodedDepartments) AND DATE(`s`.`datetime`) BETWEEN '$from' AND '$to' AND `s`.`type` = 'return' ) AS `returns`, "
                ."(SELECT COUNT(DISTINCT `b`.`borrowernumber`) AS `count` "
				."FROM `statistics` `s` "
                ."LEFT JOIN `borrowers` `b` "
                ."ON `b`.`borrowernumber` = `s`.`borrowernumber` "
				."WHERE `s`.`branch` IN ($implodedDepartments) AND DATE(`s`.`datetime`) BETWEEN '$from' AND '$to' AND `s`.`type` IN ('return', 'renew', 'issue', 'payment') ) AS `total`, "
                ."(SELECT COUNT(DISTINCT `b`.`borrowernumber`) AS `count` "
				."FROM `statistics` `s` "
                ."LEFT JOIN `borrowers` `b` "
                ."ON `b`.`borrowernumber` = `s`.`borrowernumber` "
				."WHERE `s`.`branch` IS NULL AND DATE(`s`.`datetime`) BETWEEN '$from' AND '$to' AND `s`.`type` = 'renew' ) AS `virtualProlongs`, "
                 ."(SELECT COUNT(DISTINCT `b`.`borrowernumber`) AS `count` "
				."FROM `statistics` `s` "
                ."JOIN `accountlines` `a` "
                ."ON `a`.`accountlines_id` = `s`.`other` "
                ."LEFT OUTER JOIN `borrowers` `b` "
                ."ON `b`.`borrowernumber` = `a`.`borrowernumber` "
				."WHERE `s`.`branch` IS NULL AND DATE(`s`.`datetime`) BETWEEN '$from' AND '$to' AND `s`.`type` = 'payment' AND `a`.`accounttype` IN ('".implode('\',\'', $cat_reservations)."') ) AS `virtualReservations` ";
        $stmt = $db->prepare($query);
		$stmt->execute();	

		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$db->commit();
		
	} catch(PDOException $ex) {
		
		Debugger::log($ex->getMessage());

    }
    
	$data = [
		"total" 		=> (int) $results[0]['total'],
        "registrations" => (int) $results[0]['registrations'],
        "borrows" 		=> (int) $results[0]['borrows'],
        "returns" 		=> (int) $results[0]['returns'],
        "reservations"  => (int) $results[0]['reservations'],
        "paids" 		=> (int) $results[0]['allPayments'],
        "prolongs" 		=> (int) $results[0]['prolongs'],
        "virtualProlongs" 		=> (int) $results[0]['virtualProlongs'],
        "virtualReservations" 		=> (int) $results[0]['virtualReservations']
	];
	
	echo json_encode($data);
   


	
