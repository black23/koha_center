<?php

require_once __DIR__."/StatisticalReport.class.php";
require_once dirname(__FILE__) . '/../system/PHPExcel/Classes/PHPExcel.php';
    
use Tracy\Debugger;

class KrizovatkaDenik extends StatisticalReport
{
    
    private $objPHPExcel;
    private $style;
    private $style2;
    private $style3;
    private $style4;
    
    private $before;
    
    /**
     * 
     * @param PDO $db
     * @param string $from
     * @param string $to
     * @param type $categoryCode
     * @param array $docTypes
     * @param array $ccodes
     */
    public function __construct(PDO $db, $from, $to, $categoryCode, array $docTypes, array $ccodes, array $catPrints, array $docCollections)
    {
        parent::__construct($db, $from, $to, $categoryCode, $docTypes, $ccodes, $catPrints, $docCollections);
        $this->before = new StatisticalReport($db, substr($from, 0, 4)."-01-01", $from, $categoryCode, $docTypes, $ccodes, $catPrints, $docCollections);
        $this->objPHPExcel = new PHPExcel();
    }
    
    private function sheet1(array $data)
    {
        $sheetIndex = 0;
        
        $this->objPHPExcel->getSheet($sheetIndex)->setTitle('Uživatelé');
        
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('A1:K1')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('D3:E3')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('A2:A6')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('B2:C2')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('D2:K2')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('B3:B6')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('C3:C6')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('D4:D6')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('E4:E6')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('F3:F6')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('G3:G6')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('H3:H6')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('I3:I6')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('J3:J6')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('K3:K6')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('A1', "Deník veřejné knihovny $this->from - $this->to uživatelé knihovny")
                    ->setCellValue('A2', 'Datum')
                    ->setCellValue('B3', 'celkem')
                    ->setCellValue('C3', 'do 15 let')
                    ->setCellValue('D3', 'knihovny')
                    ->setCellValue('F3', 'půjčoven a studoven')
                    ->setCellValue('G3', 'využívající internet v knihově')
                    ->setCellValue('H3', 'kulturních akcí (sl. 49, odd. V.)')
                    ->setCellValue('I3', 'vzdělávacích akcí pro děti')
                    ->setCellValue('J3', 'vzdělávacích akcí pro dospělé')
                    ->setCellValue('K3', 'on-line služeb (virtuální návštěvy) (sl. 29+31+33 odd. III)')
                    ->setCellValue('D4', 'celkem součet sl. (4+9)')
                    ->setCellValue('E4', '(fyzické návštěvy) součet sl. (5+6+7+8)')
                    ->setCellValue('B2', 'Registrovaní uživatelé')
                    ->setCellValue('D2', 'Návštěvníci')
                    ->setCellValue('B7', 'sl. 1')
                    ->setCellValue('C7', 'sl. 2')
                    ->setCellValue('D7', 'sl. 3')
                    ->setCellValue('E7', 'sl. 4')
                    ->setCellValue('F7', 'sl. 5')
                    ->setCellValue('G7', 'sl. 6')
                    ->setCellValue('H7', 'sl. 7')
                    ->setCellValue('I7', 'sl. 8a')
                    ->setCellValue('J7', 'sl. 8b')
                    ->setCellValue('K7', 'sl. 9');
        
        /* Add data to Sheet 1 */
        
        $count = count($data);
        
        $i = 8;
        if($count > 0){
            foreach($data AS $key => $val){
                $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                        ->setCellValue('A'.$i, date("d. m.", strtotime($key)))
                        ->setCellValue('B'.$i, isset($val['sl1']) ? $val['sl1'] : "0")
                        ->setCellValue('C'.$i, isset($val['sl2']) ? $val['sl2'] : "0")
                        ->setCellValue('D'.$i, "=(E$i+K$i)")
                        ->setCellValue('E'.$i, "=(F$i+G$i+H$i+I$i+J$i)")
                        ->setCellValue('F'.$i, isset($val['sl5']) ? $val['sl5'] : "0")
                        ->setCellValue('G'.$i, isset($val['sl6']) ? $val['sl6'] : "0");

                $i++;
            }
        }
        else{
            $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                        ->setCellValue("A$i", "");
            $count++;
        }
        /* Add data to Sheet 1 End */
        
        $footer = $count + 7 + 1;
        $footer2 = $footer + 1;
        $footer3 = $footer + 2;
        
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('B'.$footer2, $this->before->getArray0201())
                    ->setCellValue('C'.$footer2, $this->before->getArray0202())
                    ->setCellValue('D'.$footer2, "=(E$footer2+K$footer2)")
                    ->setCellValue('E'.$footer2, "=(F$footer2+G$footer2+H$footer2+I$footer2+J$footer2)")
                    ->setCellValue('F'.$footer2, $this->before->getArray0205())
                    ->setCellValue('G'.$footer2, $this->before->getArray0206());
        
        $last = $footer - 1;
        
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('A'.$footer, "Celkem za období")
                    ->setCellValue('B'.$footer, "=SUM(B8:B$last)")
                    ->setCellValue('C'.$footer, "=SUM(C8:C$last)")
                    ->setCellValue('D'.$footer, "=SUM(D8:D$last)")
                    ->setCellValue('E'.$footer, "=SUM(E8:E$last)")
                    ->setCellValue('F'.$footer, "=SUM(F8:F$last)")
                    ->setCellValue('G'.$footer, "=SUM(G8:G$last)")
                    ->setCellValue('H'.$footer, "=SUM(H8:H$last)")
                    ->setCellValue('I'.$footer, "=SUM(I8:I$last)")
                    ->setCellValue('J'.$footer, "=SUM(J8:J$last)")
                    ->setCellValue('K'.$footer, "=SUM(K8:K$last)")
                    ->setCellValue('A'.$footer2, "K tomu od zač. roku")
                    ->setCellValue('A'.$footer3, "Celkem od zač. roku")
                    ->setCellValue('B'.$footer3, "=(B$footer+B$footer2)")
                    ->setCellValue('C'.$footer3, "=(C$footer+C$footer2)")
                    ->setCellValue('D'.$footer3, "=(D$footer+D$footer2)")
                    ->setCellValue('E'.$footer3, "=(E$footer+E$footer2)")
                    ->setCellValue('F'.$footer3, "=(F$footer+F$footer2)")
                    ->setCellValue('G'.$footer3, "=(G$footer+G$footer2)")
                    ->setCellValue('H'.$footer3, "=(H$footer+H$footer2)")
                    ->setCellValue('I'.$footer3, "=(I$footer+I$footer2)")
                    ->setCellValue('J'.$footer3, "=(J$footer+J$footer2)")
                    ->setCellValue('K'.$footer3, "=(K$footer+K$footer2)");
        
        /* Formatovani */
        $this->cellColor("A1", '009EDB');
        $this->cellColor("M1", '009EDB');
        
        $this->cellColor("H8:K$last", 'ffcccc');
        $this->cellColor("H$footer2:K$footer2", 'ffcccc');
        
        $this->cellColor("G8:G$last", 'ffef99');
        
        $this->objPHPExcel->getActiveSheet()->getStyle("A1:K$footer3")->applyFromArray($this->style2);
        $this->objPHPExcel->getActiveSheet()->getStyle('A7:K7')->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer:K$footer")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer2:K$footer2")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer3:K$footer3")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$last:K$last")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("K1:K$footer3")->applyFromArray($this->style4);
        
        $this->objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->getStyle("A7:K7")->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer:A$footer3")->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->getStyle("A2:K2")->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->getStyle("D3")->getFont()->setBold(true);
        
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(19);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        
        /**/
        
        /* Vysvetlivky */
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('M1', "Vysvětlivky");
                
        $this->cellColor("M2", 'ffcccc');
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('M2', "Doplnit ručně");
        
        $this->cellColor("M3", 'ffef99');
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('M3', "Doplnit DB přes Kohu");
        
        $this->cellColor("M4", 'e1e1e1');
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('M4', "Doplní centrum samo");
        /**/
        
    }
    
    private function sheet2(array $data)
    {
        $sheetIndex = 1;
        
        $this->objPHPExcel->createSheet($sheetIndex);
        $this->objPHPExcel->getSheet($sheetIndex)->setTitle('Výpůjčky');
        
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('A2:A7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('B2:N2')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('B3:B7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('C3:C7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('D3:D7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('E3:E7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('F3:F7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('G3:G7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('H3:H7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('I3:I7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('J3:J7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('K3:K7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('L3:L7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('M3:M7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('N3:N7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('O4:O7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('P4:P7')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('A1:P1')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('O2:P3')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('A1', "Deník veřejné knihovny $this->from - $this->to služby uživatelům - výpůjčky")
                    ->setCellValue('B2', "Výpůjčky")
                    ->setCellValue('A2', 'Datum')
                    ->setCellValue('B3', 'celkem součet sl. 11-24')
                    ->setCellValue('C3', 'naučné literatury dospělým uživatelům')
                    ->setCellValue('D3', 'krásné literatury dospělým uživatelům')
                    ->setCellValue('E3', 'naučné literatury dětem')
                    ->setCellValue('F3', 'krásné literatury dětem')
                    ->setCellValue('G3', 'periodik')
                    ->setCellValue('H3', 'kartografických dokumentů')
                    ->setCellValue('I3', 'tištěných hudebnin')
                    ->setCellValue('J3', 'zvukových dokumentů')
                    ->setCellValue('K3', 'zvukově obrazových dokumentů')
                    ->setCellValue('L3', 'obrazových dokumentů')
                    ->setCellValue('M3', 'elektronických dokumentů')
                    ->setCellValue('N3', 'jiných dokumentů')
                    ->setCellValue('O4', 'evidované prezenční výpůjčky')
                    ->setCellValue('P4', 'počet prolongací')
                    ->setCellValue('B8', 'sl. 10')
                    ->setCellValue('O2', 'ze sloupce 10')
                    ->setCellValue('C8', 'sl. 11')
                    ->setCellValue('D8', 'sl. 12')
                    ->setCellValue('E8', 'sl. 13')
                    ->setCellValue('F8', 'sl. 14')
                    ->setCellValue('G8', 'sl. 15')
                    ->setCellValue('H8', 'sl. 18')
                    ->setCellValue('I8', 'sl. 19')
                    ->setCellValue('J8', 'sl. 20')
                    ->setCellValue('K8', 'sl. 21')
                    ->setCellValue('L8', 'sl. 22')
                    ->setCellValue('M8', 'sl. 23')
                    ->setCellValue('N8', 'sl. 24')
                    ->setCellValue('O8', 'sl. 25')
                    ->setCellValue('P8', 'sl. 26');
        
        /* Add data to Sheet 2 */
        
        $count = count($data);
        
        $i = 9;
        if($count > 0){
            foreach($data AS $key => $val){
                $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                        ->setCellValue('A'.$i, date("d. m.", strtotime($key)))
                        ->setCellValue('B'.$i, isset($val['sl10']) ? $val['sl10'] : "0")
                        ->setCellValue('C'.$i, isset($val['sl11']) ? $val['sl11'] : "0")
                        ->setCellValue('D'.$i, isset($val['sl12']) ? $val['sl12'] : "0")
                        ->setCellValue('E'.$i, isset($val['sl13']) ? $val['sl13'] : "0")
                        ->setCellValue('F'.$i, isset($val['sl14']) ? $val['sl14'] : "0")
                        ->setCellValue('G'.$i, isset($val['sl15']) ? $val['sl15'] : "0")
                        ->setCellValue('H'.$i, isset($val['sl18']) ? $val['sl18'] : "0")
                        ->setCellValue('I'.$i, isset($val['sl19']) ? $val['sl19'] : "0")
                        ->setCellValue('J'.$i, isset($val['sl20']) ? $val['sl20'] : "0")
                        ->setCellValue('K'.$i, isset($val['sl21']) ? $val['sl21'] : "0")
                        ->setCellValue('L'.$i, isset($val['sl22']) ? $val['sl22'] : "0")
                        ->setCellValue('M'.$i, isset($val['sl23']) ? $val['sl23'] : "0")
                        ->setCellValue('N'.$i, isset($val['sl24']) ? $val['sl24'] : "0")
                        ->setCellValue('O'.$i, isset($val['sl25']) ? $val['sl25'] : "0")
                        ->setCellValue('P'.$i, isset($val['sl26']) ? $val['sl26'] : "0");

                $i++;
            }
        }
        else{
            $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                        ->setCellValue("A$i", "");
            $count++;
        }
        
        /* Add data to Sheet 2 End */
        
        $footer = $count + 8 + 1;
        $footer2 = $footer + 1;
        $footer3 = $footer + 2;
        
        $last = $footer - 1;
        
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('A'.$footer, "Celkem za období")
                    ->setCellValue('B'.$footer, "=SUM(B9:B$last)")
                    ->setCellValue('C'.$footer, "=SUM(C9:C$last)")
                    ->setCellValue('D'.$footer, "=SUM(D9:D$last)")
                    ->setCellValue('E'.$footer, "=SUM(E9:E$last)")
                    ->setCellValue('F'.$footer, "=SUM(F9:F$last)")
                    ->setCellValue('G'.$footer, "=SUM(G9:G$last)")
                    ->setCellValue('H'.$footer, "=SUM(H9:H$last)")
                    ->setCellValue('I'.$footer, "=SUM(I9:I$last)")
                    ->setCellValue('J'.$footer, "=SUM(J9:J$last)")
                    ->setCellValue('K'.$footer, "=SUM(K9:K$last)")
                    ->setCellValue('L'.$footer, "=SUM(L9:L$last)")
                    ->setCellValue('M'.$footer, "=SUM(M9:M$last)")
                    ->setCellValue('N'.$footer, "=SUM(N9:N$last)")
                    ->setCellValue('O'.$footer, "=SUM(O9:O$last)")
                    ->setCellValue('P'.$footer, "=SUM(P9:P$last)")
                    ->setCellValue('A'.$footer2, "K tomu od zač. roku")
                    ->setCellValue('B'.$footer2, $this->before->getArray0301())
                    ->setCellValue('C'.$footer2, $this->before->getArray0302())
                    ->setCellValue('D'.$footer2, $this->before->getArray0303())
                    ->setCellValue('E'.$footer2, $this->before->getArray0304())
                    ->setCellValue('F'.$footer2, $this->before->getArray0305())
                    ->setCellValue('G'.$footer2, $this->before->getArray0306())
                    ->setCellValue('H'.$footer2, $this->before->getArray0309())
                    ->setCellValue('I'.$footer2, $this->before->getArray0310())
                    ->setCellValue('J'.$footer2, $this->before->getArray0311())
                    ->setCellValue('K'.$footer2, $this->before->getArray0312())
                    ->setCellValue('L'.$footer2, $this->before->getArray0313())
                    ->setCellValue('M'.$footer2, $this->before->getArray0314())
                    ->setCellValue('N'.$footer2, $this->before->getArray0315())
                    ->setCellValue('O'.$footer2, "")
                    ->setCellValue('P'.$footer2, $this->before->getArray0317())
                    ->setCellValue('A'.$footer3, "Celkem od zač. roku")
                    ->setCellValue('B'.$footer3, "=SUM(B$footer:B$footer2)")
                    ->setCellValue('C'.$footer3, "=SUM(C$footer:C$footer2)")
                    ->setCellValue('D'.$footer3, "=SUM(D$footer:D$footer2)")
                    ->setCellValue('E'.$footer3, "=SUM(E$footer:E$footer2)")
                    ->setCellValue('F'.$footer3, "=SUM(F$footer:F$footer2)")
                    ->setCellValue('G'.$footer3, "=SUM(G$footer:G$footer2)")
                    ->setCellValue('H'.$footer3, "=SUM(H$footer:H$footer2)")
                    ->setCellValue('I'.$footer3, "=SUM(I$footer:I$footer2)")
                    ->setCellValue('J'.$footer3, "=SUM(J$footer:J$footer2)")
                    ->setCellValue('K'.$footer3, "=SUM(K$footer:K$footer2)")
                    ->setCellValue('L'.$footer3, "=SUM(L$footer:L$footer2)")
                    ->setCellValue('M'.$footer3, "=SUM(M$footer:M$footer2)")
                    ->setCellValue('N'.$footer3, "=SUM(N$footer:N$footer2)")
                    ->setCellValue('O'.$footer3, "=SUM(O$footer:O$footer2)")
                    ->setCellValue('P'.$footer3, "=SUM(P$footer:P$footer2)");
        
        /* Formatovani */
        $this->cellColor("A1", '009EDB');
        
        $this->cellColor("O9:O$last", 'ffcccc');
        $this->cellColor("O$footer2", 'ffcccc');
        
        $this->objPHPExcel->getActiveSheet()->getStyle('B3:N3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
        $this->objPHPExcel->getActiveSheet()->getStyle('O4:P4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
        
        $this->objPHPExcel->getActiveSheet()->getStyle('B3:N3')->getAlignment()->setTextRotation(90);
        $this->objPHPExcel->getActiveSheet()->getStyle('O4:P4')->getAlignment()->setTextRotation(90);
        
        $this->objPHPExcel->getActiveSheet()->getStyle("A1:P$footer3")->applyFromArray($this->style2);
        
        $this->objPHPExcel->getActiveSheet()->getStyle('A8:P8')->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer:P$footer")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer2:P$footer2")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer3:P$footer3")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$last:P$last")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("P1:P$footer3")->applyFromArray($this->style4);
        
        $this->objPHPExcel->getActiveSheet()->getStyle("A1:P2")->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->getStyle("B8:P8")->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer:A$footer3")->getFont()->setBold(true);
        
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        
        /**/
        
    }
    
    private function sheet3(array $data)
    {
        
        $sheetIndex = 2;
        
        $this->objPHPExcel->createSheet($sheetIndex);
        $this->objPHPExcel->getSheet($sheetIndex)->setTitle('Služby');
        
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('A1:F1')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('A2:A5')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('B2:B5')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('C2:C5')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('D2:D5')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('E2:E5')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('F2:F5')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('A1', "Deník veřejné knihovny $this->from - $this->to služby uživatelům - pokračování")
                    ->setCellValue('A2', 'Datum')
                    ->setCellValue('B2', 'On-line informační služby (počet zodpovězených dotazů, např. mailem)')
                    ->setCellValue('C2', 'Informace')
                    ->setCellValue('D2', 'Tisk')
                    ->setCellValue('E2', 'Výstavy')
                    ->setCellValue('F2', 'Minuty na Internetu');
        
        /* Add data to Sheet 3 */
        
        $count = count($data);
        
        $i = 6;
        if($count > 0){
            foreach($data AS $key => $val){
                $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                        ->setCellValue('A'.$i, date("d. m.", strtotime($key)))
                        ->setCellValue('D'.$i, isset($val['slTisk']) ? $val['slTisk'] : "0");

                $i++;
            }
        }
        else{
            $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                        ->setCellValue("A$i", "");
            $count++;
        }
        
        /* Add data to Sheet 3 End */
        
        $footer = $count + 5 + 1;
        $footer2 = $footer + 1;
        $footer3 = $footer + 2;
        
        $last = $footer - 1;
        
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('A'.$footer, "Celkem za období")
                    ->setCellValue('B'.$footer, "=SUM(B6:B$last)")
                    ->setCellValue('C'.$footer, "=SUM(C6:C$last)")
                    ->setCellValue('D'.$footer, "=SUM(D6:D$last)")
                    ->setCellValue('E'.$footer, "=SUM(E6:E$last)")
                    ->setCellValue('F'.$footer, "=SUM(F6:F$last)")
                    ->setCellValue('A'.$footer2, "K tomu od zač. roku")
                    ->setCellValue('B'.$footer2, "")
                    ->setCellValue('C'.$footer2, "")
                    ->setCellValue('D'.$footer2, $this->before->getArrayTisk())
                    ->setCellValue('E'.$footer2, "")
                    ->setCellValue('F'.$footer2, "")
                    ->setCellValue('A'.$footer3, "Celkem od zač. roku")
                    ->setCellValue('B'.$footer3, "=SUM(B$footer:B$footer2)")
                    ->setCellValue('C'.$footer3, "=SUM(C$footer:C$footer2)")
                    ->setCellValue('D'.$footer3, "=SUM(D$footer:D$footer2)")
                    ->setCellValue('E'.$footer3, "=SUM(E$footer:E$footer2)")
                    ->setCellValue('F'.$footer3, "=SUM(F$footer:F$footer2)");
        
        /* Formatovani */
        $this->cellColor("A1", '009EDB');
        
        $this->objPHPExcel->getActiveSheet()->getStyle("A1:F$footer3")->applyFromArray($this->style2);
        
        $this->objPHPExcel->getActiveSheet()->getStyle("A1:F5")->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer:A$footer3")->getFont()->setBold(true);
        
        $this->objPHPExcel->getActiveSheet()->getStyle('A5:F5')->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer:F$footer")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer2:F$footer2")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer3:F$footer3")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$last:F$last")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("F1:F$footer3")->applyFromArray($this->style4);
        
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(24);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
        
        /**/
        
    }
    
    private function sheet4(array $data)
    {
        
        $sheetIndex = 3;
        
        $this->objPHPExcel->createSheet($sheetIndex);
        $this->objPHPExcel->getSheet($sheetIndex)->setTitle('Akce');
        
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('A1:O1')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('A2:A5')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('B2:I5')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('J2:K3')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('L2:M3')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('N2:O3')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('J4:K4')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('L4:M4')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('N4:O4')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells('B6:I6')->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('A1', "Deník veřejné knihovny $this->from - $this->to kulturní a vzdělávací akce")
                    ->setCellValue('A2', "Datum")
                    ->setCellValue('B2', "Název akce, místo, náplň akce (apod.)")
                    ->setCellValue('J2', "Kulturní akce pro veřejnost")
                    ->setCellValue('L2', "Vzdělávací akce pro školy")
                    ->setCellValue('N2', "Vzdělávací akce pro dospělé")
                    ->setCellValue('J4', "Počet")
                    ->setCellValue('L4', "Počet")
                    ->setCellValue('N4', "Počet")
                    ->setCellValue('J5', "akcí")
                    ->setCellValue('K5', "návštěvníků")
                    ->setCellValue('L5', "akcí")
                    ->setCellValue('M5', "návštěvníků")
                    ->setCellValue('N5', "akcí")
                    ->setCellValue('O5', "návštěvníků")
                    ->setCellValue('B6', "sl. 47")
                    ->setCellValue('J6', "sl. 48")
                    ->setCellValue('K6', "sl. 49")
                    ->setCellValue('L6', "sl. 50a")
                    ->setCellValue('M6', "sl. 51a")
                    ->setCellValue('N6', "sl. 50b")
                    ->setCellValue('O6', "sl. 51b");
        
        /* Add data to Sheet 4 */
        $count = 20;
        
        for($i = 7; $i < ($count+7); $i++){
            $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('J'.$i, "0");
            $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('K'.$i, "0");
            $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('L'.$i, "0");
            $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('M'.$i, "0");
            $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('N'.$i, "0");
            $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('O'.$i, "0");
            
            $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells("B$i:I$i")->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
            
        }
        
        /* Add data to Sheet 4 End */
        
        $footer = $count + 6 + 1;
        $footer2 = $footer + 1;
        $footer3 = $footer + 2;
        
        $last = $footer - 1;
        
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('A'.$footer, "Celkem za období")
                    ->setCellValue('J'.$footer, "=SUM(J7:J$last)")
                    ->setCellValue('K'.$footer, "=SUM(K7:K$last)")
                    ->setCellValue('L'.$footer, "=SUM(L7:L$last)")
                    ->setCellValue('M'.$footer, "=SUM(M7:M$last)")
                    ->setCellValue('N'.$footer, "=SUM(N7:N$last)")
                    ->setCellValue('O'.$footer, "=SUM(O7:O$last)")
                    ->setCellValue('A'.$footer2, "K tomu od zač. roku")
                    ->setCellValue('J'.$footer2, "")
                    ->setCellValue('K'.$footer2, "")
                    ->setCellValue('L'.$footer2, "")
                    ->setCellValue('M'.$footer2, "")
                    ->setCellValue('N'.$footer2, "")
                    ->setCellValue('O'.$footer2, "")
                    ->setCellValue('A'.$footer3, "Celkem od zač. roku")
                    ->setCellValue('J'.$footer3, "=SUM(J$footer:J$footer2)")
                    ->setCellValue('K'.$footer3, "=SUM(K$footer:K$footer2)")
                    ->setCellValue('L'.$footer3, "=SUM(L$footer:L$footer2)")
                    ->setCellValue('M'.$footer3, "=SUM(M$footer:M$footer2)")
                    ->setCellValue('N'.$footer3, "=SUM(N$footer:N$footer2)")
                    ->setCellValue('O'.$footer3, "=SUM(O$footer:O$footer2)");
        
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells("B$footer:I$footer")->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells("B$footer2:I$footer2")->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        $this->objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells("B$footer3:I$footer3")->getDefaultStyle()->applyFromArray($this->style)->getAlignment()->setWrapText(true);
        
        /* Formatovani */
        $this->cellColor("A1", '009EDB');
        
        $this->objPHPExcel->getActiveSheet()->getStyle("A1:O$footer3")->applyFromArray($this->style2);
        
        $this->objPHPExcel->getActiveSheet()->getStyle("A1:O6")->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer:A$footer3")->getFont()->setBold(true);
        
        $this->objPHPExcel->getActiveSheet()->getStyle('A6:O6')->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer:O$footer")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer2:O$footer2")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$footer3:O$footer3")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("A$last:O$last")->applyFromArray($this->style3);
        $this->objPHPExcel->getActiveSheet()->getStyle("O1:O$footer3")->applyFromArray($this->style4);
        
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(14);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        
        /**/
        
    }
    
    public function createFile()
    {
        if (PHP_SAPI == 'cli') throw new Exception('This example should only be run from a Web Browser');
        
        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Center")
                                        ->setLastModifiedBy("Center")
                                        ->setTitle("Report")
                                        ->setSubject("Report")
                                        ->setDescription("Report")
                                        ->setKeywords("report")
                                        ->setCategory("Statistics");
        // Styles
        $this->style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ) 
        );
        $this->style2 = array(
            'borders' => array(
              'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              )
            )
          );
        
        $this->style3 = array(
            'borders' => array(
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                ), 
            )
        );
        
        $this->style4 = array(
            'borders' => array(
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                ), 
            )
        );

        
        $data = array();
        
        /* Get data from methods to array */
        
        $result = $this->getSl1();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl1'] = $val['count'];
        }
        
        $result = $this->getSl2();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl2'] = $val['count'];
        }
        
        $result = $this->getSl5();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl5'] = $val['count'];
        }
        
        $result = $this->getSl6();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl6'] = $val['count'];
        }
        
        $result = $this->getSl10();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl10'] = $val['count'];
        }
        
        $result = $this->getSl11();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl11'] = $val['count'];
        }
        
        $result = $this->getSl12();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl12'] = $val['count'];
        }
        
        $result = $this->getSl13();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl13'] = $val['count'];
        }
        
        $result = $this->getSl14();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl14'] = $val['count'];
        }
        
        $result = $this->getSl15();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl15'] = $val['count'];
        }
        
        $result = $this->getSl18();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl18'] = $val['count'];
        }
        
        $result = $this->getSl19();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl19'] = $val['count'];
        }
        
        $result = $this->getSl20();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl20'] = $val['count'];
        }
        
        $result = $this->getSl21();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl21'] = $val['count'];
        }
        
        $result = $this->getSl22();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl22'] = $val['count'];
        }
        
        $result = $this->getSl23();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl23'] = $val['count'];
        }
        
        $result = $this->getSl24();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl24'] = $val['count'];
        }
        
        $result = $this->getSl26();
        foreach($result AS $key => $val){
           $data[$val['date']]['sl26'] = $val['count'];
        }
        
        $result = $this->getSlTisk();
        foreach($result AS $key => $val){
           $data[$val['date']]['slTisk'] = $val['count'];
        }
        
        ksort($data);
        
        /**/
        
        $this->sheet1($data);
        $this->sheet2($data);
        $this->sheet3($data);
        $this->sheet4($data);
    }
    
    public function saveAsExcel()
    {
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Deník_veřejné_knihovny.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
        //$objWriter->setPreCalculateFormulas(FALSE);
        $objWriter->save('php://output');
    }
    
    protected function getSl1()
    {
        try {

            $query = "SELECT count(`s`.`borrowernumber`) AS `count`, DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
                    ."LEFT JOIN `accountlines` `a` "
                    ."ON `a`.`accountlines_id` = `s`.`other` "
                    ."WHERE DATE(`s`.`datetime`) BETWEEN :from AND :to "
                    ."  AND `s`.`type` = 'payment' "
                    ."  AND `a`.`accounttype` = 'A' "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl2()
    {
        try {

            $query = "SELECT count(`s`.`borrowernumber`) AS 'count', DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
                    ."LEFT JOIN `accountlines` `a` "
                    ."  ON `a`.`accountlines_id` = `s`.`other` "
                    ."LEFT OUTER JOIN `borrowers` `b` "
                    ."  ON `b`.`borrowernumber` = `s`.`borrowernumber` "
                    ."WHERE DATE(`s`.`datetime`) BETWEEN :from AND :to "
                    ."  AND `s`.`type` = 'payment' "
                    ."  AND `a`.`accounttype` = 'A' "
                    ."  AND `b`.`dateofbirth` > DATE_SUB(`s`.`datetime`,INTERVAL 15 YEAR) "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl5()
    {
        try {

            $query = "SELECT COUNT(DISTINCT `b`.`borrowernumber`) AS `count`, DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
                    ."LEFT JOIN `borrowers` `b` "
                    ."  ON `b`.`borrowernumber` = `s`.`borrowernumber` "
                    ."WHERE DATE(`s`.`datetime`) BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('return', 'renew', 'issue', 'payment') "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl6()
    {
        try {

            $query = "SELECT SUM(`internet`) AS `count`, DATE(`datetime`) AS `date` "
                    ."FROM `cen_circulations` "
                    ."WHERE DATE(`datetime`) BETWEEN :from AND :to "
                    ."GROUP BY DATE(`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl10()
    {
        try {

            $query = "SELECT count(`datetime`) AS 'count', DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
                    ."WHERE `datetime` BETWEEN :from AND :to "
                    ."  AND `type` IN ('issue','renew') "
                    ."GROUP BY DATE(`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl11()
    {
        try {
            
            $ccodes = $this->ccodes["array"]["educationalLiteratureForAdults"];

            $query = "SELECT count(`s`.`datetime`) AS 'count', DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`ccode` IN ('".implode('\',\'', $ccodes)."') "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl12()
    {
        try {
            
            $ccodes = $this->ccodes["array"]["niceLiteratureForAdults"];

            $query = "SELECT count(`s`.`datetime`) AS 'count', DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`ccode` IN ('".implode('\',\'', $ccodes)."') "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl13()
    {
        try {
            
            $ccodes = $this->ccodes["array"]["educationalLiteratureForChildren"];

            $query = "SELECT count(`s`.`datetime`) AS 'count', DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`ccode` IN ('".implode('\',\'', $ccodes)."') "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl14()
    {
        try {
            
            $ccodes = $this->ccodes["array"]["niceLiteratureForChildren"];

            $query = "SELECT count(`s`.`datetime`) AS 'count', DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`ccode` IN ('".implode('\',\'', $ccodes)."') "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl15()
    {
        try {
            
            $type = $this->docCollections["periodics"];

            $query = "SELECT count(`s`.`datetime`) AS 'count', DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."') "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl18()
    {
        try {
            
            $type = $this->docCollections["cartographicDocuments"];

            $query = "SELECT count(`s`.`datetime`) AS 'count', DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."') "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl19()
    {
        try {

            $type = $this->docCollections["printedMusic"];
            
            $query = "SELECT count(`s`.`datetime`) AS 'count', DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."') "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl20()
    {
        try {

            $type = $this->docCollections["audioDocuments"];
            
            $query = "SELECT count(`s`.`datetime`) AS 'count', DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."') "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl21()
    {
        try {

            $type = $this->docCollections["audioVisualDocuments"];
            
            $query = "SELECT count(`s`.`datetime`) AS 'count', DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."') "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl22()
    {
        try {

            $type = $this->docCollections["visualDocuments"];
            
            $query = "SELECT count(`s`.`datetime`) AS 'count', DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."') "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl23()
    {
        try {

            $type = $this->docCollections["electronicDocuments"];
            
            $query = "SELECT count(`s`.`datetime`) AS 'count', DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` IN ('".implode('\',\'', $type)."') "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl24()
    {
        try {

            $types = $this->docTypes;
            
            $query = "SELECT count(`s`.`datetime`) AS 'count', DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
		    ."LEFT JOIN `items` `i` "
		    ."ON `s`.`itemnumber` = `i`.`itemnumber` "
                    ."WHERE `s`.`datetime` BETWEEN :from AND :to "
                    ."  AND `s`.`type` IN ('issue','renew') "
		    ."  AND `i`.`itype` NOT IN ('".implode('\',\'', $types)."') "
                    ."GROUP BY DATE(`s`.`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSl26()
    {
        try {

            $query = "SELECT count(`datetime`) AS 'count', DATE(`datetime`) AS `date` "
                    ."FROM `statistics` "
                    ."WHERE `datetime` BETWEEN :from AND :to "
                    ."  AND `type` IN ('renew') "
                    ."GROUP BY DATE(`datetime`)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->from, PDO::PARAM_STR);
            $stmt->bindValue(':to', $this->to, PDO::PARAM_STR);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {

            Debugger::Log($ex->getMessage());
            varDump($ex->getMessage());

        }
        return $results;
    }
    
    protected function getSlTisk()
    {
        try {
            
            $catPrints = $this->catPrints;

            $query = "SELECT count(*) as `count`, DATE(`s`.`datetime`) AS `date` "
                    ."FROM `statistics` `s` "
                    ."JOIN `accountlines` `a` "
                    ."  ON `a`.`accountlines_id` = `s`.`other` "
                    ."WHERE DATE(`s`.`datetime`) BETWEEN :from AND :to "
                    ."  AND `s`.`type` = :type "
                    ."  AND `a`.`accounttype` IN ('".implode('\',\'', $catPrints)."') "
                    ."GROUP BY DATE(`s`.`datetime`)";

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
        return $results;
    }
    
    function cellColor($cells, $color){

        $this->objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                 'rgb' => $color
            )
        ));
        
    }
    
}
