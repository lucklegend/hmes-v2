<?php

/**
 * This is the model class for table "request".
 *
 * The followings are the available columns in table 'request':
 * @property integer $id
 * @property string $requestRefNum
 * @property string $requestId
 * @property string $requestDate
 * @property string $requestTime
 * @property integer $rstl_id
 * @property integer $labId
 * @property integer $customerId
 * @property integer $paymentType
 * @property integer $discount
 * @property integer $orId
 * @property double $total
 * @property string $reportDue
 * @property string $conforme
 * @property string $receivedBy
 * @property integer $cancelled
 * @property string $create_time
 * @property double $inplant_charge
 * @property double $additional
 * @property string $validated_by
 */
class Request extends CActiveRecord
{
	public $customer_search;
	public $request_count;
	public $sample_count;
	public $total_income;
	public $payment_details;
	public $customerName;
    public $modeofrelease;
	
	public $import_path;
	public $import = false;
	public $minDate;
	public $testReport;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'request';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('requestDate, requestTime, labId, paymentType, reportDue, conforme, receivedBy, customerName, addforcert, validated_by, contact_number, vat', 'required'),
			array('rstl_id, labId, customerId, paymentType, discount, orId, cancelled, vat', 'numerical', 'integerOnly'=>true),
			array('total, inplant_charge, additional, discounted', 'numerical'),
			array('purpose', 'length', 'max'=>500),
			array('requestRefNum', 'length', 'max'=>50),
			array('requestId, conforme, receivedBy, transmission, conforme_designation, addforcert, validated_by', 'length', 'max'=>100),
			array('requestTime', 'length', 'max'=>25),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, requestRefNum, requestId, requestDate, requestTime, rstl_id, labId, customerId, paymentType, discount, orId, total, reportDue, modeofreleaseId, purposeId, conforme, conforme_designation, receivedBy, cancelled, from_date, to_date, customer_search, paymentStatus, customerName, create_time, inplant_charge, additional, transmission, purpose, addforcert', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'rstl'	=> array(self::BELONGS_TO, 'Rstl', 'rstl_id'),
			'laboratory'	=> array(self::BELONGS_TO, 'Lab', 'labId'),
			'customer'	=> array(self::BELONGS_TO, 'Customer', 'customerId'),
			'collection' => array(self::STAT, 'Collection', 'request_id', 'select'=> 'SUM(amount)', 'condition' => 'cancelled=0'),
			'receipts' => array(self::HAS_MANY, 'Collection', 'request_id', 'condition' => 'cancelled=0'),
		
			'otherfees' => array(self::HAS_MANY, 'Fee', 'request_id'),
			'samps' => array(self::HAS_MANY, 'Sample', 'request_id'),
			'sampleCount' => array(self::STAT, 'Sample', 'request_id', 'condition' => 'cancelled=0'),
        	'anals' => array(self::HAS_MANY, 'Analysis', array('id'=>'sample_id'), 'through'=>'samps', 'order'=>'sample_id ASC, package DESC'),
			//'requestTotal' => array(self::STAT, 'Analysis', '', 'through'=>'samps', 'order'=>'sample_id ASC', 'select'=>'SUM(fee)'),
			'paymentItem' => array(self::HAS_MANY, 'Paymentitem', '', 'on'=>'t.requestRefNum=details'),
			'disc'	=> array(self::BELONGS_TO, 'Discount', 'discount'),
			'purpose'	=> array(self::BELONGS_TO, 'Purpose', 'purpose'),
			'cancelDetails' => array(self::HAS_ONE, 'Cancelledrequest', 'request_id'),
			/********/
			'customersA' => array(self::BELONGS_TO, 'Customer', 'customerId'),
			'samplesA' => array(self::HAS_MANY, 'Sample', 'request_id'),
			'discountA' => array(self::BELONGS_TO, 'Discount','discount'),
			/********/
            
            'testreports' => array(self::HAS_MANY, 'Testreport', 'request_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'requestRefNum' => 'SR Num',
			'requestId' => 'Request',
			'requestDate' => 'Request Date',
			'requestTime' => 'Request Time',
			'labId' => 'Services',
			'sublabId' => 'Sub Laboratory',
			'customerId' => 'Customer',
			'customerName' => 'Customer',
			'paymentType' => 'Payment Type',
			'discount' => 'Discount',
			'orId' => 'OR No(s).',
			'orDate' => 'OR Date(s)',
			'amountReceived' => 'Amount Received',
			'balance' => 'Unpaid Balance',
			'total' => 'Total',
			'reportDue' => 'Report Due',
			'conforme' => 'Conforme',
			'conforme_designation' => 'Position',
			'receivedBy' => 'Received By',
			'cancelled' => 'Cancelled',
			'blank' => '',
			'customer_search' => 'Customers',
			'sampleName' => 'SAMPLE',
			'create_time' => 'Create Time',
			'inplant_charge' => 'On-site Charge',
			'additional' => 'Pick Up/Delivery Charge',
			'transmission' => 'Transmission', 
			'purpose' => 'Purpose',
			'validated_by' => 'Validated By',
			'modeofrelease' => 'Mode(s) of Release',
			'addforcert' => 'Addresee for Certificate',
			'contact_number' => 'Contact Number',
			'vat' => 'VAT Inclusive',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		
		$criteria->with = array('customer');
		$criteria->order = 't.requestDate DESC, t.id DESC';
		$criteria->compare('t.id',$this->id);
		//$criteria->compare('t.rstl_id', Yii::app()->getModule('user')->user()->profile->getAttribute('pstc'));
		$criteria->compare('t.rstl_id', Yii::app()->Controller->getRstlId());
		$criteria->compare('customer.customerName', $this->customer_search, true );
		$criteria->compare('requestRefNum',$this->requestRefNum,true);
		$criteria->compare('requestId',$this->requestId,true);
		$criteria->compare('requestDate',$this->requestDate,true);
		$criteria->compare('requestTime',$this->requestTime,true);

		// $criteria->compare('labId',$this->labId);
		switch(Yii::app()->getModule('user')->user()->profile->getAttribute('labId')){
            case 0:     
                $criteria->compare('labId', $this->labId);
                break;
            
            default:
                $criteria->compare('labId', Yii::app()->getModule('user')->user()->profile->getAttribute('labId'));
        }
		$criteria->compare('customerId',$this->customerId);
		$criteria->compare('paymentType',$this->paymentType);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('orId',$this->orId);
		$criteria->compare('total',$this->total);
		$criteria->compare('reportDue',$this->reportDue,true);
		$criteria->compare('conforme',$this->conforme,true);
		$criteria->compare('receivedBy',$this->receivedBy,true);
		$criteria->compare('cancelled',$this->cancelled);
		$criteria->compare('inplant_charge',$this->inplant_charge);
		$criteria->compare('additional',$this->additional);
		$criteria->compare('transmission',$this->transmission);
		$criteria->compare('purpose',$this->purpose);
		$criteria->compare('validated_by',$this->validated_by);
		$criteria->compare('addforcert',$this->addforcert);

		return new CActiveDataProvider(get_class($this),array(
            'pagination'=>array(
                'pageSize'=> Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
                //'pageSize'=> 10,
            ),
            'criteria'=>$criteria,
            'sort'=>array(
		        'attributes'=>array(
		            'customer_search'=>array(
		                'asc'=>'customer.customerName',
		                'desc'=>'customer.customerName DESC',
		            ),
		            '*',
		        ),
		    ),
        ));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->limsDb;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Request the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getORs($collections) 
	{
        $tmp = '<div class="raw2">';
        foreach ($collections as $collection) {
            $tmp = $tmp.$collection->receiptid.'-'.$collection->amount.'  ';
        }
        $tmp = $tmp.'</div>';
        return $tmp;
    }
    
	public static function getORsAdminView($collections) 
	{
		$tmp = '<div class="raw2">';
        foreach ($collections as $collection) {
            $tmp .= $collection->receiptid.' - '.Yii::app()->format->formatNumber($collection->amount).'<br/>';
        }
        return $tmp.'</div>';
    }
  
	public static function getORDates($collections) {
        $tmp = '<div class="raw2">';
        foreach ($collections as $collection) {
            $tmp = $tmp.$collection->receipt->receiptDate.'  ';
           
        }
        $tmp = $tmp.'</div>';
        return $tmp;
    }
    
	public static function getBalance($total, $collection) 
	{
        $balance = $total - $collection;
        return $balance;
    }
    
	public function getBalance2(){
		return $this->total - $this->collection;
	}
    
    public function getTestTotal($keys)
	{
        $analysis = Analysis::model()->findAllByPk($keys);
        $requestTotal=0;
        foreach($analysis as $fee)
                $requestTotal+=$fee->fee;
        //$less = $this->getDiscount($requestTotal, $discount);        
        
        return $requestTotal;
	} 
	public function getTotalFees($keys){
		$requestTotal = $this->getTestTotal($keys);
		$onsitecharge = $this->inplant_charge;
		$additional = $this->additional;
		$total = $requestTotal+$onsitecharge+$additional;

		return $total;
	}
	public function getDiscount($keys, $discount)
	{
		$requestTotal = $this->getTestTotal($keys);
		
		if($this->disc)
			return $requestTotal * ($this->disc->rate/100);
		else 
			return 0;
	}
	public function getDiscountFee($keys, $discount)
	{
		$requestTotal = $this->getTotalFees($keys);
		
		if($this->disc->id != 8){
			$discounted = $requestTotal * ($this->disc->rate/100);
			if($discounted != 0){
				$discounted ='-'.$discounted;
			}
		}
		else{
			$discounted = '-'.$this->discounted;
		}
		return $discounted;
	}
	public function getDiscountedFee($keys, $discount)
	{
		$requestTotal = $this->getTotalFees($keys);
		if($this->disc->id != 8){
			$discounted = $requestTotal * ($this->disc->rate/100);
		}
		else{
			$discounted = $this->discounted;
		}
		return $requestTotal - $discounted;
	}

	public function getSubTotal($keys, $discount, $vat)
	{
		$requestTotal = $this->getTotalFees($keys);
		if($this->disc->id != 8){
			$discounted = $requestTotal * ($this->disc->rate/100);
		}
		else{
			$discounted = $this->discounted;
		}
		$total = $requestTotal - $discounted;

		if($vat == 1){
			// $subTotal = bcmul(0.12, 36000);
			$subTotal = $total * 0.12;
		}else{
			$subTotal = 0;
		}
		return $subTotal;
		// return number_format($subTotal,2);
	}

	public function getRequestTotal($keys, $discount, $vat)
	{
		$requestTotal = $this->getTotalFees($keys);
		if($this->disc->id != 8){
			$discounted = $requestTotal * ($this->disc->rate/100);
		}
		else{
			$discounted = $this->discounted;
		}
		$total = $requestTotal - $discounted;

		if($vat == 1){
			$subTotal = $total * 0.12;
		}else{
			$subTotal = 0;
		}
		
		return $total + $subTotal;
	}

	public function getInplantCharge(){
		return $this->inplant_charge;
	}

	public function getAdditional(){
		return $this->additional;
	}
	public function getRemarks(){
		return $this->remarks;
	}
	public function beforeSave(){
	   if(parent::beforeSave())
	   {
			if($this->isNewRecord){
				if(!$this->import){
					$this->rstl_id = Yii::app()->Controller->getRstlId();
					$this->requestRefNum = Request::generateRequestRef($this->labId);
					$this->requestDate = date('Y-m-d',strtotime($this->requestDate));
					$this->reportDue = date('Y-m-d',strtotime($this->reportDue));
					$this->cancelled = 0;
					if($this->remarks == ''){
						$this->remarks = 'None';
					}
				}
				return true;
			}else{
				$this->requestDate = date('Y-m-d',strtotime($this->requestDate));
				$this->reportDue = date('Y-m-d',strtotime($this->reportDue));
				return true;
			}
	   }
	   return false;
	}
	
	protected function afterSave(){
		parent::afterSave();
		if($this->isNewRecord){
			if(!$this->import){
				$requestCode = new Requestcode;
				 
				$requestCode->requestRefNum = $this->requestRefNum;
				$requestCode->rstl_id = Yii::app()->getModule('user')->user()->profile->getAttribute('pstc');
				$requestCode->labId = $this->labId;
				$codeArray = explode('-',$this->requestRefNum);
				
				/** Old Code: 012014-M-0001-R9 **/
				//$requestCode->number = $codeArray[2];
				
				 /** New Code: R9-092014-CHE-0343 **/
				$requestCode->number = $codeArray[3];
				
				$requestCode->year = date('Y', strtotime($this->requestDate));
				$requestCode->cancelled = 0;
				$requestCode->save();
			}
		}else{
			$this->updateRequestTotal($this->id);
		}
	}
	
	function generateRequestRef($lab){
		$date = date('Y-m', strtotime($this->requestDate));
		$year = date('Y', strtotime($this->requestDate));
		$month = date('m',strtotime($this->requestDate));
		$request = Request::model()->find(array(
   			'select'=>'requestRefNum, rstl_id, labId, requestDate', 
			'order'=>'create_time DESC, id DESC',
    		'condition'=>'rstl_id = :rstl_id AND labId = :labId AND YEAR(requestDate) = :year',
    		'params'=>array(':rstl_id' => Yii::app()->Controller->getRstlId(), ':labId' => $lab, ':year' => $year )
		));
		
		if(isset($request)){
			$requestCode = explode('-', $request->requestRefNum);
			$number = Request::addZeros($requestCode[2] + 1);
		}else{
			$initializeCode = Initializecode::model()->find(array(
	   			'select'=>'*',
	    		'condition'=>'rstl_id = :rstl_id AND lab_id = :lab_id AND codeType = :codeType AND active = 1',
	    		'params'=>array(':rstl_id' => Yii::app()->Controller->getRstlId(), ':lab_id' => $lab, ':codeType' => 1 )
			));
			if(isset($initializeCode))
				$number = Request::addZeros($initializeCode->startCode + 1);
			else
				$number = Request::addZeros(1);
		}
		
		$labCode = Lab::model()->findByPk($lab);
		// $rstl = Rstl::model()->findByPk(Yii::app()->Controller->getRstlId());
		// $requestRefNo = $rstl->code.'-'.$labCode->labCode.'-'.$date.'-'.$number;
		$rstl = 'HME';
		// $requestRefNo = $rstl.$year.'-'.$labCode->labCode.'-'.$month.'-'.$number;
		$requestRefNo = $rstl.$year.'-'.$labCode->labCode.'-'.$number;

		return $requestRefNo;
	}
	
	function addZeros($count){
		if($count < 10)
			return '000'.$count;
		elseif ($count < 100)
			return '00'.$count;
		elseif ($count < 1000)
			return '0'.$count;
		elseif ($count >= 1000)
			return $count;
	}
	
	public function updateRequestTotal($id){
		$total = 0;
		$request = Request::model()->findByPk($id);
		foreach($request->anals as $analysis)
		{
			$total = $total + $analysis->fee;
		}
			$total = $total + $request->inplant_charge + $request->additional;
		
		if($request->disc)
			$total = $total - ($total * $request->disc->rate/100);
		else 	
			$total = $total;
			
		Request::model()->updateByPk($request->id, 
			array('total'=>$total,
			));
	}
	
	public function getChemSamples()
	{
		
		$customerId = $this->customerId;
		$year = date('Y', strtotime($this->requestDate));
		$sample_count = 0;
		
		$minDate = date("Y-m-d", mktime(0, 0, 0, 1, 1, $year));
		$maxDate = date("Y-m-d", mktime(0, 0, 0, 12, 31, $year));
		
		$requests = Request::model()
				->findAll(array(
					'condition' => 'labId = :labId AND customerId = :customerId AND requestDate >= :minDate AND requestDate <= :maxDate AND t.cancelled = :cancelled',
				    'params' => array(':labId' => 1, ':customerId'=> $customerId, ':minDate'=> $minDate, ':maxDate'=> $maxDate, ':cancelled' => 0),
				));
		
		foreach($requests as $request)
		{
			$sample_count += $request->sampleCount;
		}
		return $sample_count;
	}
	
	public function getMicroSamples()
	{
		$customerId = $this->customerId;
		$year = date('Y', strtotime($this->requestDate));
		$sample_count = 0;
		
		$minDate = date("Y-m-d", mktime(0, 0, 0, 1, 1, $year));
		$maxDate = date("Y-m-d", mktime(0, 0, 0, 12, 31, $year));
		
		$requests = Request::model()
				->findAll(array(
					'condition' => 'labId = :labId AND customerId = :customerId AND requestDate >= :minDate AND requestDate <= :maxDate AND t.cancelled = :cancelled',
				    'params' => array(':labId' => 2, ':customerId'=> $customerId, ':minDate'=> $minDate, ':maxDate'=> $maxDate, ':cancelled' => 0),
				));
		
		foreach($requests as $request)
		{
			$sample_count += $request->sampleCount;
		}
		return $sample_count;
	}
	
	public function getChemTests()
	{
		$customerId = $this->customerId;
		$year = date('Y', strtotime($this->requestDate));
		$test_count = 0;
		
		$minDate = date("Y-m-d", mktime(0, 0, 0, 1, 1, $year));
		$maxDate = date("Y-m-d", mktime(0, 0, 0, 12, 31, $year));
		
		$requests = Request::model()
				->findAll(array(
					'condition' => 'labId = :labId AND customerId = :customerId AND requestDate >= :minDate AND requestDate <= :maxDate AND t.cancelled = :cancelled',
				    'params' => array(':labId' => 1, ':customerId'=> $customerId, ':minDate'=> $minDate, ':maxDate'=> $maxDate, ':cancelled' => 0),
				));
		
		foreach($requests as $request)
		{
			foreach($request->samps as $sample)
			{
				$test_count += $sample->analysisCount;
			}
			
		}
		return $test_count;
	}
	
	public function getMicroTests()
	{
		$customerId = $this->customerId;
		$year = date('Y', strtotime($this->requestDate));
		$test_count = 0;
		
		$minDate = date("Y-m-d", mktime(0, 0, 0, 1, 1, $year));
		$maxDate = date("Y-m-d", mktime(0, 0, 0, 12, 31, $year));
		
		$requests = Request::model()
				->findAll(array(
					'condition' => 'labId = :labId AND customerId = :customerId AND requestDate >= :minDate AND requestDate <= :maxDate AND t.cancelled = :cancelled',
				    'params' => array(':labId' => 2, ':customerId'=> $customerId, ':minDate'=> $minDate, ':maxDate'=> $maxDate, ':cancelled' => 0),
				));
		
		foreach($requests as $request)
		{
			foreach($request->samps as $sample)
			{
				$test_count += $sample->analysisCount;
			}
			
		}
		return $test_count;
	}
	
	public function getCustomerRequests()
	{
		
		$customerId = $this->customerId;
		$year = date('Y', strtotime($this->requestDate));
		
		$reqs = '<br/><div class="requestCodes'.$this->customerId.'">';
		
		$minDate = date("Y-m-d", mktime(0, 0, 0, 1, 1, $year));
		$maxDate = date("Y-m-d", mktime(0, 0, 0, 12, 31, $year));
		
		$requests = Request::model()
				->findAll(array(
					'condition' => 'customerId = :customerId AND requestDate >= :minDate AND requestDate <= :maxDate AND t.cancelled = :cancelled',
				    'params' => array(':customerId'=> $customerId, ':minDate'=> $minDate, ':maxDate'=> $maxDate, ':cancelled' => 0),
				));
		
		
		foreach($requests as $request)
		{
				$reqs .= CHtml::link($request->requestRefNum, Yii::app()->createUrl('lab/request/view', array('id'=>$request->id)), array('target'=>'_blank')).' ';
		}
		
		$reqs .= '</div>';
		return $reqs;
	}
	
 	public function getColor() {
        
        $statuscolor='active';
        switch ($this->cancelled) {
            case 1:
                $statuscolor='cancelled';
                break;
        }
        return $statuscolor;
        
    }
    
    public function getPaymentDetails(){
    	$request = Request::model()->findByPk($this->id);
    	
    	$balance = $this->total - $this->collection;
    	
	    switch ($balance) {
		    case 0 :
		        return "Paid";
		        break;
		    case $this->total :
		        return "unPaid";
		        break;
		}
    	//return Yii::app()->format->formatNumber($balance);
    }
    
    public function checkInitializeCode($lab)
    {
    	$code = Initializecode::model()->find(array(
    		'condition'=>'rstl_id=:rstl_id AND lab_id=:labId AND codeType = 1 AND active = 1',
    		'params'=>array(':rstl_id' => Yii::app()->user->rstlId, ':labId' => $lab)
		));	
		
		if(isset($code)){
			$initializeCode = array(
				'initialize' => true,
				'code'	=> $code->startCode
			);
		}else{
			$initializeCode = array(
				'initialize' => false,
				'code'	=> $code->startCode
			);
		}
		
		return $initializeCode;
    }
    
	public function getStatus()
	{
		$reportDue=strtotime($this->reportDue);
		$now=time();
		
		$datediff=$reportDue-$now;
		/*if($reportDue > $now)
			return true;*/
		if($this->cancelled)
			return array('id'=>0, 'label'=>'Cancelled', 'class'=>'alert alert-danger');
		
		if($reportDue > $now){
			if(floor($datediff/(60*60*24)) <=2) //3 days before report due
				return array('id'=>3, 'label'=>'Report Nearly Due', 'class'=>'alert gray alert-warning');
				
			return array('id'=>2, 'label'=>'Ongoing', 'class'=>'alert gray alert-info');
		}
		return array('id'=>1, 'label'=>'Completed', 'class'=>'alert gray alert-success');
	}
    
	public function getPaymentStatus()
	{
		$receipts=$this->receipts;
		$balance=$this->getBalance($this->total, $this->collection);
		
		if($receipts){
			if($balance>0)
				return array('id'=>2, 'label'=>'Paid-Partial', 'class'=>'payment alert-warning');
				
			return array('id'=>1, 'label'=>'Paid-Full', 'class'=>'payment alert-success');
		}
		
		return array('id'=>0, 'label'=>'Unpaid', 'class'=>'payment alert-danger');
	}
    
  	public function getReportStatus()
	{
		if(count($this->testreports) == 0)
			return array('id'=>0, 'label'=>'None', 'class'=>'payment alert-warning');
		else
            return array('id'=>1, 'label'=>'View', 'class'=>'payment alert-success');
	}

	public static function listDataUnpaid($model=NULL, $customer_id)
	{
		$criteria=new CDbCriteria;
		$criteria->select='id,requestRefNum,total,labId';
		$criteria->condition='rstl_id = :rstl_id AND t.cancelled=0 AND customerId = :customerId';
		$criteria->order='id DESC';
		$criteria->params=array(':rstl_id'=>Yii::app()->Controller->getRstlId(), ':customerId'=>$customer_id);
		$requests=Request::model()->findAll($criteria);
		if($requests){
			foreach ($requests as $request){
				$balance=$request->getBalance();
				if($balance!=0 OR ($model->request_id==$request->id)){ //$model->request_id==$request->id --> needed on update
					$list[] = array(
					'id'=>$request->id,
					'requestRefNum'=>$request->requestRefNum,
					'labId'=>$request->labId,
					'balance'=>$balance
					);
				}
	    	}
		}

		return CHtml::listData($list, 'requestRefNum', 'requestRefNum', 'labId');
	}
	
	public static function displaySamplesForImport($samples)
	{
		$html = '<table id="samples-for-import" class="">';
		
		foreach($samples as $sample){
			$html .= '<tr rowspan="'.count($samples).'"><td>'.$sample['sampleCode'].' - '.$sample['sampleName'].'<br/>'.$sample['description'].'</td>';
			$countAnalyses = 1;
			foreach($sample['analyses'] as $analysis)
			{
				if($countAnalyses > 1)
					$html .= '<tr><td></td><td>'.$analysis['testName'].' - '.$analysis['fee'].'</td>';
				else 
					$html .= '<td>'.$analysis['testName'].' - '.$analysis['fee'].'</td>';

				$html .= '</tr>';
				$countAnalyses += 1;
			}
			
			
			if(count($samples) > 1)
			{
				
			}
		}
		
		$html .= '</table>';
		return $html;
	}
	public function countCustomersByYearMonth($fromDate, $toDate, $requestDate, $custypeId, $labId, $rstlId)
	{	
		//initial load set date
		if(empty($fromDate) && empty($toDate))
		{
			//$labId = 1;
			$fromDate = date('Y-01-01'); //first day of the year
			$toDate = date('Y-m-d'); //as of today
			//$rstlID = Yii::app()->Controller->getRstlId();
			
		}
	
		$years = date("Y", strtotime($requestDate)); //get month only
		$months = date("m", strtotime($requestDate)); //get year only
		
		$criteria = new CDbCriteria;
		$criteria->condition = "t.requestDate <= :toDate AND t.requestDate >= :fromDate AND t.cancelled =:cancelled AND t.labId =:labId AND t.rstl_id = :rstlId AND DATE_FORMAT(t.requestDate,'%Y')=:years AND DATE_FORMAT(t.requestDate,'%m')=:months";
		$criteria->params=array(':fromDate'=>$fromDate,':toDate'=>$toDate,':cancelled'=>0, ':labId'=>$labId, ':rstlId'=>$rstlId,':years'=>$years,':months'=>$months);
		
		$countCustomers=$this->with(array(
		//$countCustomers=Request::model()->cache(1000, $dependency)->with(array(
			'customersA' => array(
					//'condition' => "customersA.typeId = '$custypeId'"
					'condition' => 'customersA.typeId =:custypeId AND customersA.rstl_id =:rstlId',
					'params' => array(':custypeId'=>$custypeId,':rstlId'=>$rstlId)
				)
			)
		)->count($criteria);
		
		return $countCustomers;
	}
	
	public $totalFee;
	//function for the sum of gross income per month by year
	public function sumGrossIncome($fromDate, $toDate, $requestDate, $labId, $rstlId)
	{
		//initial load set date
		if(empty($fromDate) && empty($toDate))
		{
			//$labId = 1;
			$fromDate = date('Y-01-01'); //first day of the year
			$toDate = date('Y-m-d'); //as of today
			//$rstlID = Yii::app()->Controller->getRstlId();
			
		}
		
		$years = date("Y", strtotime($requestDate)); //get month only
		$months = date("m", strtotime($requestDate)); //get year only
		
		$dependency = new CDbCacheDependency('SELECT MAX(id) FROM limslab.request'); //use to cache data
		
		$criteria = new CDbCriteria;
		$criteria->select = 'sum(analysisA.fee) AS totalFee'; //totalFee declared as virtual attribute
		$criteria->condition = "t.requestDate <= :toDate AND t.requestDate >= :fromDate AND DATE_FORMAT(t.requestDate,'%Y')=:years AND DATE_FORMAT(t.requestDate,'%m')=:months AND t.cancelled =:cancelled AND labId =:labId AND t.rstl_id =:rstlId";
		$criteria->params=array(':fromDate'=>$fromDate,':toDate'=>$toDate,':years'=>$years, ':months'=>$months, ':cancelled'=>0, ':labId'=>$labId, ':rstlId'=>$rstlId);		
		
		$subTotalFee=Request::model()->cache(1000, $dependency)->with(array(
		//$subTotalFee=$this->with(array(
				'samplesA'=>array(
					'condition'=>'samplesA.cancelled =:cancelled',
					'params'=>array(':cancelled'=>0),
					'with' => array('analysisA' => array(
							'condition' => "analysisA.cancelled =:cancelled AND analysisA.deleted =:deleted",
							'params'=> array(':cancelled'=>0, ':deleted'=>0)
						)
					)
				)
			)
		)->find($criteria);
		
		$totFee = $subTotalFee->totalFee;

		if($totFee == 0){
			return $totFee = '0.00';
		} else {
			return $totFee;
		}
	}
	
	public $totalIncomeGen;
	//function for the sum of total income generated per month by year
	public function incomeGenerated($fromDate, $toDate, $requestDate, $labId, $custypeId, $rstlId)
	{
		//initial load set date
		if(empty($fromDate) && empty($toDate))
		{
			//$labId = 1;
			$fromDate = date('Y-01-01'); //first day of the year
			$toDate = date('Y-m-d'); //as of today
			//$rstlID = Yii::app()->Controller->getRstlId();
			
		}
		
		$years = date("Y", strtotime($requestDate)); //get years only
		$months = date("m", strtotime($requestDate)); //get months only
		
		$criteria = new CDbCriteria;
		$criteria->select = 'sum(t.total) AS totalIncomeGen'; //totalIncomeGen declared as virtual attribute
		$criteria->condition = "t.requestDate <= :toDate AND t.requestDate >= :fromDate AND t.cancelled =:cancelled AND t.labId =:labId AND t.rstl_id = :rstlId AND DATE_FORMAT(t.requestDate,'%Y')=:years AND DATE_FORMAT(t.requestDate,'%m')=:months AND t.paymentType =:paymentType";
		$criteria->params=array(':fromDate'=>$fromDate,':toDate'=>$toDate,':cancelled'=>0, ':labId'=>$labId, ':rstlId'=>$rstlId,':years'=>$years,':months'=>$months,':paymentType'=>1);
		
		$incomeGen=$this->with(array(
		//$incomeGen=Request::model()->cache(1000, $dependency)->with(array(
				'customersA' => array(
					'condition' => 'customersA.typeId =:custypeId AND customersA.rstl_id =:rstlId',
					'params' => array(':custypeId'=>$custypeId,':rstlId'=>$rstlId)
				)
			))->find($criteria);
		
		$totIncomeGenerated = $incomeGen->totalIncomeGen;
		
		if($totIncomeGenerated == 0){
			return $totIncomeGenerated = '0.00';
		} else {
			return $totIncomeGenerated;
		}
	}

	//function for the subtotal of customers by year
	public function subTotalCustomers($fromDate, $toDate, $requestDate, $labId, $custypeId, $rstlId)
	{	
		$years = date("Y", strtotime($requestDate)); //get years only
		
		$criteria = new CDbCriteria;
		$criteria->condition = "t.requestDate <= :toDate AND t.requestDate >= :fromDate AND t.cancelled =:cancelled AND t.labId =:labId AND t.rstl_id = :rstlId AND DATE_FORMAT(t.requestDate,'%Y')=:years";
		$criteria->params=array(':fromDate'=>$fromDate,':toDate'=>$toDate,':cancelled'=>0, ':labId'=>$labId, ':rstlId'=>$rstlId,':years'=>$years);
		
		
		$countCustomers=$this->with(array(
			'customersA' => array(
					//'condition' => "customersA.typeId = '$custypeId'"
					'condition' => 'customersA.typeId =:custypeId AND customersA.rstl_id =:rstlId',
					'params' => array(':custypeId'=>$custypeId,':rstlId'=>$rstlId)
				)
			)
		)->count($criteria);
		
		return $countCustomers;
	}
	
	public $subTotIncomeGen;
	//function for the subtotal of income generated by year
	public function subTotalIncome($fromDate, $toDate, $requestDate,$custypeId, $labId, $rstlId){
		
		$years = date("Y", strtotime($requestDate)); //get years only
		
		$dependency = new CDbCacheDependency('SELECT MAX(id) FROM limslab.request'); //use to cache data
		
		$criteria = new CDbCriteria;
		$criteria->select = 'sum(t.total) AS subTotIncomeGen'; //subTotIncomeGen declared as virtual attribute
		$criteria->condition = "t.requestDate <= :toDate AND t.requestDate >= :fromDate AND t.cancelled =:cancelled AND t.labId =:labId AND t.rstl_id = :rstlId AND DATE_FORMAT(t.requestDate,'%Y')=:years AND t.paymentType =:paymentType";
		$criteria->params=array(':fromDate'=>$fromDate,':toDate'=>$toDate,':cancelled'=>0, ':labId'=>$labId, ':rstlId'=>$rstlId,':years'=>$years,':paymentType'=>1);
		
		$incomeGen=Request::model()->cache(1000, $dependency)->with(array(
				'customersA' => array(
					'condition' => 'customersA.typeId =:custypeId AND customersA.rstl_id =:rstlId',
					'params' => array(':custypeId'=>$custypeId,':rstlId'=>$rstlId)
				)
			))->find($criteria);
		
		$subTotIncomeGenerated = $incomeGen->subTotIncomeGen;
		
		if($subTotIncomeGenerated == 0){
			return $subTotIncomeGenerated = '0.00';
		} else {
			return $subTotIncomeGenerated;
		}
	}
	
	public $subTotalFee;
	//function for the subtotal of gross income by year
	public function subTotalGrossIncome($fromDate, $toDate, $requestDate, $labId, $rstlId)
	{	
		$years = date("Y", strtotime($requestDate)); //get years only
		
		$dependency = new CDbCacheDependency('SELECT MAX(id) FROM limslab.request'); //use to cache data
		
		$criteria = new CDbCriteria;
		$criteria->select = 'sum(analysisA.fee) AS subTotalFee'; //subTotalFee declared as virtual attribute
		$criteria->condition = "t.requestDate <= :toDate AND t.requestDate >= :fromDate AND DATE_FORMAT(t.requestDate,'%Y')=:years AND t.cancelled =:cancelled AND labId =:labId AND t.rstl_id =:rstlId";
		$criteria->params=array(':fromDate'=>$fromDate,':toDate'=>$toDate,':years'=>$years,':cancelled'=>0, ':labId'=>$labId, ':rstlId'=>$rstlId);		
		
		$subTotalFees=Request::model()->cache(1000, $dependency)->with(array(
		//$subTotalFee=$this->with(array(
				'samplesA'=>array(
					'condition'=>'samplesA.cancelled =:cancelled',
					'params'=>array(':cancelled'=>0),
					'with' => array('analysisA' => array(
							'condition' => "analysisA.cancelled =:cancelled AND analysisA.deleted =:deleted",
							'params'=> array(':cancelled'=>0, ':deleted'=>0)
						)
					)
				)
			)
		)->find($criteria);
		
		$subTotFee = $subTotalFees->subTotalFee;

		if($subTotFee == 0){
			return $subTotFee = '0.00';
		} else {
			//return Yii::app()->format->number($subTotFee);
			return $subTotFee;
		}
	}
	
	public $totalFees;
	//function for the total of gross income
	public function grandTotalGrossIncome($fromDate, $toDate, $labId, $rstlId)
	{
		
		if(empty($labId) && empty($fromDate) && empty($toDate))
		{
			$labId = 1;
			$fromDate = date('Y-01-01'); //first day of the year
			$toDate = date('Y-m-d'); //as of today
			$rstlId = Yii::app()->Controller->getRstlId();
		}
		
		$dependency = new CDbCacheDependency('SELECT MAX(id) FROM limslab.request');

		$criteria = new CDbCriteria;
		$criteria->select = 'sum(analysisA.fee) AS totalFees';
		$criteria->condition = "t.requestDate <= :toDate AND t.requestDate >= :fromDate AND t.cancelled =:cancelled AND labId =:labId AND t.rstl_id =:rstlId";
		$criteria->params=array(':fromDate'=>$fromDate,':toDate'=>$toDate,':cancelled'=>0, ':labId'=>$labId, ':rstlId'=>$rstlId);		
		
		$subTotalFees=Request::model()->cache(1000, $dependency)->with(array(
		//$subTotalFee=$this->with(array(
				'samplesA'=>array(
					'condition'=>'samplesA.cancelled =:cancelled',
					'params'=>array(':cancelled'=>0),
					'with' => array('analysisA' => array(
							'condition' => "analysisA.cancelled =:cancelled AND analysisA.deleted =:deleted",
							'params'=> array(':cancelled'=>0, ':deleted'=>0)
						)
					)
				)
			)
		)->find($criteria);
		
		$TotFee = $subTotalFees->totalFees;

		if($TotFee == 0){
			return '';
		} else {
			//return Yii::app()->format->number($TotFee);
			return $TotFee;
		}
	}
	
	public function grandTotalCustomers($fromDate, $toDate, $labId, $rstlId)
	{	
		if(empty($labId) && empty($fromDate) && empty($toDate))
		{
			$labId = 1;
			$fromDate = date('Y-01-01'); //first day of the year
			$toDate = date('Y-m-d'); //as of today
			$rstlId = Yii::app()->Controller->getRstlId();
		}
	
		$dependency = new CDbCacheDependency('SELECT MAX(id) FROM limslab.request');
		
		$criteria = new CDbCriteria;
		$criteria->condition = "t.requestDate <= :toDate AND t.requestDate >= :fromDate AND t.cancelled =:cancelled AND t.labId =:labId AND t.rstl_id = :rstlId";
		$criteria->params=array(':fromDate'=>$fromDate,':toDate'=>$toDate,':cancelled'=>0, ':labId'=>$labId, ':rstlId'=>$rstlId);
		
		
		//$countCustomers=$this->with(array(
		$countCustomers=Request::model()->cache(1000, $dependency)->with(array(
			'customersA' => array(
					//'condition' => "customersA.typeId = '$custypeId'"
					'condition' => 'customersA.rstl_id =:rstlId',
					'params' => array(':rstlId'=>$rstlId)
				)
			)
		)->count($criteria);
		
		if($countCustomers == 0){
			return "";
		} else {
			//return number_format($countCustomers);
			return $countCustomers;
		}
		
		//return 1;
	}
	
	public $totalGrossIn;
	//public $totalDiscounts;
	//public $totalGratis;
	
	public function getTotalIncomeCollections($fromDate, $toDate, $labId, $rstlId)
	{
		if(empty($labId) && empty($fromDate) && empty($toDate))
		{
			$labId = 1;
			$fromDate = date('Y-01-01'); //first day of the year
			$toDate = date('Y-m-d'); //as of today
			$rstlId = Yii::app()->Controller->getRstlId();
		}
		
		$requestGross = new CDbCacheDependency('SELECT MAX(id) FROM limslab.request');
	
		$criteria1 = new CDbCriteria;
		$criteria1->select = 'sum(analysisA.fee) AS totalGrossIn';
		$criteria1->condition = "t.requestDate <= :toDate AND t.requestDate >= :fromDate AND t.cancelled =:cancelled AND labId =:labId AND t.rstl_id =:rstlId";
		$criteria1->params=array(':fromDate'=>$fromDate,':toDate'=>$toDate,':cancelled'=>0, ':labId'=>$labId, ':rstlId'=>$rstlId);		
		
		$subTotalFee=Request::model()->cache(1000, $requestGross)->with(array(
		//$subTotalFee=Request::model()->with(array(
				'samplesA'=>array(
					'condition'=>'samplesA.cancelled =:cancelled',
					'params'=>array(':cancelled'=>0),
					'with' => array('analysisA' => array(
							'condition' => "analysisA.cancelled =:cancelled AND analysisA.deleted =:deleted",
							'params'=> array(':cancelled'=>0, ':deleted'=>0)
						)
					)
				)
			)
		)->find($criteria1);
		
		$totGross = $subTotalFee->totalGrossIn;

		$analysisGratis = new CDbCacheDependency('SELECT MAX(id) FROM limslab.analysis');
	
		$criteria2 = new CDbCriteria;
		$criteria2->select = 'sum(t.fee) AS grandTotalAssisted';
		$criteria2->condition = "t.cancelled =:cancelled AND t.deleted=:deleted AND t.package <> :package";
		$criteria2->params = array(':cancelled'=>0,':deleted'=>0,':package'=>2);
		
		//$subGratis=Analysis::model()->with(
		$subGratis=Analysis::model()->cache(1000, $analysisGratis)->with(
			array(
				'sample' => array(
					'condition'=>'sample.cancelled =:cancelled',
					'params'=>array(':cancelled'=>0),
					'with' => array(
						'requestE' => array(
							'condition'=>"requestE.rstl_id =:rstlId AND requestE.requestDate <= :toDate AND requestE.requestDate >= :fromDate AND labId =:labId AND requestE.cancelled =:cancelled AND requestE.paymentType =:paymentType",
							'params'=>array(':rstlId'=>$rstlId,':fromDate'=>$fromDate,':toDate'=>$toDate,':labId'=>$labId,':cancelled'=>0,':paymentType'=>2),
							'with'=>array('customersA' => array(
								'condition' => "customersA.rstl_id =:rstlId",
								'params'=>array(':rstlId'=>$rstlId)
								)
							)
						)
					)
				)
			)
		)->find($criteria2);
		
		$totGratis = $subGratis->grandTotalAssisted;
		
		$analysisDiscount = new CDbCacheDependency('SELECT MAX(id) FROM limslab.analysis');
		
		$criteria3 = new CDbCriteria;
		$criteria3->select = 'sum(t.fee * (discountA.rate/100)) AS grandTotalDiscount';
		$criteria3->condition = "t.cancelled =:cancelled AND t.deleted=:deleted AND t.package <> :package";
		$criteria3->params = array(':cancelled'=>0,':deleted'=>0,':package'=>2);
		
		//$subDiscount=Analysis::model()->with(
		$subDiscount=Analysis::model()->cache(1000, $analysisDiscount)->with(
			array(
				'sample' => array(
					'condition'=>'sample.cancelled =:cancelled',
					'params'=>array(':cancelled'=>0),
					'with' => array(
						'requestE' => array(
							'condition'=>"requestE.rstl_id =:rstlId AND requestE.requestDate <= :toDate AND requestE.requestDate >= :fromDate AND labId =:labId AND requestE.cancelled =:cancelled",
							'params'=>array(':rstlId'=>$rstlId,':fromDate'=>$fromDate,':toDate'=>$toDate,':labId'=>$labId,':cancelled'=>0),
							'with' => 'discountA'
						)
					)
				)
			)
		)->find($criteria3);
		
		$totDiscount = $subDiscount->grandTotalDiscount;
		
		$totalIncome = $totGross - ($totDiscount + $totGratis);
		
		if($totalIncome == 0){
			return '';
		} else {
			return $totalIncome;
		}
	}
	
	public function subTotalIncomeCollections($fromDate, $toDate, $requestDate, $labId, $rstlId)
	{
		//Request::model()->subTotalIncomeCollections(Yii::app()->Controller->subTotalFromDate(),Yii::app()->Controller->subTotalToDate(),$data["requestDate"],'.$labId.',Yii::app()->Controller->getRstlId())
		$subTotIncome = Request::model()->subTotalIncome($fromDate,$toDate,$requestDate,1,$labId,$rstlId);
		$subTotAssistedN = Analysis::model()->subTotalAssisted($fromDate,$toDate,$requestDate,2,$labId,$rstlId);
		$subTotAssistedS = Analysis::model()->subTotalAssisted($fromDate,$toDate,$requestDate,1,$labId,$rstlId);
		$subTotDisc = Analysis::model()->subTotalDiscount($fromDate,$toDate,$requestDate,$labId,$rstlId);
		
		$subTotGross = Request::model()->subTotalGrossIncome($fromDate,$toDate,$requestDate,$labId,$rstlId);
		
		
		$totalGenerated = ($subTotGross - ($subTotIncome + $subTotAssistedN + $subTotAssistedS + $subTotDisc));
		
		return $totalGenerated;
	}
	
	public function getIncomeCollected($fromDate, $toDate, $requestDate, $labId, $rstlId)
	{
		
		$gross = Request::model()->sumGrossIncome($fromDate,$toDate,$requestDate, $labId, $rstlId);
		$gratisNsetup = Analysis::model()->sumValueAssist($fromDate,$toDate,$requestDate,$custype = 2,$labId,$rstlId);
		$gratisSetup = Analysis::model()->sumValueAssist($fromDate,$toDate,$requestDate,$custype = 1,$labId,$rstlId);
		$discount = Analysis::model()->countDiscount($fromDate,$toDate,$requestDate, $labId, $rstlId);
		$incomesetup = Request::model()->incomeGenerated($fromDate,$toDate,$requestDate, $labId, $custype = 1, $rstlId);
		
		
		$income_gen = ($gross - ($gratisNsetup + $gratisSetup + $discount + $incomesetup));
		
		return $income_gen;
		
	}
    
    public function dailySampleCount($requestDate, $labId)
    {
        $criteria = new CDbCriteria;
            
        $criteria->condition = 'requestDate = :requestDate AND labId = :labId AND t.cancelled = :cancelled';
        $criteria->params = array(':cancelled'=>0, 'labId'=>$labId, ':requestDate'=>$requestDate);
        $requests = Request::model()->findAll($criteria);
        
        $dailySamples = 0;
        foreach($requests as $request){
            $dailySamples += $request->sampleCount;
        }
        
        return $dailySamples;
    }
    
    public function dailyAnalysisCount($requestDate, $labId)
    {
        $criteria = new CDbCriteria;
            
        $criteria->condition = 'requestDate = :requestDate AND labId = :labId AND t.cancelled = :cancelled';
        $criteria->params = array(':cancelled'=>0, 'labId'=>$labId, ':requestDate'=>$requestDate);
        $requests = Request::model()->findAll($criteria);
        
        $dailyAnalysis = 0;
        foreach($requests as $request){
            foreach($request->samps as $samples)
                $dailyAnalysis += $samples->analysisCount;
        }
        
        return $dailyAnalysis;
    }

    public function perAnalysisCount($requestRefNum){
    	$criteria = new CDbCriteria;
            
        $criteria->condition = 'requestId = :requestRefNum AND sampleCode <> :sampleCode AND cancelled = :cancelled AND deleted = :deleted';
        $criteria->params = array(':cancelled'=>0, ':deleted'=>0, ':sampleCode'=>'', ':requestRefNum'=>$requestRefNum);
        $analyses = Analysis::model()->findAll($criteria);
        
        $dailyAnalysis = 0;
        foreach($analyses as $analysis){
                $dailyAnalysis += $analysis->test->count;
        }
        
        return $dailyAnalysis;
    }

    public function perSampleCount($requestRefNum){
    	 $criteria = new CDbCriteria;
            
        $criteria->condition = 'requestId = :requestRefNum AND sampleCode <> :sampleCode AND cancelled = :cancelled';
        $criteria->params = array(':cancelled'=>0, ':sampleCode'=>'', ':requestRefNum'=>$requestRefNum);
        $samples = Sample::model()->findAll($criteria);
        
        $dailyAnalysis = 0;
        foreach($samples as $sample){
                $dailyAnalysis += 1;
        }
        
        return $dailyAnalysis;
    }
    
    public function perDiscounted($fee,$discount,$requestRefNum){
    	 
       	if($discount != 0){
       		$criteria = new CDbCriteria;
       		$criteria->condition = 'id = :discountId';
        	$criteria->params = array(':discountId' => $discount);
	       	$discountRate = Discount::model()->find($criteria);
	       	
	       	if(isset($discountRate)){
	       		$remaining = 100 - $discountRate->rate;
		        $discount_rate = $remaining / 100;
		        $orig = $fee / $discount_rate;
		        $discounted = $orig-$fee;
	       	}
	       	if($discount == 9){
       			$criteria = new CDbCriteria;
		        $criteria->condition = 'requestId = :requestRefNum AND sampleCode <> :sampleCode AND cancelled = :cancelled AND deleted = :deleted';
		        $criteria->params = array(':cancelled'=>0, ':deleted'=>0, ':sampleCode'=>'', ':requestRefNum'=>$requestRefNum);
		        $analyses = Analysis::model()->findAll($criteria);
		        
		        $dailyAnalysis = 0;
		        foreach($analyses as $analysis){
		                $dailyAnalysis = $dailyAnalysis+$analysis->fee;
		        }
		        $discounted = $dailyAnalysis;
       		}
       	}else{
       		$discounted = "0";
       	}
        return $discounted;
    }
}
