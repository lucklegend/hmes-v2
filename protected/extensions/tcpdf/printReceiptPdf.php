<?php
require_once(dirname(__FILE__).'/tcpdf.php');

class PrintReceiptPdf extends TCPDF {
 
    var $receipt;
    
    public function setReceipt($receipt) {
        $this->receipt = $receipt;
    }

    public function printRows() {
        $receipt = $this->receipt;
        
        $receiptDetails = array(
            'payor' => strtoupper($receipt->payor),
            'date' => Yii::app()->dateFormatter->format('MMMM d, yyyy', strtotime($receipt->receiptDate)),
            'purpose' => strtoupper($receipt->orderofpayment->purpose),
            'agency' => 'DOST-III',            
        );
        $totalAmount = $receipt->totalCollection;
        $amounts = '';
        $trsnums = '';
        foreach ($receipt->collection as $collection) {
            $amounts .= Yii::app()->format->formatNumber($collection->amount).'</br>';
            $tsrnums .= $collection->nature.'</br>';
        }
        
        $this->SetFont('helvetica', 'B', 9);
        $this->writeHTMLCell(49,'',46,46,$receiptDetails['date'], 0, 2);
        $this->writeHTMLCell(73,'',22,54,$receiptDetails['agency'], 0, 2);
        $this->writeHTMLCell(78,'',17,60,$receiptDetails['payor'], 0, 2);
        $this->writeHTMLCell(85,'',8,80,$receiptDetails['purpose'], 0, 2);

        $this->MultiCell(45, 0, $amounts, 0, 'R', 0, 1, 50, 84, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(200, 0, $tsrnums, 0, 'L', 0, 1, 9, 84, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(45, 0, Yii::app()->format->formatNumber($totalAmount), 0, 'R', 0, 1, 50, 124.5, true, 0, true, true, 0, 'T', false);
        // $this->MultiCell(45, 0, $receiptDetails['totalCollection'], 0, 'R', 0, 1, 50, 80, true, 0, true, true, 0, 'T', false);

        $space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $amountinwords = $space.Yii::app()->Controller->convert_number_to_words($totalAmount).' PESOS ONLY';
        $this->MultiCell(85, 0, $amountinwords, 0, 'L', 0, 1, 9, 132.5, true, 0, true, true, 0, 'T', false);
        $checkSymbol = 'X';
        if($receipt->paymentModeId == 1){
            $this->writeHTMLCell(200,'',9,145,$checkSymbol, 0, 2);
        }
         if($receiptDetails->paymentModeId == 2){
            $this->writeHTMLCell(200,'',9,151,$checkSymbol, 0, 2);
        }
    }
 
    public function Header() {

    }


    public function Footer() {      
        
    }
}
?>
