<?php

class ReferralController extends Controller
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
				'actions'=>array('index','view', 'searchCustomer', 'searchSample'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		//Resource Address
        $url = Yii::app()->Controller->getApiUrl().'/lab/api/view/model/referrals/id/'.$id;
			
		$response = Yii::app()->curl->get($url);
		
		//Send Request to Resource
		//$client = curl_init();
		
	    //curl_setopt($client, CURLOPT_URL, $url);
		//curl_setopt($client, CURLOPT_RETURNTRANSFER, TRUE);
		//curl_setopt($client, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		//curl_setopt($client, CURLOPT_HEADER, FALSE);

		//Get Response from Resource
		//$response = curl_exec($client);
		//curl_close($client);
		
		//Decode
		$referral = json_decode($response, true);
		
		$subtotal = 0;
		foreach($referral["analyses"] as $analysis)
		{
			$subtotal += $analysis["fee"];
		}
		$discount = $subtotal*($referral["discount"]["rate"]/100);
		$computeTotal = ($subtotal-$discount);
		
		//Backwards compatability.
		if(!function_exists('json_last_error')){
			if($referral === false || $referral === null){
				throw new Exception('Could not decode JSON!');
			}
		} else{
			
			//Get the last JSON error.
			$jsonError = json_last_error();
			
			//In some cases, this will happen.
			if(is_null($referral) && $jsonError == JSON_ERROR_NONE){
				throw new Exception('Could not decode JSON!');
			}
			
			//If an error exists.
			if($jsonError != JSON_ERROR_NONE){
				$error = 'Could not decode JSON! ';
				
				//Use a switch statement to figure out the exact error.
				switch($jsonError){
					case JSON_ERROR_DEPTH:
						$error .= 'Maximum depth exceeded!';
					break;
					case JSON_ERROR_STATE_MISMATCH:
						$error .= 'Underflow or the modes mismatch!';
					break;
					case JSON_ERROR_CTRL_CHAR:
						$error .= 'Unexpected control character found';
					break;
					case JSON_ERROR_SYNTAX:
						$error .= 'Malformed JSON';
					break;
					case JSON_ERROR_UTF8:
						 $error .= 'Malformed UTF-8 characters found!';
					break;
					default:
						$error .= 'Unknown error!';
					break;
				}
				throw new Exception($error);
			}
		}
        $sampleDataProvider = new CArrayDataProvider($referral['samples'], 
			array(
				'pagination'=>false,
			)
		);
		
		//echo "<pre>";
		//print_r($referral['analyses']['package']);
		//echo "</pre>";
		
		//$arr = array(1, 2, 3, 4);
		/*foreach ($referral['analyses'] as $analysis) {
			if($analysis["package"] == 2){
				//echo "Package name"."<br />";
				$dataprovider = 
			} else {
				//echo "Not package name<br />";
			}
		}*/
		
		$analysisDataProvider = new CArrayDataProvider($referral['analyses'], 
			array(
				'pagination'=>false,
			)
		);
        $generated = $this->checkIfGeneratedSamples($referral);
        
		$this->render('view',array(
			//'model'=>$model,
			//'response'=>$response,
			'referral'=>$referral,
            'sampleDataProvider'=>$sampleDataProvider,
            'analysisDataProvider'=>$analysisDataProvider,
			'discount'=>$discount,
			'subtotal'=>$subtotal,
			'computeTotal'=>$computeTotal,
			//'referrals'=>new CArrayDataProvider($referrals)
            'generated'=>$generated
		));
	}
	
	/** Previous logic for function "checkIfGeneratedSamples" : Start **/
	function checkIfGeneratedSamples($referral)
	{
        $sampleCode = Samplecode::model()->find(array(
    		'condition'=>'rstl_id=:rstl_id AND requestId=:requestId',
    		'params'=>array(':rstl_id' => Yii::app()->Controller->getRstlId(), ':requestId' => $referral['referralCode'])
		));
        
        if($sampleCode)
            return true;
        else
            return false;
		/*$lastGenerated = Generatedrequest::model()->find(array(
			'order'=>'id DESC',
			//'limit'=>1, //not needed with find()
    		'condition'=>'rstl_id=:rstl_id AND labId=:labId AND year=:year',
    		'params'=>array(':rstl_id' => Yii::app()->Controller->getRstlId(), ':labId' => $request->labId, ':year' => date('Y', strtotime($request->requestDate)))
		));	
		
		$currentRequest = Requestcode::model()->find(array(
    		'condition'=>'rstl_id=:rstl_id AND requestRefNum=:requestRefNum',
    		'params'=>array(':rstl_id' => Yii::app()->Controller->getRstlId(), ':requestRefNum' => $request->requestRefNum)
		));
		
		return ($currentRequest->number - $lastGenerated->number);
        */
	}
	
    /**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Referral;
		//$model->paymentType = 1;
		//$model->labId = 1;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Request']))
		{
			$model->attributes=$_POST['Request'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Request']))
		{
			$model->attributes=$_POST['Request'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionCancel($id)
	{
		Request::model()->updateByPk($id, 
			array('cancelled'=>1, 'total'=>0,
			));
		$request = $this->loadModel($id);
		foreach($request->samps as $samples){
			Sample::model()->updateByPk($samples->id, 
				array('cancelled'=>1,
			));
		}
		foreach($request->anals as $analysis){
			Analysis::model()->updateByPk($analysis->id, 
				array('cancelled'=>1, 'fee'=>0,
			));
		}
		
		//$this->loadModel($id);
		//print_r($request->samps);
		//echo $id;
	}
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Request');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		//Resource Address
        $url = Yii::app()->Controller->getApiUrl().'/lab/api/list/model/referrals/agency/'.Yii::app()->Controller->getRstlId();
                
        $response = Yii::app()->curl->get($url);
        
		//Decode
		$referrals = json_decode($response, true);
        
        
        $dataProvider = new CArrayDataProvider($referrals,
            array(
                'sort'=>array(
                    'attributes'=>array('desc'=>'id ASC'),
                    //'defaultOrder'=>$model->default_order,
        )));
            
		$this->render('admin',array(
			'model'=>$model,
            'hahaha'=>$referrals,
			'referrals'=>$dataProvider,
            
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Request the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Request::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Request $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='request-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	function actionSearchCustomer(){
		
		if (!empty($_GET['term'])) {
			$sql = 'SELECT id as id, customerName as customerName, address as address, tel as tel, fax as fax, customerName as label';
			$sql .= ' FROM ulimslab.customer WHERE customerName LIKE :qterm OR head LIKE :qterm AND rstl_id = '.Yii::app()->getModule('user')->user()->profile->getAttribute('pstc');
			$sql .= ' GROUP BY customerName ORDER BY customerName ASC';
			$command = Yii::app()->db->createCommand($sql);
			$qterm = $_GET['term'].'%';
			$command->bindParam(":qterm", $qterm, PDO::PARAM_STR);
			$result = $command->queryAll();
			//$_SESSION['test'] = $result; 
			echo CJSON::encode($result); exit;
		  } else {
			return false;
		  }
	}
	
	function actionSearchSample(){
		
		if (!empty($_GET['term'])) {
			//$sql = 'SELECT id as id, name as name, description as description, CONCAT(name,": ",description) as label';
			$sql = 'SELECT id as id, name as name, description as description, name as label';
			$sql .= ' FROM ulimslab.samplename WHERE name LIKE :qterm';
			$sql .= ' ORDER BY name ASC';
			$command = Yii::app()->db->createCommand($sql);
			$qterm = $_GET['term'].'%';
			$command->bindParam(":qterm", $qterm, PDO::PARAM_STR);
			$result = $command->queryAll();
			//$_SESSION['test'] = $result; 
			echo CJSON::encode($result); exit;
		  } else {
			return false;
		  }
	}
	
	public function behaviors()
    {
        return array(
            'eexcelview'=>array(
                'class'=>'ext.eexcelview.PrintRequestBehavior',
            ),
        );
    }
	
	function actionGenRequestExcel($id){
					
	    // Load data (scoped)
	    $request = Request::model()->findByPk($id);
		$samples = $request->samps;
			
	    // Export it
	    $this->toExcel($model,
	        array(
	            'id',
	        ),
	        $request->requestRefNum,
	        array(
	            'creator' => 'RSTL',
	        	'request' => $request,
	        	'samples' => $samples,
	        ),
	        'Excel5'
	    );
	}
	
	public function actionCreateOP(){
			
		$model=new Orderofpayment;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Orderofpayment']))
		{
			$model->attributes=$_POST['Orderofpayment'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
		
		$customers = Customer::model()->findAll(
			array(
				'condition'=>'rstl_id = :rstl_id', 
				'params'=>array(':rstl_id'=>Yii::app()->Controller->getRstlId()))
		);
		//$customer_id = 447;
		$requests = Request::model()->findAll(
			array(
				'condition'=>'rstl_id = :rstl_id AND customerId = :customerId', 
				'params'=>array(':rstl_id'=>Yii::app()->Controller->getRstlId(), ':customerId'=>$customer_id))
		);
		
		$gridDataProvider = new CArrayDataProvider($requests, array('pagination'=>false));
		
		$this->render('createOP',array(
				'model'=>$model, 
				'customers'=>CHtml::listData($customers, 'id', 'customerName'),
				'gridDataProvider'=>$gridDataProvider
			));
	}
	
	public function actionSearchRequests()
	{
		$customer_id = $_POST['Orderofpayment']['customer_id'];
		
		$requests = Request::model()->findAll(
			array(	'condition'=>'rstl_id = :rstl_id AND customerId = :customerId ORDER BY id DESC', 
					'params'=>array(':rstl_id'=>Yii::app()->Controller->getRstlId(), ':customerId'=>$customer_id))
		);
		
		/*if($requests){
			foreach ($requests as $request){
				$balance=$request->getBalance2();
				if($balance!=0 OR ($model->request_id==$request->id)){ //$model->request_id==$request->id --> needed on update
					$list[] = array(
					'id'=>$request->id,
					'requestRefNum'=>$request->requestRefNum,
					'labId'=>$request->labId,
					'balance'=>$balance
					);
				}
	    	}
		}*/
		
		/*$data = CHtml::listData($requests,'id','requestRefNum');
		//append blank
		//echo CHtml::tag('option', array('value'=>''),CHtml::encode($name),true);
		
		foreach($data as $value=>$name)
		{
			$requests .= CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
		}
		
		echo CJSON::encode(array('requests'=>$requests));
		exit;*/
		
		$gridDataProvider = new CArrayDataProvider($requests, array('pagination'=>false));
		echo $this->renderPartial('_requests', array('gridDataProvider'=>$gridDataProvider));
	}
	
	public function actionPaymentDetail()
	{
		if(isset($_POST['id']))
			$requestId=$_POST['id'];
		
		$request=$this->loadModel($requestId);
		
		$criteria=new CDbCriteria;
		$criteria->condition='request_id=:requestId AND cancelled=0';
		$criteria->params=array(':requestId'=>$requestId);
		$model=new CActiveDataProvider(Collection, array('criteria'=>$criteria, 'pagination'=>false));
		
		echo CJSON::encode(array(
			'div'=>$this->renderPartial('_paymentDetail', array('model'=>$model, 'request'=>$request),true,true)
		));
	}

	public function actionPrintBarcode($id)
	{
		$ua = $_SERVER["HTTP_USER_AGENT"];
		$url = Yii::app()->Controller->getApiUrl().'/lab/api/view/model/referrals/id/'.$id;
			
		//Send Request to Resource
		$client = curl_init();
		
	    curl_setopt($client, CURLOPT_URL, $url);
		curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);

		//Get Response from Resource
		$response = curl_exec($client);
		
		//Decode
		$referral = json_decode($response, true);
		//echo "<pre>";
		//	print_r($referral);
		//echo "</pre>";

		//echo "<pre>";
		//print_r($referral['analyses']);
		//echo "</pre>";
						
		$pdf = Yii::createComponent('application.extensions.tcpdf.requestPdf', 
		                            'L', 'mm', array(66, 35), true, 'UTF-8');

		$pdf = new requestPdf('L', 'mm', array(66, 35), true, 'UTF-8', false);
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
			$class = '
			<style>
			  table {
				font-style: arial;
				border: 0px solid #FFF;
				width: 46%;
				padding-left: 2px;
			  }
			  table tr{
				border: 0px solid #FFF;
			  }
			  table tr td{
				border: 0px solid #FFF;
				text-align: left;
				valign: middle;                   
			  }
			</style>
		';
   		
   	 	$chrome = strpos($ua, 'Chrome') ? true : false;  // Google Chrome
    	$firefox = strpos($ua, 'Firefox') ? true : false;  // All Firefox
		$firefox_2 = strpos($ua, 'Firefox/2.0') ? true : false; // Firefox 2
		$firefox_3 = strpos($ua, 'Firefox/3.0') ? true : false; // Firefox 3
		$firefox_3_6 = strpos($ua, 'Firefox/3.6') ? true : false; // Firefox 3.6
		if ($ua) {
				if ($firefox) {
						$pdf->SetFont('helvetica', '', 10);
						foreach($referral["samples"] as $sample){
							$pdf->AddPage();
							$year = substr($referral['referralDate'], 0,4);
							$sampleBarcode = $sample['id'] . ' ' . $year. ' ' .$sample['sampleCode'];
							//$sampleBarcode = $sample['testname']['testName'];
							$limitname = substr($sample['sampleName'], 0,60);
							$samplename =  '<font size="6">'.$limitname.'</font>';
							$pdf->write1DBarcode($sampleBarcode, 'C39', '1', '0', '', 5, 0.2, $style, 'N');
							$title = '<b>'.$sampleBarcode.'</b>';
							$secondtitle ='<font size="6"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<b>Received:</b>&nbsp;'.$referral['referralDate'].'&nbsp;&nbsp;&nbsp;&nbsp;<b>Due:</b>&nbsp;'.$referral['reportDue'].'</font>';
								$html = '
									<table>
										<tr>
											<td width="188" align="center">'.$title.'</td>
										</tr>
										<tr>
											<td width="188" align="center">'.$samplename.'</td>
										</tr>
										</table>';
									$pdf->writeHTMLCell(0,0,0,5, $class.$html, 0, 9);
									$pdf->writeHTMLCell(0,0,0,10, $secondtitle, 0, 9);
									$style = array('width' => 0.2, 'cap' => 0, 'join' => 0, 'dash' => 0, 'color' =>'#000000');
									$pdf->Line(1, 12, 65, 12, $style);
								
									$top = 15;
									$topright = 15;
									$i = 1;
	
					//}	
						//print_r($sample['analyses']);
						//break;

							foreach($sample['analyses'] as $analysis){
								//echo "<pre>";
								//print_r($analysis);
								//echo "</pre>";
								//$limitanalysis = substr($analysis['testname']['testName'], 0,27);
								//foreach($referral['analyses'] as $test){
									//if($test['testname']['id'] == $analysis['testName_id']){
										$limitanalysis = substr($analysis['testname']['testName'], 0,27);
										if ($i++ >= 7)
										{	 
											$rows = '<font size="6">'.$limitanalysis.'</font>';	
											$pdf->writeHTMLCell(0,0,33,$top, $classRows.$rows, 0, 0);
											$top = $top + 2.5; 
										} else {
												$rows = '<font size="6">'.$limitanalysis.'</font>';	
												$pdf->writeHTMLCell(0,0,0,$topright, $classRows.$rows, 0, 0);
												$topright = $topright + 2.5; 
										}
									//}
								//}
							}
						$pdf->writeHTMLCell(0,0,0,12, $parameters, 0, 9);	 
						$pdf->lastPage();  		  		 			      
        			}
				}		
				elseif ($chrome) {
						$pdf->SetFont('helvetica', '', 10);
						foreach($referral->samples as $sample){
						$pdf->AddPage();
						$year = substr($referral->referralDate, 0,4);
						$sampleBarcode = $sample->id . ' ' . $year. ' ' .$sample->sampleCode;

						$limitname = substr($sample->sampleName, 0,60);
						$samplename =  '<font size="6">'.$limitname.'</font>';
						$pdf->write1DBarcode($sampleBarcode, 'C39', '4', '0', '0', 6, 0.16, $style, 'N');
						$title = '<b>'.$sampleBarcode.'</b>';
						$secondtitle ='<font size="5"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<b>Received:</b>&nbsp;'.$referral->referralDate.'&nbsp;&nbsp;&nbsp;&nbsp;<b>Due:</b>&nbsp;'.$referral->reportDue.'</font>';

						$html = '
						<table>
							<tr>
								<td width="180" align="center">'.$title.'</td>
							</tr>
							<tr>
								<td width="180" align="center">'.$samplename.'</td>
							</tr>
							
							<tr>
						</tr>
							</table>';
						$pdf->writeHTMLCell(0,0,0,6, $class.$html, 0, 9);
						$pdf->writeHTMLCell(0,0,0,11, $secondtitle, 0, 9);
						$style = array('width' => 0.2, 'cap' => 0, 'join' => 0, 'dash' => 0, 'color' =>'#000000');
						$pdf->Line(1, 13, 65, 13, $style);

						$top = 15;
						$topright = 15;
						$i = 1;
							foreach($sample->testAnalyses as $analysis){
								$limitanalysis = substr($analysis->testName, 0,25);	

											if ($i++ >= 7)
											 {
											 	$rows = '<font size="6">'.$limitanalysis.'</font>';	
												$pdf->writeHTMLCell(0,0,35,$top, $classRows.$rows, 0, 0);
												$top = $top + 2.5; 
											}else{
													$rows = '<font size="6">'.$limitanalysis.'</font>';	
													$pdf->writeHTMLCell(0,0,2,$topright, $classRows.$rows, 0, 0);
													$topright = $topright + 2.5; 
											}
												
									}  	
						$pdf->writeHTMLCell(0,0,0,12, $parameters, 0, 9);	 
						$pdf->lastPage();   		        
        } 	
			
				}else {
					$pdf->SetFont('helvetica', '', 10);
					foreach($referral->samples as $sample){
					$pdf->AddPage();
					$year = substr($referral->referralDate, 0,4);
					$sampleBarcode = $sample->id . ' ' . $year. ' ' .$sample->sampleCode;

					$limitname = substr($sample->sampleName, 0,60);
					$samplename =  '<font size="6">'.$limitname.'</font>';
					$pdf->write1DBarcode($sampleBarcode, 'C39', '1', '0', '', 5, 0.2, $style, 'N');
					$title = '<b>'.$sampleBarcode.'</b>';
					$secondtitle ='<font size="6"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<b>Received:</b>&nbsp;'.$referral->referralDate.'&nbsp;&nbsp;&nbsp;&nbsp;<b>Due:</b>&nbsp;'.$referral->reportDue.'</font>';
					$html = '
					<table>
						<tr>
							<td width="188" align="center">'.$title.'</td>
						</tr>
						<tr>
							<td width="188" align="center">'.$samplename.'</td>
						</tr>
						
						<tr>
					</tr>
						</table>';
					$pdf->writeHTMLCell(0,0,0,5, $class.$html, 0, 9);
					$pdf->writeHTMLCell(0,0,0,10, $secondtitle, 0, 9);
					$style = array('width' => 0.2, 'cap' => 0, 'join' => 0, 'dash' => 0, 'color' =>'#000000');
					$pdf->Line(1, 12, 65, 12, $style);
				
					$top = 15;
					$topright = 15;
					$i = 1;
						foreach($sample->testAnalyses as $analysis){
									
										$limitanalysis = substr($analysis->testName, 0,27);
										if ($i++ >= 7)
										 {
											 
											 $rows = '<font size="6">'.$limitanalysis.'</font>';	
											$pdf->writeHTMLCell(0,0,33,$top, $classRows.$rows, 0, 0);
											$top = $top + 2.5; 
										}else{
												$rows = '<font size="6">'.$limitanalysis.'</font>';	
												$pdf->writeHTMLCell(0,0,0,$topright, $classRows.$rows, 0, 0);
												$topright = $topright + 2.5; 
										}
											
								}  	
					$pdf->writeHTMLCell(0,0,0,12, $parameters, 0, 9);	 
					$pdf->lastPage();   	       
        				} 	
					}
			}		
		$pdf->IncludeJS("print();");
        $pdf->Output($referral->referralCode.'.pdf', 'I');
		exit ();
	}
}
