<?php

class RestInventoryController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionCheckconnection(){
		echo json_encode("Intercepted haha");
	}

	public function actionLogin(){
		$data = CJSON::decode($_POST['data']);
		$user = Users::model()->find("username = '".$data['username']."' AND password = '".md5($data['password'])."'");
		if($user){
            	echo CJSON::encode(array('user_id'=>$user->id,'result'=>true));
            //echo $user;
          } else {
              // header("HTTP/1.0 404 Not Found");
              // echo 'Please attach a correct username-password combination to a request.';
             echo CJSON::encode(array('message'=>"Incorrect username and/or password",'result'=>false));
              exit();
          }
	}

	public function actionGetstock(){
		$data = CJSON::decode($_POST['data']);
		$stock = Stocks::model()->findByAttributes(array('stockCode'=>$data['code']));
		if($stock){
			echo CJSON::encode(array('result'=>true,'name'=>$stock->name));
		}
		else{
			echo CJSON::encode(array('result'=>false,'message'=>'NOT FOUND'));
		}
	}

	public function actionAddtocart(){
		//echo CJSON::encode(array('result'=>false,'message'=>"NOT FOUND")); exit();
		$data = CJSON::decode($_POST['data']);
		$stock = Stocks::model()->findByAttributes(array('stockCode'=>$data['code']));
		if(!$stock){
			echo CJSON::encode(array('result'=>false,'message'=>"NOT FOUND")); exit();
		}
		else{

			if($stock->quantity < $data['qty']){
				echo CJSON::encode(array('result'=>false,'message'=>$stock->stockCode." has only ".$stock->quantity." left!")); exit();
			}
			
			echo CJSON::encode(array('result'=>true,'data'=>array('code'=>$stock->stockCode,'qty'=>$data['qty'],'name'=>$stock->name,'price'=>$stock->amount))); exit();
		}
	}

	public function actiongetallitem(){
		$stocks = Stocks::model()->findAll();
		echo CJSON::encode($stocks); exit();
	}

	public function actioncheckout(){
		$err = false;
		$datatmp = CJSON::decode($_POST['data']);
		$data = CJSON::decode($datatmp['mydata']);
		$res_id = $datatmp['user_id'];
		//echo CJSON::encode(array('result'=>false,'message'=>$data)); exit();
		foreach($data as $datum => $value){
			$Stocks = Stocks::model()->findByAttributes(array('stockCode'=>$value['code']));
			if($Stocks){
				if(($Stocks->quantity -  $value['qty'])<0){
					echo CJSON::encode(array('result'=>false,'message'=>"$Stocks->stockCode dont have enough stocks, $Stocks->quantity left!")); exit();
				}
				
			}
			else{
				echo CJSON::encode(array('result'=>false,'message'=>"Item $Stocks->stockCode not found!")); exit();
			}
		}


		foreach($data as $datum => $value){
			$Stocks = Stocks::model()->findByAttributes(array('stockCode'=>$value['code']));	
			if($Stocks){
				if(($Stocks->quantity -  $value['qty'])>-1){
					$Stocks->quantity = $Stocks->quantity - $value['qty'];
					if($Stocks->save(false)){
						$consumptions = new Consumptions();
						$consumptions->stockID = $value['code'];
						$consumptions->balance =  $Stocks->quantity;
						$consumptions->amountused = $value['qty'];
						$consumptions->dateconsumed =date("Y-m-d H:i");
						$consumptions->withdrawnby = $res_id;
						if($consumptions->save(false)){
							//return success 
							//echo CJSON::encode(array('result'=>true,'message'=>"Items Successfully Withdrawn!")); exit();
						}
					}
					else{
					//rollback process here and error report : stcoks cant be subtract
					echo CJSON::encode(array('result'=>false,'message'=>"Stocks didnt subtract")); exit();
					}
				}
				else{
					//rollback process here and error report : stcoks cant be subtract
					echo CJSON::encode(array('result'=>false,'message'=>"$Stocks->stockCode dont have enough stocks")); exit();
				}
			}
			else{
				//rollback process here and error report : Stock not found
				echo CJSON::encode(array('result'=>false,'message'=>"Item not found!")); exit();
			}
		}

		if($err == true){
			echo CJSON::encode(array('result'=>false,'message'=>"Some Items can't be withdrawn")); exit();			
		}else{
			echo CJSON::encode(array('result'=>true,'message'=>"Item(s) successfully withdrawn")); exit();
		}


	}
	public function actionGetEquipment(){
		$data = CJSON::decode($_POST["data"]);
		$equipment = Equipment::model()->findByAttributes(array("equipmentID"=>$data["code"]));
		if($equipment)
			echo CJSON::encode(array("result"=>true,"code"=>$equipment->equipmentID,"name"=>$equipment->name,"status"=>$equipment->status));
		else
			echo CJSON::encode(array("result"=>false,"message"=>"Equipment Not Found"));
		exit();
	}

	public function actionSchedule(){
		$data = CJSON::decode($_POST["data"]);
		if($data){
			if($data["enddate"]==""){
				$data["enddate"]=$data["startdate"];
			}
			//check for conflicts for usages
			$criteria = new CDbCriteria;
			$criteria->condition = "(startdate >= '".$data["startdate"]."' AND startdate <= '".$data["enddate"]."') OR (enddate >= '".$data["startdate"]."' AND enddate <= '".$data["enddate"]."')";
			$model = Equipmentusage::model()->find($criteria);
			if($model){
				echo CJSON::encode(array("result"=>false,"message"=>"Your schedule is in conflict with scheduled Usage! Please see the schedule for available date(s)")); exit();
			}


			//check for conflicts for maintenance
			$criteria = new CDbCriteria;
			$criteria->condition = "date >= '".$data["startdate"]."' AND date <= '".$data["enddate"]."'";
			$model = Equipmentmaintenance::model()->find($criteria);
			if($model){
				echo CJSON::encode(array("result"=>false,"message"=>"Your schedule is in conflict with the scheduled maintenance! Please see the schedule for available date(s)")); exit();
			}

			//check for conflicts for calibration
			$criteria = new CDbCriteria;
			$criteria->condition = "date >= '".$data["startdate"]."' AND date <= '".$data["enddate"]."'";
			$model = Equipmentcalibration::model()->find($criteria);
			if($model){
				echo CJSON::encode(array("result"=>false,"message"=>"Your schedule is in conflict with the scheduled calibration! Please see the schedule for available date(s)")); exit();
			}




			switch($data["status"]){
				case "Usage": {
					$usage = new Equipmentusage;
					$usage->user_id =$data["user_id"];
					$usage->equipmentID=$data["code"]; 
					$usage->startdate=$data["startdate"];
					$usage->enddate=$data["enddate"];
					$usage->remarks=$data["remark"];
					if($usage->save()){
						echo CJSON::encode(array("result"=>true,"message"=>"Equipment Usage successfully scheduled!")); exit();
					}
				}; 
				break;
				case "Maintenance": {
					$maintenance = new Equipmentmaintenance;
					$maintenance->user_id =$data["user_id"];
					$maintenance->equipmentID=$data["code"]; 
					$maintenance->date=$data["startdate"];
					$maintenance->isdone=0;
					if($maintenance->save()){
						echo CJSON::encode(array("result"=>true,"message"=>"Equipment Maintenance successfully scheduled!")); exit();
					}
				};
				break;
				case "Calibration": {
					$calibration = new Equipmentcalibration;
					$calibration->user_id =$data["user_id"];
					$calibration->equipmentID=$data["code"]; 
					$calibration->date=$data["startdate"];
					$calibration->isdone=0;
					if($calibration->save()){
						echo CJSON::encode(array("result"=>true,"message"=>"Equipment Calibration successfully scheduled!")); exit();
					}
				};
				break;
				default:
					echo CJSON::encode(array("result"=>false,"message"=>"Post Status Unknown!s")); exit();
			}
		}
		
		echo CJSON::encode($data);
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