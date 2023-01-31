<?php

/**
 * This is the model class for table "equipmentusage".
 *
 * The followings are the available columns in table 'equipmentusage':
 * @property integer $ID
 * @property integer $user_id
 * @property string $equipmentID
 * @property integer $status
 * @property string $startdate
 * @property string $enddate
 * @property string $remarks
 */
class Equipmentusage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'equipmentusage';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('equipmentID, startdate, enddate', 'required'),
			array('user_id, status', 'numerical', 'integerOnly'=>true),
			array('equipmentID', 'length', 'max'=>50),
			array('remarks', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ID, user_id, equipmentID, status, startdate, enddate, remarks', 'safe', 'on'=>'search'),
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
			'ID' => 'ID',
			'user_id' => 'User',
			'equipmentID' => 'Equipment',
			'status' => 'Status',
			'startdate' => 'Startdate',
			'enddate' => 'Enddate',
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

		$criteria->compare('ID',$this->ID);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('equipmentID',$this->equipmentID,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('startdate',$this->startdate,true);
		$criteria->compare('enddate',$this->enddate,true);
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
		return Yii::app()->equipmentDb;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Equipmentusage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
