<?php

/**
 * This is the model class for table "equipmentcalibration".
 *
 * The followings are the available columns in table 'equipmentcalibration':
 * @property integer $ID
 * @property integer $user_id
 * @property string $equipmentID
 * @property string $date
 * @property integer $isdone
 * @property string $certificate
 */
class Equipmentcalibration extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'equipmentcalibration';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('equipmentID, date', 'required'),
			array('user_id, isdone', 'numerical', 'integerOnly'=>true),
			array('equipmentID', 'length', 'max'=>200),
			//array('certificate', 'safe'),
			array('certificate', 'file','types'=>'pdf', 'allowEmpty'=>true, 'on'=>'create,update'), // this will allow empty field when page is update (remember here i create scenario update)
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ID, user_id, equipmentID, date, isdone, certificate', 'safe', 'on'=>'search'),
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
			'date' => 'Date',
			'isdone' => 'Status',
			'certificate' => 'Certificate',
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('isdone',$this->isdone);
		$criteria->compare('certificate',$this->certificate,true);

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
	 * @return Equipmentcalibration the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getColor() {
        
        $statuscolor='white';
        switch (strtolower($this->isdone)){
            case "0":
            	//check the date if before or after the isdone value
            	if(date('Y-m-d') > $this->date)
                	$statuscolor='orange';
                else
                	$statuscolor='yellow';
                break;
            case "1":
                $statuscolor='green';
                break;
        }
        return $statuscolor;
        
    }

    public function getstatus() {
        
        switch (strtolower($this->isdone)){
            case "0":
            	//check the date if before or after the isdone value
            	return "Not Yet";
                break;
            case "1":
                return "Done";
                break;
        }
         
        
    }
}
