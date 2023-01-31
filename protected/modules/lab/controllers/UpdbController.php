<?php



class UpdbController extends Controller
{
	public function actionIndex()
	{
		$newtable = "";
		$table="";
		$number="";
		if(isset($_POST['Tblform'])){

			$newtable = $_POST['Tblform']['name'];
			// //re-save
			// $xml->save("/tmp/test.xml");
		}

		if(isset($_GET['table'])){
			$table = $_GET['table'];
			$number=$_GET['number'];
		}


	// 	if(!$table==""){
	// echo $table; exit;}
		
			$this->render('index',[	
				'newtable'=>$newtable,
				'table'=>$table,
				'number'=>$number
				]);
		
	}

	public function actionSynccustomer($start,$url = "http://ulimsportal.onelab.ph/api/sync_customer"){
		// echo $url; exit;
		// echo $start; exit;
		//get all the customers
		$customers= Yii::app()->db->createCommand("SELECT * FROM `ulimslab`.`customer` ORDER BY id ASC LIMIT ".$start.",1000")->queryAll();
		// $customers = Customer::model()->findAll(); 
		// var_dump($customers); exit;
		$allCustomers = [];
		foreach($customers as $customer){
			// var_dump($customer); 
			$temp = [
				'customerName' => $customer['customerName'],
				'rstl_id'=> $customer['rstl_id'],
                'classification_id'=> 1,
                'latitude'=> "",
                'longitude'=> "",
                'head'=> $customer['head'],
                'barangay_id'=> $customer['barangay_id'],
                'address'=> $customer['address'],
                'tel'=> $customer['tel'],
                'fax'=> $customer['fax'],
                'email'=> $customer['email'],
                'typeId'=> $customer['typeId'],
                'natureId'=> $customer['natureId'],
                'industryId'=> $customer['industryId'],
                'created'=> $customer['created'],
                'id'=> $customer['id'],
                'municipalitycity_id'=> $customer['municipalitycity_id'],
                'district'=> $customer['district'],

			];
			array_push($allCustomers, $temp);
		}

		// var_dump($allCustomers); exit;
		$response = Yii::app()->curl->post($url, ['data'=>json_encode($allCustomers)]);
	 
	    //echo $response." # of records save!!!";
		// echo $response;
		$this->redirect(array(
			'index',
			'table'=>"customer",
			'number'=>$response
			));
	}

	public function actionSyncanalysis($start,$url = "http://ulimsportal.onelab.ph/api/sync_analysis"){
		//get all the requesta
		$analyses = Yii::app()->db->createCommand("SELECT * FROM `ulimslab`.`analysis` ORDER BY id ASC LIMIT ".$start.",1000")->queryAll();
		// $analyses = Analysis::model()->findAll(['condition'=>"id=1"]);
		$allanalyses = [];

			foreach ($analyses as $analysis) {
				$temp = [
					'rstl_id'=> $analysis['rstl_id'],
					'pstcanalysis_id'=> $analysis['pstcanalysis_id'],
					'sample_id'=> $analysis['sample_id'],
					'sample_code'=> $analysis['sampleCode'],
					'testname'=> $analysis['testName'],
					'method'=> $analysis['method'],
					'references'=> $analysis['references'],
					'quantity'=> $analysis['quantity'],
					'fee'=> $analysis['fee'],
					'test_id'=> $analysis['testId'],
					'cancelled'=> $analysis['cancelled'],
					'date_analysis'=> date('Y-m-d H:i:s', strtotime($analysis['analysisYear']."-".$analysis['analysisMonth']."-1")),
					'user_id'=> $analysis['user_id'],
					'is_package'=> $analysis['package'],
					'oldColumn_deleted'=> $analysis['deleted'],
					'analysis_old_id'=> $analysis['id'],
					'oldColumn_taggingId'=> $analysis['taggingId'],
					'oldColumn_result'=> $analysis['result'],
					'oldColumn_package_count'=> $analysis['package_count'],
					'oldColumn_requestId'=> $analysis['requestId'],
					'request_id'=> 0,
					'testcategory_id'=> 0,
					'sample_type_id'=> 0
				];
				array_push($allanalyses, $temp);
			}
		
		// var_dump($allanalyses); exit;
		$response = Yii::app()->curl->post($url, ['data'=>json_encode($allanalyses)]);
	 

	    $this->redirect(array(
			'index',
			'table'=>"analysis",
			'number'=>$response
			));

	}


	public function actionSyncrequest($start,$url = "http://ulimsportal.onelab.ph/api/sync_request"){
		//get all the requesta
		// $requests = Request::model()->findAll();
		$requests = Yii::app()->db->createCommand("SELECT * FROM `ulimslab`.`request` ORDER BY id ASC LIMIT ".$start.",1000")->queryAll();
		$allrequests = [];

		foreach ($requests as $request) {
			$temp = [
				'request_ref_num'=>$request['requestRefNum'],
				'request_datetime'=>date('Y-m-d H:i:s', strtotime($request['requestDate']." ".$request['requestTime'])),
				// 'request_datetime'=> $request['requestDate']." ".$request['requestTime'],
				'rstl_id'=>$request['rstl_id'],
				'lab_id'=>$request['labId'],
				'customer_id'=>$request['customerId'],
				'payment_type_id'=>$request['paymentType'],
				'modeofrelease_ids'=>$request['modeofreleaseId'],
				'discount'=>$request['discount'],
				'purpose_id'=>$request['purposeId'],
				'conforme'=>$request['conforme'],
				'report_due'=>$request['reportDue'],
				'total'=>$request['total'],
				'receivedBy'=>$request['receivedBy'],	
				'oldColumn_requestId'=>$request['requestId'],
				'oldColumn_sublabId'=>$request['sublabId'],
				'oldColumn_orId'=>$request['orId'],
				'oldColumn_completed'=>$request['completed'],
				'oldColumn_cancelled'=>$request['cancelled'],
				'oldColumn_create_time'=>$request['create_time'],
				'request_old_id'=>$request['id'],
				'created_at'=>0,
				'discount_id'=>0,
			];
			array_push($allrequests, $temp);
		}
		// var_dump($allrequests); exit;
		$response = Yii::app()->curl->post($url, ['data'=>json_encode($allrequests)]);
	 
	     $this->redirect(array(
			'index',
			'table'=>"request",
			'number'=>$response
			));

	}


	public function actionSyncsample($start,$url = "http://ulimsportal.onelab.ph/api/sync_sample"){
		//get all the samples
		// $samples = Sample::model()->findAll(['condition'=>"id = 1"]);
		$samples = Yii::app()->db->createCommand("SELECT * FROM `ulimslab`.`sample` ORDER BY id ASC LIMIT ".$start.",1000")->queryAll();
		// $samples = Sample::model()->findAll();
		$allsamples = [];

		foreach ($samples as $sample) {
			$temp = [
				'rstl_id'=>$sample['rstl_id'],
				'pstcsample_id'=>$sample['pstcsample_id'],
				'sample_type_id'=>$sample['sampleType_id'],
				'sample_code'=>$sample['sampleCode'],
				'samplename'=>$sample['sampleName'],
				'description'=>$sample['description'],
				'sampling_date'=>$sample['samplingDate'],
				'remarks'=>$sample['remarks'],
				'request_id'=>$sample['request_id'],
				'sample_month'=>$sample['sampleMonth'],
				'sample_year'=>$sample['sampleYear'],
				'active'=>$sample['cancelled'],
				'sample_old_id'=>$sample['id'],
				'oldColumn_requestId'=>$sample['requestId'],
				'oldColumn_completed'=>$sample['completed'],
				'oldColumn_datedisposal'=>$sample['datedisposal'],
				'oldColumn_mannerofdisposal'=>$sample['mannerofdisposal'],
				'oldColumn_batch_num'=>$sample['batch_num'],
				'oldColumn_package_count'=>$sample['package_count'],
				'testcategory_id'=>0,
			];
			array_push($allsamples, $temp);
		}

		$response = Yii::app()->curl->post($url, ['data'=>json_encode($allsamples)]);

	     $this->redirect(array(
			'index',
			'table'=>"sample",
			'number'=>$response
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