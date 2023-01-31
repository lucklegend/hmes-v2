<?php

/**
 * This is the model class for table "equipment".
 *
 * The followings are the available columns in table 'equipment':
 * @property integer $ID
 * @property string $equipmentID
 * @property string $name
 * @property string $description
 * @property string $image
 * @property string $image
 * @property integer $lab
 * @property integer $classificationID
 * @property string $specification
 * @property string $brand
 * @property string $model
 * @property string $serialno
 * @property string $tags
 * @property string $date_received
 * @property string $date_purchased
 * @property integer $are
 * @property integer $received_by
 * @property double $amount
 * @property integer $supplier
 * @property integer $status
 * @property integer $usagestatus
 * @property string $lengthofuse
 * @property integer $sourcefund
 * @property string $remarks
 * @property integer $rstl_id
 */
class Equipment extends CActiveRecord
{
	public $fundings;
	public $user_search;
	public $fullName;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'equipment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('equipmentID, name, lab, date_received, date_purchased, received_by, status', 'required'),
			array('lab, classificationID, received_by, supplier, status, usagestatus, sourcefund', 'numerical', 'integerOnly'=>true),
			array('amount', 'numerical'),
			array('equipmentID','unique'),
			array('equipmentID', 'length', 'max'=>50),
			array('name, description, remarks', 'length', 'max'=>255),
			array('image, image2, specification', 'length', 'max'=>500),
			array('brand, model', 'length', 'max'=>150),
			array('serialno', 'length', 'max'=>200),
			array('tags', 'length', 'max'=>100),
			array('image, image2', 'file','types'=>'jpg, gif, png', 'allowEmpty'=>true, 'on'=>'create,update'), // this will allow empty field when page is update (remember here i create scenario update)
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ID, fundings, fullName, user_search, equipmentID, name, description, lab, date_purchased, classificationID, specification, date_received, received_by, amount, supplier, status, usagestatus, lengthofuse, remarks', 'safe', 'on'=>'search'),
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
			'labaccess'=>array(self::BELONGS_TO,'Lab','lab'),
			'user'=>array(self::BELONGS_TO,'EquipmentUser','are'),
			'classification'=>array(self::BELONGS_TO,'Equipmentclassification','classificationID'),
			'equipstatus'=>array(self::BELONGS_TO,'Equipmentstatus','status'),
			'suppliers'=>array(self::BELONGS_TO,'Suppliers','supplier'),
			'fund'=>array(self::BELONGS_TO,'Fundings','sourcefund'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'equipmentID' => 'Equipment Code',
			'name' => 'Name',
			'description' => 'Description',
			'image' => 'Image',
			'image2'=> 'Image2',
			'lab' => 'Lab',
			'classificationID' => 'Classification',
			'specification' => 'Specification',
			'brand' => 'Brand',
			'model' => 'Model',
			'serialno' => 'Serialno',
			'tags' => 'Tag',
			'date_received' => 'Date Received',
			'date_purchased' => 'Date Purchased',
			'are' => 'Are',
			'received_by' => 'Received By',
			'amount' => 'Amount',
			'supplier' => 'Supplier',
			'status' => 'Status',
			'usagestatus' => 'Usagestatus',
			'lengthofuse' => 'Lengthofuse',
			'sourcefund' => 'Source of funds',
			'remarks' => 'Remarks',
			'rstl_id' => 'Rstl',
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
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$sort = new CSort();
		$sort->attributes = array(
			'fullName'=>array(
			  'asc'=>'fullName',
			  'desc'=>'fullName desc',
			),
			'*' //to make all other columns sortable
		);

		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with = array( 'fund' , 'user' , 'user.profile');
		$criteria->select=array(
			'*',
			'CONCAT(profile.lastName,", ",profile.firstName, " ", IFNULL(profile.mi,"")) AS fullName',
			);

		$criteria->compare('CONCAT(profile.lastName," ",profile.firstName," ",profile.mi)',$this->fullName,true, 'OR');
		// $criteria->compare('CONCAT(profile.lastName,", ",profile.firstName)',$this->fullName,true, 'OR');
		// $criteria->compare('CONCAT(profile.lastName,",",profile.firstName)',$this->fullName,true, 'OR');
		// $criteria->compare('CONCAT(profile.lastName," ",profile.firstName, " ",profile.mi)',$this->fullName,true, 'OR');
		// $criteria->compare('CONCAT(profile.lastName,", ",profile.firstName," ",profile.mi)',$this->fullName,true, 'OR');
		// $criteria->compare('CONCAT(profile.firstName," ",profile.lastName, " ",profile.mi)',$this->fullName,true, 'OR');
		// $criteria->compare('CONCAT(profile.firstName," ",profile.mi, " ",profile.lastName)',$this->fullName,true, 'OR');
		// $criteria->compare('CONCAT(profile.firstName," ",profile.lastName)',$this->fullName,true, 'OR');
		$criteria->compare('t.rstl_id', Yii::app()->Controller->getRstlId());
		$criteria->compare('fund.name', $this->fundings, true );
		$criteria->compare('`user`.`username`', $this->user_search, true );
		$criteria->compare('ID',$this->ID);
		$criteria->compare('equipmentID',$this->equipmentID,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('lab',$this->lab);
		$criteria->compare('classificationID',$this->classificationID);
		$criteria->compare('specification',$this->specification,true);
		$criteria->compare('brand',$this->brand,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('serialno',$this->serialno,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('date_received',$this->date_received,true);
		$criteria->compare('date_purchased',$this->date_purchased,true);
		$criteria->compare('are',$this->are);
		$criteria->compare('received_by',$this->received_by);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('supplier',$this->supplier);
		$criteria->compare('status',$this->status);
		$criteria->compare('usagestatus',$this->usagestatus);
		$criteria->compare('lengthofuse',$this->lengthofuse,true);
		$criteria->compare('sourcefund',$this->sourcefund);  
		$criteria->compare('remarks',$this->remarks,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	public function searchbycustomfilter($name,$date_received,$equipmentID,$fundings,$mr,$date_purchased,$lab)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$sort = new CSort();
		$sort->attributes = array(
			'fullName'=>array(
			  'asc'=>'fullName',
			  'desc'=>'fullName desc',
			),
			'*' //to make all other columns sortable
		);

		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with = array( 
			'fund'=>array(),
			'user',
			 'user.profile');
		$criteria->select=array(
			'*',
			'CONCAT(profile.lastName,", ",profile.firstName, " ", IFNULL(profile.mi,"")) AS fullName',
			);

		$criteria->compare('CONCAT(profile.lastName," ",profile.firstName," ",profile.mi)',$this->fullName,true, 'OR');
		// $criteria->compare('CONCAT(profile.lastName,", ",profile.firstName)',$this->fullName,true, 'OR');
		// $criteria->compare('CONCAT(profile.lastName,",",profile.firstName)',$this->fullName,true, 'OR');
		// $criteria->compare('CONCAT(profile.lastName," ",profile.firstName, " ",profile.mi)',$this->fullName,true, 'OR');
		// $criteria->compare('CONCAT(profile.lastName,", ",profile.firstName," ",profile.mi)',$this->fullName,true, 'OR');
		// $criteria->compare('CONCAT(profile.firstName," ",profile.lastName, " ",profile.mi)',$this->fullName,true, 'OR');
		// $criteria->compare('CONCAT(profile.firstName," ",profile.mi, " ",profile.lastName)',$this->fullName,true, 'OR');
		// $criteria->compare('CONCAT(profile.firstName," ",profile.lastName)',$this->fullName,true, 'OR');
		$criteria->compare('t.rstl_id', Yii::app()->Controller->getRstlId());
		$criteria->compare('fund.name', $this->fundings, true );
		$criteria->compare('`user`.`username`', $this->user_search, true );
		$criteria->compare('ID',$this->ID);
		$criteria->compare('equipmentID',$this->equipmentID,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('lab',$this->lab);
		$criteria->compare('classificationID',$this->classificationID);
		$criteria->compare('specification',$this->specification,true);
		$criteria->compare('brand',$this->brand,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('serialno',$this->serialno,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('date_received',$this->date_received,true);
		$criteria->compare('date_purchased',$this->date_purchased,true);
		$criteria->compare('are',$this->are);
		$criteria->compare('received_by',$this->received_by);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('supplier',$this->supplier);
		$criteria->compare('status',$this->status);
		$criteria->compare('usagestatus',$this->usagestatus);
		$criteria->compare('lengthofuse',$this->lengthofuse,true);
		$criteria->compare('sourcefund',$this->sourcefund);  
		$criteria->compare('remarks',$this->remarks,true);
		if($mr!=""){
			$criteria->condition ='CONCAT(profile.lastName," ",profile.firstName," ",profile.mi) LIKE "%'.$mr.'%"';
		}elseif($fundings!=""){
			$criteria->condition ='fund.name LIKE "%'.$fundings.'%"';
		}elseif($lab!=""){
			$criteria->condition ='lab = "'.$lab.'"';
		}
		$criteria->condition ='t.name LIKE "%'.$name.'%" AND date_received LIKE "%'.$date_received.'%" AND equipmentID LIKE "%'.$equipmentID.'%" AND date_purchased  LIKE "%'.$date_purchased.'%"';

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


	public function getDepreciation()
	{
		//(original value - (5% orignal value)) / Length of Use
		$message="";
		if(!$this->amount){
			$message.="No Cost";
			return $message;
		}

		if(!$this->lengthofuse){
			$message.="No Length of Use";
			return $message;
		}

		$yrcost = ($this->amount - ( .05 * $this->amount ))/$this->lengthofuse;

		$d1 = new DateTime($this->date_received);
		$d2 = new DateTime(date('Y-m-d'));

		$diff = $d2->diff($d1);
		

		$year= $diff->y ;
		if($diff->y==0){
			if($diff->m>=1){
				$year=1;
			}
		}

		$cost = $this->amount - ($year * $yrcost);
		return $cost;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Equipment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
