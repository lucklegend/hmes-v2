<?php
require_once(dirname(__FILE__).'/../tcpdf.php');

class storagetankworksheet extends TCPDF {
 
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
            'title'=>'CALIBRATION WORKSHEET OF LPG STORAGE TANK',
            'code'=>'HME-CM-501-F01',
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
        
        $style= '
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
        ';
        $forms = '
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
                <td width="420">[&nbsp;&nbsp;&nbsp;] Calibration &nbsp;&nbsp; [&nbsp;&nbsp;&nbsp;] Partial &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [&nbsp;&nbsp;&nbsp;] On-site Calibration&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [&nbsp;&nbsp;&nbsp;] Others</td>
            </tr>
        </table>

        <table border="0">
            <tr>
                <td width="110">Equipment Description</td>
                <td width="10">:</td>
                <td width="150" class="underline">'.$sample->brand.'</td>
                <td width="110"></td>
                <td width="10"></td>
                <td width="150"></td>
            </tr>
            <tr>
                <td>Manufacturer\'s Name</td>
                <td>:</td>
                <td class="underline">'.$sample->model_no.'</td>
                <td>Service Request No.</td>
                <td>:</td>
                <td class="underline">'.$request->requestRefNum.'</td>
            </tr>
            <tr>
                <td>Serial No.</td>
                <td>:</td>
                <td class="underline">'.$sample->serial_no. '</td>
                <td>Sample Code No.</td>
                <td>:</td>
                <td class="underline">'.$sampleCode.'</td>
            </tr>
            <tr>
                <td>Model No.</td>
                <td>:</td>
                <td class="underline">'.$sample->model_no. '</td>
                <td>Shell Thickness</td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
            <tr>
                <td>Product To Be Stored</td>
                <td>:</td>
                <td class="underline"></td>
                <td colspan="3">Length of High End from Tip of </td>
            </tr>
            <tr>
                <td>Barrel Type</td>
                <td>:</td>
                <td class="underline"></td>
                <td>Cylinder to Slip Gauge</td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
            <tr>
                <td>Head Type</td>
                <td>:</td>
                <td class="underline"></td>
                <td>Tilt/Slope</td>
                <td>:</td>
                <td width="170">X=__________ &nbsp;Y=___________</td>
            </tr>
            <tr>
                <td>Gauge Type</td>
                <td>:</td>
                <td class="underline"></td>
                <td>Date Received</td>
                <td>:</td>
                <td width="150" class="underline">'.$receiveDate. '</td>
            </tr>
            <tr>
                <td>Range</td>
                <td>:</td>
                <td class="underline"></td>
                <td>Date Calibrated</td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
            <tr>
                <td>Resolution</td>
                <td>:</td>
                <td class="underline"></td>
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
            <tr><td height="10"></td></tr>
            <tr>
                <td>CALIBRATION METHOD:</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="540" style="text-align:justify;">The instrument was calibrated in accordance with HME-CM-501, “Calibration Method for LPG Storage Tank Using Geometrical Method” based on API MPMS Chapter 2.2E/ISO 12917-1:2002.
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
        <table border="0">
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
                <td width="545" height="15">Circumference of Cylinder: ___________ unit</td>
            </tr>
        </table>
        <table border="0">
            <tr>
                <td width="20"></td>
                <td width="85" class="border centerCell">Trial 1</td>
                <td width="85" class="border centerCell">Trial 2</td>
                <td width="85" class="border centerCell">Trial 3</td>
                <td width="85" class="border centerCell">Trial 4</td>
                <td width="85" class="border centerCell">Trial 5</td>
                <td width="85" class="border centerCell">Mean Value</td>
            </tr>
            <tr>
                <td></td>
                <td class="border" ></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
            </tr>
        </table>
        <table>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="545" height="15">Length of Cylinder: ___________ unit</td>
            </tr>
        </table>
        <table border="0">
            <tr>
                <td width="20"></td>
                <td width="85" class="border centerCell">Trial 1</td>
                <td width="85" class="border centerCell">Trial 2</td>
                <td width="85" class="border centerCell">Trial 3</td>
                <td width="85" class="border centerCell">Trial 4</td>
                <td width="85" class="border centerCell">Trial 5</td>
                <td width="85" class="border centerCell">Mean Value</td>
            </tr>
            <tr>
                <td></td>
                <td class="border" ></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
            </tr>
        </table>
        <table>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="545" height="15">Circumference of Head / 2: ___________ unit</td>
            </tr>
        </table>
        <table border="0">
            <tr>
                <td width="20"></td>
                <td width="85" class="border centerCell">Trial 1</td>
                <td width="85" class="border centerCell">Trial 2</td>
                <td width="85" class="border centerCell">Trial 3</td>
                <td width="85" class="border centerCell">Trial 4</td>
                <td width="85" class="border centerCell">Trial 5</td>
                <td width="85" class="border centerCell">Mean Value</td>
            </tr>
            <tr>
                <td></td>
                <td class="border" ></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
            </tr>
        </table>
        ';
        $this->SetFont('helvetica','',10);
        $this->MultiCell(190, 0, $style.$forms, 0, 'L', 0, 1, 9, 30, true, 0, true, true, 0, 'T', false);
        $this->AddPage();
        $forms2 = '
        <table>
            <tr>
                <td width="90">Sample Code No.</td>
                <td width="10">:</td>
                <td width="150" class="underline">' . $sampleCode . '</td>
            </tr>
        </table>
        <table>
            <tr><td height="10"></td></tr>
            <tr>
                <td>CALIBRATION RESULTS:</td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
        <table>
            <tr>
                <td width="10" height="15"></td>
                <td width="140" colspan="2">Tolerances of Length</td>
                <td width="195" colspan="3"></td>
                <td width="195" colspan="3">(Temp. before measurement: _________ )</td>
            </tr>
            <tr>
                <td width="10"></td>
                <td width="70" class="border centerCell" rowspan="2">Nominal Value (_______)</td>
                <td width="70" class="border centerCell" rowspan="2">Error of Ref. Standards (_______)</td>
                <td width="325" height="15" class="border centerCell" colspan="5">Slip Gauge Readings,________</td>
                <td width="65" class="border centerCell" rowspan="2">Mean Value <i>(T1+T2+T3+T4+T5) / 5</i> (_______)</td>
            </tr>
            <tr>
                <td width="10"></td>
                <td width="65" class="border centerCell">Trial 1</td>
                <td width="65" class="border centerCell">Trial 2</td>
                <td width="65" class="border centerCell">Trial 3</td>
                <td width="65" class="border centerCell">Trial 4</td>
                <td width="65" class="border centerCell">Trial 5</td>
            </tr>
        ';
        for($i=1;$i<38;$i++){
            $forms2 .= '
                <tr>
                    <td height="15"></td>
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
        $forms2 .= '
        </table>
        ';
        $this->MultiCell(190, 0, $style.$forms2, 0, 'L', 0, 1, 9, 30, true, 0, true, true, 0, 'T', false);

        for($a=0;$a<4;$a++){
            $this->AddPage();
            $forms2 = '
            <table>
                <tr>
                    <td width="90">Sample Code No.</td>
                    <td width="10">:</td>
                    <td width="150" class="underline">' . $sampleCode . '</td>
                </tr>
                <tr><td height="10"></td></tr>
            </table>
            <table>
                <tr>
                    <td width="10"></td>
                    <td width="70" class="border centerCell" rowspan="2">Nominal Value (_______)</td>
                    <td width="70" class="border centerCell" rowspan="2">Error of Ref. Standards (_______)</td>
                    <td width="325" height="15" class="border centerCell" colspan="5">Slip Gauge Readings,________</td>
                    <td width="65" class="border centerCell" rowspan="2">Mean Value <i>(T1+T2+T3+T4+T5) / 5</i> (_______)</td>
                </tr>
                <tr>
                    <td width="10"></td>
                    <td width="65" class="border centerCell">Trial 1</td>
                    <td width="65" class="border centerCell">Trial 2</td>
                    <td width="65" class="border centerCell">Trial 3</td>
                    <td width="65" class="border centerCell">Trial 4</td>
                    <td width="65" class="border centerCell">Trial 5</td>
                </tr>
            ';
                for ($i = 1; $i < 41; $i++) {
                    $forms2 .= '
                    <tr>
                        <td height="15"></td>
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
                $forms2 .= '
            </table>
            ';
            $this->MultiCell(190, 0, $style . $forms2, 0, 'L', 0, 1, 9, 30, true, 0, true, true, 0, 'T', false);
        }
        $this->AddPage();
        $forms2 = '
            <table>
                <tr>
                    <td width="90">Sample Code No.</td>
                    <td width="10">:</td>
                    <td width="150" class="underline">' . $sampleCode . '</td>
                </tr>
                <tr><td height="10"></td></tr>
            </table>
            <table>
                <tr>
                    <td width="10"></td>
                    <td width="70" class="border centerCell" rowspan="2">Nominal Value (_______)</td>
                    <td width="70" class="border centerCell" rowspan="2">Error of Ref. Standards (_______)</td>
                    <td width="325" height="15" class="border centerCell" colspan="5">Slip Gauge Readings,________</td>
                    <td width="65" class="border centerCell" rowspan="2">Mean Value <i>(T1+T2+T3+T4+T5) / 5</i> (_______)</td>
                </tr>
                <tr>
                    <td width="10"></td>
                    <td width="65" class="border centerCell">Trial 1</td>
                    <td width="65" class="border centerCell">Trial 2</td>
                    <td width="65" class="border centerCell">Trial 3</td>
                    <td width="65" class="border centerCell">Trial 4</td>
                    <td width="65" class="border centerCell">Trial 5</td>
                </tr>
            ';
        for ($i = 1; $i < 40; $i++) {
            $forms2 .= '
                    <tr>
                        <td height="15"></td>
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
        $forms2 .= '
                <tr>
                    <td width="10" height="15"></td>
                    <td width="140" colspan="2"></td>
                    <td width="195" colspan="3"></td>
                    <td width="195" colspan="3">(Temp. after measurement: _________ )</td>
                </tr>
            </table>
            ';
        $this->MultiCell(190, 0, $style . $forms2, 0, 'L', 0, 1, 9, 30, true, 0, true, true, 0, 'T', false);
        $this->AddPage();
        $forms3 = '
            <table>
                <tr>
                    <td width="90">Sample Code No.</td>
                    <td width="10">:</td>
                    <td width="150" class="underline">' . $sampleCode . '</td>
                </tr>
                <tr><td height="10"></td></tr>
            </table>
        ';
        $this->MultiCell(190, 0, $style . $forms3, 0, 'L', 0, 1, 9, 30, true, 0, true, true, 0, 'T', false);
        
        $this->setJPEGQuality(100);
        $image_file = 'http://localhost' . Yii::app()->request->baseUrl . '/images/storage_tank.jpg';
        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
        $this->Image($image_file, 8, 35, 195, '', 'JPEG', '', '', false, 300, '', false, false, 0, false, false, false);
        $this->SetAlpha(1);
        $forms4 ='
        <table>
            <tr>
                <td>CALIBRATION RESULTS:</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="545" height="15">Outside Diameter: ___________ unit</td>
            </tr>
        </table>
        <table border="0">
            <tr>
                <td width="20"></td>
                <td width="85" class="border centerCell">Trial 1</td>
                <td width="85" class="border centerCell">Trial 2</td>
                <td width="85" class="border centerCell">Trial 3</td>
                <td width="85" class="border centerCell">Trial 4</td>
                <td width="85" class="border centerCell">Trial 5</td>
                <td width="85" class="border centerCell">Mean Value</td>
            </tr>
            <tr>
                <td height="20"></td>
                <td class="border" ></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
            </tr>
        </table>
        <table>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="545" height="15">E1: ___________ unit</td>
            </tr>
        </table>
        <table border="0">
            <tr>
                <td width="20"></td>
                <td width="85" class="border centerCell">Trial 1</td>
                <td width="85" class="border centerCell">Trial 2</td>
                <td width="85" class="border centerCell">Trial 3</td>
                <td width="85" class="border centerCell">Trial 4</td>
                <td width="85" class="border centerCell">Trial 5</td>
                <td width="85" class="border centerCell">Mean Value</td>
            </tr>
            <tr>
                <td height="20"></td>
                <td class="border" ></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
            </tr>
        </table>
        <table>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="545" height="15">E2: ___________ unit</td>
            </tr>
        </table>
        <table border="0">
            <tr>
                <td width="20"></td>
                <td width="85" class="border centerCell">Trial 1</td>
                <td width="85" class="border centerCell">Trial 2</td>
                <td width="85" class="border centerCell">Trial 3</td>
                <td width="85" class="border centerCell">Trial 4</td>
                <td width="85" class="border centerCell">Trial 5</td>
                <td width="85" class="border centerCell">Mean Value</td>
            </tr>
            <tr>
                <td height="20"></td>
                <td class="border" ></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
            </tr>
        </table>
        ';
        $this->MultiCell(190, 0, $style . $forms4, 0, 'L', 0, 1, 9, 130, true, 0, true, true, 0, 'T', false);
    }

    public function Footer() {
        
        $this->SetFont('helvetica', '', 10);
        $footerCheckby = '
        <table border="0">
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
        $this->MultiCell(190, 0, $footerCheckby, 0, 'L', 0, 1, 10, 271, true, 0, true, true, 0, 'T', false);
        $footDetails = array(
            'revCode'=>'Rev. 0',
            'effectDate'=>'Effective Date: 04 September 2021',
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
