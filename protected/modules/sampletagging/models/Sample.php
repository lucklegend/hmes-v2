<?php
/*******************************************************
		name: Janeedi A. Grapa
		org: DOST-IX, 991-1024
		date created: April 12, 2017
	
********************************************************/
?>

<?php
/**
 * This is the model class for table "sample".
 *
 * The followings are the available columns in table 'sample':
 * @property integer $id
 * @property string $sampleCode
 * @property string $sampleName
 * @property string $description
 * @property string $remarks
 * @property string $requestId
 * @property integer $request_id
 * @property integer $sampleMonth
 * @property integer $sampleYear
 * @property integer $cancelled
 */
class Sample extends CActiveRecord
{
	public $lab_search;
	public $analysis_search;
	public $requestRefNum, $requestDate, $reportDue, $parameters, $dateAnalyzed;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sample';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('sampleCode, sampleName, description, remarks, requestId, request_id, sampleMonth, sampleYear, cancelled', 'required'),
			array('sampleName, description', 'required'),
			array('request_id, sampleMonth, sampleYear, cancelled', 'numerical', 'integerOnly'=>true),
			array('sampleCode', 'length', 'max'=>20),
			array('sampleName, requestId', 'length', 'max'=>50),
			array('remarks', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sampleCode, sampleName, samplingDate, description, remarks, requestId, request_id, sampleMonth, sampleYear, cancelled, analysis_search, lab_search',  'safe', 'on'=>'search'),
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
			'request'	=> array(self::BELONGS_TO, 'Request', 'request_id'),
			'requestsA'	=> array(self::BELONGS_TO, 'Request', 'request_id', 'condition'=>'t.cancelled=0'),
			//'analyses'	=> array(self::HAS_MANY, 'Analysis', 'sample_id', 'condition' => 'sampleCode=sampleCode AND cancelled=0 AND deleted=0'),
			//'analysisCount' => array(self::STAT, 'Analysis', 'sample_id', 'condition' => 'sampleCode=sampleCode AND cancelled=0 AND deleted=0'),
			//'analysisTotal' => array(self::STAT, 'Analysis', 'sample_id', 'condition' => 'sampleCode=sampleCode AND cancelled=0 AND deleted=0', 'select'=> 'SUM(fee)'),
			'analysesForGeneration'	=> array(self::HAS_MANY, 'Analysis', 'sample_id', 'condition' => 'cancelled=0 AND deleted=0'),
			'analyses'	=> array(self::HAS_MANY, 'Analysis', 'sample_id', 'condition' => 'cancelled=0 AND deleted=0 AND package!=2'),
			'analysisCount' => array(self::STAT, 'Analysis', 'sample_id', 'condition' => 'cancelled=0 AND deleted=0 AND package!=2'),
			'analysisTotal' => array(self::STAT, 'Analysis', 'sample_id', 'condition' => 'cancelled=0 AND deleted=0'),			
			/*******/
			'analysisA'	=> array(self::HAS_MANY, 'Analysis', 'sample_id'),
			'requestE'	=> array(self::BELONGS_TO, 'Request', 'request_id'),
			/******/
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sampleCode' => 'Sample Code',
			'sampleName' => 'Sample Name',
			'samplingDate' => 'Sampling Date',
            'description' => 'Description',
			'remarks' => 'Remarks',
			'requestId' => 'Request',
			'request_id' => 'Request',
			'sampleMonth' => 'Sample Month',
			'sampleYear' => 'Sample Year',
			'cancelled' => 'Cancelled',
			'analyses' => 'Analyses',
			'saveAsTemplate' => 'saveAsTemplate',
			'requestRefNum' => 'Request Reference Number / Referral Code',
			'requestDate' => 'Date Received',
			'reportDue' => 'Due Date',
			'parameters' => 'Parameters'
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
	// public function search()
	// {
	// 	// @todo Please modify the following code to remove attributes that should not be searched.

	// 	$criteria=new CDbCriteria;
		
	// 	$criteria->with = array('request', 'analysis_search');
	// 	$criteria->order = 't.id DESC';
	// 	$criteria->compare('t.id',$this->id);
	// 	$criteria->compare('request.labId', $this->lab_search, true);
	// 	$criteria->compare('analyses.sample_id', $this->analysis_search, true);
	// 	$criteria->compare('analyses',$this->analyses,true);
	// 	$criteria->compare('sampleCode',$this->sampleCode,true);
	// 	$criteria->compare('sampleName',$this->sampleName,true);
    //     $criteria->compare('samplingDate',$this->samplingDate,true);
	// 	$criteria->compare('description',$this->description,true);
	// 	$criteria->compare('remarks',$this->remarks,true);
	// 	$criteria->compare('requestId',$this->requestId,true);
	// 	$criteria->compare('request_id',$this->request_id);
	// 	$criteria->compare('sampleMonth',$this->sampleMonth);
	// 	$criteria->compare('sampleYear',$this->sampleYear);
	// 	$criteria->compare('cancelled',$this->cancelled);

	// 	return new CActiveDataProvider($this, array(
	// 		'criteria'=>$criteria,
	// 	));
	// }

	// public function search()
	// {
	// 	$criteria=new CDbCriteria;
		
	// 	$criteria->with = array('request');
	// 	$criteria->order = 't.id DESC';
	// 	$criteria->compare('t.id',$this->id);
	// 	$criteria->compare('request.labId', $this->lab_search, true);
	// 	$criteria->compare('analyses',$this->analyses,true);
	// 	$criteria->compare('sampleCode',$this->sampleCode,true);
	// 	$criteria->compare('sampleName',$this->sampleName,true);
    //     $criteria->compare('samplingDate',$this->samplingDate,true);
	// 	$criteria->compare('description',$this->description,true);
	// 	$criteria->compare('remarks',$this->remarks,true);
	// 	$criteria->compare('requestId',$this->requestId,true);
	// 	$criteria->compare('request_id',$this->request_id);
	// 	$criteria->compare('sampleMonth',$this->sampleMonth);
	// 	$criteria->compare('sampleYear',$this->sampleYear);
	// 	$criteria->compare('cancelled',$this->cancelled);
	// 			return new CActiveDataProvider($this, array(
	// 		'criteria'=>$criteria,
	// 	));

	// }
	public function search($lab)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;
		$criteria->with = array('request');
		$criteria->order = 't.id DESC';
		$criteria->condition='sampleCode LIKE "'.$lab.'-%"';
		$criteria->compare('t.id',$this->id);
		$criteria->compare('request.labId', $this->lab_search, true);
		$criteria->compare('analyses',$this->analyses,true);
		$criteria->compare('sampleCode',$this->sampleCode,true);
		$criteria->compare('sampleName',$this->sampleName,true);
        $criteria->compare('samplingDate',$this->samplingDate,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('remarks',$this->remarks,true);
		$criteria->compare('requestId',$this->requestId,true);
		$criteria->compare('request_id',$this->request_id);
		$criteria->compare('sampleMonth',$this->sampleMonth);
		$criteria->compare('sampleYear',$this->sampleYear);
		$criteria->compare('cancelled',$this->cancelled);

			return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function searchByRequest($id)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$sort = new CSort();
		$sort->attributes = array(
			'sampleCode'=>array(
			  'asc'=>'sampleCode',
			  'desc'=>'sampleCode desc',
			),			
			'*' //to make all other columns sortable
		);
		$sort->defaultOrder='sample.sampleCode ASC';
		
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('request_id',$id);
		$criteria->compare('analyses',$this->analyses,true);
		$criteria->compare('sample.sampleCode',$this->sampleCode,true);
		$criteria->compare('sample.sampleName',$this->sampleName,true);
        $criteria->compare('sample.samplingDate',$this->samplingDate,true);
		$criteria->compare('sample.description',$this->description,true);
		$criteria->compare('sample.remarks',$this->remarks,true);
		$criteria->compare('sample.requestId',$this->requestId,true);
		$criteria->compare('sample.request_id',$this->request_id);
		$criteria->compare('sample.sampleMonth',$this->sampleMonth);
		$criteria->compare('sample.sampleYear',$this->sampleYear);
		$criteria->compare('sample.cancelled',$this->cancelled);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
			'pagination'=>false,
		));
	}
	
	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->ulimsDb;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sample the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getAnalyses($analyses) 
	{
		        $tmp = '<div class="raw2">';
        foreach ($analyses as $analysis) {
            $tmp = $tmp.$analysis->testName.'';
        }
        $tmp = $tmp.'</div>';
        return $tmp;
    }
    
	public function beforeSave(){
	   if(parent::beforeSave())
	   {
			if($this->isNewRecord){
                $this->samplingDate = date('Y-m-d',  strtotime($_POST['Sample']['samplingDate']));
				$this->cancelled = 0;
		        return true;
			}else{
                $this->samplingDate = date('Y-m-d',  strtotime($_POST['Sample']['samplingDate']));
				return true;
			}
	   }
	   return false;
	}
	
	public function getStatus() {
        
        $statuscolor='white';
        switch ($this->cancelled) {
            case 0:
                $statuscolor='green';
                break;
            case 1:
                $statuscolor='redish';
                break;
        }
        return $statuscolor;
        
    }
    
    //function for counting samples per month by year
	public function countSamplesByYearMonth($fromDate, $toDate, $requestDate, $labId, $custypeId, $rstlID)
	{
		//initial load set date
		if(empty($fromDate) && empty($toDate))
		{
			//$labId = 1;
			$fromDate = date('Y-01-01'); //first day of the year
			$toDate = date('Y-m-d'); //as of today
			//$rstlID = Yii::app()->Controller->getRstlId();
		}
		
		$year = date("Y", strtotime($requestDate)); //get year only
		$months = date("m", strtotime($requestDate)); //get months only
		
		//convert months into no zeros (eg. 01,02-09) to match data in database
		$month = Yii::app()->Controller->convertDateToSingleDigit($months);
		
		//$dependency = new CDbCacheDependency('SELECT MAX(id) FROM ulimslab.sample');
		$criteria = new CDbCriteria;
		$criteria->condition = "t.cancelled =:cancelled AND t.sampleMonth =:month AND t.sampleYear =:year";
		$criteria->params = array(':cancelled'=>0,':month'=>$month,':year'=>$year);		
					
		$countSamples=$this->with(array(
		//$countSamples=Sample::model()->cache(1000, $dependency)->with(array(
			'requestE' => array(
					'condition'=>"requestE.rstl_id =:rstlID AND requestE.requestDate <= :toDate AND requestE.requestDate >= :fromDate AND labId =:labId AND requestE.cancelled =:cancelled",
					'params'=>array(':rstlID'=>$rstlID,':fromDate'=>$fromDate,':toDate'=>$toDate,':labId'=>$labId,':cancelled'=>0),
					'group'=>"DATE_FORMAT(requestE.requestDate,'%m'), DATE_FORMAT(requestE.requestDate,'%Y')",
					'with' => array('customersA' => array(
						'condition' => "customersA.typeId =:custypeId",
						'params'=>array(':custypeId'=>$custypeId)
						)
					)
				)
			)
		)->count($criteria);
		
		return $countSamples;
	}
	
	//function for the subtotal of samples by year
	public function subTotalSamples($fromDate, $toDate, $requestDate, $labId, $custypeId, $rstlID)
	{
		
		$year = date("Y", strtotime($requestDate)); //get year only
	
		$criteria = new CDbCriteria;
		$criteria->condition = "t.cancelled =:cancelled AND t.sampleYear =:year";
		$criteria->params = array(':cancelled'=>0,':year'=>$year);			
					
		$countSamples=$this->with(array(
		//$countSamples=Sample::model()->cache(1000, $dependency)->with(array(
			'requestE' => array(
					'condition'=>"requestE.rstl_id =:rstlID AND requestE.requestDate <= :toDate AND requestE.requestDate >= :fromDate AND labId =:labId AND requestE.cancelled =:cancelled",
					'params'=>array(':rstlID'=>$rstlID,':fromDate'=>$fromDate,':toDate'=>$toDate,':labId'=>$labId,':cancelled'=>0),
					'group'=>"DATE_FORMAT(requestE.requestDate,'%Y')",
					'with' => array('customersA' => array(
						'condition' => "customersA.typeId =:custypeId AND customersA.rstl_id =:rstlID",
						'params'=>array(':custypeId'=>$custypeId,':rstlID'=>$rstlID)
						)
					)
				)
			)
		)->count($criteria);
		
		//return number_format($countSamples);
		return $countSamples;
	}
	
	//function for the total of samples
	public function grandTotalSamples($fromDate, $toDate, $labId, $rstlId)
	{
		//initial load set date and lab id
		if(empty($labId) && empty($fromDate) && empty($toDate))
		{
			$labId = 1;
			$fromDate = date('Y-01-01'); //first day of the year
			$toDate = date('Y-m-d'); //as of today
			$rstlId = Yii::app()->Controller->getRstlId();
		}
		
		//$dependency = new CDbCacheDependency('SELECT MAX(id) FROM ulimslab.sample'); //use to cache data
		
		$criteria = new CDbCriteria;
		$criteria->condition = "t.cancelled =:cancelled";
		$criteria->params = array(':cancelled'=>0);
		
		$countSamples=$this->with(array(
		//$countSamples=Sample::model()->cache(1000, $dependency)->with(array(
			'requestE' => array(
					'condition'=>"requestE.rstl_id =:rstlId AND requestE.requestDate <= :toDate AND requestE.requestDate >= :fromDate AND labId =:labId AND requestE.cancelled =:cancelled",
					'params'=>array(':rstlId'=>$rstlId,':fromDate'=>$fromDate,':toDate'=>$toDate,':labId'=>$labId,':cancelled'=>0),
					'with' => array('customersA' => array(
						'condition' => "customersA.rstl_id =:rstlId",
						'params'=>array(':rstlId'=>$rstlId)
						)
					)
				)
			)
		)->count($criteria);
		
		if($countSamples == 0){
			return "";
		} else {
			//return number_format($countSamples);
			return $countSamples;
		}
	}


	public function getQRcode(){
		return $this->id." ".$this->sampleYear." ".$this->sampleCode;
	}

}
