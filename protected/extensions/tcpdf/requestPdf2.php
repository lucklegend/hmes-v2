<?php
require_once(dirname(__FILE__).'/tcpdf.php');

class requestPdf2 extends TCPDF {
 
    var $request;
    var $customerId;
    private $subTotal;
    private $discount;
    private $totalFees;
    private $vat;
    private $grandTotal;


    public function setRequest($request) {
        $this->request = $request;
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
            'header1'=>'HYDROMECH ENGINEERING SERVICES',
            'header2'=>'Banlic Road, Zone 6, San Isidro, Bacolor, Pampanga',
            'header3'=> '0928 500 9150 / 0921 553 5057 / 0936 405 9335',
            'header4'=>'SERVICE REQUEST',
        );
        // set JPEG quality
        $this->setJPEGQuality(200);

        // $image_file = '*http://localhost'.Yii::app()->request->baseUrl.'/images/dost_logo.png';
        $image_file = 'http://localhost'.Yii::app()->request->baseUrl.'/images/hydro_logo.jpg';
        // echo $image_file;
        // Image method signature:
        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
        
        $this->Image($image_file, 70, 7, 70, '', 'JPEG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetAlpha(1);

        $this->SetFont('helvetica','B',9);
        $this->MultiCell(200, 0, $headDetails['header1'], 0, 'C', 0, 1, 5, 24.75, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helvetica','',8);
        $this->MultiCell(200, 0, $headDetails['header2'], 0, 'C', 0, 1, 5, 27.75, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(200, 0, $headDetails['header3'], 0, 'C', 0, 1, 5, 30.75, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helvetica','',12);
        $this->MultiCell(200, 0, $headDetails['header4'], 0, 'C', 0, 1, 5, 36.75, true, 0, true, true, 0, 'T', false);     
        
        $this->SetFont('helvetica','',12);
        $this->MultiCell(200, 0, '<br/><br/>'.$headerdata['formTitle'], 0, 'C', 0, 1, 5, 25, true, 0, true, true, 0, 'T', false);
        
        $this->SetFont('helvetica','',9);
        $dateMake = date("F d, Y",strtotime($request->requestDate));

            $class = '
                <style>
                  table {
                    font-style: arial;
                    width: 100%;
                    padding-right: 7px;
                  }td.border1{
                      border:1px solid #000;
                  }
                </style>
            ';

            $html = '
                <table border="0">
                    <tr>
                        <td width="50%">SR No.: '.$request->requestRefNum.'</td>
                        <td width="50%" align="right" style="text-align:right;">'.$dateMake.'</td>
                    </tr>
                </table>
            ';
            $this->writeHTMLCell('','',5,38,$class.$html, 0, 2);

            $this->SetFont('helvetica','B',9);
            $this->MultiCell(200, 0, 'CUSTOMER DETAILS', 0, 'L', 0, 1, 5, 44, true, 0, true, true, 0, 'T', false);    

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
                        
            /*
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
                */
                 $customerDetails = '
                    <table id="main_table">
                        <tr>
                            <td width="50%" style="padding-left:0px;"><table cellspacing="0" cellpadding="0"><tr><td width="16%">Company:</td><td width="84%">'.$request->customer->customerName.'</td></tr><tr><td width="16%">Address:</td><td width="84%">'.$request->customer->completeAddress.'</td></tr><tr><td width="16%">Attention:</td> <td width="84%">'.$customerHead.'</td></tr> </table> </td> 
                            <td width="50%"> <table cellspacing="0" cellpadding="0"> <tr> <td width="25%">Contact Number:</td><td width="75%">'.$contactNum.'</td> </tr> <tr> <td width="25%">Email Address:</td> <td width="75%">'.$request->customer->email.'</td> </tr> <tr> <td width="25%">Business Type:</td> <td width="75%">'.$request->customer->naturebusiness->nature.'</td> </tr> </table> </td>
                        </tr> 
                    </table>
                    <table><tr><td height="10"></td></tr><table>
                ';    
            // }
            
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
            
            $testingHeader = '
                <table>
                    <tr>
                        <th width="100" valign="middle">SAMPLE CODE</th>
                        <th width="123" align="center" valign="middle" >SAMPLE NAME</th>
                        <th width="120" valign="middle">TEST/CALIBRATION METHOD REQUESTED</th>
                        <th width="174" valign="middle">DESCRIPTION</th>
                        <th width="50" valign="middle">FEE</th>
                    </tr>
                </table>
            ';            
            $totalcharcus = strlen($request->customer->customerName);
            $totalcharadd = strlen($request->customer->completeAddress);

            if($totalcharcus>=50 && $totalcharadd>=50){
                $this->writeHTMLCell('','',5,71.5,$classTestingTitle.$testingTitle, 0, 2);   
                $this->writeHTMLCell('','',5,75.5,$classTestingHeader.$testingHeader, 0, 2,false, true, 'C', true); 
            }else{
                $this->writeHTMLCell('','',5,65.5,$classTestingTitle.$testingTitle, 0, 2);   
                $this->writeHTMLCell('','',5,69.5,$classTestingHeader.$testingHeader, 0, 2,false, true, 'C', true); 
            }
           
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
                        <td style="width: 100px; text-align: center" class="border1">'.$request->requestRefNum.'-'.$exSampleCode.'</td>
                        <td width="123" style="valign:center;" class="border1">'.$sample->sampleName.'</td>                        
                        ';
                        $analysisCount = 0;
                        foreach($sample->analyses as $analysis){
                            if($analysisCount != 0){

                                if($analysis->method == "-" || $analysis->method=="none"){
                                    $tm = "";
                                }else{
                                    $tm = $analysis->method;
                                }
                                if($analysis->references == "-" || $analysis->references == "To be update" || $analysis->references == "To be updated" || $analysis->references =="To Be Updated" || $analysis->references =="none"){
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
                                    <td width="100" class="border1"></td>
                                    <td width="123" class="border1"></td>
                                    <td width="120" class="border1">'.$testMethods.'</td>
                                    <td width="174" class="border1">'.$otherServices.'</td>
                                    <td width="50" style="text-align: right" class="border1">'.Yii::app()->format->formatNumber($analysis->fee).'</td>
                                    ';
                            }else{

                                if($analysis->method == "-" || $analysis->method=="none"){
                                    $tm = "";
                                }else{
                                    $tm = $analysis->method;
                                }
                                
                                if($analysis->references == "-" || $analysis->references == "To be update" || $analysis->references == "To be updated" || $analysis->references =="To Be Updated" || $analysis->references == "none"){
                                    $an = "";
                                }else{
                                    $an = ", ".$analysis->references;
                                }
                                if($an=="" && $tm==""){
                                    $testMethods = $analysis->testName;    
                                }else{
                                    $testMethods = $analysis->testName." [".$tm.$an."]";
                                }
                                
                                if($sample->jobType != ''){
                                    $desc = ' / Type of Job: '.$sample->jobType.$desc;
                                }
                                if($sample->serial_no != ''){
                                    $desc = $desc.' / Serial No.: '. $sample->serial_no;
                                }
                                if($sample->brand != ''){
                                    $desc = $desc.' / Brand or Manufacture: '.$sample->brand;
                                }
                                if($sample->capacity_range != ''){
                                    $desc = $desc.' / Capacity or Range: '.$sample->capacity_range;
                                }
                                if($sample->resolution != ''){
                                    $desc = $desc.' / Resolution: '.$sample->resolution;
                                }

                                $rows .= '
                                    <td width="120" class="border1">'.$testMethods.'</td>
                                    <td width="174" class="border1">'.$sample->description.$desc.'</td>
                                    <td width="50" style="text-align: right" class="border1">'.Yii::app()->format->formatNumber($analysis->fee).'</td>
                                    ';
                                $desc = '';
                            }
                            $rows .='</tr>';
                            $analysisCount = $analysisCount + 1;
                            $subTotal = $subTotal + $analysis->fee;
                        }
                        
            }
            $rows .='</table>
                <table border="0">
                    <tr>
                        <td width="437" style="border: none;" colspan="3"></td>
                        <td width="80" style="border: 1px solid black; text-align: right;" class="border1">Sub-Total</td>
                        <td width="50" style="border: 1px solid black; text-align: right;" class="border1">'.Yii::app()->format->formatNumber($subTotal).'</td>
                    </tr>';

        $requestTotal = $request->inplantcharge + $request->additional + $subTotal;

        if($request->discount != 8){
            $discountFee = $requestTotal * ($request->disc->rate/100);
        }
        else{
            $discountFee = $request->discounted;
        }
        
        $total = $requestTotal - $discountFee;
        if($request->vat == 1){
            $vat = $total * 0.12;
        }else{
            $vat = 0;
        }

        $grandTotal = $total + $vat;
        /*making it global*/
        $this->subTotal = $subTotal;
        $this->discount = $discountFee;
        $this->totalFees = $total;
        $this->vat = $vat;
        $this->grandTotal = $grandTotal;

        $totalcharcus = strlen($request->customer->customerName);
        $totalcharadd = strlen($request->customer->completeAddress);
        $rows .= '</table>';
        //if($request->labId != 3){
        if($request->labId != 4){
             $rows .= '<br/><b style="font-size:9px">REMARKS</b><br/>
                  <table width="101%" style="border: 0.5px solid #000;">
                    <tr>
                        <td><br/><br/>
                            '.$request->remarks.'
                            <br/>
                        </td>
                    </tr>
                  </table>';
        }
       
        $rows .= $totalcharacter;
        $this->SetFont('helvetica', '', 8);
        if($totalcharcus>=50 && $totalcharadd>=50){
            $this->SetMargins('', 83.5, 10, 10);
            $this->writeHTMLCell(200,'',4,83.5,$classRows.$rows, 0, 2);
        }else{
            $this->SetMargins('', 77.5, 10, 10);
            $this->writeHTMLCell(200,'',4,77.5,$classRows.$rows, 0, 2);
        }
        
            
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
        /*
         $formDetails = '
            <style>
                table {
                    font-style: arial;
                    padding-left: 2px;
                    width: 97.6%;
                    border: 0.5px solid #000;
                  }
                  td{
                    valign: middle;
                    text-align: left;
                  }
            </style>
            <table>
                <tr>
                    <td width="50%">O.R. No(s):</td>
                    <td style="text-align:right;" width="50%">'.$paging.'</td>
                    <!--<td>'.Yii::app()->params["FormRequest"]["number"].'</td>-->
                </tr>
                <tr>
                    <td><width="50%">O.R. Date(s): </td>
                    <td></td>
                </tr>
            </table>
        ';

        $this->writeHTMLCell(205,'',5,238,$formDetails);
        */
        /*end of page Number*/

        $this->SetFont('helvetica','',9);

        $classORDetails = '
            <style>
                  table {
                    font-style: arial;
                    padding-left: 2px;
                    border: 0.5px solid #000;
                    //border-left: 0.5px solid #000;
                    width: 100%;
                  }
                  td{
                    //border-bottom: 0.5px solid #000;
                    //border-right: 0.5px solid #000;
                    text-align: left;
                    valign: middle;
                  }
                  .tdright{
                    padding-right: 2px;
                    margin-right: 2px;
                    text-align: right;
                  }
            </style>
        ';
        if($request->validated_by == '' || $request->validated_by == NULL){
            if(isset($request->laboratory->manager->user))
                $labManager=$request->laboratory->manager->user->getFullname(); 
        }else{
            $labManager=$request->validated_by;
        }
        if($request->discount==8){ 
            $discounted = '('.$request->disc->type.' '.') -'.Yii::app()->format->formatNumber($this->discount);
        }else if($this->discount != '0.00'){
            $discounted = '('.$request->disc->type.' '.number_format($request->disc->rate,0).'%) -'.Yii::app()->format->formatNumber($this->discount);
        }else{
            $discounted = '0.00';
        }
        //<td width="156" align="right">'.$discounted.'</td>
        $ORdetails = '
            <table>
                <tr>
                    <td width="70">Transmission:</td>
                    <td width="229">'.$request->transmission.'</td>
                    <td width="112">SUBTOTAL:</td>
                    <td width="156" class="tdright">'.Yii::app()->format->formatNumber($this->subTotal).'</td>
                </tr>
                <tr>
                    <td>Due Date:</td>
                    <td>'.Yii::app()->dateFormatter->format('EEEE, MMMM d, yyyy', strtotime($request->reportDue)).'</td>
                    <td>On-site Charge:</td>
                    <td align="right">'.Yii::app()->format->formatNumber($request->inplant_charge).'</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Pick-Up/Delivery Charge:</td>
                    <td align="right">'.Yii::app()->format->formatNumber($request->additional).'</td>
                </tr>
                <tr>
                    <td rowspan="2">Conforme:</td>
                    <td rowspan="2">I have agreed to the details including the Terms and Conditions stated in this Service Request.</td>
                    <td>Discount:</td>
                    <td align="right">'.$discounted.'</td>
                </tr>';
        if($request->vat == 1){
            $ORdetails .= '
                <tr>
                    <td>Total Fees:</td>
                    <td align="right">'.Yii::app()->format->formatNumber($this->totalFees).'</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>VAT (12%):</td>
                    <td align="right">'.Yii::app()->format->formatNumber($this->vat).'</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><b>GRAND TOTAL:</b></td>
                    <td align="right"><b>'.Yii::app()->format->formatNumber($this->grandTotal).'</b></td>
                </tr>
            </table>';
        }else{

            $ORdetails .= '
                <tr>
                    <td></td>
                    <td align="right"></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><b>GRAND TOTAL:</b></td>
                    <td align="right"><b>'.Yii::app()->format->formatNumber($this->grandTotal).'</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td align="right"></td>
                </tr>
            </table>   
        ';
        }
        $this->writeHTMLCell('','',5,238,$classORDetails.$ORdetails);

        ### Signatories ###
        $classSignatories = '
            <style>
                  table.borderClass {
                    font-style: arial;
                    padding-left: 2px;
                    border: 0.5px solid #000;
                    width: 97.6%;
                  }
                  td{
                    text-align: left;
                    valign: middle;
                  }
                  td.top_left{
                    border-right: 0.5px solid #000;
                    border-top: 0.5px solid #000;
                    text-align:center;
                  }
                  td.top{
                    border-top: 0.5px solid #000;
                    text-align:center;
                  }
                  td.center_left{
                    border-right: 0.5px solid #000;
                    vertical-align: bottom;
                    valign: bottom;
                    text-align:center;
                  }
            </style>
        ';

        $signatories = '
            <table class="borderClass">
                <tr>
                    <td width="189" style="border-right: 0.5px solid #000;">Received by:</td>
                    <td width="189" style="border-right: 0.5px solid #000;">Reviewed by:</td>
                    <td width="189">Conforme: </td>
                </tr>
                
                <tr>
                    <td width="189" style="border-right: 0.5px solid #000;"></td>
                    <td width="189" style="border-right: 0.5px solid #000;"></td>
                    <td width="189"></td>
                </tr>
                <tr >
                    <td width="189" class="center_left">'.$request->receivedBy.'</td>
                    <td width="189" class="center_left">'.$labManager.'</td>
                    <td width="189" class="center_left">'.$request->conforme.'</td>
                </tr>
                <tr>
                    <td width="189" class="top_left">Customer Relation Officer</td>
                    <td width="189" class="top_left">Technical Staff</td>
                    <td width="189" class="top">Customer/Authorized Representative </td>
                </tr>
            </table>
            <table border="0">
                <tr>
                    <td>'.$paging.'</td>
                </tr>
            </table>
        ';

         $this->writeHTMLCell('','',5,267,$classSignatories.$signatories);

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
        // $this->writeHTMLCell(205,'',5,281.5,$formDetails);

       
    }
}
?>
