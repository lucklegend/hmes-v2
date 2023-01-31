<?php
/*******************************************************
		name: Janeedi A. Grapa
		org: DOST-IX, 991-1024
		date created: April 12, 2017
	
********************************************************/
?>
<?php
/**
 * This is the model class for table "analysis".
 *
 * The followings are the available columns in table 'analysis':
 * @property integer $id
 * @property integer $rstl_id
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
 * @property integer $package
 * @property integer $cancelled
 * @property integer $deleted
 * @property integer $taggingId
 * @property string $user_id
 */
class Analysis extends CActiveRecord
{
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
			array('rstl_id, requestId, sample_id, sampleCode, testName, method, references, quantity, fee, testId, analysisMonth, analysisYear, package, cancelled, deleted, taggingId, user_id', 'required'),
			array('rstl_id, sample_id, quantity, testId, analysisMonth, analysisYear, package, cancelled, deleted, taggingId', 'numerical', 'integerOnly'=>true),
			array('fee', 'numerical'),
			array('requestId, user_id', 'length', 'max'=>50),
			array('sampleCode', 'length', 'max'=>20),
			array('testName', 'length', 'max'=>200),
			array('method', 'length', 'max'=>150),
			array('references', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rstl_id, requestId, sample_id, sampleCode, testName, method, references, quantity, fee, testId, analysisMonth, analysisYear, package, cancelled, deleted, taggingId, user_id', 'safe', 'on'=>'search'),
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
			'tags' => array(self::BELONGS_TO, 'Tagging', 'taggingId'),
			'profiles' => array(self::BELONGS_TO, 'Profiles', 'user_id'),
			'CancelledBy' => array(self::BELONGS_TO, 'Profiles', 'cancelledBy'),
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'rstl_id' => 'Rstl',
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
			'package' => 'Package',
			'cancelled' => 'Cancelled',
			'deleted' => 'Deleted',
			'taggingId' => 'Tagging',
			'user_id' => 'User',
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
		$criteria->compare('rstl_id',$this->rstl_id);
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
		$criteria->compare('package',$this->package);
		$criteria->compare('cancelled',$this->cancelled);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('taggingId',$this->taggingId);
		$criteria->compare('user_id',$this->user_id,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
	 * @return Analysis the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
