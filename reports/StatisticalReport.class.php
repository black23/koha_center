<?php

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
    private $db;
    
    /**
     * Contains datetime from
     *
     * @var string
     */
    private $from;
    
    /**
     * Contains datetime to
     *
     * @var string
     */
    private $to;
    
    /**
     * Contains results of SQL queries
     *
     * @var array 
     */
    protected $report;
    
    /**
     * Constructor for initializing the class.
     *
     * @param $db
     * @return void
     */
    public function __construct(PDO $db, $from, $to)
    {
        $this->db = $db;
        $this->from = $from;
        $this->to = $to;
        $this->report = array();
        
        /**
        * Delete this code below
        */
        $this->report["0101"] = 0;
        $this->report["0102"] = 0;
        $this->report["0103"] = 0;
        $this->report["0104"] = 0;
        $this->report["0105"] = 0;
        $this->report["0106"] = 0;
        $this->report["0107"] = 0;
        $this->report["0108"] = 0;
        $this->report["0109"] = 0;
        $this->report["0110"] = 0;
        $this->report["0111"] = 0;
        $this->report["0112"] = 0;
        $this->report["0113"] = 0;
        $this->report["0114"] = 0;
        $this->report["0115"] = 0;
        $this->report["0116"] = 0;
        $this->report["0117"] = 0;
        $this->report["0118"] = 0;
        $this->report["0119"] = 0;
        
        $this->report["0201"] = 0;
        $this->report["0202"] = 0;
        $this->report["0203"] = 0;
        $this->report["0204"] = 0;
        $this->report["0205"] = 0;
        
        $this->report["0301"] = 0;
        $this->report["0302"] = 0;
        $this->report["0303"] = 0;
        $this->report["0304"] = 0;
        $this->report["0305"] = 0;
        $this->report["0306"] = 0;
        $this->report["0307"] = 0;
        $this->report["0308"] = 0;
        $this->report["0309"] = 0;
        $this->report["0310"] = 0;
        $this->report["0311"] = 0;
        $this->report["0312"] = 0;
        $this->report["0313"] = 0;
        $this->report["0314"] = 0;
        $this->report["0315"] = 0;
        $this->report["0316"] = 0;
        $this->report["0317"] = 0;
        $this->report["0339"] = 0;
        
        $this->report["0402"] = 0;
        $this->report["0403"] = 0;
        $this->report["0404"] = 0;
        $this->report["0405"] = 0;
        $this->report["0406"] = 0;
        $this->report["0407"] = 0;
        $this->report["0408"] = 0;
        $this->report["0409"] = 0;
        $this->report["0410"] = 0;
        $this->report["0411"] = 0;
        $this->report["0412"] = 0;
        
        $this->report["0701"] = 0;
        $this->report["0702"] = 0;
        
        $this->report["0808"] = 0;
        $this->report["0809"] = 0;
        
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
        $this->getArray0101(); // Finished
        $this->getArray0103(); // Finished
        $this->getArray0104(); // Finished
        $this->getArray0104();
        $this->getArray0105();
        $this->getArray0106();
        $this->getArray0107();
        $this->getArray0108();
        $this->getArray0110();
        $this->getArray0111();
        $this->getArray0112();
        $this->getArray0113();
        $this->getArray0102(); // Finished, must be after 0102-0113
        
        $this->getArray0114(); // Finished
        $this->getArray0115();
        $this->getArray0116(); // Finished
        $this->getArray0118();
        $this->getArray0119();
        $this->getArray0139(); // Finished, must be after 0101-0119
        
        $this->getArray0201(); // Finished
        $this->getArray0202(); // Finished
        $this->getArray0205(); // Finished
        
        $this->getArray0302(); // Finished
        $this->getArray0303(); // Finished
        $this->getArray0304(); // Finished
        $this->getArray0305(); // Finished
        $this->getArray0306(); // Finished
        $this->getArray0307();
        $this->getArray0308();
        $this->getArray0309();
        $this->getArray0310();
        $this->getArray0311();
        $this->getArray0312();
        $this->getArray0313();
        $this->getArray0314();
        $this->getArray0315();
        $this->getArray0301(); // Finished, must not be after 0301-0315
        $this->getArray0316();
        $this->getArray0317(); // Finished
        $this->getArray0339(); // Finished, must be after 0301-0317
        
        $this->getArray0402();
        $this->getArray0403(); // Finished
        $this->getArray0404(); // Finished
        $this->getArray0405();
        $this->getArray0406();
        $this->getArray0407();
        $this->getArray0408();
        $this->getArray0409();
        $this->getArray0410();
        $this->getArray0411();
        $this->getArray0412();
        
        $this->getArray0701();
        $this->getArray0702();
        
        $this->getArray0808();
        $this->getArray0809();
        
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

        $this->report['0101'] = $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0102()
    {
        
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
        
        $this->report['0102'] =  array_sum($array);
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0103()
    {
        
        try {

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` <= :to AND `ccode` IN('5', '7')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        $this->report['0103'] = $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0104()
    {
        
        try {

            $query = "SELECT count(`itype`) AS 'count' "
                    ."FROM `items` "
                    ."WHERE `dateaccessioned` <= :to AND `ccode` IN('6', '8', 'FIC', 'NFIC')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        $this->report['0104'] = $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0105()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0106()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0107()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0108()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0109()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0110()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0111()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0112()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0113()
    {
        
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

        $this->report['0114'] = $results[0]['count'];      
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0115()
    {
        
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

        $this->report['0116'] = $results[0]['count'];
        
    }


    /**
      * {@inheritDoc}
      */
    public function getArray0118()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0119()
    {
        
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
                       $this->report["0117"],
                       $this->report["0118"],
                       $this->report["0119"],
                      );
        
        $this->report['0139'] = array_sum($array);
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0201()
    {
        
        try {

            $query = "SELECT count(*) AS 'count' "
                    ."FROM `borrowers` "
                    ."WHERE `dateenrolled` BETWEEN :from AND :to";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        $this->report['0201'] = $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0202()
    {
        
        try {

            $query = "SELECT count(*) AS 'count' "
                    ."FROM `borrowers` "
                    ."WHERE `dateofbirth` > DATE_SUB(CURRENT_DATE(),INTERVAL 15 YEAR) "
                    ."AND `dateenrolled` BETWEEN :from AND :to";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }

        $this->report['0202'] = $results[0]['count'];
        
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

        $this->report['0205'] = $results[0]['count'];
                
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
        
        $this->report['0301'] = $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0302()
    {
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`ccode` IN ('5')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        $this->report['0302'] = $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0303()
    {
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`ccode` IN ('6', 'FIC', 'NFIC')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        $this->report['0303'] = $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0304()
    {
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`ccode` IN ('7')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        $this->report['0304'] = $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0305()
    {
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`ccode` IN ('8', 'FIC', 'NFIC')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        $this->report['0305'] = $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0306()
    {
        
        try{
                
            $query = "SELECT count(`s`.`datetime`) AS 'count' "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('PE')";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        
        $this->report['0306'] = $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0307()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0308()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0309()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0310()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0311()
    {
        
    }
    
    /**
      * {@inheritDoc}
      */
    public function getArray0312() 
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0313()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0314()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0315()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0316()
    {
        
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
        
        $this->report['0317'] = $results[0]['count'];
        
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
                       $this->report["0316"],
                       $this->report["0317"]
                      );
        
        $this->report['0339'] = array_sum($array);
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0402()
    {
        
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
        
        $this->report['0403'] = $results[0]['count'];
        
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
        
        $this->report['0404'] = $results[0]['count'];
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0405()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0406()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0407()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0408()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0409()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0410()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0411()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0412()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0701()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0702()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0808()
    {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0809()
    {
        
    }

}
