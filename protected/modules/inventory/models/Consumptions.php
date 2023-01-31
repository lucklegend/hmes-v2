<?php

/**
 * This is the model class for table "consumptions".
 *
 * The followings are the available columns in table 'consumptions':
 * @property integer $id
 * @property integer $stockID
 * @property double $balance
 * @property double $amountused
 * @property string $dateconsumed
 * @property integer $withdrawnby
 * @property string $remarks
 */
class Consumptions extends CActiveRecord
{
	public $qty;
	public $user_id;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'consumptions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stockID, balance, amountused, dateconsumed, withdrawnby, remarks', 'required'),
			array('withdrawnby', 'numerical', 'integerOnly'=>true),
			array('balance, amountused', 'numerical'),
			array('remarks', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, stockID, balance, amountused, dateconsumed, withdrawnby, remarks', 'safe', 'on'=>'search'),
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
			// 'stocks'=>array(self::BELONGS_TO,'Stocks','stockID'),
			'stocks'=>array(self::BELONGS_TO, 'Stocks', '', 'on'=>'stockID = stockCode'),
			'user'=>array(self::BELONGS_TO, 'EquipmentUser', 'withdrawnby'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'stockID' => 'Stock',
			'balance' => 'Stock-on-Hand',
			'amountused' => 'Quantity used',
			'dateconsumed' => 'Date used',
			'withdrawnby' => 'Withdrawnby',
			'remarks' => 'Remarks',
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
		$criteria->with = array( 'user' );
		$criteria->compare('user.username', $this->user_id, true );
		$criteria->compare('id',$this->id);
		$criteria->compare('stockID',$this->stockID);
		$criteria->compare('balance',$this->balance);
		$criteria->compare('amountused',$this->amountused);
		$criteria->compare('dateconsumed',$this->dateconsumed,true);
		$criteria->compare('withdrawnby',$this->withdrawnby);
		$criteria->compare('remarks',$this->remarks,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->inventoryDb;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Consumptions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
