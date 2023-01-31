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
    public $request_search;
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
			array('request_id, lab_id, status, reissue', 'numerical', 'integerOnly'=>true),
			array('reportNum', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, request_id, lab_id, reportNum, reportDate, status, releaseDate, reissue, request_search', 'safe', 'on'=>'search'),
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
            'samples'	=> array(self::HAS_MANY, 'TestreportSample', 'testreport_id'),
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
            'reissue' => 'Reissue'
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

        $criteria->with = array('request');
        $criteria->order = 't.reportDate DESC, t.id DESC';
		$criteria->compare('id',$this->id);
		//$criteria->compare('request_id',$this->request_id);
        $criteria->compare('request.requestRefNum', $this->request_search, true );
		//$criteria->compare('lab_id',$this->lab_id);
        
        /* Modified to restrict the viewing of Request per laboratory ID */
        switch(Yii::app()->getModule('user')->user()->profile->getAttribute('labId'))
        {
            case 0:     
                    $criteria->compare('lab_id', $this->lab_id);
                    break;
            
            default:
                    $criteria->compare('lab_id', Yii::app()->getModule('user')->user()->profile->getAttribute('labId'));
        }
		$criteria->compare('reportNum',$this->reportNum,true);
		$criteria->compare('reportDate',$this->reportDate,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('releaseDate',$this->releaseDate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
		        'attributes'=>array(
		            'request_search'=>array(
		                'asc'=>'request.requestRefNum',
		                'desc'=>'request.requestRefNum DESC',
		            ),
		            '*',
		        ),
		    ),
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
    
    public function beforeSave(){
	   if(parent::beforeSave())
	   {
			if($this->isNewRecord){
                $this->lab_id = Yii::app()->getModule('user')->user()->profile->getAttribute('labId');
                $this->reportDate = date('Y-m-d', strtotime($this->reportDate));
                $this->reportNum = $this->generaterReportNum($this->reportDate, $this->lab_id);
                $this->updateCounter($this->lab_id);
                return true;
			}else{
				$this->reportDate = date('Y-m-d', strtotime($this->reportDate));
				return true;
			}
	   }
	   return false;
	}
    
    public function afterFind(){
        if($this->reissue)
            $this->reportNum = $this->reportNum.'-R';
        return true;
	}
    
    function generaterReportNum($reportDate, $lab_id)
    {
        $date = date('mdY', strtotime($reportDate));
        $count = $this->getReportCount();
        $number = $this->addZeros($count[$lab_id]['count'] + 1);
        $reportNum = $date.'-'.Lab::model()->findByPk($lab_id)->labCode.'-'.$number;
        return $reportNum;
    }
    
    function getReportCount()
    {
        $counter = dirname(__FILE__).'/../../../config/testreport-counter.ini';
        $counter_array = parse_ini_file($counter, true);
        return $counter_array;
    }
    
    function addZeros($count){
		if($count < 10)
			return '000'.$count;
		elseif ($count < 100)
			return '00'.$count;
		elseif ($count < 1000)
			return '0'.$count;
		elseif ($count >= 1000)
			return $count;
	}
    
    function updateCounter($lab_id)
    {
        $counterPath = dirname(__FILE__).'/../../../config/testreport-counter.ini';
        $counter_array = parse_ini_file($counterPath, true);
        
        $count = $counter_array[$lab_id]['count'] + 1;
        $counter_array[$lab_id]['count'] = $count;
        
		$this->write_ini_file($counter_array, $counterPath, TRUE);
    }
    
    //function used to save settings in an ini file
	public function write_ini_file($assoc_arr, $path, $has_sections=FALSE) {
		$content = "";
		if ($has_sections) {
			foreach ($assoc_arr as $key=>$elem) {
				$content .= "[".$key."]\n";
				foreach ($elem as $key2=>$elem2) {
					if(is_array($elem2))
					{
						for($i=0;$i<count($elem2);$i++)
						{
							$content .= $key2."[] = \"".$elem2[$i]."\"\n";
						}
					}
					else if($elem2=="") $content .= $key2." = \n";
					else $content .= $key2." = \"".$elem2."\"\n";
				}
			}
		}
		else {
			foreach ($assoc_arr as $key=>$elem) {
				if(is_array($elem))
				{
					for($i=0;$i<count($elem);$i++)
					{
						$content .= $key2."[] = \"".$elem[$i]."\"\n";
					}
				}
				else if($elem=="") $content .= $key2." = \n";
				else $content .= $key2." = \"".$elem."\"\n";
			}
		}
	 
		if (!$handle = fopen($path, 'w')) {
			return false;
		}
		if (!fwrite($handle, $content)) {
			return false;
		}
		fclose($handle);
		return true;
	}
    
    public function getReleaseStatus()
	{
		if(!$this->status)
			return array('id'=>0, 'label'=>'Not Released', 'class'=>'alert alert-warning');
        
        if($this->status)
			return array('id'=>1, 'label'=>'Release', 'class'=>'alert alert-success');
	}
}
