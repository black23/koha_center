<?php

/**
 * 
 */

    require_once __DIR__."/StatisticalReportInterface.php";
    
    use Tracy\Debugger;

/**
 * Statistical report class
 * 
 * Class for statistical report generation
 * 
 * @author Martin Kravec
 */
class StatisticalReport implements StatisticalReportInterface
{
    
    /**
     * Contains database handler
     *
     * @var object
     */
    protected $db;
    
    /**
     * Contains datetime from
     *
     * @var string
     */
    protected $from;
    
    /**
     * Contains datetime to
     *
     * @var string
     */
    protected $to;
    
    /**
     * Contains results of SQL queries
     *
     * @var array 
     */
    protected $report;
    
    /**
     * Array of document types
     *
     * @var array
     */
    protected $docTypes;
    
    /**
     * Array of literature types
     *
     * @var array
     */
    protected $ccodes;
    
    protected $catPrints;
    
    protected $docCollections;
    
    /**
     * Constructor for initializing the class.
     *
     * @param $db
     * @return void
     */
    public function __construct(PDO $db, $from, $to, $categoryCode, array $docTypes, array $ccodes, $catPrints, array $docCollections)
    {
        $this->db = $db;
        $this->from = $from;
        $this->to = $to;
        $this->report = array();
        $this->categoryCode = $categoryCode;
        $this->docTypes = $docTypes;
        $this->docCollections = $docCollections;
        $this->ccodes = $ccodes;
        $this->catPrints = $catPrints;
        
    }
    
    /**
      * {@inheritDoc}
      */
    public function getFrom()
    {
        return $this->from;
    }

    /**
      * {@inheritDoc}
      */
    public function getTo()
    {
        return $this->to;
    }

    /**
      * {@inheritDoc}
      */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
      * {@inheritDoc}
      */
    public function setTo($to)
    {
        $this->to = $to;
    }
    
    /**
      * @return void
      */
    public function runQueries()
    {
        $this->report["0101"] = $this->getArray0101(); // Finished
        $this->report["0103"] = $this->getArray0103(); // Finished
        $this->report["0104"] = $this->getArray0104(); // Finished
        $this->report["0105"] = $this->getArray0105(); // Finished
        $this->report["0106"] = $this->getArray0106(); // Finished
        $this->report["0107"] = $this->getArray0107(); // Finished
        $this->report["0108"] = $this->getArray0108(); // Finished
        $this->report["0109"] = $this->getArray0109(); // Finished
        $this->report["0110"] = $this->getArray0110(); // Finished
        $this->report["0111"] = $this->getArray0111(); // Finished
        $this->report["0112"] = $this->getArray0112(); // Finished
        $this->report["0113"] = $this->getArray0113(); // Finished
        $this->report["0102"] = $this->getArray0102(); // Finished, must (not) be after 0102-0113
        
        $this->report["0114"] = $this->getArray0114(); // Finished
        $this->report["0115"] = $this->getArray0115(); // Finished with exception
        $this->report["0116"] = $this->getArray0116(); // Finished
        $this->report["0118"] = $this->getArray0118();
        $this->report["0119"] = $this->getArray0119();
        $this->report["0139"] = $this->getArray0139(); // Finished, must be after 0101-0119
        
        $this->report["0201"] = $this->getArray0201(); // Finished
        $this->report["0202"] = $this->getArray0202(); // Finished
        $this->report["0205"] = $this->getArray0205(); // Finished
        $this->report["0206"] = $this->getArray0206(); // Finished
        
        $this->report["0302"] = $this->getArray0302(); // Finished
        $this->report["0303"] = $this->getArray0303(); // Finished
        $this->report["0304"] = $this->getArray0304(); // Finished
        $this->report["0305"] = $this->getArray0305(); // Finished
        $this->report["0306"] = $this->getArray0306(); // Finished
        $this->report["0307"] = $this->getArray0307(); // Finished
        $this->report["0308"] = $this->getArray0308(); // Finished
        $this->report["0309"] = $this->getArray0309(); // Finished
        $this->report["0310"] = $this->getArray0310(); // Finished
        $this->report["0311"] = $this->getArray0311(); // Finished
        $this->report["0312"] = $this->getArray0312(); // Finished
        $this->report["0313"] = $this->getArray0313(); // Finished
        $this->report["0314"] = $this->getArray0314(); // Finished
        $this->report["0315"] = $this->getArray0315(); // Finished
        $this->report["0301"] = $this->getArray0301(); // Finished, must not be after 0301-0315
        $this->report["0317"] = $this->getArray0317(); // Finished
        $this->report["0339"] = $this->getArray0339(); // Finished, must be after 0301-0317
        
        $this->report["0401"] = $this->getArray0401(); // Finished, must be before 0402
        $this->report["0402"] = $this->getArray0402(); // Finished
        $this->report["0403"] = $this->getArray0403(); // Finished
        $this->report["0404"] = $this->getArray0404(); // Finished
        $this->report["0405"] = $this->getArray0405();
        $this->report["0406"] = $this->getArray0406();
        $this->report["0407"] = $this->getArray0407();
        $this->report["0408"] = $this->getArray0408();
        $this->report["0409"] = $this->getArray0409();
        $this->report["0410"] = $this->getArray0410();
        $this->report["0411"] = $this->getArray0411();
        $this->report["0412"] = $this->getArray0412();
        
        $this->report["0701"] = $this->getArray0701();
        $this->report["0702"] = $this->getArray0702();
        
    }

    /**
      * {@inheritDoc}
      */
    final public function getReportAsArray()
    {
        $this->runQueries();
        return $this->report;
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0101()
    {
        
        try {

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` <= :to";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0102()
    {
        /*
        $array = array($this->report["0103"],
                       $this->report["0104"],
                       $this->report["0105"],
                       $this->report["0106"],
                       $this->report["0107"],
                       $this->report["0108"],
                       $this->report["0109"],
                       $this->report["0110"],
                       $this->report["0111"],
                       $this->report["0112"],
                       $this->report["0113"]
                      );
        
        return array_sum($array);
        */
        
        try {
            
            $type = $this->docTypes["periodics"];

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` BETWEEN :from AND :to "
                    ."  AND `itype` NOT IN ('$type')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0103()
    {
        
        $type = $this->docTypes["books"];
        $ccodes = $this->ccodes["array"]["educationalLiterature"];
        
        try {

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` BETWEEN :from AND :to "
                    ."  AND `ccode` IN ('".implode('\',\'', $ccodes)."') "
                    ."  AND `itype` IN ('$type')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0104()
    {
        
        $type = $this->docTypes["books"];
        $ccodes = $this->ccodes["array"]["niceLiterature"];
        
        try {

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` BETWEEN :from AND :to "
                    ."  AND `ccode` IN ('".implode('\',\'', $ccodes)."') "
                    ."  AND `itype` IN ('$type')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0105()
    {
        
        $type = $this->docTypes["handwriting"];
        
        try {

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` BETWEEN :from AND :to AND `itype` = '$type'";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0106()
    {
        $type = $this->docTypes["micrographicDocument"];
        
        try {

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` BETWEEN :from AND :to AND `itype` = '$type'";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0107()
    {
        $type = $this->docCollections["cartographicDocuments"];
        
        try {

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` BETWEEN :from AND :to AND `itype` IN ('".implode('\',\'', $type)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0108()
    {
        $type = $this->docCollections["printedMusic"];
        
        try {

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` BETWEEN :from AND :to AND `itype` IN ('".implode('\',\'', $type)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0109()
    {
        $type = $this->docCollections["audioDocuments"];
        
        try {

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` BETWEEN :from AND :to AND `itype` IN ('".implode('\',\'', $type)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0110()
    {
        $type = $this->docCollections["audioVisualDocuments"];
        
        try {

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` BETWEEN :from AND :to AND `itype` IN ('".implode('\',\'', $type)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0111()
    {
        $type = $this->docCollections["visualDocuments"];
        
        try {

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` BETWEEN :from AND :to AND `itype` IN ('".implode('\',\'', $type)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0112()
    {
        $type = $this->docCollections["electronicDocuments"];
        
        try {

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` BETWEEN :from AND :to AND `itype` IN ('".implode('\',\'', $type)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0113()
    {
        $types = $this->docCollections["otherDocuments"];;
        
        try {

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` BETWEEN :from AND :to AND `itype` IN ('".implode('\',\'', $types)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0114()
    {
        
        try {

            $query = "SELECT COUNT(*) AS 'count' "
                ."FROM serial, biblio, biblioitems "
                ."WHERE serial.biblionumber = biblio.biblionumber "
                ."  AND serial.biblionumber = biblioitems.biblionumber "
                ."  AND DATE(planneddate) BETWEEN :from AND :to "
                ."  AND status = 2";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];      
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0115()
    {
        
        return "-";
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0116()
    {

        try {

            $query = "SELECT count(`biblionumber`) AS 'count' "
                    ."FROM `biblio` "
                    ."WHERE `datecreated` BETWEEN :from AND :to";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
        
    }


    /**
      * {@inheritDoc}
      */
    public function getArray0118()
    {
        $count = 0;
        return (int) $count;
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0119()
    {
        $count = 0;
        return (int) $count;
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0139()
    {
        
        $array = array($this->report["0101"],
                       $this->report["0102"],
                       $this->report["0103"],
                       $this->report["0104"],
                       $this->report["0105"],
                       $this->report["0106"],
                       $this->report["0107"],
                       $this->report["0108"],
                       $this->report["0109"],
                       $this->report["0110"],
                       $this->report["0111"],
                       $this->report["0112"],
                       $this->report["0113"],
                       $this->report["0114"],
                       $this->report["0115"],
                       $this->report["0116"],
                       $this->report["0118"],
                       $this->report["0119"],
                      );
        
        return array_sum($array);
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0201()
    {
        
        try {

            $query = "SELECT count(`s`.`borrowernumber`) AS 'count' "
                    ."FROM `statistics` `s` "
                    ."LEFT JOIN `accountlines` `a` "
                    ."ON `a`.`accountlines_id` = `s`.`other` "
                    ."WHERE DATE(`s`.`datetime`) BETWEEN :from AND :to "
                    ."  AND `s`.`type` = 'payment' "
                    ."  AND `a`.`accounttype` = 'A'";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0202()
    {
        
        try {

            $query = "SELECT count(`s`.`borrowernumber`) AS 'count' "
                    ."FROM `statistics` `s` "
                    ."LEFT JOIN `accountlines` `a` "
                    ."ON `a`.`accountlines_id` = `s`.`other` "
                    ."LEFT OUTER JOIN `borrowers` `b` "
		    ."ON `b`.`borrowernumber` = `s`.`borrowernumber` "
                    ."WHERE DATE(`s`.`datetime`) BETWEEN :from AND :to "
                    ."  AND `s`.`type` = 'payment' "
                    ."  AND `a`.`accounttype` = 'A' "
                    ."  AND `b`.`dateofbirth` > DATE_SUB(`s`.`datetime`,INTERVAL 15 YEAR) ";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0205()
    {

        try {

            $query = "SELECT COUNT(DISTINCT `b`.`borrowernumber`) AS `count` "
                    ."FROM `statistics` `s` "
                    ."LEFT JOIN `borrowers` `b` "
                    ."ON `b`.`borrowernumber` = `s`.`borrowernumber` "
                    ."WHERE `s`.`branch` IS NOT NULL "
                    . "AND DATE(`s`.`datetime`) BETWEEN :from AND :to "
                    . "AND `s`.`type` IS NOT NULL";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['count'];
                
    }
    
     /**
      * {@inheritDoc}
      */
    public function getArray0206()
    {

        try {

            $query = "SELECT COALESCE(SUM(`internet`), 0) AS `sum` "
                    ."FROM `cen_circulations` "
                    ."WHERE DATE(`datetime`) BETWEEN :from AND :to";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        return $results[0]['sum'];
                
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0301()
    {
        
        try{
                
            $query = "SELECT count(`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
                    ."WHERE `datetime` BETWEEN :from AND :to "
                    ."AND `type` IN ('issue','renew')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0302()
    {
        
        $ccodes = array_merge(
            		$this->ccodes["array"]["educationalLiteratureForAdults"],
            		$this->ccodes["array"]["educationalLiteratureForChildren"]
            );
        $type = $this->docTypes["books"];
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
		    ."LEFT JOIN `borrowers` `b` "
		    ."ON `s`.`borrowernumber` = `b`.`borrowernumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
                    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."') "
                    ."  AND `b`.`dateofbirth` > DATE_SUB(`s`.`datetime`,INTERVAL 15 YEAR) "
		    ."  AND `i`.`ccode` IN ('".implode('\',\'', $ccodes)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0303()
    {
        
        $ccodes = array_merge(
    			$this->ccodes["array"]["niceLiteratureForAdults"],
    			$this->ccodes["array"]["niceLiteratureForChildren"]
    	);
        $type = $this->docTypes["books"];
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
		    ."LEFT JOIN `borrowers` `b` "
		    ."ON `s`.`borrowernumber` = `b`.`borrowernumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
                    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."') "
                    ."  AND `b`.`dateofbirth` > DATE_SUB(`s`.`datetime`,INTERVAL 15 YEAR) "
		    ."  AND `i`.`ccode` IN ('".implode('\',\'', $ccodes)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0304()
    {
    	$ccodes = array_merge(
    			$this->ccodes["array"]["educationalLiteratureForAdults"],
    			$this->ccodes["array"]["educationalLiteratureForChildren"]
    	);
        $type = $this->docTypes["books"];
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
		    ."LEFT JOIN `borrowers` `b` "
		    ."ON `s`.`borrowernumber` = `b`.`borrowernumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
                    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."') "
                    ."  AND `b`.`dateofbirth` > DATE_SUB(`s`.`datetime`,INTERVAL 15 YEAR) "
		    ."  AND `i`.`ccode` IN ('".implode('\',\'', $ccodes)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0305()
    {
        
        $ccodes = array_merge(
				$this->ccodes["array"]["niceLiteratureForAdults"],
				$this->ccodes["array"]["niceLiteratureForChildren"]
			);
        $type = $this->docTypes["books"];
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
		    ."LEFT JOIN `borrowers` `b` "
		    ."ON `s`.`borrowernumber` = `b`.`borrowernumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
                    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."') "
                    ."  AND `b`.`dateofbirth` > DATE_SUB(`s`.`datetime`,INTERVAL 15 YEAR) "
		    ."  AND `i`.`ccode` IN ('".implode('\',\'', $ccodes)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0306()
    {
        
        $type = $this->docCollections["periodics"];
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0307()
    {
        $type = $this->docTypes["handwriting"];
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('$type')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0308()
    {
        $type = $this->docTypes["micrographicDocument"];
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('$type')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0309()
    {
        $type = $this->docCollections["cartographicDocuments"];
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0310()
    {
        $type = $this->docCollections["printedMusic"];
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0311()
    {
        $type = $this->docCollections["audioDocuments"];
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
    }
    
    /**
      * {@inheritDoc}
      */
    public function getArray0312() 
    {
        $type = $this->docCollections["audioVisualDocuments"];
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0313()
    {
        $type = $this->docCollections["visualDocuments"];
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0314()
    {
        $type = $this->docCollections["electronicDocuments"];
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0315()
    {
        $types = $this->docCollections["otherDocuments"];
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $types)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0317()
    {
        
        try{
                
            $query = "SELECT count(`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
                    ."WHERE `datetime` BETWEEN :from AND :to "
                    ."AND `type` IN ('renew')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
        
    }
    
    /**
      * {@inheritDoc}
      */
    public function getArray0339()
    {
    
        $array = array($this->report["0301"],
                       $this->report["0302"],
                       $this->report["0303"],
                       $this->report["0304"],
                       $this->report["0305"],
                       $this->report["0306"],
                       $this->report["0307"],
                       $this->report["0308"],
                       $this->report["0309"],
                       $this->report["0310"],
                       $this->report["0311"],
                       $this->report["0312"],
                       $this->report["0313"],
                       $this->report["0314"],
                       $this->report["0315"],
                       $this->report["0317"]
                      );
        
        return array_sum($array);
        
    }
    
    /**
      * {@inheritDoc}
      */
    public function getArray0401()
    {
        
        try{
                
            $query = "SELECT COUNT(*) AS 'count' "
                    ."FROM `statistics` `s` "
                    ."INNER JOIN `borrowers` `b` "
                    ."ON `b`.`borrowernumber` = `s`.`borrowernumber` "
                    ."WHERE `s`.`type` = 'issue' "
                    ."    AND `b`.`categorycode` = :categorycode "
                    ."    AND DATE(`s`.`datetime`) BETWEEN :from AND :to";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);
            $stmt->bindValue(':categorycode', $this->categoryCode, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0402()
    {
        
        return $this->report['0401'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0403()
    {

        try{
                
            $query = "SELECT COUNT(`barcode`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `barcode` <> '' "
                    ."    AND `barcode` IS NOT NULL "
                    ."    AND `itype` = 'MVS'"
                    ."    AND `dateaccessioned` BETWEEN :from AND :to";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0404()
    {
        
        try{
                
            $query = "SELECT COUNT(DISTINCT  `i`.`barcode`) AS 'count' "
                    ."FROM `items` `i` "
                    ."INNER JOIN `statistics` `s` "
                    ."ON `i`.`itemnumber` = `s`.`itemnumber` "
                    ."WHERE `i`.`barcode` <> '' "
                    ."    AND `i`.`barcode` IS NOT NULL "
                    ."    AND `i`.`itype` = 'MVS' "
                    ."    AND `i`.`dateaccessioned` BETWEEN :from AND :to "
                    ."    AND `s`.`type` = 'issue'";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        return $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0405()
    {
        $count = 0;
        return (int) $count;
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0406()
    {
        $count = 0;
        return (int) $count;
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0407()
    {
        $count = 0;
        return (int) $count;
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0408()
    {
        $count = 0;
        return (int) $count;
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0409()
    {
        $count = 0;
        return (int) $count;
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0410()
    {
        $count = 0;
        return (int) $count;
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0411()
    {
        $count = 0;
        return (int) $count;
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0412()
    {
        $count = 0;
        return (int) $count;
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0701()
    {
        $count = 0;
        return (int) $count;
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0702()
    {
        $count = 0;
        return (int) $count;
    }
    
    public function getArrayTisk()
    {
        try {
            
            $catPrints = $this->catPrints;

            $query = "SELECT count(*) as `count` "
                    ."FROM `statistics` `s` "
                    ."JOIN `accountlines` `a` "
                    ."  ON `a`.`accountlines_id` = `s`.`other` "
                    ."WHERE DATE(`s`.`datetime`) BETWEEN :from AND :to "
                    ."  AND `s`.`type` = :type "
                    ."  AND `a`.`accounttype` IN ('".implode('\',\'', $catPrints)."')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);
            $stmt->bindValue(':type', "payment", PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results[0]["count"];
    }

}
