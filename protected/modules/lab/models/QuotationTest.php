<?php

/**
 * This is the model class for table "quotation_test".
 *
 * The followings are the available columns in table 'quotation_test':
 * @property integer $id
 * @property integer $quotation_id
 * @property integer $sample_id
 * @property string $testName
 * @property string $method
 * @property string $references
 * @property double $fee
 * @property string $created_at
 * @property integer $lab_id
 */
class QuotationTest extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'quotation_test';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('quotation_id, testName, method, references, fee, lab_id', 'required'),
			array('lab_id, sample_id', 'numerical', 'integerOnly'=>true),
			array('fee', 'numerical'),
			array('testName, method, references', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, quotation_id, testName, method, references, fee, created_at, lab_id, sample_id', 'safe', 'on'=>'search'),
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
			'quotation'	=> array(self::BELONGS_TO, 'Quotation', 'quotation_id'),
			'sample'	=> array(self::BELONGS_TO, 'QuotationSample', 'sample_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'quotation_id' => 'Quotation ID',
			'sample_id' => 'Sample',
			'testName' => 'Test Name',
			'method' => 'Method',
			'references' => 'References',
			'fee' => 'Fee',
			'created_at' => 'Created At',
			'lab_id' => 'Laboratory',
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
		$criteria->compare('quotation_id',$this->quotation_id);
		$criteria->compare('sample_id',$this->sample_id);
		$criteria->compare('testName',$this->testName,true);
		$criteria->compare('method',$this->method,true);
		$criteria->compare('references',$this->reference,true);
		$criteria->compare('fee',$this->fee);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('lab_id',$this->lab_id);

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
	 * @return QuotationTest the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function listData()
	{
		return CHtml::listData(QuotationTest::model()->findAll(), 'id', 'testName');
	}
	public static function listData2($id)
	{
		return CHtml::listData(Test::model()->findAll(array(
					'condition' => 'labId < :labId',
				    'params' => array(':labId' => $id),
					'order'=>'testName ASC',
					 
			)), 'id', 'categoryName');
	}

}
