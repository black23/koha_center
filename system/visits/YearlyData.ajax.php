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

	require_once __DIR__."/../config.php";
    
    use Tracy\Debugger;

	$department = $_GET["department"];
	$from = $_GET["from"];
    $to = $_GET["to"];
	
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
    
    $startYear = (int) substr($from, 0, 4);
    $startMonth = (int) substr($from, 5, 2);
    
    $endYear = (int) substr($to, 0, 4);
    $endMonth = (int) substr($to, 5, 2);
    
    $no_months = (12 - $startMonth + 1 + $endMonth) + ( ($endYear - $startYear - 1) * 12 );
    
    $tempMonth = 1;
    $tempYear = $startYear;
    
    $query = "SELECT ";
    
    function createQuery(&$query, $departments, $month, $year) {
        $tempFrom = $year."-".$month."-01";
        $tempTo = date("Y-m-t", strtotime($tempFrom));
        $query .= "(SELECT round(Sum(ABS(`s`.`value`)),0) as `value`, `a`.`accounttype` "
				."FROM `statistics` `s` "
				."JOIN `accountlines` `a` "
				."ON `a`.`accountlines_id` = `s`.`other` "
                ."LEFT OUTER JOIN `branches` `b` "
                ."ON `s`.`branch` = `b`.`branchcode` "
                ."LEFT OUTER JOIN `accounttypes` `t` "
                ."ON `t`.`code` = `a`.`accounttype` "
				."WHERE `s`.`branch` IN ('".implode('\',\'', $departments)."') AND DATE(`s`.`datetime`) BETWEEN '$tempFrom' AND '$tempTo' AND `s`.`type` = 'payment' "
                ."GROUP BY `s`.`branch`, `a`.`accounttype`) AS `age_$year-$month`, ";
    }
    
    for($i = 1; $i <= $no_months; $i++) {
        if($i !== 1){
            if (($i % 12) == 1) {
                $tempYear++;
                $tempMonth = 1;
            }
        }
        $tempMonth = $i;
        if($i < 10){
            $tempMonth = "0".$tempMonth;
        }
        
        createQuery($query, $myArray, $tempMonth, $tempYear);
    }
    
    // works regardless of statements emulation
    //$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    
    // works not with the following set to 0. You can comment this line as 1 is default
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    
	try {
		
		$db->beginTransaction();
        $query = substr($query, 0, -2);
		$stmt = $db->prepare($query);
		$stmt->execute();

		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$db->commit();
		
	} catch(PDOException $ex) {
		
		Debugger::log($ex->getMessage());
		
	}
       
    //varDump($results);
    echo $query;
    $index = $startMonth - 1;
    
    /*
	$jan = 0;
	$feb = 0;
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

	*/
