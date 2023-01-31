<?php

/**
 * This is the model class for table "sample".
 *
 * The followings are the available columns in table 'sample':
 * @property integer $id
 * @property integer $rstl_id
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
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ulimslab.sample';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rstl_id, sampleCode, sampleName, description, remarks, requestId, request_id, sampleMonth, sampleYear, cancelled', 'required'),
			array('rstl_id, request_id, sampleMonth, sampleYear, cancelled', 'numerical', 'integerOnly'=>true),
			array('sampleCode', 'length', 'max'=>20),
			array('sampleName, requestId', 'length', 'max'=>50),
			array('remarks', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rstl_id, sampleCode, sampleName, description, remarks, requestId, request_id, sampleMonth, sampleYear, cancelled', 'safe', 'on'=>'search'),
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
			'sampleCode' => 'Sample Code',
			'sampleName' => 'Sample Name',
			'description' => 'Description',
			'remarks' => 'Remarks',
			'requestId' => 'Request',
			'request_id' => 'Request',
			'sampleMonth' => 'Sample Month',
			'sampleYear' => 'Sample Year',
			'cancelled' => 'Cancelled',
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
		$criteria->compare('sampleCode',$this->sampleCode,true);
		$criteria->compare('sampleName',$this->sampleName,true);
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
}
