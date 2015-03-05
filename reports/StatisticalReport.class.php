<?php

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
     * Constructor for initializing the class.
     *
     * @param $db
     * @return void
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->report = array();
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
      * {@inheritDoc}
      */
    final public function getReportAsArray()
    {
        return $this->report;
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0101() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0102() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0103() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0104() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0105() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0106() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0107() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0108() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0109() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0110() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0111() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0112() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0113() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0114() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0115() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0116() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0117() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0118() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0119() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0139() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0201() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0202() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0203() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0204() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0205() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0301() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0302() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0303() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0304() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0305() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0306() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0307() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0308() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0309() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0310() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0311() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0312() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0313() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0314() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0315() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0316() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0317() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0402() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0403() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0404() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0405() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0406() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0407() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0408() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0409() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0410() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0411() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0412() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0701() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0702() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0808() {
        
    }

    /**
      * {@inheritDoc}
      */
    public function getArray0809()
    {
        
        
        
    }

}
