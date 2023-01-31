<?php

class TestreportController extends Controller
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
        $model = $this->loadModel($id);
        
        $testReportSampleDataProvider = new CArrayDataProvider($model->samples, 
			array(
				'pagination'=>false,
			)
		);
        
		$this->render('view',array(
			'model'=>$model,
            'testReportSampleDataProvider'=>$testReportSampleDataProvider,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Testreport;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

        if(isset($_POST['Testreport']['request_id']))
            $request_id = $_POST['Testreport']['request_id'];
        
        $request = Request::model()->findAll(
			array(
				'condition'=>'labId = :labId', 
                'order'=>'requestDate DESC, id DESC',
				'params'=>array(':labId'=>Yii::app()->getModule('user')->user()->profile->getAttribute('labId')))
		);
        
        $samples = $this->listSamples($request_id);
        
		$gridDataProvider = new CArrayDataProvider($samples);
        
		if(isset($_POST['Testreport']))
		{
			$model->attributes=$_POST['Testreport'];
            $model->reportDate = date('Y-m-d', strtotime($_POST['Testreport']['reportDate']));
			if($model->save()){
                foreach($_POST['sampleIds'] as $id){
					$sample = Sample::model()->findByPk($id);
					
					$testReportSample = new TestreportSample;
					$testReportSample->testreport_id = $model->id;
					$testReportSample->sample_id = $sample->id;
					$testReportSample->save();
				}
                $this->redirect(array('view','id'=>$model->id));
            }
		}

		$this->render('create',array(
			'model'=>$model,
            'request'=>CHtml::listData($request, 'id', 'requestRefNum'),
            'gridDataProvider'=> $gridDataProvider,
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

		if(isset($_POST['Testreport']))
		{
			$model->attributes=$_POST['Testreport'];
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

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Testreport');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Testreport('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Testreport']))
			$model->attributes=$_GET['Testreport'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Testreport the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Testreport::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Testreport $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='testreport-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
    public function actionSearchSamples()
	{
		$request_id = $_POST['Testreport']['request_id'];
        $samples = $this->listSamples($request_id);
		$gridDataProvider = new CArrayDataProvider($samples, array('pagination'=>false));
		
		echo $this->renderPartial('_samples', array('gridDataProvider'=>$gridDataProvider),true);
	}
    
    public function listSamples($request_id){
		$request = Request::model()->findByPk($request_id);
        $samples = array();
		if($request->samps){
            foreach($request->samps as $sample){
                //$testReportSample = TestreportSample::model()->findByPk();
                $testReportSample = TestreportSample::model()->find(
                    array(
                        'condition'=>'sample_id = :sample_id', 
                        'params'=>array(':sample_id'=>$sample->id))
                );
                $samples[] = array(
                    'id' => $sample->id,
                    'sampleCode' => $sample->sampleCode,
                    'sampleName' => $sample->sampleName,
                    'description' => $sample->description,
                    'remarks' => $testReportSample ? 'Test Report: '.$testReportSample->testreport->reportNum : '',
                    'testReportSample' => $testReportSample ? true : false,
                );
            }
        }
        
        if(empty($samples))
				$samples=array();
        
        return $samples;
    }
}
