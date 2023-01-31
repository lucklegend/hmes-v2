<?php
/*******************************************************
		name: Janeedi A. Grapa
		org: DOST-IX, 991-1024
		date created: April 26, 2017
	

********************************************************/
class RequestController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		/*return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);*/
		return array('rights');
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('analysisStatus', 'transferAnalysis', 'updateUser', 'cancelledAnalysis', 'printPDF', 'cancelledAnalysis'),
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */

	public function actionCancelledAnalysis()
	{
		
		if(isset($_POST['id']))
		$id=$_POST['id'];
		$tag = Tagging::model()->findByPk($id);
		$analysis = Analysis::model()->findByPk($id);

		$tagging=new CActiveDataProvider('Tagging', 
	 	array(
			'criteria'=>array(
		 	'condition'=>"analysisId='" .$id. "' AND status=3"
				 ),
			 )
		);

		echo CJSON::encode(array(
			'div'=>$this->renderPartial('_cancelledAnalysis', array('tagging' => $tagging, 'tag'=>$tag, 'analysis'=>$analysis),true,true)
		));
	}

	public function actionPrintPDF($id)
	{
		$request = Request::model()->findByPk($id);
		$pdf = Yii::createComponent('application.extensions.tcpdf.requestPdf', 
		                            'L', 'mm', array(66, 29), true, 'UTF-8');
		$pdf = new requestPdf('L', 'mm', array(66, 29), true, 'UTF-8', false);
		$pdf->SetAutoPageBreak(TRUE, 0);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$tagvs = array('p' => array(0 => array('h' => 0, 'n' => 0), 0 => array('h' => 0, 'n' => 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->SetMargins(0, 0, 0, true); 
		$barcode_style = array(
			'border' => 0,
			'padding' => 2,
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, 
			'module_width' => 1, 
			'module_height' => 1 
			);
        $classRows = '
                <style>
                  table {
                    font-style: arial;
                    border-top: 0px solid #000;
                    border-left: 0px solid #000;
                    width: 50%;
					table-layout:fixed;
					overflow: hidden;
					text-overflow: ellipsis;
					display:block;
					overflow: hidden;
					white-space: nowrap;
					margin-left:0;		
                  }
                  td{
                    border-right: 0px solid #000;
                    border-bottom: 0px solid #000;
					 margin: 0pt !important;
        			padding: 0pt !important;
					table-layout:fixed;
					width:180px;
					text-overflow: ellipsis;
					display:block;
					overflow: hidden;
					white-space: nowrap;
                  }
                </style>
            ';
				$pdf->SetFont('helvetica', '', 10);
                foreach($request->samps as $sample){
				$pdf->AddPage();
						$year = substr($request->requestDate, 0,4);
						$sampleBarcode = $sample->id . ' ' . $year. ' ' .$sample->sampleCode;
						$limitname = substr($sample->sampleName, 0,22);
						$pdf->write2DBarcode($sampleBarcode, 'QRCODE,L', 42, 0,24,24, $barcode_style, 'T');
						$title = '<b>'.$sample->sampleCode.'</b> <font size="6"><i>'.$limitname.'</i><br><i><b>Received:</b>&nbsp;'.$request->requestDate.'&nbsp;&nbsp;<b>Due:</b>&nbsp;'.$request->reportDue.'</br></i></font>';
						$pdf->writeHTMLCell(0,0,0,2, $title, 0, 0);
						$style = array('width' => 0.2, 'cap' => 0, 'join' => 0, 'dash' => 0, 'color' =>'#000000');
						$pdf->Line(1, 6, 43, 6, $style);
						$text = '<font size="6">WI-003-F1';
						$pdf->writeHTMLCell(0,0,43,22, $classRows.$text, 0, 0);
						$text2 = '<font size="6">Rev 01/01.02.12';
						$pdf->writeHTMLCell(0,0,43,24.5, $classRows.$text2, 0, 0);
						$top = 8;
						$i = 1;
							foreach($sample->analyses as $analysis){
											$rows = '<font size="7">'.$analysis->testName.'</font><br>';	
											$pdf->writeHTMLCell(0,0,0,$top, $classRows.$rows, 0, 0);
											$top = $top + 3; 

											if ($i++ == 6)
											break;
									}  		 
					$pdf->lastPage();   		        
        }   
		$pdf->IncludeJS("print();");
        $pdf->Output($request->requestRefNum.'.pdf', 'I');
		exit ();
	}	
}