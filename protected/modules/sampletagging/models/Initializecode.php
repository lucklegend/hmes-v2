<?php

/**
 * This is the model class for table "initializecode".
 *
 * The followings are the available columns in table 'initializecode':
 * @property integer $id
 * @property integer $rstl_id
 * @property integer $lab_id
 * @property integer $codeType
 * @property integer $startCode
 * @property integer $active
 */
class Initializecode extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'initializecode';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rstl_id, lab_id, codeType, startCode', 'required'),
			array('rstl_id, lab_id, codeType, startCode, active', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rstl_id, lab_id, codeType, startCode, active', 'safe', 'on'=>'search'),
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
			'rstl'	=> array(self::BELONGS_TO, 'Rstl', 'rstl_id'),
			'lab'	=> array(self::BELONGS_TO, 'Lab', 'lab_id'),
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
			'lab_id' => 'Lab',
			'codeType' => 'Code Type',
			'startCodeRequest' => 'Request Start Code',
			'startCodeSample' => 'Sample Start Code',
			'active' => 'Active',
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
		
		$criteria->order = 'codeType ASC';
		$criteria->compare('id',$this->id);
		$criteria->compare('rstl_id',Yii::app()->Controller->getRstlId());
		$criteria->compare('lab_id',$this->lab_id);
		$criteria->compare('codeType',$this->codeType);
		$criteria->compare('startCode',$this->startCode);
		$criteria->compare('active',$this->active);

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
	 * @return Initializecode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function listCodeType()
	{
		$codeType = array();
		$request = array("index" => 1 , "code" => "Request Code");
		$sample = array("index" => 2 , "code" => "Sample Code");
		
		array_push($codeType, $request);
		array_push($codeType, $sample);
		
		return CHtml::listData($codeType, 'index', 'code');	
	}
	
	function getCodeType($codeType)
	{
		switch ($codeType) {
			case 1:
				return 'Request Code';
				break;
				
			case 2:
				return 'Sample Code';
				break;
				
			default:
				break;
		}
	}
	
	public static function listLabName()
	{
		return CHtml::listData(Initializecode::model()->with(array(
				'lab'=>array(
					'condition'=>'status = :status',
					'order'=>'t.id ASC',
					'params'=>array(':status'=>1))
			))->findAll(array('group'=>'lab_id')
		),	'lab.id', 'lab.labName');
	}

	public static function listLab()
	{
		return Initializecode::model()->with(array(
				'lab'=>array(
					'condition'=>'status = :status',
					'order'=>'t.id ASC',
					'params'=>array(':status'=>1))
			))->findAll(array('group'=>'lab_id')
		);
	}
}
