<?php

/**
 * This is the model class for table "testreport".
 *
 * The followings are the available columns in table 'testreport':
 * @property integer $id
 * @property integer $request_id
 * @property integer $lab_id
 * @property string $reportNum
 * @property string $reportDate
 * @property integer $status
 * @property string $releaseDate
 */
class Testreport extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'testreport';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('request_id', 'required'),
			array('request_id, lab_id, status', 'numerical', 'integerOnly'=>true),
			array('reportNum', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, request_id, lab_id, reportNum, reportDate, status, releaseDate', 'safe', 'on'=>'search'),
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
            'request'	=> array(self::BELONGS_TO, 'Request', 'request_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'request_id' => 'Request',
			'lab_id' => 'Lab',
			'reportNum' => 'Report Num',
			'reportDate' => 'Report Date',
			'status' => 'Status',
			'releaseDate' => 'Release Date',
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
		$criteria->compare('request_id',$this->request_id);
		$criteria->compare('lab_id',$this->lab_id);
		$criteria->compare('reportNum',$this->reportNum,true);
		$criteria->compare('reportDate',$this->reportDate,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('releaseDate',$this->releaseDate,true);

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
	 * @return Testreport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /*public function beforeSave(){
	   if(parent::beforeSave())
	   {
			if($this->isNewRecord){
                $this->reportNum = 'asdhlaksjdkhl';
                //$this->reportNum = Testreport::generaterReportNum($this->lab_id);
                //$this->reportDate = date('Y-m-d', strtotime($_POST['Request']['requestDate']));
                return true;
			}else{
				//$this->reportDate = date('Y-m-d',strtotime($this->reportDate));
				return true;
			}
	   }
	   return false;
	}*/
    
    static function generaterReportNum($lab_id)
    {
        $date = date('mY', strtotime($this->reportDate));
        $count = Testreport::getReportCount();
		$number = Testreport::addZeros($count[$lab_id]);
        
        $reportNum = $date.'-'.Lab::model()->findByPk($lab_id)->labCode.'-'.$number;
        return $reportNum;
    }
    
    static function getReportCount()
    {
        $counter = dirname(__FILE__).'/../../../config/testreport-counter.ini';
        $counter_array = parse_ini_file($counter, true);
        return $counter_array;
    }
    
    static function addZeros($count){
		if($count < 10)
			return '000'.$count;
		elseif ($count < 100)
			return '00'.$count;
		elseif ($count < 1000)
			return '0'.$count;
		elseif ($count >= 1000)
			return $count;
	}
}
