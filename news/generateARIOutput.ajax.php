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
    
    header('Content-type: text/plain;charset=UTF-8');

	require_once "../system/config.php";
	
	$from = $_GET["from"];
        $to = $_GET["to"];
        $branches = $_GET["branches"];
        $types = $_GET["types"];
        $ariNumber = $_GET["ariNumber"];
        $zadatel = $_GET["zadatel"];
        $zpracovatel = $_GET["zpracovatel"];
        
        function tokenTruncate($string, $your_desired_width) {
        $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
        $parts_count = count($parts);

        $length = 0;
        $last_part = 0;
        for (; $last_part < $parts_count; ++$last_part) {
          $length += strlen($parts[$last_part]);
          if ($length > $your_desired_width) { break; }
        }

        return implode(array_slice($parts, 0, $last_part));
      }
        
	try {

$query = <<<EOT
SELECT `biblio`.`author`,
       `biblio`.`title`,
       ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="520"]/subfield[@code="a"]') AS `annotation`,
       ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="245"]/subfield[@code="b"]') AS `subtitle` 
FROM `items` 
LEFT JOIN `biblioitems` 
ON `items`.`biblioitemnumber` = `biblioitems`.`biblioitemnumber` 
LEFT JOIN `biblio` 
ON `biblioitems`.`biblionumber` = `biblio`.`biblionumber` 
WHERE DATE(`items`.`dateaccessioned`) BETWEEN '$from' AND '$to' 
AND `items`.`itype` IN ($types) 
AND `items`.`homebranch` IN ($branches)
GROUP BY `biblio`.`title`
EOT;
			
		$stmt = $db->prepare($query);

                $stmt->execute();

		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                		
	} catch(PDOException $ex) {
		
		error_log($ex->getMessage(), 3, "../log/error.log") or logConsole("Error: ", $ex->getMessage());
		echo $ex->getMessage();
                
	}
        
        $newFrom = new DateTime($from);
        $dayFrom = $newFrom->format('d.m');

        $newTo = new DateTime($to);
        $dayTo = $newTo->format('d.m');
        
        $count = count($results);
        
$html = "
ARI č.:             $ariNumber               	
Název:              Toxikomanie, drogy, alkohol      	
Počet záznamů:      $count
Žadatel:            $zadatel
Zpracovatel:        $zpracovatel
Zpracováno:         ".date("d. m. Y", strtotime(date("Y-m-d")))."  	
        ";

        foreach($results as $key => $val){
            
            $val['title'] = str_replace("--", "", $val['title']);
            $val['title'] = str_replace("=", "", $val['title']);
            $val['title'] = str_replace("/", "", $val['title']);
            $val['title'] = str_replace("%", "", $val['title']);
            $val['title'] = trim($val['title']);
            $val['title'] = ucfirst($val['title']);
            
            $authorPart = "";
            $annotationPart = "";
            
            if($val['author'] != ""){
                $authorPart = " ".$val['author'];
            }
            
            if($val['annotation'] != ""){
                $annotationPart = "<p style='text-align: justify;' align='justify'>".tokenTruncate($val['annotation'], 500)."";
                if(strlen($val['annotation']) > 500){
                    $annotationPart .= " [<em>Více v našem katalogu</em>]";
                }
                $annotationPart .= "</p>";
            }
            
        $html .= ""
                ."".$val['title']." ".$val['subtitle']."$authorPart"."\n"
                . "\n"
                ."$annotationPart"
            ."";
        }
              echo $html;      

       file_put_contents('output.txt', "\xEF\xBB\xBF".$html);
       
       header("Location: output.txt");
