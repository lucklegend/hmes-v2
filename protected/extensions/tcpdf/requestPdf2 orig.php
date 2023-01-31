<?php
require_once(dirname(__FILE__).'/tcpdf.php');

class requestPdf2 extends TCPDF {
 
    var $request;
    var $customerId;
    private $subTotal;
    private $discount;
    private $total;

    public function setRequest($request) {
        $this->request = $request;
    }

    public function setCustomer($customer) {
        $this->customer = $customer;
    }
 
    public function Header() {

        //$request = Request::model()->findByPk(6);
        $request = $this->request;

        $headerdata = array(
                'agencyName'=>Yii::app()->params['Agency']['name'],
                'rstl'=>'<b>'.strtoupper(Yii::app()->params['Agency']['labName']).'</b>',
                'agencyAddress'=>Yii::app()->params['Agency']['address'],
                'agencyContactInfo'=>'Contact No. '.Yii::app()->params['Agency']['contacts'],
                'formTitle'=>'<b>'.Yii::app()->params['FormRequest']['title'].'<b>'
            );

        $headDetails = array(
            'header1'=>'Republic of the Philippines',
            'header2'=>'Department of Science and Technology',
            'header3'=>'DOST Regional Office III',
            'header4'=>'Government Center, Maimpis, City of San Fernando, Pampanga',
            'header5'=>'TECHNICAL SERVICE REQUEST',
            'header6'=>'Contact Nos:(045) 455-0594, (045) 455-0800',
        );
        // set JPEG quality
        $this->setJPEGQuality(100);

        // $image_file = '*http://localhost'.Yii::app()->request->baseUrl.'/images/dost_logo.png';
        $image_file = 'http://localhost'.Yii::app()->request->baseUrl.'/images/dost_logo.jpg';
        // echo $image_file;
        // Image method signature:
        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
        
        $this->Image($image_file, 10, 10, 25, '', 'JPEG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetAlpha(1);

        $this->SetFont('helvetica','B',9);
        $this->MultiCell(200, 0, $headDetails['header1'], 0, 'C', 0, 1, 5, 8, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(200, 0, $headDetails['header2'], 0, 'C', 0, 1, 5, 11.25, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helvetica','B',17);
        $this->MultiCell(200, 0, $headDetails['header3'], 0, 'C', 0, 1, 5, 14.50, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helvetica','',9);
        $this->MultiCell(200, 0, $headDetails['header4'], 0, 'C', 0, 1, 5, 21.75, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helvetica','',8);
        $this->MultiCell(200, 0, $headDetails['header6'], 0, 'C', 0, 1, 5, 25.75, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helvetica','',12);
        $this->MultiCell(200, 0, $headDetails['header5'], 0, 'C', 0, 1, 5, 31.75, true, 0, true, true, 0, 'T', false);     
        
        $this->SetFont('helvetica','',12);
        $this->MultiCell(200, 0, '<br/><br/>'.$headerdata['formTitle'], 0, 'C', 0, 1, 5, 25, true, 0, true, true, 0, 'T', false);
        
        $this->SetFont('helvetica','',9);
        $dateMake = date("F d, Y",strtotime($request->requestDate));

            $class = '
                <style>
                  table {
                    font-style: arial;
                    width: 100%;
                    padding-right: 15px;
                  }
                </style>
            ';

            $html = '
                <table>
                    <tr>
                        <td width="50%">TSR No.: '.$request->requestRefNum.'</td>
                        <td width="50%" align="right">'.$dateMake.'</td>
                    </tr>
                </table>
            ';
            $this->writeHTMLCell('','',5,38,$class.$html, 0, 2);

            $this->SetFont('helvetica','B',9);
            $this->MultiCell(200, 0, 'CUSTOMER DETAILS', 0, 'L', 0, 1, 6, 44, true, 0, true, true, 0, 'T', false);    

            $this->SetFont('helvetica','',9);
            ### CUSTOMER ###
            $classCustomer = '
                <style>
                  table#main_table {
                    font-style: arial;
                    border: 0.5px solid #000;
                    padding-left: 2px;
                    width: 567px;
                  }
                  td{
                    text-align: left;
                    valign: middle;
                  }
                </style>
            ';
            if(!empty($request->customer->district) || $request->customer->district < 0){
                $district =  'District '.$request->customer->district;
            }else{
                $district = 'Lone District';
            }

            if($request->customer->barangay_id > 0){
                $brgyaddress = $request->customer->address. ', '.$request->customer->barangay->name;
            }else{
                $brgyaddress = $request->customer->address;
            }

            
            if(strlen($request->customer->industry->classification)>36){
                $cusSector = substr($request->customer->industry->classification, 0, 35).'...';
            }else{
                $cusSector = $request->customer->industry->classification;
            }
            if($request->addforcert == NULL || $request->addforcert == ''){
                $customerHead = $request->customer->head;
            }else{
                $customerHead = $request->addforcert;
            }
            if($request->contact_number == NULL || $request->addforcert == ''){
                $contactNum = $request->customer->tel;
            }else{
                $contactNum = $request->contact_number;
            }
            if(strlen($request->customer->customerName)>=35 && strlen($brgyaddress)>=35){
                //move district
                $customerDetails = '
                    <table id="main_table">
                        <tr>
                            <td width="50%" style="padding-left:0px;"><table cellspacing="0" cellpadding="0"><tr><td width="40%">Customer Representative:</td><td width="60%">'.$request->conforme.'</td></tr><tr><td width="40%">Designation: </td><td width="60%">'.$request->conforme_designation.'</td></tr><tr><td width="100%">Company:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$request->customer->customerName.'</td></tr><tr><td width="100%">Address:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$brgyaddress.'</td></tr><tr><td width="40%">City/Municipality:</td><td width="60%">'.$request->customer->municipality->name.'</td></tr><tr><td width="40%">Province:</td><td width="60%">'.$request->customer->province->name.'</td></tr> </table> </td> 
                            <td width="50%"> <table cellspacing="0" cellpadding="0"> <tr> <td width="40%">Addressee for Certificate:</td> <td width="60%">'.$customerHead.'</td> </tr> <tr> <td width="100%">Contact Number: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$contactNum.'</td> </tr> <tr> <td width="40%">Email Address:</td> <td width="60%">'.$request->customer->email.'</td> </tr> <tr> <td width="40%">Customer Type:</td> <td width="60%">'.$request->customer->customertype->type.'</td> </tr> <tr> <td width="40%">Sector:</td> <td width="60%">'.$cusSector.'</td> </tr><tr><td width="40%">District:</td><td width="60%">'.$district.'</td></tr> </table> </td>
                        </tr> 
                    </table>
                    <table><tr><td height="10"></td></tr><table>
                ';    
            }else{
                 $customerDetails = '
                    <table id="main_table">
                        <tr>
                            <td width="50%" style="padding-left:0px;"><table cellspacing="0" cellpadding="0"><tr><td width="40%">Customer Representative:</td><td width="60%">'.$request->conforme.'</td></tr><tr><td width="40%">Designation: </td><td width="60%">'.$request->conforme_designation.'</td></tr><tr><td width="100%">Company:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$request->customer->customerName.'</td></tr><tr><td width="100%">Address:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$brgyaddress.'</td></tr><tr><td width="40%">City/Municipality:</td><td width="60%">'.$request->customer->municipality->name.'</td></tr><tr><td width="40%">Province:</td><td width="60%">'.$request->customer->province->name.'</td></tr><tr><td width="40%">District:</td><td width="60%">'.$district.'</td></tr> </table> </td> 
                            <td width="50%"> <table cellspacing="0" cellpadding="0"> <tr> <td width="40%">Addressee for Certificate:</td> <td width="60%">'.$customerHead.'</td> </tr> <tr> <td width="100%">Contact Number: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$contactNum.'</td> </tr> <tr> <td width="40%">Email Address:</td> <td width="60%">'.$request->customer->email.'</td> </tr> <tr> <td width="40%">Customer Type:</td> <td width="60%">'.$request->customer->customertype->type.'</td> </tr> <tr> <td width="40%">Sector:</td> <td width="60%">'.$cusSector.'</td> </tr> </table> </td>
                        </tr> 
                    </table>
                    <table><tr><td height="10"></td></tr><table>
                ';    
            }
            
            $this->writeHTMLCell('', '', 5, 48, $classCustomer.$customerDetails, 0, 2);


            ##### 1. TESTING OR CALIBRATION SERVICE #####
            $classTestingTitle = '
                <style>
                  div {
                    font-size: 9;
                  }
                </style>
                ';
            
            $classTestingHeader = '
                <style>
                  table {
                    font-style: arial;
                    border: 0.5px solid #000;
                    width: 97.5%;
                  }

                  th{
                    border: 0.5px solid #000;
                    
                    vertical-align: middle;
                    
                  }
                  td{
                    border: 0.5px solid #000;
                    text-align: left;
                    valign: middle;
                    vertical-align: middle;
                    display: table-cell;
                  }
                </style>
            ';

            $testingTitle = '
                <div><b>SERVICE DETAILS</b></div>
            ';
            if($request->labId == 3){
            $testingHeader = '
                <table>
                    <tr>
                        <th width="103" valign="middle">SAMPLE CODE</th>
                        <th width="100" align="center" valign="middle" >SAMPLE NAME</th>
                        <th width="120" valign="middle">TEST/CALIBRATION & METHOD REQUESTED</th>
                        <th width="114" valign="middle">DESCRIPTION</th>
                        <th width="80" valign="middle">REMARKS*</th>
                        <th width="50" valign="middle">FEE</th>
                    </tr>
                </table>
            ';
            }else{
            $testingHeader = '
                <table>
                    <tr>
                        <th width="103" valign="middle">SAMPLE CODE</th>
                        <th width="100" align="center" valign="middle" >SAMPLE NAME</th>
                        <th width="120" valign="middle">TEST/CALIBRATION & METHOD REQUESTED</th>
                        <th width="114" valign="middle">DESCRIPTION</th>
                        <th width="80" valign="middle">REMARKS</th>
                        <th width="50" valign="middle">FEE</th>
                    </tr>
                </table>
            ';    
            }
            

            $this->writeHTMLCell('','',5,80.5,$classTestingTitle.$testingTitle, 0, 2);   
            $this->writeHTMLCell('','',5,84.5,$classTestingHeader.$testingHeader, 0, 2,false, true, 'C', true);   
    }

    public function printRows() {
        $request = $this->request;

        $classRows = '
                <style>
                  table {
                    font-style: arial;
                    width: 60%;
                  }
                  td.border1{
                    border: 0.5px solid #000;                    
                  }
                </style>
            ';
        $rows = '<table >';
            $sampleCount = 0;
            $subTotal = 0;
            foreach($request->samps as $sample){
                $exSampleCode = explode("-", $sample->sampleCode);
                $exSampleCode = substr($exSampleCode[1], 1, 3);
                $rows .='
                    <tr nobr="true">
                        <td style="width: 103px; text-align: center" class="border1">'.$request->requestRefNum.'-'.$exSampleCode.'</td>
                        <td width="100" style="valign:center;" class="border1">'.$sample->sampleName.'</td>                        
                        ';
                        $analysisCount = 0;
                        foreach($sample->analyses as $analysis){
                            if($analysisCount != 0){

                                if($analysis->method == "-"){
                                    $tm = "";
                                }else{
                                    $tm = $analysis->method;
                                }
                                if($analysis->references == "-" || $analysis->references == "To be update" || $analysis->references == "To be updated" || $analysis->references =="To Be Updated"){
                                    $an = "";
                                }else{
                                    $an = ", ".$analysis->references;
                                }
                                if($an=="" && $tm==""){
                                    $testMethods = $analysis->testName;    
                                }else{
                                    $testMethods = $analysis->testName." [".$tm.$an."]";
                                }

                                $rows .='
                                    <tr nobr="true">
                                    <td width="103" class="border1"></td>
                                    <td width="100" class="border1"></td>
                                    <td width="120" class="border1">'.$testMethods.'</td>
                                    <td width="114" class="border1">'.$otherServices.'</td>
                                    <td width="80" class="border1"></td>
                                    <td style="width: 50px; text-align: right" class="border1">'.Yii::app()->format->formatNumber($analysis->fee).'</td>
                                    ';
                            }else{
                                if($sample->remarks==""||$sample->remarks==NULL){
                                    $newRemarks='<span align="center">~</span>';
                                }else{
                                    $newRemarks=$sample->remarks;
                                    if($newRemarks == "Drinking Water"){
                                        $newRemarks = '<span style="color:#FFA500;">'.$newRemarks.'</span>';
                                    }

                                }
                                if($sample->samplingDate == "0000-00-00"){
                                    $samplingEdate="";
                                }else if ($sample->samplingDate == "1970-01-01") {
                                    $samplingEdate="";
                                }else{
                                    $samplingEdate=" / ".$sample->samplingDate;
                                }

                                if($analysis->method == "-"){
                                    $tm = "";
                                }else{
                                    $tm = $analysis->method;
                                }
                                if($analysis->references == "-" || $analysis->references == "To be update" || $analysis->references == "To be updated" || $analysis->references =="To Be Updated"){
                                    $an = "";
                                }else{
                                    $an = ", ".$analysis->references;
                                }
                                if($an=="" && $tm==""){
                                    $testMethods = $analysis->testName;    
                                }else{
                                    $testMethods = $analysis->testName." [".$tm.$an."]";
                                }
                                
                                $rows .= '
                                    <td width="120" class="border1">'.$testMethods.'</td>
                                    <td width="114" class="border1">'.$sample->description.$samplingEdate.'</td>
                                    <td width="80" class="border1">'. $newRemarks.'</td>
                                    <td style="width: 50px; text-align: right" class="border1">'.Yii::app()->format->formatNumber($analysis->fee).'</td>
                                    ';
                            }
                            $rows .='</tr>';
                            $analysisCount = $analysisCount + 1;
                            $subTotal = $subTotal + $analysis->fee;
                        }
                        
            }
            $rows .='</table>
                <table border="0">
                    <tr>
                        <td width="437" style="border: none;" colspan="4"></td>
                        <td width="80" style="border: 1px solid black; text-align: right;" class="border1">Sub-Total</td>
                        <td width="50" style="border: 1px solid black; text-align: right;" class="border1">'.Yii::app()->format->formatNumber($subTotal).'</td>
                    </tr>';
        
        $discount = $subTotal * $request->disc->rate/100;
        $inplantcharge = $request->inplant_charge;
        $additional = $request->additional;
        $total = ($inplantcharge + $additional) + ($subTotal - $discount);
        /*making it global*/
        $this->subTotal = $subTotal;
        $this->discount = $discount;
        $this->total = $total;
       
        $rows .= '</table>';
        if($request->labId == 3){
             $rows .= '<br/><br/><table width="100%">
                    <tr>
                        <td><br/>
                        *For calibration services, <b>Calibration Interval</b> is indicated only when requested by the customer.
                        </td>
                    </tr>
                  <table>';
        }
       

        $this->SetFont('helvetica', '', 8);
        $this->SetMargins('', 92.5, 10, 10);
        $this->writeHTMLCell(200,'',4,92.5,$classRows.$rows, 0, 2);
            
    }

    public function Footer() {

        $request = $this->request;

        /*page Number*/
        $this->SetFont('helvetica', '', 9);

        //$paging = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();

        //$this->getAliasNumPage()
        //$this->getAliasNbPages()

        $numPage = $this->getAliasNumPage();
        $nbPage = $this->getAliasNbPages();
        $paging = 'Page '.$numPage.' of '.$nbPage;

        $w_page = isset($this->l['w_page']) ? $this->l['w_page'].' ' : '';
        if (empty($this->pagegroups)) {
            $pagenumtxt = $w_page.$this->getAliasNumPage().' / '.$this->getAliasNbPages();
        } else {
            $pagenumtxt = $w_page.$this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
        }
        //$this->SetY($cur_y)
        //Print page number
        if ($this->getRTL()) {
            //$this->SetX($this->original_rMargin);
            $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');
        } else {
            //$this->SetX($this->original_lMargin);
            $this->Cell(0, 0, $this->getAliasRightShift().$pagenumtxt, '', 0, 'L');
        }

         $formDetails = '
            <style>
                table {
                    font-style: arial;
                    padding-left: 2px;
                    width: 97.6%;
                  }
                  td{
                    valign: middle;
                    text-align: right;
                  }
            </style>
            <table>
                <tr>
                    <td style="text-align:center;" width="100%">'.$paging.'</td>
                    <!--<td>'.Yii::app()->params["FormRequest"]["number"].'</td>-->
                </tr>
            </table>
        ';

        $this->writeHTMLCell(205,'',5,238,$formDetails);

        /*end of page Number*/

        $this->SetFont('helvetica','',9);

        $classORDetails = '
            <style>
                  table {
                    font-style: arial;
                    padding-left: 2px;
                    border: 0.5px solid #000;
                    //border-left: 0.5px solid #000;
                    width: 97.5%;
                  }
                  td{
                    //border-bottom: 0.5px solid #000;
                    //border-right: 0.5px solid #000;
                    text-align: left;

                    valign: middle;
                  }
            </style>
        ';
        if($request->validated_by == '' || $request->validated_by == NULL){
            if(isset($request->laboratory->manager->user))
                $labManager=$request->laboratory->manager->user->getFullname(); 
        }else{
            $labManager=$request->validated_by;
        }
        if($this->discount != '0.00'){
            $discounted = '('.$request->disc->type.' '.number_format($request->disc->rate,0).'%) -'.Yii::app()->format->formatNumber($this->discount);
        }else{
            $discounted = '0.00';
        }
        $ORdetails = '
            <table>
                <tr>
                    <td width="70">Transmission:</td>
                    <td width="229">'.$request->transmission.'</td>
                    <td width="112">Total Fees:</td>
                    <td width="156" align="right">'.Yii::app()->format->formatNumber($this->subTotal).'</td>
                </tr>
                <tr>
                    <td width="70">Due Date:</td>
                    <td width="229">'.Yii::app()->dateFormatter->format('EEEE, MMMM d, yyyy', strtotime($request->reportDue)).'</td>
                    <td width="112">Discount:</td>
                    <td width="156" align="right">'.$discounted.'</td>
                </tr>
                <tr>
                    <td width="70">Prepared by:</td>
                    <td width="229">'.$request->receivedBy.'</td>
                    <td width="112">On-site Charge:</td>
                    <td width="156" align="right">'.Yii::app()->format->formatNumber($request->inplant_charge).'</td>
                </tr>
                <tr>
                    <td width="70">Validated by:</td>
                    <td width="229">'.$labManager.'</td>
                    <td width="112">Additional:</td>
                    <td width="156" align="right">'.Yii::app()->format->formatNumber($request->additional).'</td>
                </tr>
                <tr>
                    <td width="70"></td>
                    <td width="229"></td>
                    <td width="112">Grand Total:</td>
                    <td width="156" align="right">'.Yii::app()->format->formatNumber($this->total).'</td>
                </tr>
            </table>
        ';

        $this->writeHTMLCell('','',5,242,$classORDetails.$ORdetails);

        ### Signatories ###
        $classSignatories = '
            <style>
                  table {
                    font-style: arial;
                    padding-left: 2px;
                    border: 0.5px solid #000;
                    width: 97.6%;
                  }
                  td{
                    text-align: left;
                    valign: middle;
                  }
            </style>
        ';

        $signatories = '
            <table>
                <tr>
                    <td width="70"></td>
                    <td width="279"></td>
                    <td width="218"></td>
                </tr>
                <tr>
                    <td width="70"> Conforme: </td>
                    <td width="279" align="center">I have agreed to the details including the Terms and Conditions</td>
                    <td width="218" align="center">'.$request->conforme.'</td>
                </tr>
                <tr>
                    <td width="70"></td>
                    <td width="279" align="center">stated in this Technical Service Request.</td>
                    <td width="218" align="center">Customer Representative</td>
                </tr>
                
            </table>
        ';

        $this->writeHTMLCell('','',5,262.5,$classSignatories.$signatories);
         ### OR Information ###

         $classOR = '
            <style>
                  table {
                    font-style: arial;
                    padding-left: 2px;
                    border: 0.5px solid #000;
                    width: 97.6%;
                  }
                  td{
                    text-align: left;
                    valign: middle;
                  }
            </style>
        ';

        $ornos = '
            <table>
                <tr>
                    <td width="70">O.R. No(s).:</td>
                    <td width="109"></td>
                    <td width="220">Received by: Carmen M. Flores</td>
                    <td width="168">Date: </td>
                </tr>                
            </table>
        ';

        $this->writeHTMLCell('','',5,275.5,$classOR.$ornos);

        $this->SetFont('helvetica', '', 7);
        $formDetails = '
            <style>
                table {
                    font-style: arial;
                    padding-left: 2px;
                    width: 97.6%;
                  }
                  td{
                    valign: middle;
                    text-align: right;
                  }
            </style>
            <table>
                <tr>
                    <td align="right">OP-007-F01</td>
                </tr>
                <tr>
                    <td align="right">Rev. 3</td>
                </tr>
                <tr>
                    <td align="right">14 December 2018</td>
                </tr>
            </table>
        ';
                // <tr>
                //     <td align="right">Effectivity Date:<br>20 Jan 2017</td>
                // </tr>
        $this->writeHTMLCell(205,'',5,281.5,$formDetails);

       
    }
}
?>
