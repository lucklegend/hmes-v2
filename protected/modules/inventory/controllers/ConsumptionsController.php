<?php

class ConsumptionsController extends Controller
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
				'actions'=>array('index','view','destroy','deleteitem'),
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
		$model=new Consumptions;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Consumptions']))
		{
			$model->attributes=$_POST['Consumptions'];
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

		if(isset($_POST['Consumptions']))
		{
			$model->attributes=$_POST['Consumptions'];
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

	public function actionDeleteOrder($item){

	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		/*******************************************************
		name: Bergel T. Cutara
		org: DOST-IX, 991-1024
		date created: MArch 20 2017
		description: stores data in session and get data from session and transorm it into acataactiverecord
		********************************************************/
		$CheckOutForm = new CheckOutForm;
		$ordermodel = new OrderForm;
		$session = Yii::app()->session;
		$this->layout = "";
		
		// if it is ajax validation request
		$this->performAjaxValidation($ordermodel);

		//if the user checks out his cart
		if(isset($_POST['CheckOutForm']))
		{	

			$CheckOutForm->attributes=$_POST['CheckOutForm'];
			//validate stock and quantity
			if($CheckOutForm->validate()){
				//saves the orders to the session to db
				$CheckOutForm->customsave();
			}
			$CheckOutForm = new CheckOutForm;
			
		}

		//if the user adds an item to cart
		if(isset($_POST['OrderForm']))
		{	
			$ordermodel->attributes=$_POST['OrderForm'];

			//validates stocks and quantity
			if($ordermodel->validate()){
				//saves the record to the session
				$ordermodel->customsave();
			}else{
				$ordermodel->customgetErrors();
			}
			 
			$ordermodel = new OrderForm;
			
		}

		//fetch the session order to display on cgriview
		if($session['orders']!=""){
			 $unserialize = unserialize($session['orders']);
		}else{
			$unserialize = null;
		}	
		$total = OrderForm::getTotal($unserialize);
		$importDataProvider = new CArrayDataProvider($unserialize);
		$ordermodel->Quantity = 1;
		$this->render('index',array(
			'ordermodel'=>$ordermodel,
			'CheckOutForm'=>$CheckOutForm,
			'unserialize'=>$unserialize,
			'importDataProvider'=>$importDataProvider,
			'total'=>$total
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Consumptions('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Consumptions']))
			$model->attributes=$_GET['Consumptions'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}


	public function actionDeleteitem($item)
	{
		$session = Yii::app()->session;
		$unserialize = unserialize($session['orders']);
		//search the key of the item to delete
		$key = OrderForm::find_order_with_item($unserialize,$item);
		//there is an existing item in session then get the quantity
		if($key!==false){
			
			array_splice($unserialize,$key,1);
			$session['orders'] = serialize($unserialize);
			$total = OrderForm::getTotal($unserialize);
			echo $total;
		}
		else{
			//echo "the key shud be :".$key;
		}

		
		//exit();
	}


	public function actionDestroy()
	{
		$session = Yii::app()->session;
		$session['orders']=array();
		$this->redirect(array('Consumptions/')); 

	}


	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Consumptions the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Consumptions::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Consumptions $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='consumptions-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
