<?php

/**
 * This is the model class for table "quotation".
 *
 * The followings are the available columns in table 'quotation':
 * @property integer $id
 * @property string $company
 * @property string $address
 * @property string $contact_person
 * @property string $contact_number
 * @property string $requestDate
 * @property string $email
 * @property double $discount
 * @property double $discout_rate
 * @property double $total
 * @property string $estimated_due_date
 * @property string $remarks
 */
class Quotation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'quotation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company, address, contact_person, contact_number, noted_by, noted_byPos, created_by, created_byPos', 'required'),
			array('discount, discount_rate, total', 'numerical'),
			array('company, contact_person, contact_number, email, estimated_due_date', 'length', 'max'=>255),
			array('requestDate, remarks', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, quotationCode, company, address, contact_person, contact_number, requestDate, email, discount, discout_rate, total, estimated_due_date, remarks', 'safe', 'on'=>'search'),
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
			'tests'=>array(self::HAS_MANY, 'QuotationTest', 'quotation_id', 'order'=>'sample_id ASC'),
			'samples'=>array(self::HAS_MANY, 'QuotationSample', 'quotation_id'),
			'testCount'=>array(self::STAT, 'QuotationTest', 'quotation_id', 'select'=> 'count(id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'company' => 'Company',
			'quotationCode' => 'Quotation Code',
			'address' => 'Complete Address',
			'contact_person' => 'Contact Person',
			'designation' =>'Designation',
			'contact_number' => 'Contact Number',
			'requestDate' => 'Request Date',
			'email' => 'Email',
			'discount' => 'Discount',
			'discount_rate' => 'Discount Rate',
			'total' => 'Total',
			'estimated_due_date' => 'Estimated Due Date',
			'remarks' => 'Remarks',
			'noted_by' => 'Noted by',
			'noted_byPos' => 'Position',
			'created_by' => 'Created by',
			'created_byPos' => 'Position',
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
		$criteria->order = 't.id DESC';
		$criteria->compare('id',$this->id);
		$criteria->compare('quotationCode',$this->quotationCode,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('contact_person',$this->contact_person,true);
		$criteria->compare('designation',$this->designation,true);
		$criteria->compare('contact_number',$this->contact_number,true);
		$criteria->compare('requestDate',$this->requestDate,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('discount_rate',$this->discount_rate);
		$criteria->compare('total',$this->total);
		$criteria->compare('estimated_due_date',$this->estimated_due_date,true);
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
		return Yii::app()->limsDb;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Quotation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function getTestTotal($keys)
	{
        $tests = QuotationTest::model()->findAllByPk($keys);
        $requestTotal=0;
        foreach($tests as $test)
                $requestTotal+=$test->fee*$test->sample->qty;         
        return $requestTotal;
	}

	public function getQutationTotal($keys, $discount_rate, $onsite_charge,$id)
	{
		$requestTotal = $this->getTestTotal($keys);
		if($discount_rate != 0.00 && $discount_rate != ''){
			$less = $requestTotal * ($discount_rate/100);
		}	
		else{
			$less = 0;
		}
		$quoteTotal = $requestTotal - $less + $onsite_charge;
		$quotation = Quotation::model()->findByPk($id);
		$quotation->total = $quoteTotal;
		$quotation->update();
		return $quoteTotal;	
	}
	public function getDiscount($keys, $discount_rate, $id){
		$requestTotal = $this->getTestTotal($keys);
		if($discount_rate != 0.00 && $discount_rate != ''){
			$discount = $requestTotal * ($discount_rate / 100);
		}	
		else{
			$discount = 0;
		}
		$quotation = Quotation::model()->findByPk($id);
		$quotation->discounted = $discount;
		$quotation->update();
		return - $discount;
	} 
}
