<?php

class QuotationController extends Controller
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
		return array(
			// 'accessControl', // perform access control for CRUD operations
			// 'postOnly + delete', // we only allow deletion via POST request
			'rights'
		);
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
				'actions'=>array('index','view'),
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
		$model=$this->loadModel($id);

		$sampleDataProvider = new CArrayDataProvider($model->samples, 
			array(
				'pagination'=>false,
			)
		);

		$testDataProvider = new CArrayDataProvider($model->tests, 
			array(
				'pagination'=>false,
			)
		);

		$this->render('view',array(
			'id'=>$id,
			'model'=>$model,
			'sampleDataProvider'=>$sampleDataProvider,
			'testDataProvider'=>$testDataProvider,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Quotation;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Quotation']))
		{
			$model->attributes=$_POST['Quotation'];
			$model->requestDate = date('Y-m-d',strtotime($_POST['Quotation']['requestDate']));
			$quotation = Quotation::model()->find(array(
				'select'=>'quotationCode, requestDate',
				'condition'=>'cancelled = 0',
				'order'=>'id DESC',
			));
			if($quotation){
				if(!$quotation){
					$date = date('ym');
				}else{
					$date = date('ym', strtotime($model->requestDate));
				}
				$year = date('Y', strtotime($quotation->requestDate));
				$explodeQuote = explode('-', $quotation->quotationCode);
				$yearToday = date('Y');
				if($yearToday == $year){
					$number = (int)$explodeQuote[3];
					$num = Request::addZeros($number+1);
				}else{
					$num = Request::addZeros(1);
				}
				
			}else{
				$num = Request::addZeros(1);
			}
			$rstl = Rstl::model()->findByPk(Yii::app()->Controller->getRstlId());
			$quoteCode = $rstl->code.'-QUO-'.$date.'-'.$num;
			$model->quotationCode = $quoteCode;
			$model->designation = $_POST['Quotation']['designation'];
			if($model->save()){
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionCreatesample($id){
		$model = new QuotationSample;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_GET['id']))
		{
			$quotationId = $_GET['id'];
			$quotaion = Quotation::model()->findByPk($quotationId); 
		}	

		if(isset($_POST['QuotationSample']))
		{
			$model->attributes=$_POST['QuotationSample'];
			
			
			$model->quotation_id = $quotationId;
			
			if($model->save()){
				//$this->redirect(array('view','id'=>$model->id));
				if (Yii::app()->request->isAjaxRequest)
                {
                    echo CJSON::encode(array(
                        'status'=>'success', 
                        'div'=>"Sample successfully added"
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
                'div'=>$this->renderPartial('_formsample', array('model'=>$model, 'quotationId'=>$quotationId) ,true , true)));
            exit;               
        }else{
            $this->render('_formsample',array('model'=>$model,));
        }
	}
	
	public function actionCreatetest($id){
		$model = new QuotationTest;
		if(isset($_GET['id']))
		{
			$quotationId = $_GET['id'];
			$quotation = Quotation::model()->findByPk($quotationId); 
		}	

		if(isset($_POST['QuotationTest'])){
			$totalSamples = count($_POST['QuotationTest']['sample_id']);
			$count = 0;
			$done = false;
			$model->attributes=$_POST['QuotationTest'];

			foreach($_POST['QuotationTest']['sample_id'] as $sample_id){
						
				//$model->attributes=$_POST['Analysis'];
				
				$model = new QuotationTest;
				$model->quotation_id = $_POST['QuotationTest']['quotation_id'];
				$model->sample_id = $sample_id;
				$model->test_id = $_POST['QuotationTest']['testName'];
				$model->testName = Test::model()->findByPk($_POST['QuotationTest']['testName'])->testName;
				$model->method = $_POST['QuotationTest']['method'];
				$model->references = $_POST['QuotationTest']['references'];
				$model->fee = $_POST['QuotationTest']['fee'];
				$model->lab_id = $_POST['QuotationTest']['lab_id'];
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
                        'div'=>"Test/Calibration successfully added"
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
                'div'=>$this->renderPartial('_formtest', array('model'=>$model, 'quotationId'=>$id) ,true , true)));
            exit;               
        }else{
            $this->render('_formtest',array('model'=>$model,));
        }
	}

	public function actionPackage($id){
		$model = new QuotationTest;
		if(isset($_GET['id']))
		{
			$quotationId = $_GET['id'];
			$quotation = Quotation::model()->findByPk($quotationId); 
		}	

		if(isset($_POST['QuotationTest'])){
			$totalSamples = count($_POST['QuotationTest']['sample_id']);
			$count = 0;
			$done = false;
			$model->attributes=$_POST['QuotationTest'];

			$package = Package::model()->findByPk($_POST['QuotationTest']['package']);
			$testArray = array();
			$testArray = explode(',', $package->tests);
			
			$countTests = count($testArray);
			foreach($_POST['QuotationTest']['sample_id'] as $sample_id){
						
				//$model->attributes=$_POST['Analysis'];
				for($i=0; $i<$countTests; $i++){
					$test = Test::model()->findByPk($testArray[$i]);
					$model = new QuotationTest;
					$model->quotation_id = $_POST['QuotationTest']['quotation_id'];
					$model->sample_id = $sample_id;
					$model->test_id = $test->id;
					$model->testName = $test->testName;
					$model->method = $test->method;
					$model->references = $test->references;
					$model->fee = $test->fee;
					$model->lab_id = $_POST['QuotationTest']['lab_id'];
					$model->package = $_POST['QuotationTest']['package'];
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
                        'div'=>"Test/Calibration successfully added"
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
                'div'=>$this->renderPartial('_formpackage', array('model'=>$model, 'quotationId'=>$id) ,true , true)));
            exit;               
        }else{
            $this->render('_formpackage',array('model'=>$model,));
        }
	}
	public function actionGetAnalysis(){
		if(isset($_POST['QuotationTest']['lab_id']))
			$labId = $_POST['QuotationTest']['lab_id'];

		$data=Test::model()->findAll('labId=:labId ORDER BY testName', 
					  array(':labId'=>$labId));
	 
		$data=CHtml::listData($data,'id','testName');
		//append blank
		echo CHtml::tag('option', array('value'=>''),CHtml::encode($name),true);
		
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',
					   array('value'=>$value),CHtml::encode($name),true);
		}
		//echo CJSON::encode($data); 
		// Yii::app()->session['analysis'] = $data;	
	}
	public function actionGetPackages(){
		if(isset($_POST['QuotationTest']['lab_id']))
			$labId = $_POST['QuotationTest']['lab_id'];

		$data=Package::model()->findAll('labId=:labId ORDER BY id', 
					  array(':labId'=>$labId));
	 
		$data=CHtml::listData($data,'id','name');
		//append blank
		echo CHtml::tag('option', array('value'=>''),CHtml::encode($name),true);
		
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',
					   array('value'=>$value),CHtml::encode($name),true);
		}
		// Yii::app()->session['analysis'] = $data;	
	}
	public function actionGetPackagedetails(){
		if(isset($_POST['package']))
			$testName = $_POST['package'];
		if(isset($_POST['QuotationTest']))
			$testName = $_POST['QuotationTest']['package'];
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
	public function actionGetAnalysisDetails(){
		if(isset($_POST['QuotationTest']['testName']))
			$testName = $_POST['QuotationTest']['testName'];
			
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

		if(isset($_POST['Quotation']))
		{
			$model->attributes=$_POST['Quotation'];
			$model->requestDate = date('Y-m-d',strtotime($_POST['Quotation']['requestDate']));
			$model->designation = $_POST['Quotation']['designation'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionUpdatesample($id=NULL)
	{
		if(isset($_POST['QuotationSample']['id'])){
			$id=$_POST['QuotationSample']['id'];
		}else{
			if(isset($_POST['id']))
			$id=$_POST['id'];
		}
		
		$model=QuotationSample::model()->findByPk($id);

		$quotationId=$model->quotation_id;
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['QuotationSample']))
		{
			$model->attributes=$_POST['QuotationSample'];
			$model->quotation_id = $quotationId;
			if($model->save()){
				if (Yii::app()->request->isAjaxRequest)
				{
					echo CJSON::encode(array(
                        'status'=>'success', 
                        'div'=>"Sample updated"
                        ));
                      
                    exit;    
				}
				else
					$this->redirect(array('view','id'=>$model->quotation_id));
			}
		}

		if (Yii::app()->request->isAjaxRequest)
        {
			echo CJSON::encode(array(
                'status'=>'failure',
                'div'=>$this->renderPartial('_formsample', array('model'=>$model,'quotation_id'=>$quotation_id,
				), true, true)));
			
            exit;               
        }else{
			$this->render('update',array('model'=>$model,'requestId'=>$requestId));
        }
	}
	public function actionUpdatetest($id=NULL){
		if(isset($_POST['QuotationTest']['id'])){
			$testid=$_POST['QuotationTest']['id'];
		}else if(isset($_POST['id'])){
			$testid=$_POST['id'];
		}else{
			$testid = $id;
		}
		$model = QuotationTest::model()->findByPk($testid);
		$quotationId = $model->quotation_id;
		$sample_id = $model->sample_id;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['QuotationTest'])){
			$model->attributes=$_POST['QuotationTest'];
			$model->quotation_id = $quotationId;
			$model->sample_id = $sample_id;
			$model->test_id = $_POST['QuotationTest']['testName'];
			$model->testName = Test::model()->findByPk($_POST['QuotationTest']['testName'])->testName;
			if($model->save())
				if (Yii::app()->request->isAjaxRequest){
					echo CJSON::encode(array(
                        'status'=>'success', 
                        'div'=>"Test/calibration updated"
                        ));
                    exit;    
				}
				else
					$this->redirect(array('view','id'=>$quotationId));
		}

		if (Yii::app()->request->isAjaxRequest)
        {
			echo CJSON::encode(array(
                'status'=>'failure',
                'div'=>$this->renderPartial('_formtest', array('model'=>$model, 'quotationId'=>$quotationId
				), true, true)));
            exit;               
        }else{
			$this->render('update',array('model'=>$model));
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
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionDeletesample($id)
	{
		$model=QuotationSample::model()->findByPk($id);
		$model->delete();
		QuotationTest::model()->deleteAll('sample_id=:id', array(':id'=>$id));
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	public function actionDeletetest($id)
	{
		$model=QuotationTest::model()->findByPk($id);
		$model->delete();
		
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionDiscount(){
		$model = new Quotation;
		$datas = array();
		if(isset($_GET['id'])){
			$quotationId = $_GET['id'];
			$model = $model->findByPk($quotationId);
			$datas['id'] = $quotationId;
			$datas['discount'] = $model->discount;
		}
		if(isset($_POST['Quotation'])){
			$post = Quotation::model()->findByPk($quotationId);
			$post->discount = $_POST['Quotation']['discount'];
			$discount = Discount::model()->findByPk($_POST['Quotation']['discount']);
			$post->discount_rate = $discount->rate;
			$post->update();

			if($post->update() === true){
				if (Yii::app()->request->isAjaxRequest){
	                echo CJSON::encode(array(
	                    'status'=>'success', 
	                    'div'=>"Discount successfully added"
	                    ));
	                echo "save";
	                exit;               
	            }else{
	            	echo CJSON::encode(array(
	            		'status'=>'error',
	            		'div'=>"Could not save"
	        		));
	            	$this->redirect(array('view','id'=>$quotationId));
	            }	
			}else{
				echo CJSON::encode(array(
	           		'status'=>'error',
	           		'div'=>"Nothing to save"
	        	));
			}
		}
		if (Yii::app()->request->isAjaxRequest){
			$this->renderPartial('_formdiscount',array('model'=>$model, 'datas'=>$datas));
	        exit;               
	    }else{
	        $div = $this->renderPartial('_formdiscount',array('model'=>$model, 'datas'=>$datas));
	    }		
	}
	public function actionOnsitecharge(){
		$model = new Quotation;
		$datas = array();
		if(isset($_GET['id'])){
			$quotationId = $_GET['id'];
			$model = $model->findByPk($quotationId);
			$datas['id'] = $quotationId;
			$datas['onsite_charge'] = $model->discount;
		}
		if(isset($_POST['Quotation'])){
			$post = Quotation::model()->findByPk($quotationId);
			$post->onsite_charge = $_POST['Quotation']['onsite_charge'];
			$post->update();
			
			if($post->update() === true){
				if (Yii::app()->request->isAjaxRequest){
	                echo CJSON::encode(array(
	                    'status'=>'success', 
	                    'div'=>"On-site Charge successfully added"
	                    ));
	                echo "save";
	                exit;               
	            }else{
	            	echo CJSON::encode(array(
	            		'status'=>'error',
	            		'div'=>"Could not save"
	        		));
	            	$this->redirect(array('view','id'=>$quotationId));
	            }	
			}else{
				echo CJSON::encode(array(
	           		'status'=>'error',
	           		'div'=>"Nothing to save"
	        	));
			}
		}
		if (Yii::app()->request->isAjaxRequest){
			$this->renderPartial('_formonsite',array('model'=>$model, 'datas'=>$datas));
	        exit;               
	    }else{
	        $div = $this->renderPartial('_formonsite',array('model'=>$model, 'datas'=>$datas));
	    }		
	}
	public function actionPrint($id){
		$quotation = Quotation::model()->findByPk($id);

		$pdf = Yii::createComponent('application.extensions.tcpdf.quotePdf', 'P', 'cm', 'A4', true, 'UTF-8');
		$pdf = new quotePdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        spl_autoload_register(array('YiiBase','autoload'));
 		
 		$pdf->setQuotation($quotation);
       
        $pdf->SetCreator(PDF_CREATOR);  
 
        $pdf->SetTitle($quotation->quotationCode);               
        $pdf->SetMargins(0,57.15,0);
        $pdf->SetAutoPageBreak(TRUE, 50);
        $pdf->AddPage();
        $pdf->printRows();
        $pdf->lastPage();
        
        $pdf->Output($quotation->quotationCode, 'I');
	}
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Quotation('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Quotation']))
			$model->attributes=$_GET['Quotation'];
			
		$dataProvider=new CActiveDataProvider('Quotation');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Quotation('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Quotation']))
			$model->attributes=$_GET['Quotation'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Quotation the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Quotation::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Quotation $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='quotation-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
}
