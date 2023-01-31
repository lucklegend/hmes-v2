<?php

/**
 * This is the model class for table "tagging".
 *
 * The followings are the available columns in table 'tagging':
 * @property integer $id
 * @property integer $analysisId
 * @property string $startDate
 * @property string $endDate
 * @property integer $status
 * @property string $user_id
 * @property integer $cancelled
 * @property string $cancelDate
 * @property string $reason
 * @property string $cancelledBy
 * @property string $disposedDate
 * @property integer $isoAccredited
 */
class Tagging extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tagging';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('analysisId, startDate, endDate, user_id, cancelled, cancelDate, reason, cancelledBy, disposedDate, isoAccredited', 'required'),
			array('analysisId, status, cancelled, isoAccredited', 'numerical', 'integerOnly'=>true),
			array('user_id, cancelledBy', 'length', 'max'=>20),
			array('reason', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, analysisId, startDate, endDate, status, user_id, cancelled, cancelDate, reason, cancelledBy, disposedDate, isoAccredited', 'safe', 'on'=>'search'),
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
            'profiles'=> array(self::BELONGS_TO, 'Profiles', 'user_id'),
            'tags'	=> array(self::BELONGS_TO, 'Analysis', 'analysisId'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'analysisId' => 'Analysis',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
			'status' => 'Status',
			'user_id' => 'User',
			'cancelled' => 'Cancelled',
			'cancelDate' => 'Cancel Date',
			'reason' => 'Reason',
			'cancelledBy' => 'Cancelled By',
			'disposedDate' => 'Disposed Date',
			'isoAccredited' => 'Iso Accredited',
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
		$criteria->compare('analysisId',$this->analysisId);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('cancelled',$this->cancelled);
		$criteria->compare('cancelDate',$this->cancelDate,true);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('cancelledBy',$this->cancelledBy,true);
		$criteria->compare('disposedDate',$this->disposedDate,true);
		$criteria->compare('isoAccredited',$this->isoAccredited);

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
	 * @return Tagging the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
