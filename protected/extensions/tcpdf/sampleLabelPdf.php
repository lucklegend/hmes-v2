<?php
require_once(dirname(__FILE__).'/tcpdf.php');

class sampleLabelPdf extends TCPDF {
 
    var $sampleLabel;
    //var $customerId;

    public function setSampleLabel($sampleLabel) {
        $this->sampleLabel = $sampleLabel;
    }

    public function printRows() {
        $label = $this->sampleLabel;

        $classRows = '
                <style>
                  table {
                    font-style: arial;
                    border-top: 0.5px solid #000;
                    border-left: 0.5px solid #000;
                    width: 60%;
                  }
                  td{
                    border-right: 0.5px solid #000;
                    border-bottom: 0.5px solid #000;
                  }
                </style>
            ';

        $rows = '<table>';
                //$sampleCount = 0;
                //$subTotal = 0;
                    $rows .='
                        <tr>
                            <td width="140">'.$label->sampleName.'</td>
                            <td style="width: 45px; text-align: center">'.$label->sampleCode.'</td>
                                                <td width="140"></td>
                                        <td width="45"></td>';
                                        
                                    

            

            $this->SetFont('helvetica', '', 8);
            $this->writeHTMLCell(200,'',4,87,$classRows.$rows, 0, 2);
			
            
            
    }
 
    public function Header() {

    }


    public function Footer() {
        //$this->SetXY(20, 268);
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

        
        
    }
}
?>
