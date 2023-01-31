<?php ob_clean();
require_once(dirname(__FILE__).'/tcpdf.php');

class orderpaymentPdf extends TCPDF {
 
    var $orderpayment;
    var $paymentitem;
    var $orderpayment_id;

    public function setOrderpayment($orderpayment) {
        $this->orderpayment = $orderpayment;
    }

    public function setPaymentitem($paymentitem) {
        $this->paymentitem = $paymentitem;
    }
 
    public function Header() {
        $orderpayment = $this->orderpayment;
        $headerdata = array(
                'agencyName'=>Yii::app()->params['Agency']['name'],
                'rstl'=>'<b>'.strtoupper(Yii::app()->params['Agency']['labName']).'</b>',
                'agencyAddress'=>Yii::app()->params['Agency']['address'],
                'agencyContactInfo'=>'Contact No. '.Yii::app()->params['Agency']['contacts'],
                'formTitle'=>'<b>'.Yii::app()->params['FormRequest']['title'].'<b>'
            );

        $headDetails = array(
            'header1'=>'DEPARTMENT OF SCIENCE AND TECHNOLOGY',
            'header2'=>'Regional Office III',
            'header3'=>'Government Center, Maimpis, City of San Fernando, Pampanga',
            'header4'=>'Order of Payment'
        );
        
        $this->SetFont('helvetica','',10);
        $this->MultiCell(200, 0, $headDetails['header1'], 0, 'C', 0, 1, 5, 16.50, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(200, 0, $headDetails['header2'], 0, 'C', 0, 1, 5, 20, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(200, 0, $headDetails['header3'], 0, 'C', 0, 1, 5, 24.50, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helvetica','B',10);
        $this->MultiCell(200, 0, $headDetails['header4'], 0, 'C', 0, 1, 5, 54.50, true, 0, true, true, 0, 'T', false);
        
        $this->SetFont('helvetica','',10);
        $dateMake = date("F d, Y",strtotime($orderpayment->date));
        $timeMake = date("g:iA");

        $class = '
            <style>
              table {
                font-style: arial;
                width: 100%;
                padding-right: 15px;
                //border: 1px solid #000;
              }
            </style>
        ';
        $lab = explode('-', $orderpayment->tsrNumber);
        /*
        if($lab[2] == 'CHE' || $lab[2] == 'MIC' || $lab[2] == 'MET'){
            $opNum = $orderpayment->transactionNum.'-'.$lab[2];
        }elseif($lab[1] == 'CHE' || $lab[1] == 'MIC' || $lab[1] == 'MET'){
            $opNum = $orderpayment->transactionNum.'-'.$lab[1];
        }
        */
        $opNum = $orderpayment->transactionNum;
        $html = '
            <table>
                <tr>
                    <td width="60%"></td>
                    <td width="10%">No.:</td>
                    <td width="30%">'.$opNum.'</td>
                </tr>
                <tr>
                    <td width="60%"></td>
                    <td width="10%">Date:</td>
                    <td width="30%">'.$dateMake.'</td>
                </tr>
                <tr>
                    <td width="60%"></td>
                    <td width="10%"></td>
                    <td width="30%">'.$timeMake.'</td>
                </tr>
            </table>
        ';
        $this->writeHTMLCell('','','',38,$class.$html, 0, 2);
    }
    public function printRows() {
    $orderpayment = $this->orderpayment;
    $paymentitem = $this->paymentitem;
        $class = '
            <style>
              table {
                font-style: arial;
                width: 100%;
                //border: 1px solid #000;
              }
            </style>
        ';

        $html = '
            <table>
                <tr>
                    <td width="100%"><b>The Collecting Officer</b></td>
                </tr>
                <tr>
                    <td width="100%">Cash Unit</td>
                </tr>
            </table>
        ';
        $this->SetFont('helvetica','',10);
        $this->writeHTMLCell('','','',62,$class.$html, 0, 2);

        $sentence = '
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Please issue Official Receipt in favor of
            ';
        $nameOfCustomer = $orderpayment->customerName;
        $this->writeHTMLCell('','','',75,$sentence.$nameOfCustomer, 0, 2);

        $hr1 = '<hr width="244">';
        $this->writeHTMLCell('','',97,79.5,$hr1, 0, 2);
        $hr2 = '<hr width="448">';
        $this->writeHTMLCell('','','',83.5,$hr2, 0, 2);
        $this->writeHTMLCell('','','',88.5,$hr2, 0, 2);
        $this->writeHTMLCell('','','',93.5,$hr2, 0, 2);

        $address = $orderpayment->address;
        $this->writeHTMLCell('','','',84.5,$address, 0, 2, false, true, 'C');

        $address2 = '(Address/Office)';
        $this->SetFont('helvetica','',8);
        $this->writeHTMLCell('','','',93.5,$address2, 0, 2, false, true, 'C');
        $this->SetFont('helvetica','',10);

        $words = 'in the amount of ';
        //$amountWord = $orderpayment->totalPayment;

        /*SETTING UP CONDITIONS*/
        //$amountWord = Yii::app()->Controller->convert_number_to_words(110700.12).' PESOS';
        $amountWord = $this->converting_into_words($orderpayment->totalPayment);
        //$amountWord = $this->converting_into_words(110700.121);

        $this->writeHTMLCell('','','',103.5,$words.$amountWord, 0, 2); 
        $hr3 = '<hr width="374">';
        $this->writeHTMLCell('','',51,108,$hr3, 0, 2);
        $this->writeHTMLCell('','','',112.5,$hr2, 0, 2);

        $foramount = 'for the payment of ';
        $this->writeHTMLCell('','','',112.5,$foramount.$orderpayment->purpose.'.', 0, 2); 
        $foramount2 = 'P ';
        $this->writeHTMLCell('','',150,112.5,$foramount2, 0, 2); 
        $totalamount = Yii::app()->format->number($orderpayment->totalPayment);
        $this->writeHTMLCell('','','',112.5,$totalamount, 0, 2,false, true, 'R'); 
        $hr4 = '<hr width="93">';
        $this->writeHTMLCell('','',150,117,$hr4, 0, 2); 

        $hrwidth = 99;
        $words2 = 'Per TSR No.:';
        
        $this->writeHTMLCell('','','',122,$words2, 0, 2,false, true, 'L'); 
        $i = 1;
        
        $tsrArray = [];
        foreach ($orderpayment->tsrNumbers as $tsrNum) {
            $tsrArray[] = $tsrNum['details'];
        }
        $tsrNumber = implode(', ', $tsrArray);
        if(count($orderpayment->tsrNumbers) > 3){
            $hrwidth = $hrwidth * 3;
        }else{
            $hrwidth = $hrwidth * count($orderpayment->tsrNumbers);
        }
        $tres= count($orderpayment->tsrNumbers)/3;
        $hr5 = '<hr width="'.$hrwidth.'">';
        $hrY = 126.5;
        $y = 122;
        $this->writeHTMLCell('','',50,$y,$tsrNumber, 0, 2,false, true, 'L');
        //$tsrNumber = count($orderpayment->tsrNumbers);
        $this->writeHTMLCell('','',49,$hrY,$hr5, 0, 2);
        
        for($i=1;$i<$tres;$i++){
            $hrY=$hrY+4.5;
            $this->writeHTMLCell('','',49,$hrY,$hr5, 0, 2);
            $y=$y+5;
        }
        $hr6 = '<hr width="99">';
        $words3 = 'Dated: ';
        //get the tsr_date_created fields
        $fromTsrNum = $orderpayment->tsrNumber;
        $getTsrDateCreate = Request::model()->findAll(array(
            'condition' => 'requestRefNum=:c',
            'params' => array(':c' => $fromTsrNum),
        ));
        foreach ($getTsrDateCreate as $req) {
            $dateCreated = $req->requestDate;
        }
        if(!$getTsrDateCreate || $getTsrDateCreate == NULL){
            $dateCreated = $orderpayment->date;
        }
        $dateMake = date("F d, Y",strtotime($dateCreated));
        $this->writeHTMLCell('','','',$y+5,$words3, 0, 2,false, true, 'L'); 
        $this->writeHTMLCell('','',50,$y+5,$dateMake, 0, 2,false, true, 'L'); 
        $this->writeHTMLCell('','',49,$hrY+5.5,$hr6, 0, 2); 

        $words4 = 'Please deposit the collection under Bank Account/s:';
        $this->writeHTMLCell('','','',$y+20,$words4, 0, 2,false, true, 'L'); 

        $account =Yii::app()->controller->getBankAccount();

        $table = '
            <style>
              table {
                width: 100%;
                text-align:center;
                padding:2px;
              }
              .botborder{
                border-bottom:1px solid #000;
              }
            </style>
             <table>
                <tr class="upmarg">
                    <td width="25%">No.</td>
                    <td width="2.5%"></td>
                    <td width="50%">Name of Bank</td>
                    <td width="2.5%"></td>
                    <td width="20%">Amount</td>
                </tr>
                <tr>
                    <td width="25%"></td>
                    <td width="2.5%"></td>
                    <td width="50%"></td>
                    <td width="2.5%"></td>
                    <td width="20%"></td>
                </tr>
                <tr>
                    <td width="25%" class="botborder">'.$account['accountNumber'].'</td>
                    <td width="2.5%"></td>
                    <td width="50%" class="botborder">'.$account['bankName'].'</td>
                    <td width="2.5%"></td>
                    <td width="20%" class="botborder" align="right">'.$totalamount.'</td>
                </tr>
                <tr>
                    <td width="25%" class="botborder"></td>
                    <td width="2.5%"></td>
                    <td width="50%" class="botborder"></td>
                    <td width="2.5%"></td>
                    <td width="20%" class="botborder"></td>
                </tr>
                <tr>
                    <td width="25%" class="botborder"></td>
                    <td width="2.5%"></td>
                    <td width="50%" class="botborder"></td>
                    <td width="2.5%"></td>
                    <td width="20%" class="botborder"></td>
                </tr>
            </table>
        ';
        //+5px for every line....//
        $this->writeHTMLCell('','','',$y+28,$table, 0, 2); 
        $foramount3 = 'Total:   P';
        $this->writeHTMLCell('','',140,$y+61.5,$foramount3, 0, 2); 
        $this->writeHTMLCell('','','',$y+61.5,$totalamount, 0, 2,false, true, 'R');
        $this->writeHTMLCell('','',150,$y+66,$hr4, 0, 2);

        $accountant = Yii::app()->controller->getPersonnel('accountant');
        $table2='
            <table>
                <tr >
                    <td width="50%"></td>
                    <td width="50%" align="center">'.$accountant['name'].'<br>
                        (Authorized Signatory)<br>
                        Accounting Unit
                    </td>
                </tr>
            </table>
        '; 
        $this->writeHTMLCell('','','',$y+79,$table2, 0, 2);
    }
    function converting_into_words($number){
        $round_of = number_format($number, 2, '.', '');
        $number =  Yii::app()->Controller->convert_number_to_words($round_of);
        $split_number = explode(' AND ', $number);
        if($split_number[1] == '00/100'){
            return $split_number[0].' PESOS';
        }else if (!isset($split_number[1])){
            return $split_number[0].' PESOS';
        }else{
            $centavos = explode('/', $split_number[1]);
            $centavosWord = Yii::app()->Controller->convert_number_to_words($centavos[0]);
            return $split_number[0].' AND '.$centavosWord.' CENTAVOS';
        }
    }

    public function Footer() {

    }
}