<?php

/**
 * This is the model class for table "analysis".
 *
 * The followings are the available columns in table 'analysis':
 * @property integer $id
 * @property string $requestId
 * @property integer $sample_id
 * @property string $sampleCode
 * @property string $testName
 * @property string $method
 * @property string $references
 * @property integer $quantity
 * @property double $fee
 * @property integer $testId
 * @property integer $analysisMonth
 * @property integer $analysisYear
 * @property integer $cancelled
 * @property integer $deleted
 */
class MonthlyTest extends CActiveRecord
{
	public $countTest, $total, $discountRate, $requestRefNum;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'analysis';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('requestId, sample_id, sampleCode, testName, method, references, quantity, fee, testId, analysisMonth, analysisYear, cancelled, deleted', 'required'),
			//array('requestId, sample_id, quantity, fee, testId', 'required'),
			array('requestId, sample_id, quantity, fee', 'required'),
			array('sample_id, quantity, testId, analysisMonth, analysisYear, cancelled, deleted', 'numerical', 'integerOnly'=>true),
			array('fee', 'numerical'),
			array('requestId', 'length', 'max'=>50),
			array('sampleCode', 'length', 'max'=>20),
			array('testName', 'length', 'max'=>200),
			array('method', 'length', 'max'=>150),
			array('references', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, requestId, sample_id, sampleCode, testName, method, references, quantity, fee, testId, analysisMonth, analysisYear, cancelled, deleted, package, countTest, requestRefNum', 'safe', 'on'=>'search'),
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
			'sample'	=> array(self::BELONGS_TO, 'Sample', 'sample_id'),
			'test' 		=> array(self::BELONGS_TO, 'Test', 'testId'),
			'sampleCount' => array(self::STAT, 'Analysis', 'sample_id', 'select'=>'COUNT(sample_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'requestId' => 'Request',
			'sample_id' => 'Sample',
			'sampleCode' => 'Sample Code',
			'testName' => 'Test Name',
			'method' => 'Method',
			'references' => 'References',
			'quantity' => 'Quantity',
			'fee' => 'Fee',
			'testId' => 'Test',
			'analysisMonth' => 'Analysis Month',
			'analysisYear' => 'Analysis Year',
			'cancelled' => 'Cancelled',
			'deleted' => 'Deleted',
			
			'sampleName' => 'SAMPLE',
			'sampleType' => 'Sample Type',
			'testCategory' => 'Test Category',
			
			'rate' => 'Rate',
			'tests' => 'Tests',
			'package' => 'Package',
            'requestRefNum' => 'Request Reference'
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

		$criteria->compare('id',$this->id);
		
		$criteria->compare('requestId',$this->requestId,true);
		$criteria->compare('sample_id',$this->sample_id);
		$criteria->compare('sampleCode',$this->sampleCode,true);
		$criteria->compare('testName',$this->testName,true);
		$criteria->compare('method',$this->method,true);
		$criteria->compare('references',$this->references,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('fee',$this->fee);
		$criteria->compare('testId',$this->testId);
		$criteria->compare('analysisMonth',$this->analysisMonth);
		$criteria->compare('analysisYear',$this->analysisYear);
		$criteria->compare('cancelled',$this->cancelled);
		$criteria->compare('deleted',$this->deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
	 * @return Analysis the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
    function getPaidNonSetup()
    {
        $type = $this->checkCustomerType($this->sample->request->customer->typeId);
        
        if(($this->sample->request->paymentType == 1) AND ($type == 'non-setup'))
            return $this->sample->request->total;
        else
            return '';
    }
    
    function getPaidSetup()
    {
        $type = $this->checkCustomerType($this->sample->request->customer->typeId);
        
        if(($this->sample->request->paymentType == 1) AND ($type == 'setup'))
            return $this->sample->request->total;
        else
            return '';
    }
    
    function getGratisNonSetup()
    {
        if($this->sample->request->paymentType == 2){
            $type = $this->checkCustomerType($this->sample->request->customer->typeId);
        
            if($type == 'non-setup')
                return $this->sample->request->total;
        }
    }
    
    function getGratisSetup()
    {
        if($this->sample->request->paymentType == 2){
            $type = $this->checkCustomerType($this->sample->request->customer->typeId);
        
            if($type == 'setup')
                return $this->sample->request->total;
        }
    }
    
	function getDiscount()
    {
        if($this->sample->request->disc->id != 0)
            return $this->requestTotal - (($this->sample->request->disc->rate/100) * $this->requestTotal);
        else
            return '0.00';
    }
	
    function getRequestTotal()
    {
        $requestTotal = 0;
        foreach($this->sample->request->samps as $sample)
            foreach($sample->analyses as $analysis){
                switch($analysis->package){
                    case 0:    
                        $requestTotal = $requestTotal + $analysis->fee;
                        break;
                    case 1:
                        $requestTotal = $requestTotal + $analysis->fee;
                        break;
                }
                
            }
        
        return $requestTotal;
    }
    
    function getTotalFeesCollected()
    {        
        return $this->requestTotal - $this->discount;
    }
    
    private function checkCustomerType($type) //Setup or Non-Setup
    {
        switch($type){
            case    1:
                return  'setup';
                
            case    2:
                return  'non-setup';
                
            default :
                return  'non-setup';
        }
    }
    
    function getNonsetup()
    {
        return ($this->sample->request->customer->typeId == 2) ? '1' : '0';
    }
    
    function getSetup()
    {
        return ($this->sample->request->customer->typeId == 1) ? '1' : '0';
    }
    function countSampletotal($year, $testId){
    	$criteria = new CDbCriteria;
            
        $criteria->condition = 't.testId = :testId AND t.cancelled = :cancelled AND t.analysisYear = :analysisYear';
        $criteria->params = array(':testId'=>$testId, ':cancelled'=>0, ':analysisYear'=>$year);
        $analysis = MonthlyTest::model()->findAll($criteria);
        $countSamples = count($analysis);
        
        return $countSamples;
    }
    function countMonthlysamples($month, $year, $testId){
    	$criteria = new CDbCriteria;
         
        $criteria->condition = 't.testId = :testId AND t.cancelled = :cancelled AND t.analysisMonth = :analysisMonth AND t.analysisYear = :analysisYear';
        $criteria->params = array(':testId'=>$testId, ':cancelled'=>0, ':analysisMonth'=>$month, ':analysisYear'=>$year);
        $analysis = MonthlyTest::model()->findAll($criteria);
        $countSamples = count($analysis);
        
        return $countSamples;
    }		
}
