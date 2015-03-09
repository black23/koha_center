<?php

interface StatisticalReportInterface
{
    
    /**
     * Get from datetime
     *
     * @return string
     */
    public function getFrom();

    /**
     * Get to datetime
     *
     * @return string
     */
    public function getTo();

    /**
     * Set from datetime
     *
     * @param string datetime
     * @return void
     */
    public function setFrom($from);

    /**
     * Set to datetime
     *
     * @param string datetime
     * @return void
     */
    public function setTo($to);
    
    /**
     * @return array Array of SQL results
     */
    public function getReportAsArray();
    
    /**
     * @return  void
     */
    public function getArray0101();
    public function getArray0102();
    public function getArray0103();
    public function getArray0104();
    public function getArray0105();
    public function getArray0106();
    public function getArray0107();
    public function getArray0108();
    public function getArray0109();
    public function getArray0110();
    public function getArray0111();
    public function getArray0112();
    public function getArray0113();
    public function getArray0114();
    public function getArray0115();
    public function getArray0116();
    public function getArray0117();
    public function getArray0118();
    public function getArray0119();
    public function getArray0139();
    
    public function getArray0201();
    public function getArray0202();
    public function getArray0205();

    public function getArray0301();
    public function getArray0302();
    public function getArray0303();
    public function getArray0304();
    public function getArray0305();
    public function getArray0306();
    public function getArray0307();
    public function getArray0308();
    public function getArray0309();
    public function getArray0310();
    public function getArray0311();
    public function getArray0312();
    public function getArray0313();
    public function getArray0314();
    public function getArray0315();
    public function getArray0316();
    public function getArray0317();
    public function getArray0339();
    
    public function getArray0402();
    public function getArray0403();
    public function getArray0404();
    public function getArray0405();
    public function getArray0406();
    public function getArray0407();
    public function getArray0408();
    public function getArray0409();
    public function getArray0410();
    public function getArray0411();
    public function getArray0412();
    
    public function getArray0701();
    public function getArray0702();
    
    public function getArray0808();
    
    public function getArray0809();
    
}
