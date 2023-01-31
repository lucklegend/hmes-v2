<?php

class SampleController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

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
			array(
				'allow',  // allow all users to perform 'index' and 'view' actions
				'actions' => array('index', 'view'),
				'users' => array('*'),
			),
			array(
				'allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('create', 'update'),
				'users' => array('@'),
			),
			array(
				'allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array('admin', 'delete'),
				'users' => array('admin'),
			),
			array(
				'deny',  // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * Returns sample code(s) to be assigned for the incoming Referral 
	 * @param integer $lab_id, $sampleCount and $year are passed
	 */
	/*public function actionGetsamplecode($lab_id, $year, $sampleCount)
	{
		
		//{"id":"1","rstl_id":"11","requestId":"012013-M-0001-R9","labId":"2","number":"1","year":"2013","cancelled":"0"}
		//$model = Samplecode::model()->findByPk(1);
		$sampleCode = Samplecode::model()->find(array(
	   			'select'=>'*',
				'order'=>'number DESC, id DESC',
	    		'condition'=>'rstl_id = :rstl_id AND labId = :labId AND year = :year AND cancelled = 0',
	    		//'params'=>array(':rstl_id' => 11, ':labId' => $lab_id, ':year' => $year )
	    		'params'=>array(':rstl_id' => Yii::app()->Controller->getRstlId(), ':labId' => $lab_id, ':year' => $year )
			));
		$labCode = Lab::model()->findByPk($lab_id)->labCode;
		
		$codes = array();
		
		for($i=1; $i<=$sampleCount; $i++){
			$code = array(
				"samplecode" => $labCode."-".Yii::app()->Controller->addZeros($sampleCode->number + $i)
			);
			array_push($codes, $code);
		}
		echo CJSON::encode($codes);

		exit();
	}*/

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Sample;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_GET['id'])) {
			$requestId = $_GET['id'];
			$request = Request::model()->findByPk($requestId);
		}

		if (isset($_POST['Sample'])) {

			$model->attributes = $_POST['Sample'];

			if (isset($_POST['saveAsTemplate'])) {
				$sampleName = new Samplename;
				$sampleName->name = $model->sampleName;
				$sampleName->remarks = $model->remarks;
				$sampleName->model_no = $model->model_no;
				$sampleName->description = $model->description;
				$sampleName->serial_no = $model->serial_no;
				$sampleName->jobType = $model->jobType;
				$sampleName->brand = $model->brand;
				$sampleName->capacity_range = $model->capacity_range;
				$sampleName->resolution = $model->resolution;

				$sampleName->save();
			}


			$model->request_id = $requestId;
			$model->rstl_id = Yii::app()->user->rstlId;

			if ($model->save()) {
				//$this->redirect(array('view','id'=>$model->id));
				//Added new feature from JANNO
				$request = Request::model()->findByPk($requestId);
				if ($request->sampleCount && $request->anals) {
					foreach ($request->samps as $sample) {
						$labCode = Lab::model()->findByPk($request->labId);
						$year = date('Y', strtotime($request->requestDate));
						$code = new Samplecode;
						//$sampleCode = $code->generateSampleCode($labCode, $year);
						$tsrNum = $request->requestRefNum;
						$sampleCode = $code->generateSampleCode2(
							$labCode,
							$year,
							$tsrNum
						);
						$number = explode('-', $sampleCode);
						$generated = $this->checkIfGeneratedSamples($request);
						if($generated == 0){
							if ($sample->sampleCode == '') {
								$this->appendSampleCode($request, $number[1]);
								Sample::model()->updateByPk($sample->id, array('sampleCode' => $sampleCode));
							}
						}
					
					}
				}
				//END OF added FEATURE of JANNO
				if (Yii::app()->request->isAjaxRequest) {
					echo CJSON::encode(array(
						'status' => 'success',
						'div' => "Sample successfully added"
					));
					exit;
				} else
					$this->redirect(array('view', 'id' => $model->id));
			}
		}

		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status' => 'failure',
				'div' => $this->renderPartial('_form', array('model' => $model, 'requestId' => $requestId, 'request' => $request), true, true)
			));
			exit;
		} else {
			$this->render('create', array('model' => $model,));
		}

		//$this->render('create',array('model'=>$model,));
	}
	public function actionCreatebulk()
	{
		$model = new Sample;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_GET['id'])) {
			$requestId = $_GET['id'];
			$request = Request::model()->findByPk($requestId);
		}

		if (isset($_POST['Sample'])) {

			if (isset($_POST['saveAsTemplate'])) {
				$sampleName = new Samplename;
				$sampleName->name = $model->sampleName;
				$sampleName->remarks = $model->remarks;
				$sampleName->model_no = $model->model_no;
				$sampleName->serial_no = $model->serial_no;
				$sampleName->jobType = $model->jobType;
				$sampleName->brand = $model->brand;
				$sampleName->capacity_range = $model->capacity_range;
				$sampleName->resolution = $model->resolution;

				$sampleName->save();
			}
			if (isset($_POST['quantity'])) {
				if ($_POST['quantity'] > 1) {
					$max = $_POST['quantity'];

					for ($i = 0; $i < $max; $i++) {
						$model = new Sample;
						$model->sampleName = $_POST['sampleName'];
						$model->samplingDate = $_POST['samplingDate'];
						$model->remarks = $_POST['remarks'];
						$model->description = $_POST['description'];
						$model->requestId = $_POST['requestId'];
						$model->sampleMonth = $_POST['sampleMonth'];
						$model->sampleYear = $_POST['sampleYear'];
						$model->sampleYear = $_POST['sampleYear'];
						$model->request_id = $requestId;
						$model->rstl_id = Yii::app()->user->rstlId;
						$model->isNewRecord = true;
						$model->attributes = $_POST['Sample'];
						$model->save();
					}
				} else {
					$model = new Sample;
					$model->attributes = $_POST['Sample'];
					$model->request_id = $requestId;
					$model->rstl_id = Yii::app()->user->rstlId;
				}
			} else {
				$model = new Sample;
				$model->attributes = $_POST['Sample'];
				$model->request_id = $requestId;
				$model->rstl_id = Yii::app()->user->rstlId;
			}



			if ($model->save()) {
				//$this->redirect(array('view','id'=>$model->id));
				if (Yii::app()->request->isAjaxRequest) {
					echo CJSON::encode(array(
						'status' => 'success',
						'div' => "Sample successfully added"
					));
					exit;
				} else
					$this->redirect(array('view', 'id' => $model->id));
			}
		}

		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status' => 'failure',
				'div' => $this->renderPartial('_formbulk', array('model' => $model, 'requestId' => $requestId, 'request' => $request), true, true)
			));
			exit;
		} else {
			$this->render('createbulk', array('model' => $model,));
		}

		//$this->render('create',array('model'=>$model,));
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */

	public function actionUpdate($id = NULL)
	{
		if (isset($_POST['Sample']['id'])) {
			$id = $_POST['Sample']['id'];
		} else if (isset($_GET['id'])) {
			$id = $_GET['id'];
		} else {
			if (isset($_POST['id']))
				$id = $_POST['id'];
		}

		$model = $this->loadModel($id);

		$requestId = $model->request_id;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Sample'])) {
			$model->attributes = $_POST['Sample'];
			$model->request_id = $requestId;
			if ($model->save()) {
				if (Yii::app()->request->isAjaxRequest) {
					echo CJSON::encode(array(
						'status' => 'success',
						'div' => "Sample updated"
					));

					exit;
				} else
					$this->redirect(array('view', 'id' => $model->id));
			}
		}

		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status' => 'failure',
				'div' => $this->renderPartial('_form', array(
					'model' => $model, 'requestId' => $requestId,
				), true, true)
			));

			exit;
		} else {
			$this->render('update', array('model' => $model, 'requestId' => $requestId));
		}
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
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionCancel($id)
	{
		Sample::model()->updateByPk(
			$id,
			array(
				'cancelled' => 1,
			)
		);
	}
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('Sample');
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Sample('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Sample']))
			$model->attributes = $_GET['Sample'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Sample the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Sample::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Sample $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'sample-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionGenerateSampleCode($id)
	{
		$html = "";
		$modelRequest = Request::model()->findByPk($id);
		if ($modelRequest->sampleCount && $modelRequest->anals) {
			foreach ($modelRequest->samps as $sample) {
				$labCode = Lab::model()->findByPk($modelRequest->labId);

				$year = date('Y', strtotime($modelRequest->requestDate));

				$code = new Samplecode;
				//$sampleCode = $code->generateSampleCode($labCode, $year);
				$tsrNum = $modelRequest->requestRefNum;
				$sampleCode = $code->generateSampleCode2($labCode, $year, $tsrNum);
				$number = explode('-', $sampleCode);
				$this->appendSampleCode($modelRequest, $number[1]);

				Sample::model()->updateByPk($sample->id, array('sampleCode' => $sampleCode));

				foreach ($sample->analysesForGeneration as $analysis) {
					Analysis::model()->updateByPk($analysis->id, array('sampleCode' => $sampleCode));
				}

				$sampleNew = Sample::model()->findByPk($sample->id);
				$html .= '<p>' . $sampleNew->sampleName . ' : ' . $sampleNew->sampleCode . '</p><br/>';
			}
			$this->updateGeneratedRequest($modelRequest);
			echo CJSON::encode(array(
				'status' => 'success',
				'div' => $html . '<br \> Successfully Generated.'
			));
			exit;
		} else {
			echo CJSON::encode(array(
				'status' => 'failure',
				'div' => '<div style="text-align:center;" class="alert alert-error"><i class="icon icon-warning-sign"></i><font style="font-size:14px;"> System Warning. </font><br \><br \><div>Cannot generate sample code. <br \>Please add at least one(1) sample and analysis.</div></div>'
			));
			exit;
		}
	}
	public function actionPrintworksheet($id)
	{
		$sample = Sample::model()->findByPk($id);
		$request = Request::model()->findByPk($sample->request_id);

		$codes = explode('-', $sample->sampleCode);
		$sampleCode = $sample->requestId . '-' . substr($codes[1], 1);

		foreach ($sample->analyses as $analysis) {
			$sampleWorksheet = $analysis->worksheet;
		}

		if ($sampleWorksheet == '') {
			return $this->redirect(array('request/view', 'id' => $sample->request_id));
		}

		if ($sampleWorksheet == 'balanceworksheet') {
			$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.balanceworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
			$pdf = new balanceworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		} elseif ($sampleWorksheet == 'hydrostaticworksheet') {
			$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.hydrostaticworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
			$pdf = new hydrostaticworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		} elseif ($sampleWorksheet == 'pressureworksheet') {
			$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.pressureworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
			$pdf = new pressureworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		} elseif ($sampleWorksheet == 'reliefvalveworksheet') {
			$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.reliefvalveworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
			$pdf = new reliefvalveworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		} elseif ($sampleWorksheet == 'loadworksheet') {
			$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.loadworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
			$pdf = new loadworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		} elseif ($sampleWorksheet == 'pneumaticworksheet') {
			$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.pneumaticworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
			$pdf = new pneumaticworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		} elseif ($sampleWorksheet == 'stopwatchworksheet') {
			$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.stopwatchworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
			$pdf = new stopwatchworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		} elseif ($sampleWorksheet == 'textiletapeworksheet') {
			$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.textiletapeworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
			$pdf = new textiletapeworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		} elseif ($sampleWorksheet == 'steeltapeworksheet') {
			$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.steeltapeworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
			$pdf = new steeltapeworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		} elseif ($sampleWorksheet == 'steeltapeworksheet50') {
			$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.steeltapeworksheetfifty', 'P', 'cm', 'A4', true, 'UTF-8');
			$pdf = new SteelTapeWorksheetFifty(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		} elseif ($sampleWorksheet == 'steelruleworksheet') {
			$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.steelruleworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
			$pdf = new steelruleworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		} elseif ($sampleWorksheet == 'tempcontrollerworksheet') {
			$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.tempcontrollerworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
			$pdf = new tempcontrollerworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		} elseif ($sampleWorksheet == 'storagetankworksheet') {
			$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.storagetankworksheet', 'P', 'cm', 'A4', true, 'UTF-8');
			$pdf = new storagetankworksheet(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->setFooterMargin(20);
		} else {
			$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.requestPdf', 'P', 'cm', 'A4', true, 'UTF-8');
			$pdf = new requestPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		}
		//$pdf = Yii::createComponent('application.extensions.tcpdf.worksheet.requestPdf', 'P', 'cm', 'A4', true, 'UTF-8');
		//$pdf = new requestPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


		spl_autoload_register(array('YiiBase', 'autoload'));

		$pdf->setRequest($request);
		$pdf->setSample($sample);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle($sampleCode);
		$pdf->SetMargins(0, 28.15, 0);
		
		if($sampleWorksheet == 'storagetankworksheet'){
			$pdf->SetAutoPageBreak(TRUE, 27);	
		}else{
			$pdf->SetAutoPageBreak(TRUE, 10);
		}

		$pdf->AddPage();

		$pdf->printRows();

		// reset pointer to the last page
		$pdf->lastPage();

		//Close and output PDF document
		$pdf->Output($sampleCode . '.pdf', 'I');
		//Yii::app()->end();

	}
	public function actionGenerateSampleCodeReferral()
	{
		$html = "<pre>";
		//$modelRequest = Request::model()->findByPk($id);
		$url = Yii::app()->Controller->getApiUrl() . '/lab/api/view/model/referrals/id/' . $_GET["id"];

		$response = Yii::app()->curl->get($url);

		//Decode
		$referral = json_decode($response, true);

		//Decode
		//$referral = json_decode($response, true);
		//$html .= $url.$_GET['test'];

		$labCode = Lab::model()->findByPk($referral['lab_id']);
		$year = date('Y', strtotime($referral['referralDate']));


		foreach ($referral['samples'] as $sample) {
			$code = new Samplecode;
			$sampleCode = $code->generateSampleCode($labCode, $year);
			$number = explode('-', $sampleCode);

			// Append samplecode
			$samplecode = new Samplecode;
			$samplecode->rstl_id = Yii::app()->Controller->getRstlId();
			$samplecode->requestId = $referral['referralCode'];
			$samplecode->labId = $referral['lab_id'];
			$samplecode->number = $number[1];
			$samplecode->year = date('Y', strtotime($referral['referralDate']));
			$samplecode->cancelled = 0;

			//Update Sample on Referral
			if ($samplecode->save()) {
				$url = Yii::app()->Controller->getApiUrl() . '/lab/api/update/model/samples/id/' . $sample["id"];

				$data = array('sampleCode' => $labCode->labCode . '-' . $number[1]);

				$ch = curl_init($url);

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

				$response = curl_exec($ch);
				if (!$response) {
					return false;
				}
			}
			$html .= $labCode->labCode . '-' . $number[1] . '<br/>';
		}

		echo CJSON::encode(array(
			'status' => 'success',
			//'div'=>$html.'<br \> Successfully Generated.'
			'div' => $html . '</pre>'
		));
		exit;
	}

	function appendSampleCode($modelRequest, $count)
	{
		$sampleCode = new Samplecode;
		$sampleCode->rstl_id = $modelRequest->rstl_id;
		$sampleCode->requestId = $modelRequest->requestRefNum;
		$sampleCode->labId = $modelRequest->labId;
		$sampleCode->number = $count;
		$sampleCode->year = date('Y', strtotime($modelRequest->requestDate));
		$sampleCode->cancelled = 0;
		$sampleCode->save();
	}

	function updateGeneratedRequest($modelRequest)
	{
		/*$currentRequest = Requestcode::model()->find(array(
    		'condition'=>'rstl_id = :rstl_id AND requestRefNum = :requestRefNum',
    		//'params'=>array(':rstl_id' => Yii::app()->Controller->getRstlId(), ':requestRefNum' => $modelRequest->requestRefNum)
    		'params'=>array(':rstl_id' => Yii::app()->user->rstlId, ':requestRefNum' => $modelRequest->requestRefNum)
		));
		$lastGenerated = Generatedrequest::model()->find(array(
			'condition' => 'rstl_id = :rstl_id AND labId = :labId',
			'params' => array(':rstl_id' => Yii::app()->Controller->getRstlId(), ':labId' => $modelRequest->labId)
		));*/
		$currentRequest = explode('-', $modelRequest->requestRefNum);

		$generatedRequest = new Generatedrequest;
		$generatedRequest->rstl_id = $modelRequest->rstl_id;
		$generatedRequest->request_id = $modelRequest->id;
		$generatedRequest->labId = $modelRequest->labId;
		$generatedRequest->year = date('Y', strtotime($modelRequest->requestDate));
		//$generatedRequest->number = isset($currentRequest[2]) ? $currentRequest[2] : $currentRequest(2);
		$generatedRequest->number = $currentRequest[2];
		$generatedRequest->save();
	}

	function addZeros($count)
	{
		if ($count < 10)
			return '000' . $count;
		elseif ($count < 100)
			return '00' . $count;
		elseif ($count < 1000)
			return '0' . $count;
		elseif ($count >= 1000)
			return $count;
	}

	function actionConfirm()
	{
		$model = new User;

		if (isset($_POST['User'])) {
			//$model->attributes=$_POST['User'];

			//$model->sample_id = $sampleId;
			if (isset($_POST['User']['email'])) {
				if (Yii::app()->request->isAjaxRequest) {
					echo CJSON::encode(array(
						'status' => 'success',
						'div' => "Sample updated"
					));
					exit;
				} else
					$this->redirect(array('view', 'id' => $model->id));
			}
		}

		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status' => 'failure',
				'div' => $this->renderPartial('_confirm', array('model' => $model), true)
			));
			exit;
		} else {

			$this->render('_confirm', array('model' => $model));
		}
	}

	function actionSearchSample()
	{

		if (!empty($_GET['term'])) {
			//$sql = 'SELECT id as id, name as name, description as description, CONCAT(name,": ",description) as label';
			$sql = 'SELECT id as id, name as name, description as description, name as label';
			$sql .= ' FROM ulimslab.samplename WHERE name LIKE :qterm';
			$sql .= ' ORDER BY name ASC';
			$command = Yii::app()->db->createCommand($sql);
			$qterm = $_GET['term'] . '%';
			$command->bindParam(":qterm", $qterm, PDO::PARAM_STR);
			$result = $command->queryAll();
			//echo CJSON::encode($result); exit;
		} /*else {
			return false;
		  }*/
		echo CJSON::encode($result);
		Yii::app()->end();
	}

	function actionGetSamplename($id)
	{
		$data = Samplename::model()->findByPk($id);
		return $data;
	}

	function actionGetSampleNameTemplate($name)
	{
		//$name = $_POST['name'];
		$sample = Samplename::model()->findByAttributes(array('name' => $name));

		$data = array(
			'name' => $sample->name,
			'description' => $sample->description,
			'remarks' => $sample->remarks,
			'model_no' => $sample->model_no,
			'serial_no' => $sample->serial_no,
			'jobType' => $sample->jobType,
			'brand' => $sample->brand,
			'capacity_range' => $sample->capacity_range,
			'resolution' => $sample->resolution
		);
		echo CJSON::encode($data);
		exit;
	}
	function checkIfGeneratedSamples($request)
	{
		$generatedThisRequest = Generatedrequest::model()->count(array(
			'condition' => 'request_id =:request_id',
			'params' => array(':request_id' => $request->id)
		));

		$previousRequest = Request::model()->find(array(
			'order' => 'id DESC',
			'condition' => 'id<:id AND rstl_id=:rstl_id AND labId=:labId',
			'params' => array(':id' => $request->id, ':rstl_id' => Yii::app()->Controller->getRstlId(), ':labId' => $request->labId)
		));

		$generatedPreviousRequest = Generatedrequest::model()->count(array(
			'condition' => 'request_id =:request_id',
			'params' => array(':request_id' => $previousRequest->id)
		));

		switch ($generatedThisRequest) {
			case (0):

				if ($generatedPreviousRequest == 1 || !isset($previousRequest)) {
					//echo "Generate Sample Code!";
					return 1;
					break;
				} else {
					//echo '<p style="font-style: italic; font-weight: bold; color: red;">Generate Sample Codes from previous requests and refresh this page!</p>';
					return 2;
					break;
				}

			case (1):
				//echo "Print Request";
				return 0;
				break;

				break;
		}
	}
}
