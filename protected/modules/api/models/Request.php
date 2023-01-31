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
 */
class Request extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ulimslab.request';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('requestRefNum, requestId, requestDate, requestTime, rstl_id, labId, customerId, paymentType, discount, orId, total, reportDue, conforme, receivedBy, cancelled, create_time', 'required'),
			array('rstl_id, labId, customerId, paymentType, discount, orId, cancelled', 'numerical', 'integerOnly'=>true),
			array('total', 'numerical'),
			array('requestRefNum, requestId, conforme, receivedBy', 'length', 'max'=>50),
			array('requestTime', 'length', 'max'=>25),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, requestRefNum, requestId, requestDate, requestTime, rstl_id, labId, customerId, paymentType, discount, orId, total, reportDue, conforme, receivedBy, cancelled, create_time', 'safe', 'on'=>'search'),
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
			'requestRefNum' => 'Request Ref Num',
			'requestId' => 'Request',
			'requestDate' => 'Request Date',
			'requestTime' => 'Request Time',
			'rstl_id' => 'Rstl',
			'labId' => 'Lab',
			'customerId' => 'Customer',
			'paymentType' => 'Payment Type',
			'discount' => 'Discount',
			'orId' => 'Or',
			'total' => 'Total',
			'reportDue' => 'Report Due',
			'conforme' => 'Conforme',
			'receivedBy' => 'Received By',
			'cancelled' => 'Cancelled',
			'create_time' => 'Create Time',
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
		$criteria->compare('requestRefNum',$this->requestRefNum,true);
		$criteria->compare('requestId',$this->requestId,true);
		$criteria->compare('requestDate',$this->requestDate,true);
		$criteria->compare('requestTime',$this->requestTime,true);
		$criteria->compare('rstl_id',$this->rstl_id);
		$criteria->compare('labId',$this->labId);
		$criteria->compare('customerId',$this->customerId);
		$criteria->compare('paymentType',$this->paymentType);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('orId',$this->orId);
		$criteria->compare('total',$this->total);
		$criteria->compare('reportDue',$this->reportDue,true);
		$criteria->compare('conforme',$this->conforme,true);
		$criteria->compare('receivedBy',$this->receivedBy,true);
		$criteria->compare('cancelled',$this->cancelled);
		$criteria->compare('create_time',$this->create_time,true);

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
	 * @return Request the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
