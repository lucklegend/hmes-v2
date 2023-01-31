<?php

class StatisticController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionCustomer()
	{
		$model=new Statistic;
		$model->unsetAttributes();
		
		if(isset($_GET['year'])){
			$labId = $_GET['lab'];
			$year = $_GET['year'];
		}else{
			//$labId = 1;
			$year = date('Y');		
		}
		
		$customers = $this->getCustomers($scope, $year, $month);
		
		$this->render('customer',array(
			'model'=>$model, 
			'customers'=>$customers,
			'year'=>$year,
		));
	}
	
	function getCustomers($scope, $year, $month)
	{
		$minDate = date("Y-m-d", mktime(0, 0, 0, 1, 1, $year));
		$maxDate = date("Y-m-d", mktime(0, 0, 0, 12, 31, $year));
		
		$criteria=new CDbCriteria;
		$criteria->with = array(
			'customer'
		);
		$criteria->order = 'customer.customerName ASC';
		$criteria->select = array(
			'*',
			'count(*) as request_count',
			'sum(total) as total_income'
		);
		$criteria->group = 't.customerId';
		$criteria->condition = 't.cancelled = :cancelled AND requestDate >= :minDate AND requestDate <= :maxDate';
		$criteria->params = array(':cancelled' => 0, ':minDate' => $minDate, ':maxDate' => $maxDate);
		
		$dataProvider=new CActiveDataProvider(
		    'Request',
		    array(
		        'criteria'=>$criteria,
		        'pagination' => false,
		    )
		);
		
		return $dataProvider;
	}
	
	function actionExportCustomer($year){
	    $customers = $this->getCustomers($scope, $year, $month);
		
	    $this->toExcel($customers,
	        array(
	            //'id',
	            array(
	            	'name'=>'customer.customerName',
	            	'header'=>'CUSTOMER / COMPANY / FIRM',
	            ),
	        	'customer.address',
	        	'customer.tel',
	        	'request_count',
	        	'chemSamples',
	        	'microSamples',
	        	'chemTests',
	        	'microTests',
	        	'total_income'
	        ),
	        $year.' Customers Served (as of '.date("m-d-Y").')',
	        array(
	            //'year' => $year,
	        	'creator' => 'RSTL',
	        	'customers' => $customers,
	        ),
	        'Excel5'
	    );
	}
	
	public function getYear()
	{
		$listYear = array();
		for ($year = date('Y'); $year >= 2013; $year = $year - 1) {
			$y = array("index" => $year , "year" => $year);
			array_push($listYear, $y);
		}

		return $listYear;	
	}

	public function behaviors()
    {
        return array(
            'eexcelview'=>array(
                'class'=>'ext.eexcelview.EExcelBehavior',
            ),
        );
    }	
    public function getMonth()
	{
		$listMonth = array(
            array('index'=>1 , 'month'=>'JAN'),
            array('index'=>2 , 'month'=>'FEB'),
            array('index'=>3 , 'month'=>'MAR'),
            array('index'=>4 , 'month'=>'APR'),
            array('index'=>5 , 'month'=>'MAY'),
            array('index'=>6 , 'month'=>'JUN'),
            array('index'=>7 , 'month'=>'JUL'),
            array('index'=>8 , 'month'=>'AUG'),
            array('index'=>9 , 'month'=>'SEP'),
            array('index'=>10 , 'month'=>'OCT'),
            array('index'=>11 , 'month'=>'NOV'),
            array('index'=>12 , 'month'=>'DEC'),
        );
		/*for ($year = date('Y'); $year >= 2013; $year = $year - 1) {
			$y = array("index" => $year , "year" => $year);
			array_push($listYear, $y);
		}*/

		return $listMonth;	
	}

	public function actionSamples()
	{
		$model=new Statistic;
		$model->unsetAttributes();
		

        if(isset($_GET['year']) || isset($_GET['month'])){
			$month = $_GET['month'];
			$year = $_GET['year'];
		}else{
			$month = abs(date("m"));
			$year = date("Y");
		}
		
        $criteria = new CDbCriteria;

        $criteria->condition = 'YEAR(requestDate) = :year AND MONTH(requestDate) = :month AND cancelled = :cancelled';
        $criteria->order = 'requestDate DESC';
        $criteria->group = 'requestDate';
        $criteria->params = array(':cancelled'=>0, ':year'=>$year, ':month'=>$month);

        $sampleStats = new CActiveDataProvider('Request', array(
            'criteria'=>$criteria,
            'pagination'=>false
        ));
		
		$this->render('samples',array(
			
			'year'=>$year,
            'month'=>$month,
            'sampleStats'=>$sampleStats,
		));
	}
	public function actionLab(){
		$model=new Labstatistic;
		$model->unsetAttributes();
		

        if(isset($_GET['year']) || isset($_GET['month']) || isset($_GET['lab'])){
			$month = $_GET['month'];
			$year = $_GET['year'];
			$lab = $_GET['lab'];
		}else{
			$month = abs(date("m"));
			$year = date("Y");
			$lab = 1;
		}
		
        $criteria = new CDbCriteria;

        $criteria->condition = 'YEAR(requestDate) = :year AND MONTH(requestDate) = :month AND cancelled = :cancelled AND labId = :lab';
        $criteria->order = 'requestDate ASC';
        $criteria->params = array(':cancelled'=>0, ':year'=>$year, ':month'=>$month, ':lab'=>$lab,);

        $sampleStats = new CActiveDataProvider('Request', array(
            'criteria'=>$criteria,
            'pagination'=>false
        ));

		$this->render('lab',array(
			
			'year'=>$year,
            'month'=>$month,
           	'lab'=>$lab,
            'sampleStats'=>$sampleStats,
		));
	}
}