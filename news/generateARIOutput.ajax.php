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
    
    header('Content-type: text/html;charset=UTF-8');

	require_once "../system/config.php";
	
	$from = $_GET["from"];
        $to = $_GET["to"];
        $branches = $_GET["branches"];
        $types = $_GET["types"];
        $ariNumber = $_GET["ariNumber"];
        $zadatel = $_GET["zadatel"];
        $zpracovatel = $_GET["zpracovatel"];
        $terms = $_GET["terms"];
        $marcArrays = $_GET["marcArrays"];
        
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
       ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="245"]/subfield[@code="b"]') AS `subtitle`,
       ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="300"]/subfield[@code="a"]') AS `range`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="260"]/subfield[@code="a"]') AS `nakladatel`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="260"]/subfield[@code="b"]') AS `mistoVydani`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="260"]/subfield[@code="c"]') AS `rokVydani`,
       ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="020"]') AS `isbn`,
       ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="952"]/subfield[@code="o"]') AS `signature`,
       ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="506"]/subfield[@code="a"]') AS `availability`
FROM `items` 
LEFT JOIN `biblioitems` 
ON `items`.`biblioitemnumber` = `biblioitems`.`biblioitemnumber` 
LEFT JOIN `biblio` 
ON `biblioitems`.`biblionumber` = `biblio`.`biblionumber` 
WHERE DATE(`items`.`dateaccessioned`) BETWEEN '$from' AND '$to'
EOT;

$query .= <<<EOT

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
<b>ARI č.</b>:             \t\t\t<b>$ariNumber</b><br>\n               	
<b>Název</b>:              \t\t\t<b>".str_replace(',', ' ', $terms)."</b><br>\n  	
<b>Počet záznamů</b>:      \t\t\t<b>$count</b><br>\n
<b>Žadatel</b>:            \t\t\t<b>$zadatel</b><br>\n
<b>Zpracovatel</b>:        \t\t\t$zpracovatel<br>\n
<b>Zpracováno</b>:         \t\t\t<b>".date("d. m. Y", strtotime(date("Y-m-d")))."</b><br>\n	
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
                ."".$val['title']." ".$val['subtitle']."$authorPart"."<br>\n"
                ."Jazyk: <br>\n"
                ."In: <br>\n"
                ."$annotationPart<br>\n"
                ."Dostupnost: $availability<br>\n"
            ."";
        }
              
        echo $html;  
/*
       file_put_contents('output.txt', "\xEF\xBB\xBF".$html);
       
       header("Location: output.txt");*/
