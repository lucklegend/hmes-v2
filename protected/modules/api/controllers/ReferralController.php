<?php

class ReferralController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
		//return array('rights');
	}
	
	public function allowedActions()
	{
		return 'getsamplecode';
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
				'actions'=>array('index','view','login'),
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
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */

	public function actionIndex()
	{
		$model = Referral::model()->findAll();
		echo CJSON::encode($model);

		exit();
	}

	/*public function actionLogin($username,$password)
	{
		$model = User::model()->find(array(
	   			'select'=>'*',
	    		'condition'=>'username = :username AND password = :password ',
	    		'params'=>array(':username' => $username, ':password' => $password)
			));
		if(isset($model->id))
			echo CJSON::encode(array('status'=>'success'));
		else
			echo CJSON::encode(array('status'=>'failed'));
		//echo CJSON::encode($model);

		exit();
	}*/
	
	public function sendJSONResponse( $arr)
    {
        header('Content-type: application/json');
        echo json_encode($arr);
        Yii::app()->end();
    }


}