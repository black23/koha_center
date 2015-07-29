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

	if (!headers_sent()) {
		header('Content-Type: text/html; charset=utf-8');
	}
	
	require_once "../system/config.php";
        
        use Tracy\Debugger;
        
        Debugger::enable(Debugger::DEVELOPMENT, __DIR__ . '/../log');
        Debugger::$strictMode = TRUE;
	
        $termList = array();
        try {
            $query = <<<EOT
(
SELECT ExtractValue(`auth_header`.`marcxml`,'//datafield[@tag="150"]/subfield[@code="a"]') AS `term` FROM `auth_header`
            WHERE `authtypecode` = 'TOPIC_TERM' 
	AND ExtractValue(`auth_header`.`marcxml`,'//datafield[@tag="150"]/subfield[@code="a"]') LIKE :q
     ORDER BY ExtractValue(`auth_header`.`marcxml`,'//datafield[@tag="150"]/subfield[@code="a"]') = :original_q DESC, ExtractValue(`auth_header`.`marcxml`,'//datafield[@tag="150"]/subfield[@code="a"]') LIKE :q DESC
     LIMIT 20
)
UNION
(
SELECT ExtractValue(`auth_header`.`marcxml`,'//datafield[@tag="550"]/subfield[@code="a"]') AS `term` FROM `auth_header`
    WHERE `authtypecode` = 'TOPIC_TERM' 
	AND ExtractValue(`auth_header`.`marcxml`,'//datafield[@tag="550"]/subfield[@code="a"]') LIKE :q 
    ORDER BY ExtractValue(`auth_header`.`marcxml`,'//datafield[@tag="550"]/subfield[@code="a"]') = :original_q DESC, ExtractValue(`auth_header`.`marcxml`,'//datafield[@tag="550"]/subfield[@code="a"]') LIKE :q DESC
    LIMIT 10
)
EOT;

            $stmt = $db->prepare($query);
            $stmt->bindValue(':authtypecode', 'TOPIC_TERM', PDO::PARAM_STR);
	    $stmt->bindValue(':original_q', $_GET["q"], PDO::PARAM_STR);
            $stmt->bindValue(':q', '%'.$_GET["q"].'%', PDO::PARAM_STR);
            
            $stmt->execute();

            $array = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {
            echo $ex->getMessage();
        }
        
        if (in_array(strtoupper($_GET['q']), array('A', 'AN', 'AND')))
            array_push($termList, array('id' => 'AND', 'name' => 'AND'));
        
        if (in_array(strtoupper($_GET['q']), array('O', 'OR')))
            array_push($termList, array('id' => 'OR', 'name' => 'OR'));
        
        if (in_array(strtoupper($_GET['q']), array('N', 'NO', 'NOT')))
            array_push($termList, array('id' => 'AND NOT', 'name' => 'AND NOT'));
        
        
        foreach ($array as $key => $value) {
            array_push($termList, array('id' => $value['term'], 'name' => $value['term']));
        }
        
    	
        
        echo json_encode($termList, JSON_UNESCAPED_UNICODE);	
