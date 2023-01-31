<?php

class ReportController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionStock()
	{
		$stockreport = new Stockreportform;

		//$this->performAjaxValidation($stockreport);

		if(isset($_POST['Stockreportform']))
		{	
			$stockreport->attributes=$_POST['Stockreportform'];

			//validates stocks and quantity
			if($stockreport->validate()){
				//saves the record to the session
				$stock=new CActiveDataProvider('Stocks', array(
					'criteria'=>array(
				        // 'condition'=>'daterecieved >= "'.$model->DateStart.'" and daterecieved <= "'.$model->DateEnd.'"',
				        'condition'=>'daterecieved BETWEEN "'.$stockreport->DateStart.'" AND "'.$stockreport->DateEnd.'"',
				    ),
				    'pagination'=>false,
				));
			
				$this->render('view',array('model'=>$stock,'datestart'=>$stockreport->DateStart,'dateend'=>$stockreport->DateEnd)); exit();

			 }else{
			 	$stockreport->customgetErrors();
			 }



			 
			$ordermodel = new OrderForm;
			
		}

		$this->render('stocks',array('model'=>$stockreport));
	}

	public function actionDownload($datestart,$dateend){
		//echo $datestart. " - ".$dateend; exit();

		$stock=new CActiveDataProvider('Stocks', array(
					'criteria'=>array(
				        // 'condition'=>'daterecieved >= "'.$model->DateStart.'" and daterecieved <= "'.$model->DateEnd.'"',
				        'condition'=>'daterecieved BETWEEN "'.$datestart.'" AND "'.$dateend.'"',
				    ),
				    'pagination'=>false,
				));


		$this->widget('ext.EExcelview.EExcelView', array(
		     'dataProvider'=> $stock,
		     'title'=>'Title',
		     'autoWidth'=>false,
		     'title'=>"Morale Survey",
			'filename'=>"Inventory Stock",
			'grid_mode'=>'export',
			'autoWidth'=>true,
		      'columns'=>array(
				//'ID',
				'stockCode',
				'supplyID',
				'name',
				'daterecieved',
				'dateopened',
				'expiry_date',
				'location',
				'batch_number',
			),
		));

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