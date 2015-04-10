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

    if (!headers_sent()) {
            header('Content-Type: text/html; charset=utf-8');
    }
    
    require __DIR__ . "/tracy/src/tracy.php";
    
    require __DIR__ . "/systemConfig.php";
    use Tracy\Debugger;

    /*************************************************************************/
    /************************ User configuration part ************************/
    /*************************************************************************/
    
        /* Select language. Set it also in login.php */
        $langShortcut = "cs"; // "cs" | "en"
        
        /* Display errors */
        displayErrors(true);
        //displayErrors(false);
        
        /* Categories */
        #### accounttypes table ####
        # A - Registrační poplatek
        # F - Upomínka
        # Res - Rezervace
        # rezer - Rezervace
        # tisk - Tisk
        # kopie - Kopie
        # K - 
        # L - Kopie
        # N - Nová karta
        # dovoz - Dovoz z Parníka
        # prode - Prodej ??? did not used
        #### accountlines table ####
		# M
        # rent - Rental ??? did not used
        # Rent - Rental ??? did not used
        # Copie - Copier Fees
        # posko
        # prode
        # dupli
        # obslu
        # ostat
        # kniho
        # plack
        # posto
        # inter
        # FU
        #### authorised_values table where category = "manual_inv" ####
        # 28	MANUAL_INV	Copier Fees	.25	NULL	NULL
        # 28    MANUAL_INV	kopie	1	NULL	 
        # 191	MANUAL_INV	duplikat_prukazu	20	NULL	 
        # 1332	MANUAL_INV	tisk	1	NULL	 
        # 1333	MANUAL_INV	internet	1	NULL	 
        # 1334	MANUAL_INV	poskozeni_kodu	10	NULL	 
        # 1335	MANUAL_INV	MVS	100	NULL	 
        # 1336	MANUAL_INV	poskozeni_dokumentu	1	NULL	 
        # 1337	MANUAL_INV	nahrada_dokumentu	1	NULL	 
        # 1338	MANUAL_INV	obsluha_bez_prukazu	5	NULL	 
        # 1342	MANUAL_INV	prodej_vyrazenych	1	NULL	 
        # 1345	MANUAL_INV	ostatni	1	NULL	 
        # 1346	MANUAL_INV	knihovni_zpracovani	1	NULL	 
        # 1347	MANUAL_INV	taska	48	NULL	 
        # 1348	MANUAL_INV	placka	10	NULL	 
        # 1349	MANUAL_INV	rezervace	10	NULL	 
        
        $cat_registrations = array('A');
        $cat_fines = array('F', 'FO', 'FU');
        $cat_reservations = array('Res', 'rezer', 'rezervace');
        $cat_prints = array('tisk', 'kopie', 'K', 'Copie', 'L');
        $cat_other = array('N',
                            'dovoz',
                            'MVS',
                            'nahrada_dokumentu',
                            'nahra',
                            'taska',
                            'placka',
                            'plack',
                            'poskozeni_kodu',
                            'posko',
                            'poskozeni_dokumentu',
                            'duplikat_prukazu',
                            'dupli',
                            'obsluha_bez_prukazu',
                            'obslu',
                            'prodej_vyrazenych',
                            'prode',
                            'knihovni_zpracovani',
                            'kniho',
                            'internet',
                            'inter',
                            'ostatni',
                            'ostat',
                            'posto',
                            'rent',
                            'rental',
                            'Rent',
                            'M',
                            'FFOR',
            );
        
        $cat_allPayments = array_merge($cat_registrations, $cat_fines, $cat_reservations, $cat_prints, $cat_other);
        
        /* Routing. Set it also in login.php */
        $root = "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/center";
        $rootDir = dirname(__FILE__)."../";
        
        /* Define months of your country */
        $months = "'Led',
                'Úno',
                'Bře',
                'Dub',
                'Kvě',
                'Čer',
                'Čvc',
                'Srp',
                'Zář',
                'Říj',
                'Lis',
                'Pro'";
        
        $categoryCode = "K"; // DB table categories->categorycode of library for detecting MVS in statistical reports
        
        /* Definition of foreign artefact types in KOHA */
        $doc_types["handwriting"] = "RU";
        $doc_types["micrographicDocument"] = "MD";
        $doc_types["cartographicDocument"] = "MP";
        $doc_types["printedMusic"] = "HU";
        $doc_types["audioDocument"] = "AU";
        $doc_types["audioPictorialDocument"] = "AV";
        $doc_types["pictorialDocument"] = "OB";
        $doc_types["electronicDocument"] = "ED";
        $doc_types["periodics"] = "PE";
        $doc_types["books"] = "KN";
        $doc_types["MVS"] = "MVS";
        
        $ccodes["educational_adult_literature"] = "5";
        $ccodes["educational_children_literature"] = "7";
        $ccodes["beletry_adult_literature"] = "6";
        $ccodes["beletry_children_literature"] = "8";
        $ccodes["fiction_literature"] = "FIC";
        $ccodes["non_fiction_literature"] = "NFIC";
        $ccodes["reference"] = "REF";
        
        $niceLiterature = array(
            $ccodes["beletry_adult_literature"],
            $ccodes["beletry_children_literature"],
            $ccodes["fiction_literature"],
            $ccodes["non_fiction_literature"],
        );
        
        $niceLiteratureForAdults = array(
            $ccodes["beletry_adult_literature"],
            $ccodes["fiction_literature"],
            $ccodes["non_fiction_literature"],
        );
        
        $niceLiteratureForChildren = array(
            $ccodes["beletry_children_literature"],
            $ccodes["fiction_literature"],
            $ccodes["non_fiction_literature"],
        );
        
        $educationalLiterature = array(
            $ccodes["educational_adult_literature"],
            $ccodes["educational_children_literature"],
        );
        
        $educationalLiteratureForAdults = array(
            $ccodes["educational_adult_literature"],
        );
        
        $educationalLiteratureForChildren = array(
            $ccodes["educational_children_literature"],
        );
        
        
        
    
    /*************************************************************************/
    /********************** User configuration part END **********************/
    /*************************************************************************/
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
     $ccodes["array"]["niceLiterature"] = $niceLiterature;
    $ccodes["array"]["niceLiteratureForAdults"] = $niceLiteratureForAdults;
    $ccodes["array"]["niceLiteratureForChildren"] = $niceLiteratureForChildren;
    $ccodes["array"]["educationalLiterature"] = $educationalLiterature;
    $ccodes["array"]["educationalLiteratureForAdults"] = $educationalLiteratureForAdults;
    $ccodes["array"]["educationalLiteratureForChildren"] = $educationalLiteratureForChildren;
        
    /* Login */
    
	$db = new PDO("mysql:host=$host;dbname=$database;charset=utf8", "$user", "$password");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	function varDump($var){
		echo "<pre>\n";
		print_r($var);
		echo "</pre>\n";
	}
	
	/**
     * Logs messages/variables/data to browser console from within php
     *
     * @param $name: message to be shown for optional data/vars
     * @param $data: variable (scalar/mixed) arrays/objects, etc to be logged
     * @param $jsEval: whether to apply JS eval() to arrays/objects
     *
     * @return none
     */
     function logConsole($name, $data = NULL, $jsEval = FALSE)
     {
          if (! $name) return false;
 
          $isevaled = false;
          $type = ($data || gettype($data)) ? 'Type: ' . gettype($data) : '';
 
          if ($jsEval && (is_array($data) || is_object($data)))
          {
               $data = 'eval(' . preg_replace('#[\s\r\n\t\0\x0B]+#', '', json_encode($data)) . ')';
               $isevaled = true;
          }
          else
          {
               $data = json_encode($data);
          }
 
          # sanitalize
          $data = $data ? $data : '';
          $search_array = array("#'#", '#""#', "#''#", "#\n#", "#\r\n#");
          $replace_array = array('"', '', '', '\\n', '\\n');
          $data = preg_replace($search_array,  $replace_array, $data);
          $data = ltrim(rtrim($data, '"'), '"');
          $data = $isevaled ? $data : ($data[0] === "'") ? $data : "'" . $data . "'";
 
$js = <<<JSCODE
\n<script>
     // fallback - to deal with IE (or browsers that don't have console)
     if (! window.console) console = {};
     console.log = console.log || function(name, data){};
     // end of fallback
 
     console.log('$name');
     console.log('------------------------------------------');
     console.log('$type');
     console.log($data);
     console.log('\\n');
</script>
JSCODE;
 
          echo $js;
     } # end logConsole
    
    $langFile = $root."/tpl/lang/".$langShortcut.".json";
    $langData = file_get_contents($langFile);
    $text = json_decode($langData);
    
    if((isset($_SESSION["userID"]) AND ($_SESSION["userID"] !== ""))){
        // just to be readable
    }
    else{
        header("Location:".$root."/system/login.php");
    }
    
    /*  */
    function displayErrors($bool = false){
        if($bool == true){
            Debugger::enable(Debugger::DEVELOPMENT, __DIR__ . '/../log');
            Debugger::$strictMode = TRUE;
        }
        else{
            Debugger::enable(Debugger::PRODUCTION, __DIR__ . '/../log');
            //Debugger::$strictMode = TRUE;
        }
        Debugger::$email = 'xkravec@gmail.com';
    }
    
    function getAccountType($subject, $text)
    {
        $search  = array('A', 'F', 'Res', 'rezer', 'K', 'L', 'N');
        $replace = array($text->reg_payment, $text->fine, $text->reservation, $text->reservation, $text->copie, $text->copie, $text->new_card);
        return str_replace($search, $replace, $subject);
        
    }
    
    /* PHPExcel */
    date_default_timezone_set('Europe/Prague');
    define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
