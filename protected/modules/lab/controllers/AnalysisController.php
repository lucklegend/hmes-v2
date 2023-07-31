<?php

class AnalysisController extends Controller
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
				'actions'=>array('index','view', 'getCategorytype', 'getSampletype', 'getAnalysis'),
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Analysis;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_GET['id']))
		{
			$requestId = $_GET['id'];
			$request = Request::model()->findByPk($requestId); 
		}
		
		if(isset($_POST['Analysis']))
		{
			$totalSamples = count($_POST['Analysis']['sample_id']);
			$count = 0;
			$done = false;
			foreach($_POST['Analysis']['sample_id'] as $sample_id){
						
				//$model->attributes=$_POST['Analysis'];
				
				$model = New Analysis;
				$model->rstl_id = Yii::app()->user->rstlId;
				$model->requestId = $_POST['Analysis']['requestId'];
				$model->sample_id = $sample_id;
				//$model->testName = $_POST['Analysis']['testName'];
				$model->testName = Test::model()->findByPk($_POST['Analysis']['testName'])->testName;
				$model->method = $_POST['Analysis']['method'];
				$model->references = $_POST['Analysis']['references'];
				$model->quantity = $_POST['Analysis']['quantity'];
				$model->fee = $_POST['Analysis']['fee'];
				$model->testId = $_POST['Analysis']['testId'];
				$model->analysisMonth = $_POST['Analysis']['analysisMonth'];
				$model->analysisYear = $_POST['Analysis']['analysisYear'];
				$model->worksheet = Test::model()->findByPk($_POST['Analysis']['testName'])->worksheet;
				$model->save();
				
				$count++;
				if($count == $totalSamples)
					$done = true;
				else 
					$done = false;
			}	
				if($done){
					//$this->redirect(array('view','id'=>$model->id));
					if (Yii::app()->request->isAjaxRequest)
	                {
	                    echo CJSON::encode(array(
	                        'status'=>'success', 
	                        'div'=>"Analysis successfully added"
	                        ));
	                    exit;               
	                }
	                else
	                    $this->redirect(array('view','id'=>$model->id));
				}
			
		}
		
		if (Yii::app()->request->isAjaxRequest)
        {
			if($request->sampleCount){
				$status='failure';
				$div=$this->renderPartial('_form', array('model'=>$model,'requestId'=>$requestId, 'request'=>$request) ,true , true);
			}else{
				$status='failure';
				$div='<div style="text-align:center;" class="alert alert-error"><i class="icon icon-warning-sign"></i><font style="font-size:14px;"> System Warning. </font><br \><br \><div>Please add at least one(1) sample for analysis.</div></div>';
			}
				echo CJSON::encode(array(
					'status'=>$status,'div'=>$div));
					
            exit;               
        }else{
            $this->render('create',array('model'=>$model,));
        }
		//$this->render('create',array('model'=>$model,));
	}

	public function actionPackage()
	{
		$model=new Analysis;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_GET['id']))
		{
			$requestId = $_GET['id'];
			$request = Request::model()->findByPk($requestId); 
		}
		
		if(isset($_POST['Analysis']))
		{
			$totalSamples = count($_POST['Analysis']['sample_id']);
			$count = 0;
			$done = false;
			
			$package = Package::model()->findByPk($_POST['Analysis']['package']);
			$testArray = array();
			$testArray = explode(',', $package->tests);
			
			$countTests = count($testArray);
			/*for($i=0; $i<$count; $i++){
				$testName = Test::model()->findByPk($testArray[$i])->testName;
				//$stringTests .= $testName.', ';
			}*/
			
			foreach($_POST['Analysis']['sample_id'] as $sample_id){
						
				//$countTests = count($testArray);
				
				// $model = New Analysis;
				// $model->requestId = $_POST['Analysis']['requestId'];
				// $model->sample_id = $sample_id;
				// $model->testName = $package->name;
				// //$model->method = 'hahaha';
				// //$model->references = 'hahaha';
				// $model->quantity = 1;
				// // $model->fee = $package->rate;
				// $model->fee = 0;
				// $model->testId = 0;
				// $model->analysisMonth = $_POST['Analysis']['analysisMonth'];
				// $model->analysisYear = $_POST['Analysis']['analysisYear'];
				// $model->package = $_POST['Analysis']['package'];
				// $model->rstl_id = Yii::app()->user->rstlId;
				// $model->save();
				
				for($i=0; $i<$countTests; $i++){
					$test = Test::model()->findByPk($testArray[$i]);
					
					$model = New Analysis;
					$model->requestId = $_POST['Analysis']['requestId'];
					$model->sample_id = $sample_id;
					$model->testName = $test->testName;
					$model->method = $test->method;
					$model->references = $test->references;
					$model->quantity = 1;
					$model->fee = $test->fee;
					$model->testId = $test->id;
					$model->analysisMonth = $_POST['Analysis']['analysisMonth'];
					$model->analysisYear = $_POST['Analysis']['analysisYear'];
					$model->package = $_POST['Analysis']['package'];
					$model->rstl_id = Yii::app()->user->rstlId;
					$model->save();
				}
				
				$count++;
				if($count == $totalSamples)
					$done = true;
				else 
					$done = false;
			}	
				if($done){
					//$this->redirect(array('view','id'=>$model->id));
					if (Yii::app()->request->isAjaxRequest)
	                {
	                    echo CJSON::encode(array(
	                        'status'=>'success', 
	                        'div'=>"Package successfully added"
	                        ));
	                    exit;               
	                }
	                else
	                    $this->redirect(array('view','id'=>$model->id));
				}
			
		}
		
		if (Yii::app()->request->isAjaxRequest){
			
			if($request->sampleCount){
				$div=$this->renderPartial('_formpackage', array('model'=>$model,'requestId'=>$requestId, 'request'=>$request) ,true , true);
			}else{
				$div='<div style="text-align:center;" class="alert alert-error"><i class="icon icon-warning-sign"></i><font style="font-size:14px;"> System Warning. </font><br \><br \><div>Please add at least one(1) sample for analysis.</div></div>';
			}
            echo CJSON::encode(array(
                'status'=>'failure',
                'div'=>$div));
            exit;               
        }else{
            $this->render('package',array('model'=>$model,));
        }
		//$this->render('create',array('model'=>$model,));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id=NULL)
	{
		if(isset($_POST['Analysis']['id'])){
			$id=$_POST['Analysis']['id'];
		}else{
			if(isset($_POST['id']))
			$id=$_POST['id'];
		}
		$model=$this->loadModel($id);
		
		$sampleId=$model->sample_id;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Analysis']))
		{
			$model->attributes=$_POST['Analysis'];
			$model->worksheet = Test::model()->findByPk($_POST['Analysis']['testName'])->worksheet;
			$model->sample_id = $sampleId;
			if($model->save()){
				if (Yii::app()->request->isAjaxRequest)
				{
					echo CJSON::encode(array(
                        'status'=>'success', 
                        'div'=>"Analysis updated"
                        ));
                    exit;    
				}
				else
					$this->redirect(array('view','id'=>$model->id));
			}
		}

		if (Yii::app()->request->isAjaxRequest)
        {
			echo CJSON::encode(array(
                'status'=>'failure',
                'div'=>$this->renderPartial('_form', array('model'=>$model,'sampleId'=>$sampleId,
				), true, true)));
            exit;               
        }else{
        		
			$this->render('update',array('model'=>$model,'sampleId'=>$sampleId));
        }
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$request_id = Analysis::model()->findByPk($id)->sample->request->id;
		
		$this->loadModel($id)->delete();
		
		//update Request Total after Delete using the pre-assigned request_id
		//Request::updateRequestTotal($request_id);
		$request=new Request;
		$request->updateRequestTotal($request_id);
		
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionCancel($id)
	{
		Analysis::model()->updateByPk($id, 
			array('cancelled'=>1,
				  'deleted'=>1,
				  'fee'=>0,
			));
		$request_id = Analysis::model()->findByPk($id)->sample->request->id;	
		Request::updateRequestTotal($request_id);
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Analysis');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Analysis('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Analysis']))
			$model->attributes=$_GET['Analysis'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Analysis the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Analysis::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Analysis $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='analysis-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	function actionGetCategorytype(){
		$data = Testcategory::model()->findAll('labId=:labId', array(':labId'=>3));

		$data = CHtml::listData($data,'id','categoryName');
		//append blank
		echo CHtml::tag('option', array('value'=>''), CHtml::encode($name),true);
		
		foreach($data as $value=>$name){
			echo CHtml::tag('option', array('value'=>$value), CHtml::encode($name),true);
		}			  
	}
	
	function actionGetSampletype(){
	//please enter current controller name because yii send multi dim array 
		if(isset($_POST['testCategory']))
			$category = $_POST['testCategory'];
		if(isset($_POST['testCategoryUpdate']))
			$category = $_POST['testCategoryUpdate'];
			
		$data = Sampletype::model()->findAll('testCategoryId=:testCategoryId ORDER BY sampleType', 
					  array(':testCategoryId'=>$category));
	 
		$data = CHtml::listData($data,'id','sampleType');
		//append blank
		echo CHtml::tag('option', array('value'=>''),CHtml::encode($name),true);
		
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
		}
		Yii::app()->session['sampleType'] = $data;	
	}
	
	function actionGetAnalysis(){
	//please enter current controller name because yii send multi dim array
		if(isset($_POST['testCategory']))
			$sampleType = $_POST['sampleType'];
		if(isset($_POST['testCategoryUpdate']))
			$sampleType = $_POST['sampleTypeUpdate'];
			 
		$data=Test::model()->findAll('sampleType=:sampleType ORDER BY testName', 
					  array(':sampleType'=>$sampleType));
	 
		$data=CHtml::listData($data,'id','testName');
		//append blank
		echo CHtml::tag('option', array('value'=>''),CHtml::encode($name),true);
		
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',
					   array('value'=>$value),CHtml::encode($name),true);
		}
		Yii::app()->session['analysis'] = $data;	
	}

	function actionGetPackages(){
	//please enter current controller name because yii send multi dim array
		///if(isset($_POST['sampleType']))
			//$sampleType = $_POST['sampleType'];
			 
		$data=Package::model()->findAll('sampletype_id = :sampletype_id', 
					  array(':sampletype_id'=>$_POST['sampleType']));
	 
		$data=CHtml::listData($data,'id','name');
		//append blank
		echo CHtml::tag('option', array('value'=>''),CHtml::encode($name),true);
		
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',
					   array('value'=>$value),CHtml::encode($name),true);
		}
		//Yii::app()->session['analysis'] = $data;	
	}	
	
	function actionGetAnalysisdetails(){
		if(isset($_POST['Analysis']['testName']))
			$testName = $_POST['Analysis']['testName'];
		if(isset($_POST['testNameUpdate']))
			$testName = $_POST['testNameUpdate'];
			
		$test = Test::model()->findByPk($testName);
		$data = array(
			'testName' => $test->testName,
			'method' => $test->method,
			'references' => $test->references,
			'fee' => $test->fee,
			'testId' => $test->id
		);
		echo CJSON::encode($data); 
		exit;
	}

	function actionGetPackagedetails(){
		if(isset($_POST['package']))
			$testName = $_POST['package'];
		if(isset($_POST['Analysis']))
			$testName = $_POST['Analysis']['package'];
		//if(isset($_POST['testNameUpdate']))
			//$testName = $_POST['testNameUpdate'];
			
		$package = Package::model()->findByPk($testName);
		$testArray = array();
		$testArray = explode(',', $package->tests);
		
		$stringTests = '';
		
		$count = count($testArray);
		for($i=0; $i<$count; $i++){
			$testName = Test::model()->findByPk($testArray[$i])->testName;
			$stringTests .= $testName.', ';
		}
		
		$data = array(
			//'method' => round((float)$package->rate * 100 ) . ' %',
			'data'=>$_POST,
			'method' => $package->rate,
			'references' => substr($stringTests, 0, -2),
		);
		echo CJSON::encode($data); 
		exit;
	}	

	public function actionPrintworksheetPDF($id)
	{
		$analysis = Analysis::model()->findByPk($id);
		$sample = Sample::model()->findByPk($analysis->sample_id);
		$request = Request::model()->findByPk($sample->request_id);
		$codes = explode('-', $sample->sampleCode);
		$sampleCode = $sample->requestId . '-' . substr($codes[1], 1);
		$analysisWorksheet = $analysis->worksheet;
		
		if ($analysisWorksheet == '') {
			return $this->redirect(array('request/view', 'id' => $sample->request_id));
		} 
		switch($analysisWorksheet){
			case 'balanceworksheet':
				$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.balanceworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
				$pdf = new balanceworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				break;
			case 'hydrostaticworksheet':
				$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.hydrostaticworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
				$pdf = new hydrostaticworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				break;
			case 'pressureworksheet':
				$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.pressureworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
				$pdf = new pressureworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				break;
			case 'reliefvalveworksheet':
				$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.reliefvalveworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
				$pdf = new reliefvalveworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				break;
			case 'loadworksheet':
				$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.loadworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
				$pdf = new loadworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				break;
			case 'pneumaticworksheet':
				$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.pneumaticworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
				$pdf = new pneumaticworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				break;
			case 'stopwatchworksheet':
				$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.stopwatchworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
				$pdf = new stopwatchworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				break;
			case 'textiletapeworksheet':
				$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.textiletapeworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
				$pdf = new textiletapeworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				break;
			case 'steeltapeworksheet':
				$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.steeltapeworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
				$pdf = new steeltapeworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				break;
			case 'steeltapeworksheet50':
				$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.steeltapeworksheetfifty', 'P', 'cm', 'A4', true, 'UTF-8');
				$pdf = new SteelTapeWorksheetFifty(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				break;
			case 'steelruleworksheet':
				$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.steelruleworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
				$pdf = new steelruleworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				break;
			case 'tempcontrollerworksheet':
				$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.tempcontrollerworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
				$pdf = new tempcontrollerworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				break;
			case 'storagetankworksheet':
				$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.storagetankworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
				$pdf = new storagetankworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$pdf->setFooterMargin(20);
				$pdf->SetAutoPageBreak(TRUE, 27);	
				break;
		}

		spl_autoload_register(array('YiiBase', 'autoload'));

		$pdf->setRequest($request);
		$pdf->setSample($sample);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle($sampleCode);
		$pdf->SetMargins(0, 28.15, 0);
		$pdf->SetAutoPageBreak(TRUE, 10);
		$pdf->AddPage();
		$pdf->printRows();

		// reset pointer to the last page
		$pdf->lastPage();

		//Close and output PDF document
		$pdf->Output($sampleCode . '.pdf', 'I');
		Yii::app()->end();

	}

	public function actionPrintworksheetWord($id)
	{
		$analysis = Analysis::model()->findByPk($id);
		$sample = Sample::model()->findByPk($analysis->sample_id);
		$request = Request::model()->findByPk($sample->request_id);
		$codes = explode('-', $sample->sampleCode);
		$sampleCode = $sample->requestId . '-' . substr($codes[1], 1);
		$analysisWorksheet = $analysis->worksheet;
		

		$phpWord = Yii::app()->phpWord->createDocument();
		
		// download the file.
		$filePath = '';
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($filePath));
		readfile($filePath);
	}
}