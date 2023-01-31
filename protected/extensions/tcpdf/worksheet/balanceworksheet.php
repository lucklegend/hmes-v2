<?php
require_once(dirname(__FILE__).'/../tcpdf.php');

class balanceworksheet extends TCPDF {
 
    var $request;
    var $customerId;
    var $sample;

    public function setRequest($request) {
        $this->request = $request;
    }

    public function setCustomer($customer) {
        $this->customer = $customer;
    }

    public function setSample($sample) {
        $this->sample = $sample;
    }

    public function Header() {
        //logo place
        $this->setJPEGQuality(100);
        $image_file = 'http://localhost'.Yii::app()->request->baseUrl.'/images/hydro_logo.jpg';
        $this->Image($image_file, 10, 7, 70, '', 'JPEG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetAlpha(1);

        $headDetails = array(
            'title'=>'CALIBRATION WORKSHEET FOR BALANCES',
            'code'=>'HME-CM-001-F01',
        );
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
        $this->SetFont('helvetica','B',13);
        $this->MultiCell(120, 0, $headDetails['title'], 0, 'L', 0, 1, 82, 7, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helvetica','',12);
        $this->MultiCell(120, 0, $headDetails['code'], 0, 'L', 0, 1, 82, 12, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helvetica','B',12);
        $this->MultiCell(190, 0, '<hr>', 0, 'L', 0, 1, 10, 26, true, 0, true, true, 0, 'T', false);
    }
    public function printRows() {
        $request = $this->request;
        $id = $_GET['id'];
        $sample = Sample::model()->findByPk($id);
        $codes = explode('-', $sample->sampleCode);

        $sampleCode = $sample->requestId.'-'.substr($codes[1], 1);
        $receiveDate = date('d F Y', strtotime($request->requestDate));
        
        $forms = '
        <style>
          table {
            font-style: arial;
            width: 76%;
          }
          td.underline {
            border-bottom: 0.5px solid #000;
          }
          td.border{
            border: 0.5px solid #000;
          }
          td.big{
            height: 30px;
            text-align: center;
            vertical-align: middle;
            padding: 10px;
            margin: 10px;
          }
        </style>
        <table border="0">
            <tr>
                <td width="95">Company</td>
                <td width="10">:</td>
                <td width="430" class="underline">'.$request->customer->customerName.'</td>
            </tr>
            <tr>
                <td>Address</td>
                <td>:</td>
                <td class="underline">'.$request->customer->completeAddress.'</td>
            </tr>
            <tr>
                <td>Contact Person</td>
                <td>:</td>
                <td class="underline">'.$request->customer->head.'</td>
            </tr>
           ';
            if($request->customer->email != "" || $request->customer->email != NULL){
                $contactInformation = $request->contact_number.' / '.$request->customer->email;
            }else{
                $contactInformation = $request->contact_number;
            }
            $forms .= '
            <tr>
                <td>Contact Information</td>
                <td>:</td>
                <td class="underline">'.$contactInformation.'</td>
            </tr>
        </table>
        <table>
            <tr>
                <td height="10"></td>
            </tr>   
        </table>
        <table border="0">
            <tr>
                <td width="110">Type of Job</td>
                <td width="10">:</td>
                <td width="190">[&nbsp;&nbsp;&nbsp;] Calibration</td>
                <td width="190">[&nbsp;&nbsp;&nbsp;] Others ___________________ </td>
            </tr>
            <tr>
                <td></td>
                <td>:</td>
                <td>[&nbsp;&nbsp;&nbsp;] On-site Calibration</td>
                <td></td>
            </tr>
            <tr>
                <td>Instrument Description</td>
                <td>:</td>
                <td>[&nbsp;&nbsp;&nbsp;] Analytical Balance</td>
                <td>[&nbsp;&nbsp;&nbsp;] Electronic/Platform Scale</td>
            </tr>
            <tr>
                <td></td>
                <td>:</td>
                <td>[&nbsp;&nbsp;&nbsp;] Digital Top Loading Balance</td>
                <td>[&nbsp;&nbsp;&nbsp;] Weighing Scale</td>
            </tr>
            <tr>
                <td></td>
                <td>:</td>
                <td>[&nbsp;&nbsp;&nbsp;] Spring Scale</td>
                <td>[&nbsp;&nbsp;&nbsp;] Others ___________________ </td>
            </tr>
        </table>

        <table border="0">
            <tr>
                <td width="110">Manufacturer\'s Name</td>
                <td width="10">:</td>
                <td width="150" class="underline">'.$sample->brand.'</td>
                <td width="110">Service Request No.</td>
                <td width="10">:</td>
                <td width="150" class="underline">'.$request->requestRefNum.'</td>
            </tr>
            <tr>
                <td>Model No.</td>
                <td>:</td>
                <td class="underline">'.$sample->model_no.'</td>
                <td>Sample Code No.</td>
                <td>:</td>
                <td class="underline">'.$sampleCode.'</td>
            </tr>
            <tr>
                <td>Serial No.</td>
                <td>:</td>
                <td class="underline">'.$sample->serial_no.'</td>
                <td>Date Received </td>
                <td>:</td>
                <td class="underline">'.$receiveDate.'</td>
            </tr>
            <tr>
                <td>Resolution</td>
                <td>:</td>
                <td class="underline">'.$sample->resolution.'</td>
                <td>Date Calibrated </td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
            <tr>
                <td>Range</td>
                <td>:</td>
                <td class="underline">'.$sample->capacity_range.'</td>
                <td>Ambient Temperature</td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
            <tr>
                <td>Location</td>
                <td>:</td>
                <td class="underline"></td>
                <td>Relative Humidity</td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
        </table>
        <table border="0">
            <tr><td height="10"></td></tr>
            <tr>
                <td>CALIBRATION METHOD:</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="540" style="text-align:justify;">The method of calibration is in accordance with HME-CM-001, Calibration of Weighing Scales and Balances” based on EURAMET Calibration Guide No. 18 - “Guidelines on the Calibration of Non-Automatic Weighing Instruments.”
                </td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>PRELIMINARY EVALUATION:</td>
            </tr>
            <tr><td height="10"></td></tr>
             <tr>
                <td width="110">Scale / Graduations</td>
                <td width="155">[&nbsp;&nbsp;&nbsp;] readable</td>
                <td width="155">[&nbsp;&nbsp;&nbsp;] unsatisfactory</td>
            </tr>
            <tr>
                <td>Missing / Broken parts</td>
                <td>[&nbsp;&nbsp;&nbsp;] _______________________</td>
                <td>[&nbsp;&nbsp;&nbsp;] none</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="540">Visual inspection shows that the general condition and workmanship of the instrument were found _________________________________________________________________________________________________________________</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>CALIBRATION RESULTS:</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="15">I.</td>
                <td width="530">Repeatability Test at M = ________________________ (0.5 maximum to maximum capacity) <br>Standard Mass Used: _________________________________________________________

                </td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
        <table cellpadding="2" border="1" style="margin:20px;display:block;">
            <tr valign="middle" style="padding:10px;margin-left;10px;">
                <td width="110" class="big"><br><br>Number</td>
                <td width="110" class="big"><br><br>Load<br></td>
                <td width="230" class="big"><br><br>Balance Reading (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br></td>
            </tr>
            <tr>
                <td class="big"><br>1</td>
                <td class="big">0<br>M</td>
                <td class="big"><br></td>
            </tr>
            <tr>
                <td class="big"><br>2</td>
                <td class="big">0<br>M</td>
                <td class="big"><br></td>
            </tr>
            <tr>
                <td class="big"><br>3</td>
                <td class="big">0<br>M</td>
                <td class="big"><br></td>
            </tr>
            <tr>
                <td class="big"><br>4</td>
                <td class="big">0<br>M</td>
                <td class="big"><br></td>
            </tr>
            <tr>
                <td class="big"><br>5</td>
                <td class="big">0<br>M</td>
                <td class="big"><br></td>
            </tr>
        </table>
        <table> 
            <tr>
                <td width="110"></td>
                <td width="110"></td>
                <td width="230"><br><br>Standard Deviation = ______________________</td>
            </tr>
        </table>
        <table>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>Calibrated by: _______________________</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>Checked by: ________________________</td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
        <table>
            <tr><td height="10"></td></tr>  
            <tr>
                <td width="85">Sample Code No.</td>
                <td width="10">:</td>
                <td width="115" class="underline">'.$sampleCode.'</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="15">II.</td>
                <td width="530">Test for Errors of Indication</td>
            </tr>
            <tr>
                <td width="15"></td>
                <td width="530">Standard Mass Used: _________________________________________________________</td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
        <table border="1">
            <tr>
                <td width="60" class="big">Nominal<br>Value<br>(_______)<br></td>
                <td width="120" class="big">Correction of mass<br>nominal value<br>(_______)<br></td>
                <td width="80" class="big"><br><br>Load<br>(_______)</td>
                <td width="110" class="big"><br><br>Balance Reading<br>(_______)</td>
                <td width="130" class="big">Measurement Deviation =<br>Balance Reading - Load<br>(________)</td>
            </tr>
            <tr>
                <td height="30"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="30"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="30"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="30"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="30"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="30"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="30"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="30"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="30"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="30"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="30"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="20">III.</td>
                <td width="350">Eccentricity Test at M = _________________(1/3 of maximum capacity).</td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>

        <table border="1">
            <tr>
                <td width="100"></td>
                <td width="80" style="height:20px;text-align:center;">Center</td>
                <td width="80" style="height:20px;text-align:center;">Front Left </td>
                <td width="80" style="height:20px;text-align:center;">Back Left</td>
                <td width="80" style="height:20px;text-align:center;">Back Right</td>
                <td width="80" style="height:20px;text-align:center;">Front Right</td>
            </tr>
            <tr>
                <td style="height:20px;text-align:center;">Balance Reading<br>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>Calibrated by: _______________________</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>Checked by: ________________________</td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
        ';
        $this->SetFont('helvetica','',10);
        $this->MultiCell(190, 0, $forms, 0, 'L', 0, 1, 9, 30, true, 0, true, true, 0, 'T', false);
    }

    public function Footer() {

        $footDetails = array(
            'revCode'=>'Rev. 0',
            'effectDate'=>'Effective Date: 04 May 2020',
        );
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)

        $this->MultiCell(190, 0, '<hr>', 0, 'L', 0, 1, 10, 288, true, 0, true, true, 0, 'T', false);

        $this->SetFont('helvetica','',8);
        $this->MultiCell(190, 0, $footDetails['revCode'], 0, 'L', 0, 1, 10, 288, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(190, 0, $footDetails['effectDate'], 0, 'C', 0, 1, 10, 288, true, 0, true, true, 0, 'T', false);
        $numPage = $this->getAliasNumPage();
        $nbPage = $this->getAliasNbPages();
        $paging = 'Page '.$numPage.' of '.$nbPage;

        $this->MultiCell(50, 0, $paging, 0, 'R', 0, 1, 163, 288, true, 0, true, true, 0, 'T', false);
        
    }
}
?>
