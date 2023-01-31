<?php

class RestEquipmentsController extends Controller
{

	/**
     * @return array action filters
     */
    public function filters()
    {
     	 
        //return array('AuthFilter');
    }
    /*
     * Custom code for API controller specific errors
     */
    public function init()
    {
        parent::init();
        Yii::app()->errorHandler->errorAction = $this->module->id.'/'.Yii::app()->controller->getId().'/error';
    }

    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error){
            header("HTTP/1.0 404 Not Found");
            echo 'endpoint not found';
            exit;
        }
    }

	public function actionIndex()
	{
		//$this->render('index');
		//echo "hahaha"; exit();
		$data = CJSON::decode($_POST['data']);
		$user = Users::model()->find("username = '".$data['username']."' AND password = '".md5($data['pw'])."'");
		if($user){
             
          	
            	echo CJSON::encode(array('message'=>$user->id,'result'=>true));
            //echo $user;
          } else {
              // header("HTTP/1.0 404 Not Found");
              // echo 'Please attach a correct username-password combination to a request.';
            echo CJSON::encode(array('message'=>"Incorrect username and/or password",'result'=>false));
              exit;
          }
	}


	public function actionConnectionTest(){
		echo "1";
	}

	public function actionGetEquipment(){
		$data = CJSON::decode($_POST["data"]);
		$equipment = Equipment::model()->findByAttributes(array("equipmentID"=>$data["barcode"]));
		if($equipment)
			echo CJSON::encode($equipment);
		else
			echo "Equipment Not Found!";
		exit();
	}

	public function actionGetstatus(){
		$statuses = Equipmentstatus::model()->findAll();
		echo CJSON::encode($statuses);
		exit();
	}

	public function actionGetUsage(){
		$data = CJSON::decode($_POST["data"]);
		// $usages = Equipmentusage::model()->findByAttributes(array('equipmentID'=>'1','condition'=>'equipmentID LIKE "2016-03-07%"')
		// 	);
		$criteria = new CDbCriteria;
		$criteria->condition = 'equipmentID = "'.$data["id"].'" and usagestatus = "1"';
		$usages = Equipment::model()->find($criteria);
		// $equipment = Equipment::model()->findByAttributes(array("equipmentID"=>$data["id"]));
		//  echo CJSON::encode($usages);
		if($usages)
			echo "1";
		else
			echo "0";
		exit();
	}

	public function actionAddusage()
	{
		//$_POST["data"] = '{"event_name":"sample_event","start_date":"2015/01/01","end_date":"2015/01/01","venue":"sample_venue"}';

		$model = new Equipmentusage();

		$model->attributes = CJSON::decode($_POST['data']);
		$model->startdate = date("Y-m-d h:i:sa");
		$model->remarks = "Start Usage: ".$model->remarks;
		// if($_POST["data"]["usage"]==="s")
		// 	$model->startdate = date();
		// elseif ($_POST["data"]["usage"]==="e") {
		// 	//find the record with the same equipment id 
		// }
		$response = array();

		  //save model, if that fails, get its validation errors:
		  if ($model->save() === false) {
		    $response['success'] = false;
		    $response['errors'] = $model->errors;
		  } else {
		    $response['success'] = true;
		    
		    //respond with the saved contact in case the model/db changed any values
		    $response['model'] = $model; 


		    //update the equipment usagestatus into "1" as inuse 
		    $equipment = Equipment::model()->find(array('condition'=>'equipmentID ="'.$model->equipmentID.'"'));
		    $equipment->usagestatus = 1;
		    $equipment->save();
		  }

		  header('Content-type:application/json');

		  //encode the response as json:
		  echo CJSON::encode($response);
		  //echo $_POST['data'];
		  //use exit() if in debug mode and don't want to return debug output
		  exit();
	}

	public function actionUpdateusage(){
		$modeltmp = new Equipmentusage();
		$modeltmp->attributes = CJSON::decode($_POST['data']);

		$model = Equipmentusage::model()->find(
			array(
				'condition'=>'EquipmentID="'.$modeltmp->equipmentID.'"',
				'order'=>'startdate DESC'
			)
		);
		$model->enddate = date("Y-m-d h:i:s");
		$model->remarks = $model->remarks."\n End Usage: ".$modeltmp->remarks;
		$response = array();

		  //save model, if that fails, get its validation errors:
		  if ($model->save() === false) {
		    $response['success'] = false;
		    $response['errors'] = $model->errors;
		  } else {
		    $response['success'] = true;
		    
		    //respond with the saved contact in case the model/db changed any values
		    $response['model'] = $model; 

		    //return the equipment usagestatus into "0" as not use 
		    $equipment = Equipment::model()->find(array('condition'=>'equipmentID ="'.$model->equipmentID.'"'));
		    $equipment->usagestatus = 0;
		    $equipment->save();
		  }

		  header('Content-type:application/json');

		  //encode the response as json:
		  echo CJSON::encode($response);
		  //echo $_POST['data'];
		  //use exit() if in debug mode and don't want to return debug output
		  exit();
	}


	public function filterAuthFilter($filterChain)
  {
    if (!isset($_POST['data'])) {
          header("HTTP/1.0 404 Not Found");
          echo 'Please attach a correct username-password combination to a request.';
          exit;
      } else {
          $data = CJSON::decode($_POST['data']);
          $user = Users::model()->find("username = '".$data['username']."' AND password = '".md5($data['pw'])."'");

          if($user){
              echo $filterChain->run();
          	//if($data['login']===true)
            	//echo CJSON::encode(array('message'=>$user->id,'result'=>true));
            //echo $user;
          } else {
               header("HTTP/1.0 400 Bad Request : Incorrect username and/or password");
               echo 'Incorrect username and/or password';
            //echo CJSON::encode(array('message'=>"Incorrect username and/or password",'result'=>false));
              exit;
          }
        //echo $user->id." ".$user->username." ".$user->password ;
      }
  }





	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}