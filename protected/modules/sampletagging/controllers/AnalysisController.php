<?php
/*******************************************************
		name: Janeedi A. Grapa
		org: DOST-IX, 991-1024
		date created: April 20, 2017


********************************************************/
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
				'actions'=>array('cancel'),
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


	public function actionCancel($id)
	{
		
		$tagging = Tagging::model()->findByAttributes(
					array('analysisId'=>$id)
					);

		Analysis::model()->updateByPk($id, 
			array('cancelled'=>1,
				  'deleted'=>1,
				  'fee'=>0,
			));
		
		if ($tagging->status==null){

			$model=new tagging;
			$model->reason='Cancellled by CRO';
			$model->cancelledBy=Yii::app()->user->id;
			$model->cancelDate=Date('Y-m-d');
			$model->status=3;
			$model->cancelled=1;

			if($model->save(false))
			{	
					Analysis::model()->updateByPk($id, 
					array('taggingId'=>$model->id,
					));

					Tagging::model()->updateByPk($model->id, 
					array('analysisId'=>$id,
					));	
			}
		}
		else{
		Tagging::model()->updateByPk($tagging->id, 
			array('status'=>3,
				  'cancelled'=>1,
				  'cancelledBy'=>Yii::app()->user->id,
				  'reason'=>'Cancellled by CRO',
				  'cancelDate'=>Date('Y-m-d'),
			));
		}

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


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Analysis the loaded model
	 * @throws CHttpException
	 */

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


	
}
