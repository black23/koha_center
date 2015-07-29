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
        $ariNumber = isset($_GET["ariNumber"]) ? $_GET['ariNumber'] : '';
        $zadatel = isset($_GET["zadatel"]) ? $_GET['zadatel'] : '';
        $zpracovatel = isset($_GET["zpracovatel"]) ? $_GET['zpracovatel'] : '';
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
	`biblio`.`biblionumber`,
       ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="520"]/subfield[@code="a"]') AS `annotation`,
       ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="245"]/subfield[@code="b"]') AS `subtitle`,
       ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="300"]/subfield[@code="a"]') AS `range`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="260"]/subfield[@code="a"]') AS `nakladatel`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="260"]/subfield[@code="b"]') AS `mistoVydani`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="260"]/subfield[@code="c"]') AS `rokVydani`,
       COALESCE(ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="020"]/subfield[@code="a"]'), 
                ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="264"]/subfield[@code="a"]'),
                ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="260"]/subfield[@code="a"]'),
                ''
                ) AS `isbn`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="600"]/subfield[@code="a"]') AS `pole600`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="610"]/subfield[@code="a"]') AS `pole610`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="611"]/subfield[@code="a"]') AS `pole611`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="630"]/subfield[@code="a"]') AS `pole630`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="648"]/subfield[@code="a"]') AS `pole648`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="651"]/subfield[@code="a"]') AS `pole651`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="655"]/subfield[@code="a"]') AS `pole655`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="650"]/subfield[@code="a"]') AS `pole650`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="653"]/subfield[@code="a"]') AS `pole653`,
       ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="952"]/subfield[@code="o"]') AS `signature`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="041"]/subfield[@code="a"]') AS `lang`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="773"]/subfield[@code="9"]') AS `zdrojPart1`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="773"]/subfield[@code="g"]') AS `zdrojPart2`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="773"]/subfield[@code="t"]') AS `zdrojPart3`,
        ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="773"]/subfield[@code="x"]') AS `zdrojPart4`,
       ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="506"]/subfield[@code="a"]') AS `availability`
FROM `items` 
LEFT JOIN `biblioitems` 
ON `items`.`biblioitemnumber` = `biblioitems`.`biblioitemnumber` 
LEFT JOIN `biblio` 
ON `biblioitems`.`biblionumber` = `biblio`.`biblionumber` 
 WHERE ( 
EOT;
$termsString = $terms;
$terms = explode(",", $terms);
$i = 0;
$marcArrays = explode(",", $marcArrays);
$countMarcArrays = count($marcArrays);
        
foreach ($terms as $term) {
    $term = trim($term);
    $i++;
   
    if (($i % 2) == 0) { // sude - operator
        $query .= " $term ";
    } else { // liche - term
        $query .= "(";
        
        $j = 0;
        foreach ($marcArrays as $marcArray) {
            $j++;
            $marcArray = str_replace("'", "", trim($marcArray));
            if ($j === ($countMarcArrays)) {
                $query .= <<<EOT
                    ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="$marcArray"]/subfield[@code="a"]') LIKE '%$term%' 
EOT;
            } else {
                $query .= <<<EOT
                    ExtractValue(`biblioitems`.`marcxml`,'//datafield[@tag="$marcArray"]/subfield[@code="a"]') LIKE '%$term%' OR 
EOT;
            }
        }
            
        $query .= ")";
    }
}

$query .= <<<EOT
 ) AND DATE(`biblio`.`datecreated`) BETWEEN '$from' AND '$to' 
AND `items`.`itype` IN ($types) 
AND `items`.`homebranch` IN ($branches) 
GROUP BY `biblio`.`title`
EOT;
			
		$stmt = $db->prepare($query);

                $stmt->execute();

		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                		
	} catch(PDOException $ex) {
		echo $query;
		//error_log($ex->getMessage(), 3, "../log/error.log") or logConsole("Error: ", $ex->getMessage());
		echo $ex->getMessage();
                
	}
        
        $newFrom = new DateTime($from);
        $dayFrom = $newFrom->format('d.m');

        $newTo = new DateTime($to);
        $dayTo = $newTo->format('d.m');
        
        $count = count($results);
        
$html = "
<b>ARI č.</b>:             \t\t\t<b>$ariNumber</b><br>\n               	
<b>Název</b>:              \t\t\t<b>".str_replace(',', ' ', $termsString)."</b><br>\n  	
<b>Počet záznamů</b>:      \t\t\t<b>$count</b><br>\n
<b>Žadatel</b>:            \t\t\t<b>$zadatel</b><br>\n
<b>Zpracovatel</b>:        \t\t\t$zpracovatel<br>\n
<b>Zpracováno</b>:         \t\t\t<b>".date("d. m. Y", strtotime(date("Y-m-d")))."</b><br>\n	
Za období:		\t\t\t".date("d. m. Y", strtotime($from))." - ".date("d. m. Y", strtotime($to))."<br>\n
        <br>\n";
	
	$order = 0;

        foreach($results as $key => $val){
            ++$order;
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
                $annotationPart = tokenTruncate($val['annotation'], 500);
                if(strlen($val['annotation']) > 500){
                    $annotationPart .= " [<em>Více v našem katalogu</em>]";
                }
            }
            
            $lang = trim($val['lang']);
            $range = trim($val['range']);
            $signature = trim($val['signature']);
            $isbn = trim($val['isbn']);
            
            $nakladatel = trim($val['nakladatel']);
            $mistoVydani = trim($val['mistoVydani']);
            $rokVydani = trim($val['rokVydani']);
            
            $zdrojPart1 = trim($val['zdrojPart1']);
            $zdrojPart2 = trim($val['zdrojPart2']);
            $zdrojPart3 = trim($val['zdrojPart3']);
            $zdrojPart4 = trim($val['zdrojPart4']);
                    
            $pole600 = trim($val['pole600']);
            $pole610 = trim($val['pole610']);
            $pole611 = trim($val['pole611']);
            $pole630 = trim($val['pole630']);
            $pole648 = trim($val['pole648']);
            $pole651 = trim($val['pole651']);
            $pole655 = trim($val['pole655']);
            $pole650 = trim($val['pole650']);
            $pole653 = trim($val['pole653']);
            
            $availability = trim($val['availability']);
            
        $html .= "[".$order."] <b>".$val['title']." ".$val['subtitle']."</b><br>\n";
	if ($authorPart)
	    $html .= "Autor:". $authorPart."<br>\n";

        if (! empty($lang))
            $html .= "Jazyk: $lang<br>\n";
        if ($isbn)
            $html .= "ISBN $isbn<br>\n";
        if ($pole600 || 
            $pole610 || 
            $pole611 || 
            $pole630 || 
            $pole648 || 
            $pole651 || 
            $pole655 || 
            $pole650 || 
            $pole653)
            $html .= "$pole600 $pole610  $pole611 $pole630 $pole648 $pole651 $pole655 $pole650 $pole653 <br>\n";
        
        if ($zdrojPart1 || $zdrojPart2 || $zdrojPart3 || $zdrojPart4){
            $html .= "In: $zdrojPart1 $zdrojPart2 $zdrojPart3 $zdrojPart4 $range";
            if ($range)
                $html .= '$range';
            $html = '<br>\n';
        }
        if ($nakladatel || $mistoVydani || $rokVydani)
            $html .= "$nakladatel $mistoVydani $rokVydani<br>\n";
        if ($annotationPart)
            $html .= "$annotationPart<br>\n";
        if ($availability)
            $html .= "Dostupnost: $availability<br>\n";
        if ($signature)
            $html .= "$signature<br>\n";
	if(isset($val['biblionumber']) && $val['biblionumber'] != '')
	    $html .= "ID: <a href='".$root."/../cgi-bin/koha/catalogue/detail.pl?biblionumber=".$val['biblionumber']."'>".$val['biblionumber']."</a><br>\n";
        $html .= "<br>\n"; 
        }

        echo $html;  
        
    //    echo $query;
/*
       file_put_contents('output.txt', "\xEF\xBB\xBF".$html);
       
       header("Location: output.txt");*/

