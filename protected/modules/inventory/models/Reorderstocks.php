<?php

/**
 * This is the model class for table "reorderstocks".
 *
 * The followings are the available columns in table 'reorderstocks':
 * @property integer $id
 * @property integer $supplyID
 * @property string $reorderdate
 * @property string $daterequested
 * @property string $datereceived
 * @property integer $supplierID
 * @property string $remarks
 */
class Reorderstocks extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'reorderstocks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('supplyID, reorderdate, daterequested, datereceived, supplierID, remarks', 'required'),
			array('supplyID, supplierID', 'numerical', 'integerOnly'=>true),
			array('remarks', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, supplyID, reorderdate, daterequested, datereceived, supplierID, remarks', 'safe', 'on'=>'search'),
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
			'supplyID' => 'Supply',
			'reorderdate' => 'Reorderdate',
			'daterequested' => 'Daterequested',
			'datereceived' => 'Datereceived',
			'supplierID' => 'Supplier',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('supplyID',$this->supplyID);
		$criteria->compare('reorderdate',$this->reorderdate,true);
		$criteria->compare('daterequested',$this->daterequested,true);
		$criteria->compare('datereceived',$this->datereceived,true);
		$criteria->compare('supplierID',$this->supplierID);
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
	 * @return Reorderstocks the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
