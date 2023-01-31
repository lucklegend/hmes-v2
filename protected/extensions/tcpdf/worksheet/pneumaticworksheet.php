<?php
require_once(dirname(__FILE__).'/../tcpdf.php');

class pneumaticworksheet extends TCPDF {
 
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
            'title'=>'TESTING WORKSHEET FOR PNEUMATIC PRESSURE TESTING OF PRESSURE VESSELS',
            'code'=>'HME-TM-002-F01',
        );
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
        $this->SetFont('helvetica','B',12);
        $this->MultiCell(120, 0, $headDetails['title'], 0, 'L', 0, 1, 82, 7, true, 0, true, true, 0, 'T', false);
        $this->SetFont('helvetica','',12);
        $this->MultiCell(120, 0, $headDetails['code'], 0, 'L', 0, 1, 82, 18, true, 0, true, true, 0, 'T', false);
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
        <table border="0">
            <tr><td height="10"></td></tr>
            <tr>
                <td width="110">Type of Job</td>
                <td width="10">:</td>
                <td width="415">[&nbsp;&nbsp;&nbsp;] Pneumatic Pressure Testing &nbsp;&nbsp;&nbsp; [&nbsp;&nbsp;&nbsp;] Other ____________________________________ </td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="110">Equipment Description</td>
                <td width="10">:</td>
                <td width="150" class="underline">'.$sample->sampleName.'</td>
                <td width="115"></td>
                <td width="10"></td>
                <td width="145"></td>
            </tr>
            <tr>
                <td>Manufacturer\'s Name </td>
                <td>:</td>
                <td class="underline">'.$sample->brand.'</td>
                <td>Service Request No.</td>
                <td>:</td>
                <td class="underline">'.$sample->requestId.'</td>
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
                <td>Date Received</td>
                <td>:</td>
                <td class="underline">'.$receiveDate.'</td>
            </tr>
            <tr>
                <td>Capacity</td>
                <td>:</td>
                <td class="underline">'.$sample->capacity_range.'</td>
                <td>Date of Test Conducted</td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
            <tr>
                <td>Pressure Vessel No.</td>
                <td>:</td>
                <td class="underline"></td>
                <td>Ambient Temperature </td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
            <tr>
                <td>Location of Testing</td>
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
                <td><b>TEST METHOD:</b></td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="540" style="text-align:justify;">The pressure vessel was tested in accordance with HME-TM-002, “Pneumatic Pressure Testing of Boilers and Pressure Vessels” based on “The American Society of Mechanical Engineers Boiler and Pressure Vessel Code (ASME BPVC.I-2017)” and DOLE Occupational Safety and Health Standards.
                </td>
            </tr>
        </table>
        <table border="0">
            <tr><td height="10"></td></tr>
            <tr>
                <td><b>TEST EQUIPMENT:</b></td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="110">Type of Equipment</td>
                <td width="10">:</td>
                <td width="150" class="underline"></td>
                <td width="110">Pressure Range</td>
                <td width="10">:</td>
                <td width="150" class="underline"></td>
            </tr>
             <tr>
                <td>Calibration Date</td>
                <td>:</td>
                <td class="underline"></td>
                <td>Manufacturer/Brand</td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
        </table>
        <table border="0">
            <tr><td height="10"></td></tr>
            <tr>
                <td><b>TEST PARAMETERS:</b></td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="110">Test Fluid</td>
                <td width="10">:</td>
                <td width="150" class="underline"></td>
                <td width="110">Fluid Temperature</td>
                <td width="10">:</td>
                <td width="150" class="underline"></td>
            </tr>
             <tr>
                <td>Working Pressure</td>
                <td>:</td>
                <td class="underline"></td>
                <td>Test Pressure</td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
        <table border="1"> 
            <tr>
                <td width="175" class="big"><b>Step<br>(min)</b></td>
                <td width="175" class="big"><b>Time</b></td>
                <td width="175" class="big"><b>Pressure Reading<br>(______)</b></td>
            </tr>
            <tr>
                <td height="20"></td>
                <td></td>
                <td></td>
            </tr>
             <tr>
                <td height="20"></td>
                <td></td>
                <td></td>
            </tr>
             <tr>
                <td height="20"></td>
                <td></td>
                <td></td>
            </tr>
             <tr>
                <td height="20"></td>
                <td></td>
                <td></td>
            </tr>
             <tr>
                <td height="20"></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table border="0">
            <tr><td height="10"></td></tr>
            <tr>
                <td><b>RESULTS:</b></td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
        <table border="0">
            <tr>
                <td width="75">Pressure Test: </td>
                <td width="14" class="border"></td>
                <td width="75">satisfactory</td>
                <td width="14" class="border"></td>
                <td width="350">unsatisfactory (explain) ________________________________________</td>
            </tr>
        </table>
        <table border="0">
            <tr><td height="10"></td></tr>
            <tr>
                <td><b>NOTES:</b></td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="10"></td>
                <td width="15">1.</td>
                <td>Calibrate pressure gauges and safety valve once every six months.</td>
            </tr>
             <tr>
                <td width="10"></td>
                <td width="15">2.</td>
                <td>Set the popping pressure of the safety valve to the maximum allowable working pressure.</td>
            </tr>
            <tr>
                <td width="10"></td>
                <td width="15">3.</td>
                <td class="underline"></td>
            </tr>
            <tr>
                <td width="10"></td>
                <td width="15">4.</td>
                <td class="underline"></td>
            </tr>
        </table>
        <table border="0">
            <tr><td height="10"></td></tr>
            <tr>
                <td width="80">Conducted by:</td>
                <td width="150" class="underline"></td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td >Checked by:</td>
                <td class="underline"></td>
            </tr>
            
        </table>
        ';
        $this->SetFont('helvetica','',10);
        $this->MultiCell(190, 0, $forms, 0, 'L', 0, 1, 9, 30, true, 0, true, true, 0, 'T', false);
    }

    public function Footer() {

        $footDetails = array(
            'revCode'=>'Rev. 1',
            'effectDate'=>'Effective Date: 01 September 2021',
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
