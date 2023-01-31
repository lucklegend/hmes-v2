<?php

/**
 * This is the model class for table "barangay".
 *
 * The followings are the available columns in table 'barangay':
 * @property integer $id
 * @property integer $municipalityCityId
 * @property integer $district
 * @property string $name
 */
class Sampletag extends CFormModel
{
	public $id, $sample_id, $sampleName, $dueDate, $parameters;
	

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('municipalityCityId, name', 'required'),
			//array('municipalityCityId, district', 'numerical', 'integerOnly'=>true),
			//array('name', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sample_id, sampleName, dueDate', 'safe', 'on'=>'search'),
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
			//'id' => 'ID',
			'sample_id' => 'Sample ID',
			'sampleCode' => 'Sample Code',
			'sampleName' => 'Sample Name',
			'dueDate' => 'Due Date',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Barangay the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function view($sample_id)
	{
		//return CHtml::listData(Barangay::model()->findAll(), 'id', 'name');
		$tag = array();
		$sample = Sample::model()->findByPk($sample_id);
		
		$tag = array(
				'sample_id' => $sample->id,
				'sampleCode' => $sample->sampleCode,
				'sampleName' => $sample->sampleName,
				'dueDate' => $sample->request->reportDue,
                'request_id' => $sample->request->id,
			);
		return $tag;
	}	
	
	
}
