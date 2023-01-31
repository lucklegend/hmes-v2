<?php

class EquipmentController extends Controller
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
				'users'=>array('admin','*'),
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
		// if($_FILES['import_path']['tmp_name'])
		// {

		// 	$target_dir =Yii::getPathOfAlias('webroot').'/upload/';
		// 	$newname = basename(date("Y-m-d").'-'.$_FILES["import_path"]["name"]);
		// 	$target_file = $target_dir . $newname;
		// 	// print_r($_FILES["import_path"]);
		// 	// exit();
		// 	//$target_file = Yii::app()->baseUrl.'/upload/'.basename($_FILES["import_path"]["tmp_name"]);
		// 	if($_FILES["import_path"]["type"]==="application/pdf"){
		// 		if(move_uploaded_file($_FILES["import_path"]["tmp_name"], $target_file)){
		// 			$id = $_POST['theid'];
		// 			$maintenancedata =$newname;
		// 			$tempmodel = Equipmentmaintenance::model()->findByPk($id);
		// 			$tempmodel->maintenancedata = $maintenancedata;
		// 			$tempmodel->isdone=1;
		// 			if($tempmodel->save()){
		// 				$this->redirect(Yii::app()->createUrl('equipment/equipmentmaintenance/view',array(
		// 					'id'=>$id,
		// 				)));
		// 			}
		// 		}
		// 		else{
		// 			echo $_FILES['import_path'];
		// 			//exit();
		// 			throw new CHttpException(500,$_FILES["import_path"]);
		// 		}
		// 	}
		// 	else{
		// 		Yii::app()->user->setFlash('error','The file uploaded is not of type PDF!');
		// 	}
		// }

		// if($_FILES['import_path2']['tmp_name'])
		// {
		// 	//echo file_get_contents($_FILES['import_path']['tmp_name']);
		// 	$id = $_POST['theid'];
		// 	$calibrationdata = file_get_contents($_FILES['import_path2']['tmp_name']);
		// 	$tempmodel = Equipmentcalibration::model()->findByPk($id);
		// 	$tempmodel->certificate = $calibrationdata;
		// 	$tempmodel->isdone=1;
		// 	if($tempmodel->save()){
		// 		$this->redirect(Yii::app()->createUrl('equipment/equipmentcalibration/view',array(
		// 			//'model'=>$tempmodel,
		// 			'id'=>$id,
		// 		)));
		// 	}

		// }


		
		$model = $this->loadModel($id);
		$usage=new CActiveDataProvider('Equipmentusage', array(
			'criteria'=>array(
		        'condition'=>'equipmentID="'.$model->equipmentID.'"'/*.' and isdone = "0"'*/,
		        'order'=>'startdate DESC'
		    ),
		    'pagination'=>array(
		        'pageSize'=>5,
		    ),
		));
		$maintenance=new CActiveDataProvider('Equipmentmaintenance', array(
			'criteria'=>array(
		        'condition'=>'equipmentID="'.$model->equipmentID.'"'/*.' and isdone = "0"'*/,
		        'order'=>'date DESC'
		    ),
		    'pagination'=>array(
		        'pageSize'=>5,
		    ),
		));
		$calibration=new CActiveDataProvider('Equipmentcalibration', array(
			'criteria'=>array(
		        'condition'=>'equipmentID="'.$model->equipmentID.'"'/*.' and isdone = "0"'*/,
		        'order'=>'date DESC'
		    ),
		    'pagination'=>array(
		        'pageSize'=>5,
		    ),
		));

		$this->render('view',array(
			'model'=>$model,
			'maintenance'=>$maintenance,
			'calibration'=>$calibration,
			'usage'=>$usage
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Equipment;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Equipment']))
		{
			$rnd = rand(0,9999);  // generate random number between 0-9999
			$rnd2 = rand(0,9999);  // generate random number between 0-9999
            $model->attributes=$_POST['Equipment'];
            $model->rstl_id = Yii::app()->Controller->getRstlId();
 			
            $uploadedFile=CUploadedFile::getInstance($model,'image');
            $fileName = "{$rnd}-".date('Y-m-d')."-{$uploadedFile}";  // random number + file name
            $model->image = $fileName;

            $uploadedFile2=CUploadedFile::getInstance($model,'image2');
            $fileName2 = "{$rnd2}-".date('Y-m-d')."-{$uploadedFile2}";  // random number + file name
            $model->image2 = $fileName2;


			
			if($model->save()){
				if(!empty($uploadedFile)) {
					$uploadedFile->saveAs(Yii::app()->basePath.'/../equipment_uploads/pics/'.$fileName);  // image will uplode to rootDirectory/banner/
				}
				if(!empty($uploadedFile2)) {
					$uploadedFile2->saveAs(Yii::app()->basePath.'/../equipment_uploads/pics/'.$fileName2);  // image will uplode to rootDirectory/banner/
				}
				$this->redirect(array('view','id'=>$model->ID));
			}
		}

		$dirname = Yii::getPathOfAlias('webroot').'/upload/';
		$file = Yii::getPathOfAlias('webroot').'/upload/importequipment.txt';
		
		// Create $dirname if not exist
		if (!is_dir($dirname)){
			mkdir($dirname, 0755, true);
		}
		
		// Create $file if not exist
		if(!file_exists($file)){  
			fopen($file, 'w+');
			file_put_contents($file, serialize(array()));
		}

		//loading import data
		if($_FILES['import_path']['tmp_name'])
		{
			Yii::import('application.vendors.PHPExcel',true);
            $objReader = new PHPExcel_Reader_Excel2007;
            $objPHPExcel = $objReader->load($_FILES['import_path']['tmp_name']);
            //$objPHPExcel = $objReader->load('F:\import.xls');
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow(); // e.g. 10
            $highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
			//$equipment = array();
            $importData = array();
			for ($row = 6; $row <= $highestRow; ++$row) {
				$equipment = array(
					'equipmentID'=> $objWorksheet->getCellByColumnAndRow(1, $row)->getValue(),
					'name'=>$objWorksheet->getCellByColumnAndRow(0, $row)->getValue(),
					'description'=>$objWorksheet->getCellByColumnAndRow(2, $row)->getValue(),
					'lab'=>$objWorksheet->getCellByColumnAndRow(4, $row)->getCalculatedValue(),
					'classificationID'=>$objWorksheet->getCellByColumnAndRow(6, $row)->getCalculatedValue(),
					'specification'=>$objWorksheet->getCellByColumnAndRow(7, $row)->getValue(),
					'date_received'=>date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(8, $row)->getValue())),
					//'received_by'=>$objWorksheet->getCellByColumnAndRow(10, $row)->getValue(),
					'received_by'=>Yii::app()->user->id,
					'amount'=>$objWorksheet->getCellByColumnAndRow(11, $row)->getValue(),
					'supplier'=>$objWorksheet->getCellByColumnAndRow(13, $row)->getValue(),
					'status'=>$objWorksheet->getCellByColumnAndRow(15, $row)->getCalculatedValue(),
					'remarks'=>$objWorksheet->getCellByColumnAndRow(16, $row)->getValue(),
					'brand'=>$objWorksheet->getCellByColumnAndRow(17, $row)->getValue(),
					'model'=>$objWorksheet->getCellByColumnAndRow(18, $row)->getValue(),
					'serialno'=>$objWorksheet->getCellByColumnAndRow(19, $row)->getValue(),
					'sourcefund'=>$objWorksheet->getCellByColumnAndRow(21, $row)->getCalculatedValue(),
				);
				array_push($importData, $equipment);
			}

			file_put_contents($file, serialize($importData));

			

        }

        $data = file_get_contents($file);
		$arr = unserialize($data);
		$importDataProvider = new CArrayDataProvider($arr);

		$this->render('create',array(
			'model'=>$model,
			'importDataProvider'=>$importDataProvider,
			'equipment'=>$equipment,
			'has_duplicate'=>$this->checkExistingEquipments($arr)
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

		if(isset($_POST['Equipment']))
		{
			// $_POST['Equipment']['image'] = $model->image;
			// $_POST['Equipment']['image2'] = $model->image2;
            $model->attributes=$_POST['Equipment'];
 
            $uploadedFile=CUploadedFile::getInstance($model,'image');
            $uploadedFile2=CUploadedFile::getInstance($model,'image2');
            //echo $model->image; exit();
			
			if($model->save()){
				if(!empty($uploadedFile))  // check if uploaded file is set or not
                {
                    $uploadedFile->saveAs(Yii::app()->basePath.'/../equipment_uploads/pics/'.$model->image);
                }
                if(!empty($uploadedFile2))  // check if uploaded file is set or not
                {
                    $uploadedFile2->saveAs(Yii::app()->basePath.'/../equipment_uploads/pics/'.$model->image2);
                }
				$this->redirect(array('view','id'=>$model->ID));
			}
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
		//equipment maintenance

		$maintenance=new CActiveDataProvider('Equipmentmaintenance', array(
			'criteria'=>array(
		        //'condition'=>'equipmentID='.$model->equipmentID/*.' and isdone = "0"'*/,
		        'order'=>'date DESC'
		    ),
		    'pagination'=>array(
		        'pageSize'=>5,
		    ),
		));

		//equipment calibration
		$calibration=new CActiveDataProvider('Equipmentcalibration', array(
			'criteria'=>array(
		        //'condition'=>'equipmentID='.$model->equipmentID/*.' and isdone = "0"'*/,
		        'order'=>'date DESC'
		    ),
		    'pagination'=>array(
		        'pageSize'=>5,
		    ),
		));

		$dataProvider=new CActiveDataProvider('Equipment');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'maintenance'=>$maintenance,
			'calibration'=>$calibration,
		));
	}


	/**
	 * Manages all models.
	 */
	public function actionAdmin($id = 0)
	{
		$model=new Equipment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Equipment']))
			$model->attributes=$_GET['Equipment'];
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionCreateDataEntryFile()
	{
		// $equipmentDataProvider=new CActiveDataProvider(Equipment, array(
		// 		'pagination'=>false,
		// ));
		
		//get all the lab
		$labs = Lab::model()->findAll();
		if($labs){
			$labCount=count($labs);
			$labArray=array();
			foreach($labs as $lab){
				$labArray[]=array('labId'=>$lab->id, 'labName'=>$lab->labCode. " - ".$lab->labName);
			}
		}

		$classifications = Equipmentclassification::model()->findAll();
		if($classifications){
			$classifyCount=count($classifications);
			$classifyArray=array();
			foreach($classifications as $classify){
				$classifyArray[]=array('classifyId'=>$classify->ID, 'classifyName'=>$classify->name);
			}
		}

		$statuses = Equipmentstatus::model()->findAll();
		if($statuses){
			$statusCount=count($statuses);
			$statusArray=array();
			foreach($statuses as $status){
				$statusArray[]=array('statusId'=>$status->ID, 'statusName'=>$status->name);
			}
		}

		$fundings = Fundings::model()->findAll();
		if($fundings){
			$fundingCount=count($fundings);
			$fundingsArray=array();
			foreach($fundings as $funding){
				$fundingsArray[]=array('fundingId'=>$funding->ID, 'fundingName'=>$funding->code);
			}
		}
		//put a lab and classification active data record to arrayy //merging
		$data = array_map(function ($arr1, $arr2) {
		$new2 = array();
		foreach ($arr2 as $key => $value) {
		if (($value !== NULL) || !isset($arr1[$key])) {
			$new2[$key] = $value;
			}
		}
			if($arr1==NULL){
				return $new2;
			}else{
				return array_merge($arr1, $new2);
			}
		}, $labArray, $classifyArray);


		$data2 = array_map(function ($arr1, $arr2) {
		$new2 = array();
		foreach ($arr2 as $key => $value) {
		if (($value !== NULL) || !isset($arr1[$key])) {
			$new2[$key] = $value;
			}
		}
			if($arr1==NULL){
				return $new2;
			}else{
				return array_merge($arr1, $new2);
			}
		}, $data, $statusArray);



		$data3 = array_map(function ($arr1, $arr2) {
		$new2 = array();
		foreach ($arr2 as $key => $value) {
		if (($value !== NULL) || !isset($arr1[$key])) {
			$new2[$key] = $value;
			}
		}
			if($arr1==NULL){
				return $new2;
			}else{
				return array_merge($arr1, $new2);
			}
		}, $data2, $fundingsArray);


		$dataProvider=new CArrayDataProvider($data3);

		$this->widget('ext.eexcelview.EExcelViewCreateEquipmentDataEntryFile', array(
			'dataProvider'=>$dataProvider,
			'columns'=>array(				
				'labName',
				'labId',
				'classifyName',				
				'classifyId',
				'statusName',				
				'statusId',
				'fundingName',				
				'fundingId'
			),
			'title'=>'Equipment Data Entry Form for ULIMS',
			'filename'=>'Equipment DataEntryForm',
			'grid_mode'=>'export',
			'exportType' => 'Excel2007',
			'creator' =>'ULIMS equipment',
			'subject'=>'Data entry form for ULIMS',
			'labCount'=>$labCount,
			'classifyCount'=>$classifyCount,
			'statusCount'=>$statusCount,
			'fundingCount'=>$fundingCount,
			)
		);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Equipment the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Equipment::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Equipment $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='equipment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function checkExistingEquipments($equipments)
	{
		$has_duplicate = false;
		foreach($equipments as $equipment){
			$data = equipment::model()->findByAttributes(array('equipmentID'=>$equipment['equipmentID']));
			$has_duplicate = $data ? true : false;
			if($has_duplicate){
				return true;
			}		
		}
		return false;
	}

	public function actionImport()
	{
		$file = Yii::getPathOfAlias('webroot').'/upload/importequipment.txt';
		$data = file_get_contents($file);
		$arr = unserialize($data);
		
		file_put_contents($file, serialize(array()));
		$count = 0;
		foreach($arr as $equipment){
			
			$new_equipment = new Equipment();
			$new_equipment->equipmentID =$equipment['equipmentID'];
			$new_equipment->name=$equipment['name'];
			$new_equipment->description=$equipment['description'];
			$new_equipment->image =$equipment['equipmentID']."-".rand(0,9999);
			$new_equipment->image2 =$equipment['equipmentID']."-".rand(0,9999);
			$new_equipment->lab=$equipment['lab'];
			$new_equipment->classificationID=$equipment['classificationID'];
			$new_equipment->specification=$equipment['specification'];
			$new_equipment->date_received=$equipment['date_received'];
			$new_equipment->received_by=$equipment['received_by'];
			$new_equipment->amount=$equipment['amount'];
			$new_equipment->supplier=$equipment['supplier'];
			$new_equipment->status=$equipment['status'];
			$new_equipment->remarks=$equipment['remarks'];
			$new_equipment->brand=$equipment['brand'];
			$new_equipment->model=$equipment['model'];
			$new_equipment->serialno=$equipment['serialno'];
			$new_equipment->sourcefund=$equipment['sourcefund'];
			$new_equipment->rstl_id = Yii::app()->Controller->getRstlId();
			if($new_equipment->save(false)){
				$count ++;
			}

		}
		if($count != 0){
			$html = $count;
			echo CJSON::encode(array(
	                  	'status'=>'success', 
	                    'div'=>$html.' Equipments Successfully Imported.'
	                    ));
			exit;
		}else{
			echo CJSON::encode(array(
                  	'status'=>'failure', 
                    'div'=>'<div style="text-align:center;" class="alert alert-error"><i class="icon icon-warning-sign"></i><font style="font-size:14px;"> System Warning. </font><br \><br \><div>'.$count.' Requests imported.</div></div>'
                    ));
			exit;
		}

	}
	public function actionClearFile(){
		$file = Yii::getPathOfAlias('webroot').'/upload/importequipment.txt';
		file_put_contents($file, serialize(array()));
		$this->redirect('create');
	}


	public function actionViewMaintenanceRecord(){
		if(isset($_POST['id']))
			$requestId=$_POST['id'];

		$maintenance=Equipmentmaintenance::model()->findByPk($requestId);
		// echo CJSON::encode("Date :' ".$maintenance->date."' and Type :".$maintenance->type);
		$datum = null;
		if($maintenance->type === "0")
			$datum ="Date : ".$maintenance->date." and Type : Standard Maintenance";
		else
			$datum = "Date : ".$maintenance->date." and Type : Preventive / Corrective Maintenance";

		echo json_encode(array("title"=>$datum,"data"=>nl2br($maintenance->maintenancedata)));
		//exit();
	}

	public function actionViewCalibrationRecord($id){
		
		// if(isset($_POST['id']))
		// 	$requestId=$_POST['id'];

		//echo $id; exit();

		// $calibration=Equipmentcalibration::model()->findByPk($requestId);
		$calibration=Equipmentcalibration::model()->findByPk($id);
		
			if($calibration)
			 	echo $this->renderPartial('_popupload', array('pdf'=>$calibration->certificate));
			 else
			 	echo $this->renderPartial('_popupload', array('pdf'=>NULL));

		//$basePath=Yii::app()->baseUrl.'/upload/';

		//echo $basePath.$calibration->certificate;
		
	}

	

	public function actionGroup(){
		$model = new GroupForm();
		$this->performAjaxValidation($model);
		//if (Yii::app()->request->isAjaxRequest){
			//echo $_POST['id']; exit();
			if(isset($_POST['GroupForm'])){
					$model->attributes = $_POST['GroupForm'];
					
					$model->EquipmentID = serialize($model->EquipmentID);
					if($model->validate()){
						$model->EquipmentID = unserialize($model->EquipmentID);

						//$ids = explode(",",$model->EquipmentID );
						foreach($model->EquipmentID as $id){
							$equip = Equipment::model()->findByPk($id);
							if($equip){
								$equip->tags = $model->Tag;
								$equip->save(false);
							}
						}
						//$equipment= Equipment::model()->findByAttributes

						echo CJSON::encode(array(
		                'status' => 'success',
		                'div'=>"Equipment Group!" ));
		        	exit();
					}else{
						$model->customgetErrors();
					}
		        	
		       
			}

			//if(isset($_POST['id'])&&$_POST['id']!=""){
				
				// $equipment = Equipment::model()->findByPk($_POST['id']);
				//$equipment = Equipment::model()->findByPk(9);
	        	
		        	echo CJSON::encode(array(
		                'status' => 'failure',
		                'div'=>$this->renderPartial('_group', array('model'=>$model) ,true , true)));
		        	exit();
		       
			//}
			
       // }
	}

	public function actionDownload(){
		$date_purchased = $_POST['date_purchased'];
		$date_received =$_POST['date_received'];
		$equipmentID= $_POST['equipmentID'];
		$fundings=$_POST['fundings'];
		$mr=$_POST['mr'];
		$name=$_POST['name'];
		$lab=$_POST['lab'];
		
		echo CHtml::link('Generate Excel',Yii::app()->createUrl('inventory/equipment/excel' , array('name'=>$name,'date_received'=>$date_received,'equipmentID'=>$equipmentID,'fundings'=>$fundings,'mr'=>$mr,'date_purchased'=>$date_purchased,'lab'=>$lab)),array('class'=>'btn btn-info btn-large','target'=>'_blank'));

	}


	public function actionExcel($name,$date_received,$equipmentID,$fundings,$mr,$date_purchased,$lab){
		// $equipment=new CActiveDataProvider('Equipment', array(
		// 			'criteria'=>array(
		// 				'with'=>array('fund','user','user.profile'),
		// 				'select'=>array(
		// 					'*',
		// 					'CONCAT(profile.lastName,", ",profile.firstName, " ", IFNULL(profile.mi,"")) AS `fullName`'),
		// 		       'condition'=>'t.name LIKE "%'.$name.'%" AND date_received LIKE "%'.$date_received.'%" AND equipmentID LIKE "%'.$equipmentID.'%" AND date_purchased  LIKE "%'.$date_purchased.'%" AND fund.name LIKE "%'.$fundings.'%" AND fullName LIKE "%'.$mr.'%"',
		// 		       // 'condition'=>'t.name LIKE "%'.$name.'%" AND date_received LIKE "%'.$date_received.'%" AND equipmentID LIKE "%'.$equipmentID.'%" AND date_purchased  LIKE "%'.$date_purchased.'%" AND fund.name LIKE "%'.$fundings.'%" AND (profile.firstname LIKE "%'.$mr.'%" OR profile.lastname LIKE "%'.$mr.'%" OR profile.mi LIKE "%'.$mr.'%" )',
		// 		    ),
		// 		    'pagination'=>false,
		// 		));

		$equipment = new Equipment();
		// echo "<pre>";
		// print_r($equipment->searchbycustomfilter($name,$date_received,$equipmentID,$fundings,$mr,$date_purchased,$lab));
		// echo "</pre>";
		// exit();
		$xlsData =  $this->widget('ext.EExcelview.EExcelView', array(
		     'dataProvider'=> $equipment->searchbycustomfilter($name,$date_received,$equipmentID,$fundings,$mr,$date_purchased,$lab),
		     'title'=>'Title',
		     'autoWidth'=>false, 
		     'title'=>"Equipment",
			'filename'=>"Equipment",
			'grid_mode'=>'export',
			'autoWidth'=>true,
		      'columns'=>array(
				//'ID',
				'equipmentID',
				'name',
				'lab',
				'date_recieved',
				'date_purchased',
				array(
				'name'=>'fullName',
				'header'=>'MR',
				'value'=>'$data->user->profile->fullName'
				),
				array(
					'name'=>'fundings',
					'value'=>'$data->fund->name'
					),
				'amount',
				array(
					'name'=>'Depreciated Cost',
					'type'=>'raw',
					'filter'=>false,
					'value'=> function($data){
							$cost=$data['depreciation'];
							return $cost ;
						},
					'htmlOptions'=>array('style'=>'text-align:center'),
				),
			),
		));
	}

	public function actionCalendar(){
		$this->render('calendar');
	}

	public function actionCalendarView(){

		$maintenance = equipmentmaintenance::model()->findAll();
		$calibration = equipmentcalibration::model()->findAll();
		$usage = equipmentusage::model()->findAll();

		foreach ($maintenance as $record ){
			$items[]=array(
			    'title'=>"Maintenance : name",
			    'start'=>date("Y-m-d H:i:s", strtotime($record->date." 00:00:00")),
			    'end'=>date("Y-m-d H:i:s", strtotime($record->date." 24:00:00")),
			    // 'start'=>$start." 00:00:00",
			    // 'end'=>$end." 10:00:00",
			    'color'=> '#3385ff',
			    // 'mintime'=> "24:00:00",
			    // 'editable'=>true,
			    'allDay'=>true,
			    //'url'=>'http://anyurl.com'
			);
		}


		foreach ($calibration as $record ){
			$items[]=array(
			    'title'=>"Calibration : name",
			    'start'=>date("Y-m-d H:i:s", strtotime($record->date." 00:00:00")),
			    'end'=>date("Y-m-d H:i:s", strtotime($record->date." 24:00:00")),
			    // 'start'=>$start." 00:00:00",
			    // 'end'=>$end." 10:00:00",
			    'color'=> '#29a329',
			    // 'mintime'=> "24:00:00",
			    // 'editable'=>true,
			    'allDay'=>true,
			    //'url'=>'http://anyurl.com'
			);
		}

		foreach ($usage as $record ){
			$items[]=array(
			    'title'=>"Usage : name",
			    'start'=>date("Y-m-d H:i:s", strtotime($record->startdate." 00:00:00")),
			    'end'=>date("Y-m-d H:i:s", strtotime($record->enddate." 24:00:00")),
			    // 'start'=>$start." 00:00:00",
			    // 'end'=>$end." 10:00:00",
			    'color'=> '#ff0000',
			    // 'mintime'=> "24:00:00",
			    // 'editable'=>true,
			    'allDay'=>true,
			    //'url'=>'http://anyurl.com'
			);
		}


       
        
        echo CJSON::encode($items); 

	}

}
