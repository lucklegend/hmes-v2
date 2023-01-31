<?php

class StocksController extends Controller
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
			'rights'
			// 'postOnly + delete', // we only allow deletion via POST request
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
		$model=new Stocks;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Stocks']))
		{
			$model->attributes=$_POST['Stocks'];
			$model->rstl_id = Yii::app()->Controller->getRstlId();
			$model->recieved_by= Yii::app()->user->id;
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

		if(isset($_POST['Stocks']))
		{
			$model->attributes=$_POST['Stocks'];
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
		$dataProvider=new CActiveDataProvider('Stocks');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Stocks('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Stocks']))
			$model->attributes=$_GET['Stocks'];


		/*******************************************************
		name: Bergel T. Cutara
		org: DOST-IX, 991-1024
		date created: Jan 6 2017
		description: uploads , extracts and load the data from the excel file
		********************************************************/
		$dirname = Yii::getPathOfAlias('webroot').'/upload/';
		$file = Yii::getPathOfAlias('webroot').'/upload/import.txt';
		
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
			//echo $_FILES['import_path']['tmp_name']; //exit();

            //  Read your Excel workbook
			//try {
			    $objPHPExcel = $objReader->load($_FILES['import_path']['tmp_name']);
			// } catch(Exception $e) {
			//     die('Error loading file');
			// }



           

            //$objPHPExcel = $objReader->load('F:\import.xls');
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow(); // e.g. 10
            $highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
			//$scholar = array();

            $importData = array();
			for ($row = 6; $row <= $highestRow; ++$row) {
				$stocks = array(
					'stockCode'=> $objWorksheet->getCellByColumnAndRow(2, $row)->getValue(),
					'supplyID'=>$objWorksheet->getCellByColumnAndRow(1, $row)->getCalculatedValue(),
					'name'=>$objWorksheet->getCellByColumnAndRow(3, $row)->getValue(),
					'description'=>$objWorksheet->getCellByColumnAndRow(4, $row)->getValue(),
					'manufacturer'=>$objWorksheet->getCellByColumnAndRow(9, $row)->getValue(),
					'unit'=>$objWorksheet->getCellByColumnAndRow(6, $row)->getValue(),
					'quantity'=>$objWorksheet->getCellByColumnAndRow(5, $row)->getValue(),
					//'daterecieved'=>$objWorksheet->getCellByColumnAndRow(12, $row)->getValue(),
					'daterecieved'=>date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(12, $row)->getValue())),
					// 'dateopened'=>$objWorksheet->getCellByColumnAndRow(15, $row)->getValue(),
					'dateopened'=>date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(15, $row)->getValue())),
					// 'expiry_date'=>$objWorksheet->getCellByColumnAndRow(16, $row)->getValue(),
					'expiry_date'=>date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(16, $row)->getValue())),
					'recieved_by'=>Yii::app()->user->id,
					'threshold_limit'=>$objWorksheet->getCellByColumnAndRow(8, $row)->getValue(),
					'location'=>$objWorksheet->getCellByColumnAndRow(7, $row)->getValue(),
					'batch_number'=>$objWorksheet->getCellByColumnAndRow(17, $row)->getValue(),
					'supplierID'=>$objWorksheet->getCellByColumnAndRow(11, $row)->getCalculatedValue(),
					'amount'=>$objWorksheet->getCellByColumnAndRow(18, $row)->getValue(),
				);
				array_push($importData, $stocks);
			}

			file_put_contents($file, serialize($importData));


        }

        $data = file_get_contents($file);
		$arr = unserialize($data);
		$importDataProvider = new CArrayDataProvider($arr);


		/**********END*************/

		$this->render('admin',array(
			'model'=>$model,
			'importDataProvider'=>$importDataProvider 
		));
	}


	/*******************************************************
		name: Bergel T. Cutara
		org: DOST-IX, 991-1024
		date created: Jan 6 2017
		description: clear temp excel file/s
		********************************************************/

	public function actionClearFile(){
		$file = Yii::getPathOfAlias('webroot').'/upload/import.txt';
		file_put_contents($file, serialize(array()));
		$this->redirect('admin');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Stocks the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Stocks::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Stocks $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='stocks-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}



	/*******
	name : Bergel T. Cutara
	organization : DOST-IX, 991-1024
	date created: Jan 5, 2017
	********/
	public function actionInitialinventoryform(){
		// get all of supplies and put on in an array in a value pair format (name , id)
		$supplies = Supplies::model()->findAll();
		if($supplies){
			$supplycount=count($supplies);
			$suppliesArray=array();
			foreach($supplies as $supply){
				$suppliesArray[]=array('supplyName'=>$supply->name,'supplyId'=>$supply->id);
			}
		}

		//get all of supplier and put on in an array in a value pair format (name , id)
		$suppliers = Suppliers::model()->findAll();
		if($suppliers){
			$suppliercount=count($suppliers);
			$suppliersArray=array();
			foreach($suppliers as $supplier){
				$suppliersArray[]=array('supplierName'=>$supplier->name,'supplierId'=>$supplier->id);
			}
		}

		//merge supplies and suppliers into data
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
		}, $suppliesArray, $suppliersArray);
		//get the data using providers (in an array) 
		$dataProvider=new CArrayDataProvider($data,array(
			'pagination'=>false,
			));
		// this extension would actually download the excel file
		$this->widget('ext.eexcelview.EExcelViewCreateInventoryDataEntryFile', array(
			'dataProvider'=>$dataProvider,
			'title'=>'Inventory Data Entry Form',
			'filename'=>'InventoryDataEntryForm',
			'grid_mode'=>'export',
			'exportType' =>'Excel2007',
			'creator' =>'berujiru',
			'subject'=>'Data entry form for Inventory',
			'suppliescount'=>$supplycount,
			'supplierscount'=>$suppliercount,
			)
		);


	}
	/*******END*******/

	/*************************
	name: Bergel T. Cutara
	org: DOST-IX, 991-1024
	date created: Jan 9 2017
	function description: reads data on txt file, and upload to database
	***************************/



	public function actionImportdata()
	{
		$file = Yii::getPathOfAlias('webroot').'/upload/import.txt';
		$data = file_get_contents($file);
		$arr = unserialize($data);
		
		file_put_contents($file, serialize(array()));
		$count = 0;
		foreach($arr as $scholar){
			
			$stock = new Stocks();
			$stock->stockCode =$scholar['stockCode'];
			$stock->supplyID=$scholar['supplyID'];
			$stock->name=$scholar['name'];
			$stock->description=$scholar['description'];
			$stock->manufacturer=$scholar['manufacturer'];
			$stock->unit=$scholar['unit'];
			$stock->quantity=$scholar['quantity'];
			$stock->daterecieved=$scholar['daterecieved'];
			$stock->dateopened=$scholar['dateopened'];
			$stock->expiry_date=$scholar['expiry_date'];
			$stock->recieved_by=$scholar['recieved_by'];
			$stock->threshold_limit=$scholar['threshold_limit'];
			$stock->location=$scholar['location'];
			$stock->batch_number=$scholar['batch_number'];
			$stock->supplierID=$scholar['supplierID'];
			$stock->amount=$scholar['amount'];
			$stock->rstl_id = Yii::app()->Controller->getRstlId();

			if($stock->save(false)){
				$count++; //counts record save
			}

		}
		if($count != 0){


			$html = $count;
			echo CJSON::encode(array(
	                  	'status'=>'success', 
	                    'div'=>$html.' stocks Successfully Imported.'
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

	/**********************end************************/





}
