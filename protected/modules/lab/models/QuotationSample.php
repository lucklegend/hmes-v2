<?php

/**
 * This is the model class for table "quotation_sample".
 *
 * The followings are the available columns in table 'quotation_sample':
 * @property integer $id
 * @property integer $quotation_id
 * @property string $sampleName
 * @property integer $qty
 * @property string $date_created
 */
class QuotationSample extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'quotation_sample';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('quotation_id, sampleName', 'required'),
			array('quotation_id, qty', 'numerical', 'integerOnly'=>true),
			array('sampleName', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, quotation_id, sampleName, qty, date_created', 'safe', 'on'=>'search'),
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
			'tests'	=> array(self::HAS_MANY, 'QuotationTest', 'sample_id'),
			'testCount'=>array(self::STAT, 'QuotationTest', 'sample_id', 'select'=> 'count(id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'quotation_id' => 'Quotation',
			'sampleName' => 'Sample Name',
			'qty' => 'Qty',
			'date_created' => 'Date Created',
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
		$criteria->compare('sampleName',$this->sampleName,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('date_created',$this->date_created,true);

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
	 * @return QuotationSample the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
