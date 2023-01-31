<?php
require_once(dirname(__FILE__).'/../tcpdf.php');

class loadworksheet extends TCPDF {
 
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
            'title'=>'ELEVATOR PERFORMANCE LOAD TESTING WORKSHEET',
            'code'=>'HME-TM-201-F01',
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
                <td width="105">Company</td>
                <td width="10">:</td>
                <td width="420" class="underline">'.$request->customer->customerName.'</td>
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
            <tr>
                ';
                if($request->customer->email != "" || $request->customer->email != NULL){
                    $contactInformation = $request->contact_number.' / '.$request->customer->email;
                }else{
                    $contactInformation = $request->contact_number;
                }
                $forms .= '
                <td>Contact Information.</td>
                <td>:</td>
                <td class="underline">'.$contactInformation.'</td>
            </tr>
        </table>
        <table border="0">
            <tr><td height="10"></td></tr>
            <tr>
                <td width="110">Type of Job</td>
                <td width="10">:</td>
                <td width="150">[&nbsp;&nbsp;&nbsp;] Load Testing</td>
                <td width="270">[&nbsp;&nbsp;&nbsp;] Others______________________ </td>
            </tr>
        </table>

        <table border="0">
            <tr>
                <td width="110">Equipment Description</td>
                <td width="10">:</td>
                <td width="150">[&nbsp;&nbsp;&nbsp;] Elevator<br>[&nbsp;&nbsp;&nbsp;] Dumbwaiter</td>
                <td width="270">[&nbsp;&nbsp;&nbsp;] Others______________________ </td>
            </tr>
            <tr>
                <td width="110">Manufacturer\'s Name</td>
                <td width="10">:</td>
                <td width="150" class="underline">'.$sample->brand.'</td>
                <td width="110">Service Request No.</td>
                <td width="10">:</td>
                <td width="150" class="underline">'.$sample->requestId.'</td>
                
            </tr>
            <tr>
                <td>Serial No.</td>
                <td>:</td>
                <td class="underline">'.$sample->serial_no.'</td>
                <td width="110">Sample Code No.</td>
                <td width="10">:</td>
                <td width="150" class="underline">'.$sampleCode.'</td>
                
            </tr>
            <tr>
                <td>Capacity</td>
                <td>:</td>
                <td class="underline">'.$sample->capacity_range.'</td>
                <td width="110">Date Received</td>
                <td width="10">:</td>
                <td width="150" class="underline">'.$receiveDate.'</td>
                
            </tr>
            <tr>
                <td>Rated Motor Power</td>
                <td>:</td>
                <td class="underline"></td>
                <td>Date Tested</td>
                <td>:</td>
                <td class="underline"></td>
            </tr>
            <tr>
                <td>Rated Motor Current</td>
                <td>:</td>
                <td class="underline"></td>
                <td>Ref. Temperature</td>
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
            <tr><td height="10"></td></tr>
            <tr>
                <td>TEST METHOD:</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="540" style="text-align:justify;">The test method is based on HME-TM-201 “Load Testing of Elevators” in accordance with American Society of Mechanical Engineers (ASME) A17.1:2007, Unified Facilities Guide Specifications (UFGS) 14-21-13:2016 and DOLE Occupational Safety Health Standards (DOLE-OSHS).
                </td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>PRELIMINARY EVALUATION:</td>
            </tr>
            <tr><td height="10"></td></tr>
             <tr>
                <td width="120">Markings</td>
                <td width="155">[&nbsp;&nbsp;&nbsp;] readable</td>
                <td width="135">[&nbsp;&nbsp;&nbsp;] unsatisfactory</td>
            </tr>
            <tr>
                <td>Missing / Worn out parts</td>
                <td>[&nbsp;&nbsp;&nbsp;] _______________________</td>
                <td>[&nbsp;&nbsp;&nbsp;] none</td>
            </tr>
            <tr>
                <td>Overload Test</td>
                <td>[&nbsp;&nbsp;&nbsp;] _____________ kg</td>
                <td> </td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="540">Visual inspection shows that the general condition and workmanship of the instrument were found ________________________________________________________________________________________________________________</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>PERFORMANCE TEST RESULTS:</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="15"></td>
                <td width="130">Before Load Test: </td>
                <td width="130"></td>
                <td width="130">After  Load Test: </td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
        <table>
            <tr>
                <td width="15" height="20"></td>
                <td width="90" rowspan="2" class="border centerCell">Wire Rope No. </td>
                <td width="150" colspan="3" class="border centerCell">Wire Rope Diameter </td>
                <td width="40"></td>
                <td width="90" rowspan="2" class="border centerCell">Wire Rope No. </td>
                <td width="150" colspan="3" class="border centerCell">Wire Rope Diameter </td>
            </tr>
            <tr>
                <td></td>
                <td class="border centerCell">Trial 1 </td>
                <td class="border centerCell">Trial 2 </td>
                <td class="border centerCell">Trial 3 </td>
                <td></td>
                <td class="border centerCell">Trial 1 </td>
                <td class="border centerCell">Trial 2 </td>
                <td class="border centerCell">Trial 3 </td>
            </tr>
        ';

        for($a=0;$a<5;$a++){
            $forms .='
                <tr>    
                    <td height="20"></td>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td></td>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td class="border"></td>
                </tr>
            ';

        }

        $forms .='
        </table>
        <table>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>Conducted by: _______________________</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>Checked by: &nbsp;&nbsp;&nbsp;_______________________</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
        </table>
        <table border="0">
            <tr><td height="10"></td></tr>  
            <tr>
                <td width="85">Sample Code No.</td>
                <td width="10">:</td>
                <td width="115" class="underline">'.$sampleCode.'</td>
                <td width="85"></td>
                <td width="10"></td>
                <td width="225"></td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="225">WITHOUT LOAD:</td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
        <table border="1">
            <tr>
                <td width="110" class="centerCell" rowspan="2">Motor Current (A)</td>
                <td width="140" class="centerCell" colspan="2">Trial 1</td>
                <td width="140" class="centerCell" colspan="2">Trial 2</td>
                <td width="140" class="centerCell" colspan="2">Trial 3</td>
            </tr>
            <tr>
                <td class="centerCell">Up</td>
                <td class="centerCell">Down</td>
                <td class="centerCell">Up</td>
                <td class="centerCell">Down</td>
                <td class="centerCell">Up</td>
                <td class="centerCell">Down</td>
            </tr>
            ';
            for($i=1;$i<4;$i++){
                $forms .='
                    <tr>    
                        <td height="20">Line '.$i.'</td>
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
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="225">WITH LOAD:</td>
            </tr>
            <tr>
                <td width="225">___________ kg</td>
            </tr>
            <tr><td height="10"></td></tr>
        </table>
        <table border="1">
            <tr>
                <td width="110" class="centerCell" rowspan="2">Motor Current (A)</td>
                <td width="140" class="centerCell" colspan="2">Trial 1</td>
                <td width="140" class="centerCell" colspan="2">Trial 2</td>
                <td width="140" class="centerCell" colspan="2">Trial 3</td>
            </tr>
            <tr>
                <td class="centerCell">Up</td>
                <td class="centerCell">Down</td>
                <td class="centerCell">Up</td>
                <td class="centerCell">Down</td>
                <td class="centerCell">Up</td>
                <td class="centerCell">Down</td>
            </tr>
            ';
            for($i=1;$i<4;$i++){
                $forms .='
                    <tr>    
                        <td height="20">Line '.$i.'</td>
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
            <tr><td height="10"></td></tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>REMARKS:</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td width="10"></td>
                <td width="15">1. </td>
                <td width="515" style="text-align:justify;">The testing results in the table refer to the date of test and should the equipment be modified or changed in any way, the result may not be valid and the unit may require retesting.</td>
            </tr>
            <tr>
                <td width="10"></td>
                <td width="15">2. </td>
                <td width="515" style="text-align:justify;">The user should determine the suitability of this equipment for its equipment use.</td>
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
            <tr>
                <td>Conducted by: _______________________</td>
            </tr>
            <tr><td height="10"></td></tr>
            <tr>
                <td>Checked by: &nbsp;&nbsp;&nbsp;_______________________</td>
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