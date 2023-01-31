<?php
require_once(dirname(__FILE__).'/tcpdf.php');

class quotePdf extends TCPDF {
 
    var $quotation;
    // var $customerId;
    // private $subTotal;
    private $discount;
    private $total;

    public function setQuotation($quotation) {
        $this->quotation = $quotation;
    }

    public function Header() {
        $quotation = $this->quotation;
        $this->setJPEGQuality(100);
        $image_file = 'http://localhost'.Yii::app()->request->baseUrl.'/images/dost_logo.jpg';
        $this->Image($image_file, 6, 7, 25, '', 'JPEG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetAlpha(1);
        $headDetails = array(
            'header1'=>'Republic of the Philippines',
            'header2'=>'DEPARTMENT OF SCIENCE AND TECHNOLOGY',
            'header3'=>'REGIONAL OFFICE NO. III',
            'header4'=>'REGIONAL STANDARDS AND TESTING LABORATORY',
            'header5'=>'QUOTATION',
            'quoteNo'=>'Quotation No.: '.$quotation->quotationCode,
            'date'=>'Date: '.date("F d, Y",strtotime($quotation->requestDate)),
        );
        
        $this->SetFont('helveticaB','B',12);
        $this->MultiCell(200, 0, $headDetails['header1'], 0, 'L', 0, 1, 33.5, 8, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helveticaB','B',16);
        $this->SetTextColor(100, 0, 0, 0);
        $this->MultiCell(200, 0, $headDetails['header2'], 0, 'L', 0, 1, 33.5, 13, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helvetica','',14);
        $this->MultiCell(200, 0, $headDetails['header3'], 0, 'L', 0, 1, 33.5, 19.5, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helvetica','',14);
        $this->SetTextColor(100, 100, 100, 100);
        $this->MultiCell(200, 0, $headDetails['header4'], 0, 'L', 0, 1, 33.5, 25.5, true, 0, true, true, 0, 'T', false);
        $pageWidth = $this->getPageWidth() - 7;
        $liney = 34;
        $style = array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $this->Line(6, $liney, $pageWidth, $liney, $style);
        $line2y = 35;
        $style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $this->Line(6, $line2y, $pageWidth, $line2y, $style);
        $this->SetFont('helvetica','B',15);
        $this->MultiCell(208, 0, $headDetails['header5'], 0, 'C', 0, 1, 1, 36.5, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helvetica','',11);
        $this->MultiCell(197, 0, $headDetails['quoteNo'], 0, 'L', 0, 1, 6, 44.5, true, 0, true, true, 0, 'T', false);
        //$this->MultiCell(197, 0, $headDetails['date'], 0, 'R', 0, 1, 6, 44.5, true, 0, true, true, 0, 'T', false);
    }

    public function printRows() {
        $quotation = $this->quotation;
        $dateRequest = date("F d, Y",strtotime($quotation->requestDate));
        $this->SetFont('helvetica','',10);
        $this->MultiCell(197, 0, $dateRequest, 0, 'R', 0, 1, 6, 44.5, true, 0, true, true, 0, 'T', false);
        $lineBreak = 6;
        $class = '
                <style>
                  table{
                    font-style: arial;
                    width: 100%;
                    font-size:10px;
                    padding:2px;
                  }
                  table#customerInfo{
                    font-style: arial;
                    width: 100%;
                    font-size:10px;
                    padding:2px;
                  }
                  table.border1{
                    border: 0.5px solid #000;
                  }
                  table#services td{
                    border: 0.5px solid #000;
                  }
                  table#services th{
                    border: 0.5px solid #000;
                    vertical-align: middle;
                    text-align:center;
                    
                  }
                 
                </style>
            ';
        $strAddress = strlen($quotation->address);
        if($strAddress > 55){
            $lineBreak = $lineBreak+1;
        }
        $strCompany = strlen($quotation->company);
        if($strCompany > 55){
            $lineBreak = $lineBreak+1;
        }
        $html = '
                <table><tr><td height="1"><b>CUSTOMER INFORMATION</b></td></tr></table>    
                <table id="customerInfo" class="border1" border="0">
                    <tr>
                        <td width="55%">Company: &nbsp;&nbsp;&nbsp;&nbsp;'.$quotation->company.'<br/>
                            Address: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$quotation->address.'<br/>
                            Contact No.: '.$quotation->contact_number.'
                        </td>
                        <td width="45%">Contact Person: '.$quotation->contact_person.'<br/>
                            Designation: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$quotation->designation.'<br/>
                            Email: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$quotation->email.'
                        </td>
                    </tr>
                </table>
                <table><tr><td height="1"><b>SERVICE DETAILS</b></td></tr></table>
                <table id="services" border="1">
                    <tr>
                        <th width="30%">SAMPLE NAME & DESCRIPTION</th>
                        <th width="30%">TEST/CALIBRATION REQUESTED</th>
                        <th width="10%">QTY</th>
                        <th width="15%">UNIT PRICE</th>
                        <th width="15%">TOTAL AMOUNT</th>
                    </tr>
            ';
            $subTotal = 0;
            if($quotation->samples){
                foreach ($quotation->samples as $samples) {
                    $strsamplename = strlen($samples->sampleName);
                    $html .='<tr><td>'.$samples->sampleName.'</td>';
                    $testCount = 0;
                    foreach ($samples->tests as $tests) {
                        $totalFee = $samples->qty * $tests->fee;
                        $strtestname = strlen($tests->testName);
                        if($testCount != 0){
                            $html .= '<tr><td></td>
                                    <td>'.$tests->testName.'</td>
                                    <td align="center">'.$samples->qty.'</td>
                                    <td align="right">'.Yii::app()->format->formatNumber($tests->fee).'</td>
                                    <td align="right">'.Yii::app()->format->formatNumber($totalFee).'</td></tr>';
                        }else{
                            $html .= '<td>'.$tests->testName.'</td>
                                    <td align="center">'.$samples->qty.'</td>
                                    <td align="right">'.Yii::app()->format->formatNumber($tests->fee).'</td>
                                    <td align="right">'.Yii::app()->format->formatNumber($totalFee).'</td></tr>';
                        }
                        $testCount = $testCount + 1;
                        $subTotal = $subTotal + $totalFee;

                        if($strtestname >= 70 || $strsamplename >= 70){
                            $lineBreak = $lineBreak + 3;
                        }elseif($strtestname >= 34 || $strsamplename >= 34){
                            $lineBreak = $lineBreak + 2;
                        }else{
                            $lineBreak = $lineBreak + 1;
                        }
                    }
                }
            }else{
                $html .= '<tr><td colspan="5"><i>No Samples</i></td></tr>';
            }
                
            $html .= '<tr>
                            <td width="70%" colspan="3" align="justify">Remarks: '.$quotation->remarks.'</td>
                            <td align="right" width="15%">
                                Sub Total:<br/>
                                Discount:<br/>
                                On-site Charge:<br/>
                                Grand Total:
                            </td>      
                            <td align="right" width="15%">
                                '.Yii::app()->format->formatNumber($subTotal).'<br/>
                                '.Yii::app()->format->formatNumber($quotation->discounted).'<br/>
                                '.Yii::app()->format->formatNumber($quotation->onsite_charge).'<br/>
                                '.Yii::app()->format->formatNumber($quotation->total).'
                            </td>
                        </tr>
                    </table>';
            $strlenRemarks = strlen($quotation->remarks);
            if($strlenRemarks > 305){
                $lineBreak = $lineBreak + 2;
            }
            elseif($strlenRemarks > 275){
                $lineBreak = $lineBreak + 1;
            }

            $lineBreak = $lineBreak + 4;
            if($quotation->onsite_charge!='0.00'){
                $onsite = '<p style="margin:1px 0px;"><b><i>'.$quotation->company.'</i></b> shall provide the transportation from DOST 3 to the site and vise versa of calibration officers / Analyst (1 or 2 persons) and equipment and/or standard.</p>';
                $lineBreak = $lineBreak + 3;
            }else{
                $onsite = '';
            }

            $html .= '<table id="quoteRemarks">
                <tr>
                    <td colspan="2" align="justify">'.$onsite.'<p style="margin:1px 0px;">Test/Calibration fees may be paid to the Cashier of the DOST Regional Office No. 3, DM Government Center, Maimpis, City of San Fernando, Pampanga either in cash or in check payable to DOST III.</p>
                        <p style="margin:1px 0px;">DOST-III is a regular government agency and is exempted from payment of VAT and withholding taxes as per Section 2.57.5 of BIR Revenue Regulation No. 2-98. Should you have queries, please feel free to contact us at telephone number (045) 455-0594 or cellphone number 0933 818 3317 / 0915 600 8391.</p>';
            $nbPage = $this->getAliasNbPages();
            // $lineBreak = $lineBreak + 5;
            $getRemainLine = $lineBreak / 41;
            $remainLine = round($getRemainLine);
            
            if($remainLine < 2){
                if($lineBreak >= 29){
                     $breakLines = 41 - $lineBreak -7;
                    for($i=0;$i<$breakLines;$i++){
                        $html .= '<br> &nbsp;';
                    }
                }
            }else{
                $lastPageLine = $remainLine-1;
                if($lastPageLine <= 1 ){
                    $lineBreak = $lineBreak - 41;
                    if($lineBreak >= 25){
                        $breakLines = 41 - $lineBreak -7;
                        for($i=0;$i<$breakLines;$i++){
                            $html .= '<br> &nbsp;';
                        }
                    }       
                }else{
                    for($i=0;$i<$lastPageLine;$i++){
                        $lineBreak = $lineBreak - 41;
                    }
                    if($lineBreak >= 23){
                        $breakLines = 41 - $lineBreak -7;
                        for($i=0;$i<$breakLines;$i++){
                            $html .= '<br> &nbsp;';
                        }
                    }  
                }
            }
            

            $html .= '<p style="margin:1px 0px;">Thank you very much and we are looking forward to be of service to you soon.</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <br/><br/>Prepared by:<br/><br/><br/><b>'.strtoupper($quotation->created_by).'</b><br />
                        '.$quotation->created_byPos.'
                    </td>
                    <td>
                        <br/><br/>Noted by:<br/><br/><br/><b>'.strtoupper($quotation->noted_by).'</b><br />
                        '.$quotation->noted_byPos.'
                    </td>
                </<tr>
            </table>';


        // $this->MultiCell(199,'',5,50,$class.$html, 0, 2);
        $this->MultiCell(197, 0, $class.$html, 0, 'L', 0, 0, 5, 50, true, 0, true, true, 0, 'T', false);
        
    }

    public function Footer() {

        $pageWidth = $this->getPageWidth() - 7;
        $liney = 265;
        $style = array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $this->Line(6, $liney, $pageWidth, $liney, $style);
        $line2y = 264;
        $style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $this->Line(6, $line2y, $pageWidth, $line2y, $style);
        $footDetails = array(
            'footer1'=>'Postal Address:',
            'footer2'=>'Diosdado Macapagal Regional',
            'footer3'=>'Government Center, Maimpis',
            'footer4'=>'City of San Fernando, 2000 Pampanga',
            'footer5'=>'URL: http://www.region3.dost.gov.ph',
            'footer6'=>'E-mail address: dost3@dost.gov.ph',
            'footer7'=>'OP-006-F01',
            'footer8'=>'Rev.0',
            'footer9'=>'28 September 2018'
        );
        //pagination
        $numPage = $this->getAliasNumPage();
        $nbPage = $this->getAliasNbPages();
        $paging = 'Page '.$numPage.' of '.$nbPage;

        $this->SetFont('helveticaB','',8);
        $this->MultiCell(197, 0, $footDetails['footer7'], 0, 'R', 0, 1, 6, 253, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(197, 0, $footDetails['footer8'], 0, 'R', 0, 1, 6, 256, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(197, 0, $footDetails['footer9'], 0, 'R', 0, 1, 6, 259.5, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helveticaB','',10);
        $this->MultiCell(197, 0, $paging, 0, 'C', 0, 1, 15, 258.5, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helveticaB','BI',10);
        $this->MultiCell(200, 0, $footDetails['footer1'], 0, 'L', 0, 1, 6, 266, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helveticaB','B',10);
        $this->MultiCell(200, 0, $footDetails['footer2'], 0, 'L', 0, 1, 6, 270, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(200, 0, $footDetails['footer3'], 0, 'L', 0, 1, 6, 274, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(200, 0, $footDetails['footer4'], 0, 'L', 0, 1, 6, 278, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(200, 0, $footDetails['footer5'], 0, 'L', 0, 1, 6, 282, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(200, 0, $footDetails['footer6'], 0, 'L', 0, 1, 6, 286, true, 0, true, true, 0, 'T', false);
        $footDetails2 = array(
            'footer1'=>'Telefax Nos.:',
            'footer2'=>'ORD.         :(045) 455-0800',
            'footer3'=>'Admin.       :(045) 455-1686',
            'footer4'=>'Tech.        :(045) 455-1733',
            'footer5'=>'Scholarship  :(045) 455-2348',
            'footer6'=>'RSTL         :(045) 455-0594',
        );
         $this->SetFont('helveticaB','BI',10);
        $this->MultiCell(200, 0, $footDetails2['footer1'], 0, 'R', 0, 1, 3, 266, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helveticaB','B',10);
        $this->MultiCell(200, 0, $footDetails2['footer2'], 0, 'R', 0, 1, 3, 270, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(200, 0, $footDetails2['footer3'], 0, 'R', 0, 1, 3, 274, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(200, 0, $footDetails2['footer4'], 0, 'R', 0, 1, 3, 278, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(200, 0, $footDetails2['footer5'], 0, 'R', 0, 1, 3, 282, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(200, 0, $footDetails2['footer6'], 0, 'R', 0, 1, 3, 286, true, 0, true, true, 0, 'T', false);
    }
}
?>
