<?php
/*******************************************************
		name: Bergel T. Cutara
		org: DOST-IX, 991-1024
		date created: June 13 2017
		description: custom model for stock report, handles the temporary data of report generatiom
/*******************************************************
 * Stockreportform class.
 * Stockreportform is the data structure for keeping
 * Stockreportform form data. It is used by the 'Consumption' action of 'ConsumptionController'.
 */
class Stockreportform extends CFormModel
{
	// public $user_id;
	public $DateStart;
	public $DateEnd;
	//private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that userid,items,quantity are required,
	 * and item and quantity needs to be validated first before adding to cart.
	 */
	public function rules()
	{
		return array(
			// DateStart,DateEnd are required
			array('DateStart, DateEnd', 'required'),
		
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'DateStart' => 'Date Start',
			'DateEnd' => 'Date End',
		);
	}

	public function customgetErrors(){
		//return $this->getErrors();

		$errors =  $this->getErrors();
		foreach($errors as $err=>$value){			
			foreach ($value as $key => $value2) {
				Yii::app()->user->setFlash("error".$key,$value2);
			}
		}
		return;

	}
	
	
}
