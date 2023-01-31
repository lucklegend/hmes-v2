<?php
require_once(dirname(__FILE__).'/../tcpdf.php');

class textiletapeworksheet extends TCPDF {
 
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
            'title'=>'CALIBRATION WORKSHEET OF TEXTILE TAPE MEASURE',
            'code'=>'HME-CM-301-F01',
        );
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
        $this->SetFont('helvetica','B',13);
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
          td.centerCell{
            text-align: center;
            vertical-align: middle;
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
            <tr><td height="7"></td></tr>
            <tr>
                <td width="110">Type of Job</td>
                <td width="10">:</td>
                <td width="450">[&nbsp;&nbsp;&nbsp;] CALIBRATION &nbsp;&nbsp;[&nbsp;&nbsp;&nbsp;] PARTIAL &nbsp;&nbsp; [&nbsp;&nbsp;&nbsp;] On-site Calibration &nbsp;&nbsp; [&nbsp;&nbsp;&nbsp;] Other__________________</td>
            </tr>
        </table>
        <table border="0">
            <tr>
                <td width="110">Instrument Description</td>
                <td width="10">:</td>
                <td width="150">[&nbsp;&nbsp;&nbsp;] Textile Tape Measure </td>
                <td width="110">[&nbsp;&nbsp;&nbsp;] Others</td>
                <td width="10">:</td>
                <td width="150" class="underline"></td>
            </tr>
            <tr>
                <td>Manufacturer\'s Name</td>
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
                <td width="110">Sample Code No.</td>
                <td width="10">:</td>
                <td width="150" class="underline">'.$sampleCode.'</td>
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
                <td>Range</td>
                <td>:</td>
                <td class="underline">'.$sample->capacity_range.'</td>
                <td>Date Calibrated</td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
            <tr>
                <td>Resolution</td>
                <td>:</td>
                <td class="underline">'.$sample->resolution.'</td>
                <td>Ambient Temperature</td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
            <tr>
                <td>Location of Calibration</td>
                <td>:</td>
                <td class="underline"></td>
                <td>Relative Humidity</td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
        </table>
        <table border="0">
            <tr><td height="5"></td></tr>
            <tr>
                <td>CALIBRATION METHOD:</td>
            </tr>
            <tr><td height="5"></td></tr>
            <tr>
                <td width="540" style="text-align:justify;">The instrument was calibrated in accordance with HME-CM-301, “Calibration Method of Textile Tape Measure” based on Japan Industrial Standard, JIS 7522:1993.
                </td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
            <tr>
                <td>STANDARD USED:</td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
        <table border="1">
            <tr>
                <td width="125" class="centerCell">Description</td>
                <td width="95" class="centerCell">Serial No.</td>
                <td width="95" class="centerCell">Equipment Code</td>
                <td width="95" class="centerCell">Certificate No.</td>
                <td width="125" class="centerCell">Traceable to</td>
            </tr>
            <tr>
                <td height="15"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="15"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="15"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table>
            <tr><td height="10"></td></tr>
        <table>
            <tr>
                <td>PRELIMINARY EVALUATION:</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="20"></td>
                <td width="110">Scale/Graduations</td>
                <td width="185">[&nbsp;&nbsp;&nbsp;] readable</td>
                <td width="225">[&nbsp;&nbsp;&nbsp;] unsatisfactory</td>
            </tr>
            <tr>
                <td></td>
                <td>Missing / Broken parts</td>
                <td>[&nbsp;&nbsp;&nbsp;] ______________________________</td>
                <td>[&nbsp;&nbsp;&nbsp;] none</td>
            </tr>
            <tr style="height:5px;padding:0px;line-height:0px;"><td height="10" style="height:5px;"></td></tr>
            <tr>
                <td width="540">Visual inspection shows that the general condition and workmanship of the instrument were found __________________________________________________________________________________________________________________</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="225">CALIBRATION RESULTS:</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="100" colspan="5">Tolerances of Length</td>
            </tr>
            <tr>
                <td width="20" height="15"></td>
                <td width="500" colspan="5" align="right">(Temp. before measurement:___________)</td>
            </tr>
        </table>
        <table>
            <tr>
                <td width="20"></td>
                <td width="60" class="centerCell border" rowspan="2">Nominal Value, (mm)</td>
                <td width="300" class="centerCell border" colspan="5">Error Reading,__________</td>
                <td width="70" class="centerCell border" rowspan="2">Average<br>(mm)</td>
                <td width="70" class="centerCell border" rowspan="2"><span style="font-size: 8px;">Error of Ref. Standards (mm)</span></td>
            </tr>
            <tr>
                <td></td>
                <td class="centerCell border">1</td>
                <td class="centerCell border">2</td>
                <td class="centerCell border">3</td>
                <td class="centerCell border">4</td>
                <td class="centerCell border">5</td>
            </tr>
            ';
            for($i=0;$i<12;$i++){
                $forms .='
                    <tr>    
                        <td></td>
                        <td class="border"></td>
                        <td class="border"></td>
                        <td class="border"></td>
                        <td class="border"></td>
                        <td class="border"></td>
                        <td class="border"></td>
                        <td class="border"></td>
                        <td class="border"></td>
                    </tr>
                ';

            }
        $forms .='
            <tr>
                <td width="20"></td>
                <td width="500" colspan="5" align="right">(Temp. after measurement:___________)</td>
            </tr>
        </table>
        <table>     
            <tr>
                <td width="250">Calibrated by: _______________________</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>Checked by: ________________________</td>
            </tr>
        </table>
        ';
        $this->SetFont('helvetica','',10);
        $this->MultiCell(190, 0, $forms, 0, 'L', 0, 1, 9, 30, true, 0, true, true, 0, 'T', false);
    }

    public function Footer() {

        $footDetails = array(
            'revCode'=>'Rev. 0',
            'effectDate'=>'Effective Date: ',
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