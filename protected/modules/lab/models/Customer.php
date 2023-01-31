<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property integer $id
 * @property string $customerName
 * @property string $head
 * @property string $address
 * @property string $tel
 * @property string $fax
 * @property string $email
 * @property integer $typeId
 * @property integer $natureId
 * @property integer $industryId
 * @property string $created
 */
class Customer extends CActiveRecord
{		
	public $region_id;
	public $province_id;	

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customerName, head, tel, natureId, industryId, municipalitycity_id, province_id', 'required'),
			array('typeId, natureId, industryId, municipalitycity_id, district, barangay_id', 'numerical', 'integerOnly'=>true),
			array('customerName, address', 'length', 'max'=>200),
			array('head', 'length', 'max'=>100),
			array('tel, fax, email', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customerName, head, address, completeAddress, region_id, province_id, municipalitycity_id, district, barangay_id, tel, fax, email, typeId, natureId, industryId, created', 'safe', 'on'=>'search'),
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
			'requests' => array(self::HAS_MANY, 'Request', 'customerId'),
			//'region'	=> array(self::BELONGS_TO, 'Region', 'region_id'),
			'province'	=> array(self::BELONGS_TO, 'Province', 'province_id'),
			'municipality'	=> array(self::BELONGS_TO, 'MunicipalityCity', 'municipalitycity_id'),
			'barangay'	=> array(self::BELONGS_TO, 'Barangay', 'barangay_id'),
			'customertype' => array(self::BELONGS_TO, 'Customertype', 'typeId'),
			'naturebusiness' => array(self::BELONGS_TO, 'Businessnature', 'natureId'),
			'industry' => array(self::BELONGS_TO, 'Industry', 'industryId'),
			'wallet' => array(self::HAS_ONE, 'Wallet', 'customer_id'),
			'transactions' => array(self::HAS_MANY, 'Wallettransactions', array('id'=>'wallet_id'), 'through'=>'wallet', 'order'=>'date ASC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customerName' => 'Company / School',
			'head' => 'Addresee for Certificate',
			'region_id' => 'Region',
			'province_id' => 'Province',
			'municipalitycity_id' => 'Municipality / City',
			'district' => 'District',
			'barangay_id' => 'Barangay',
			'address' => 'House No. / Rm. No. / Bldg. Name / Street Name',
			'tel' => 'Tel',
			'fax' => 'Fax',
			'email' => 'Email',
			'typeId' => 'Customer Type',
			'natureId' => 'Nature of Business',
			'industryId' => 'Choose Sector',
			'completeAddress' => 'Complete Address',
			'created' => 'Created',
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
		$criteria->compare('t.rstl_id', Yii::app()->getModule('user')->user()->profile->getAttribute('pstc'));
		$criteria->compare('customerName',$this->customerName,true);
		$criteria->compare('head',$this->head,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('completeAddress',$this->completeAddress,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('typeId',$this->typeId);
		$criteria->compare('natureId',$this->natureId);
		$criteria->compare('industryId',$this->industryId);
		$criteria->compare('created',$this->created,true);

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
	 * @return Customer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function beforeSave()
	{
	   if(parent::beforeSave())
	   {
			if($this->isNewRecord){
				$this->rstl_id = Yii::app()->getModule('user')->user()->profile->getAttribute('pstc');
		        return true;
			}else{
				return true;
			}
	   }
	   return false;
	}

	//address, region_id, province_id, municipalitycity_id, barangay_id, completeAddress,
	public function getCompleteAddress()
	{
		$completeAddress="";
		//if ($this->mailingAddress){
			if($this->address){
				if ($this->address == " ") {
					$completeAddress = "";
				}elseif($this->address == NULL){
					$completeAddress = "";
				}elseif(empty($his->address)){
					$completeAddress = "";
				}else{
					$completeAddress = $this->address.", ";
				}				
			}
				
			if($this->barangay_id || $this->barangay_id != 0){
				$barangayName=Barangay::model()->findByPk($this->barangay_id)->name;
				$completeAddress = $completeAddress.$barangayName. ", ";
			}
			
			if($this->municipalitycity_id || $this->municipalitycity_id != 0){
				$municipalityCity = MunicipalityCity::model()->findByPk($this->municipalitycity_id);
				$municipalityCityName = $municipalityCity->name;
				$provinceName=Province::model()->findByPk($municipalityCity->provinceId)->name;								
				
				if($municipalityCityName == $provinceName){
					$completeAddress = $completeAddress.$municipalityCityName;
				}else{
					$completeAddress = $completeAddress.$municipalityCityName.", ".$provinceName;
				} 	
			}
		//}
		//return $this->address.$this->barangay_id.$this->municipalitycity_id.$this->province_id;
		return $completeAddress;		
	}	
}
