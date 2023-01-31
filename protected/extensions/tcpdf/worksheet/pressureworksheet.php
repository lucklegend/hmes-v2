<?php
require_once(dirname(__FILE__).'/../tcpdf.php');

class pressureworksheet extends TCPDF {
 
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
            'title'=>'CALIBRATION WORKSHEET OF BOURDON TUBE PRESSURE GAUGES',
            'code'=>'HME-CM-201-F01',
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
            <tr><td height="10"></td></tr>
            <tr>
                <td width="110">Type of Job</td>
                <td width="10">:</td>
                <td width="290">[&nbsp;&nbsp;&nbsp;] Calibration &nbsp;&nbsp;&nbsp; [&nbsp;&nbsp;&nbsp;] Partial &nbsp;&nbsp;&nbsp; [&nbsp;&nbsp;&nbsp;] On-site Calibration</td>
            </tr>
        </table>

        <table border="0">
            <tr>
                <td width="110">Instrument Description</td>
                <td width="10">:</td>
                <td width="150" class="underline">'.$sample->sampleName.'</td>
                <td width="110">Service Request No.</td>
                <td width="10">:</td>
                <td width="150" class="underline">'.$sample->requestId.'</td>
            </tr>
            <tr>
                <td width="110">Manufacturer\'s Name</td>
                <td width="10">:</td>
                <td width="150" class="underline">'.$sample->brand.'</td>
                <td width="110">Sample Code No.</td>
                <td width="10">:</td>
                <td width="150" class="underline">'.$sampleCode.'</td>
            </tr>
            <tr>
                <td>Model No.</td>
                <td>:</td>
                <td class="underline"></td>
                <td>Date Received</td>
                <td>:</td>
                <td class="underline">'.$receiveDate.'</td>
            </tr>
            <tr>
                <td>Serial No.</td>
                <td>:</td>
                <td class="underline">'.$sample->serial_no.'</td>
                <td>Date Calibrated</td>
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
                <td>Resolution</td>
                <td>:</td>
                <td class="underline">'.$sample->resolution.'</td>
                <td>Relative Humidity</td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
            <tr>
                <td>Location of Calibration</td>
                <td>:</td>
                <td class="underline"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table border="0">
            <tr><td height="10"></td></tr>
            <tr>
                <td>CALIBRATION METHOD:</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="540" style="text-align:justify;">The method of calibration is based on HME-CM-201, “Calibration of Bourdon Tube Pressure Gauges” in accordance with Guideline DKD-R 6.1 Edition 03/2014, “Calibration of Pressure Gauges”.
                </td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
        <table>
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
                <td height="20"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="20"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="20"></td>
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
                <td width="10"></td>
                <td>External Inspection:</td>
            </tr>
            <tr>
                <td width="20"></td>
                <td width="110">Pointers</td>
                <td width="155">[&nbsp;&nbsp;&nbsp;] satisfactory</td>
                <td width="255">[&nbsp;&nbsp;&nbsp;] with damage______________________</td>
            </tr>
            <tr>
                <td></td>
                <td>Threads</td>
                <td>[&nbsp;&nbsp;&nbsp;] satisfactory</td>
                <td>[&nbsp;&nbsp;&nbsp;] with damage______________________</td>
            </tr>
            <tr>
                <td></td>
                <td>Scaling Surface</td>
                <td>[&nbsp;&nbsp;&nbsp;] satisfactory</td>
                <td>[&nbsp;&nbsp;&nbsp;] with damage______________________</td>
            </tr>
            <tr>
                <td></td>
                <td>Scale / Graduation</td>
                <td>[&nbsp;&nbsp;&nbsp;] satisfactory</td>
                <td>[&nbsp;&nbsp;&nbsp;] with damage______________________</td>
            </tr>
            <tr>
                <td></td>
                <td>Missing / Broken parts</td>
                <td>[&nbsp;&nbsp;&nbsp;] none</td>
                <td>[&nbsp;&nbsp;&nbsp;] ________________________________</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="540">Visual inspection shows that the general condition and workmanship of the instrument were found __________________________________________________________________________________________________________________</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>PRELOADING:</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="20"></td>
                <td width="530">(Note: the preloading time at the highest value and the time between two preloadings should at least be 30 seconds.)</td>
            </tr>
            <tr><td height="10"></td></tr>            
        </table>
        <table>
            <tr>
                <td width="125" height="10"></td>
                <td rowspan="2" width="75" class="centerCell border">Preloading</td>
                <td colspan="2" width="190" class="centerCell border">Maximum Load ( ___________ )</td>
            </tr>
            <tr>
                <td height="10"></td>
                <td  width="95" class="centerCell border">Standard (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                <td  width="95" class="centerCell border">IUC (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            </tr>
            <tr>
                <td height="10"></td>
                <td class="centerCell border">1st</td>
                <td class="centerCell border"></td>
                <td class="centerCell border"></td>
            </tr>
            <tr>
                <td height="10"></td>
                <td class="centerCell border">2nd</td>
                <td class="centerCell border"></td>
                <td class="centerCell border"></td>
            </tr>
            <tr>
                <td height="10"></td>
                <td class="centerCell border">3rd</td>
                <td class="centerCell border"></td>
                <td class="centerCell border"></td>
            </tr>
        </table>
        <table>
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
            <tr><td height="10"></td></tr>
        </table>
        <table border="0">
            <tr><td height="10"></td></tr>  
            <tr>
                <td width="85">Sample Code No.</td>
                <td width="10">:</td>
                <td width="442" class="underline">'.$sampleCode.'</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="225">CALIBRATION:</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="20"></td>
                <td width="50">(Note: </td>
                <td>Load change + waiting time > 30 seconds</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Waiting time at upper limit > 5 minutes</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Waiting time before Trial 2 > 2 minutes)</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="125">Unit:________________</td>
                <td width="180"></td>
                <td>Starting Temperature:________________________</td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
        <table border="1">
            <tr>
                <td width="55" class="centerCell" rowspan="2">Measuring Point</td>
                <td width="85" class="centerCell" rowspan="2">Applied <br>Pressure <br> <i>(P)</i></td>
                <td width="122" class="centerCell" colspan="2">Trial 1</td>
                <td width="122" class="centerCell" colspan="2">Trial 2</td>
                <td width="70" class="centerCell" rowspan="2">Mean <i>(Ma)<br>(M1+M2+M3+M4)/4 </i></td>
                <td width="85" class="centerCell" rowspan="2">Measurement Deviation<i><br>(Ma - P)</i></td>
            </tr>
            <tr>
                <td class="centerCell">Up <br><i>(M1)</i></td>
                <td class="centerCell">Down <br><i>(M2)</i></td>
                <td class="centerCell">Up <br><i>(M3)</i></td>
                <td class="centerCell">Down <br><i>(M4)</i></td>
            </tr>
            ';
            for($i=1;$i<11;$i++){
                if($i==1){
                    $zero = "0.0";
                }else{
                    $zero = "";
                }
                $forms .='
                    <tr>    
                        <td height="10" class="centerCell">'.$i.'</td>
                        <td class="centerCell">'.$zero.'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                ';

            }
        $forms .='
        </table>
        <table>
             <tr>
                <td width="125"></td>
                <td width="180"></td>
                <td width="235" >Finishing Temperature:_______________________</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="550">If adjustment was made, repeat the calibration process.</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>PRELOADING:</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="20"></td>
                <td width="530">(Note: the preloading time at the highest value and the time between two preloadings should at least be 30 seconds.)</td>
            </tr>
            <tr><td height="10"></td></tr>            
        </table>
        <table>
            <tr>
                <td width="125" height="10"></td>
                <td rowspan="2" width="75" class="centerCell border">Preloading</td>
                <td colspan="2" width="190" class="centerCell border">Maximum Load ( ___________ )</td>
            </tr>
            <tr>
                <td height="10"></td>
                <td  width="95" class="centerCell border">Standard (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                <td  width="95" class="centerCell border">IUC (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            </tr>
            <tr>
                <td height="10"></td>
                <td class="centerCell border">1st</td>
                <td class="centerCell border"></td>
                <td class="centerCell border"></td>
            </tr>
            <tr>
                <td height="10"></td>
                <td class="centerCell border">2nd</td>
                <td class="centerCell border"></td>
                <td class="centerCell border"></td>
            </tr>
            <tr>
                <td height="10"></td>
                <td class="centerCell border">3rd</td>
                <td class="centerCell border"></td>
                <td class="centerCell border"></td>
            </tr>
        </table>
        <table>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="125">Unit:________________</td>
                <td width="180"></td>
                <td width="250">Starting Temperature:________________________</td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
        <table border="1">
            <tr>
                <td width="55" class="centerCell" rowspan="2">Measuring Point</td>
                <td width="85" class="centerCell" rowspan="2">Applied <br>Pressure <br> <i>(P)</i></td>
                <td width="122" class="centerCell" colspan="2">Trial 1</td>
                <td width="122" class="centerCell" colspan="2">Trial 2</td>
                <td width="70" class="centerCell" rowspan="2">Mean <i>(Ma)<br>(M1+M2+M3+M4)/4 </i></td>
                <td width="85" class="centerCell" rowspan="2">Measurement Deviation<i><br>(Ma - P)</i></td>
            </tr>
            <tr>
                <td class="centerCell">Up <br><i>(M1)</i></td>
                <td class="centerCell">Down <br><i>(M2)</i></td>
                <td class="centerCell">Up <br><i>(M3)</i></td>
                <td class="centerCell">Down <br><i>(M4)</i></td>
            </tr>
            ';
            for($i=1;$i<11;$i++){
                if($i==1){
                    $zero = "0.0";
                }else{
                    $zero = "";
                }
                $forms .='
                    <tr>    
                        <td height="10" class="centerCell">'.$i.'</td>
                        <td class="centerCell">'.$zero.'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                ';

            }
        $forms .='
        </table>
        <table>
            <tr>
                <td width="125"></td>
                <td width="180"></td>
                <td width="235" >Finishing Temperature:_______________________</td>
            </tr>
            <tr><td height="10"></td></tr>
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