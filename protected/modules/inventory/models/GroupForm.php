<?php
/*******************************************************
		name: Bergel T. Cutara
		org: DOST-IX, 991-1024
		date created: May 28 2017
		description: custom model for orders, handles the temporary data of add to cart
/*******************************************************
 * GroupForm class.
 * GroupForm is the data structure for keeping
 * GroupForm form data. It is used by the 'Consumption' action of 'ConsumptionController'.
 */
class GroupForm extends CFormModel
{
	// public $user_id;
	public $EquipmentID;
	public $Tag;
	//private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that userid,items,quantity are required,
	 * and item and quantity needs to be validated first before adding to cart.
	 */
	public function rules()
	{
		return array(
			// item,quantity are required
			array('EquipmentID, Tag', 'required'),
			//array('Quantity', 'numerical', 'integerOnly'=>true,'min'=>1),
			//array('Item', 'checkitem'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'EquipmentID' => 'Equipments',
			'Tag' => 'Tag',
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
