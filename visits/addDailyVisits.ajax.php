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

    require_once "../system/config.php";
    
    use Tracy\Debugger;
	
    $date = $_POST["date"]." 00:00:00";
    $internet = $_POST["internet"];
    $studyRoom = $_POST["studyRoom"];
    $branchCode = $_POST["branchCode"];
    
    $error = 0;
    
	try {
		
            $db->beginTransaction();
        
            $query = "INSERT INTO `cen_circulations` (`internet`, `study_room`, `datetime`, `branchcode`) VALUES (:internet, :study_room, :datetime, :branchcode)";
			
            $stmt = $db->prepare($query);
            $stmt->bindValue(':internet', $internet, PDO::PARAM_INT);
            $stmt->bindValue(':study_room', $studyRoom, PDO::PARAM_STR);
            $stmt->bindValue(':datetime', $date, PDO::PARAM_STR);
            $stmt->bindValue(':branchcode', $branchCode, PDO::PARAM_STR);
            $stmt->execute();

            $db->commit();
                		
	} catch(PDOException $ex) {
		
            Debugger::log($ex->getMessage());
            $error = 1;
		
	}
        
        if($error != 1){
            $result = "ok";
        }
        else{
            $result = "error";
        }

	$data = [
		"result"             => $result,
	];
	
	echo json_encode($data);

	
