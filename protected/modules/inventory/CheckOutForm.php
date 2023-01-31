<?php
/*******************************************************
		name: Bergel T. Cutara
		org: DOST-IX, 991-1024
		date created: MArch 22 2017
		description: custom model for check out, 
/*******************************************************
 * CheckOutForm class.
 * CheckOutForm is the data structure for keeping
 * Order form data. It is used by the 'Consumption' action of 'ConsumptionController'.
 */
class CheckOutForm extends CFormModel
{
	// public $user_id;
	public $user_id;

	//private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that userid,items,quantity are required,
	 * and item and quantity needs to be validated first before adding to cart.
	 */
	public function rules()
	{
		return array(
			// user_id is required
			array('user_id', 'required'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'Officer',
		);
	}

	public function customsave()
	{

		//fetch the orders and add them to the consumption database

		//required tp use session and fetch the orders
		$session = Yii::app()->session;
		$unserialize = unserialize($session['orders']);
		//print_r($unserialize); echo "</br>";
		foreach($unserialize as $key=>$value){
			//subtract the qty on stock
			//print_r($value);
			//echo $value['Item'];
			$Stocks = Stocks::model()->findByAttributes(array('stockCode'=>$value['Item']));	
			if($Stocks){
				$Stocks->quantity = $Stocks->quantity - $value['Quantity'];
				if($Stocks->save(false)){
					$consumptions = new Consumptions();
					$consumptions->stockID = $value['Item'];
					$consumptions->balance =  $Stocks->quantity;
					$consumptions->amountused = $value['Quantity'];
					$consumptions->dateconsumed =date("Y-m-d H:i");
					$consumptions->withdrawnby = $this->user_id;
					if($consumptions->save(false)){
						//clears the order session
						$session['orders']=array();
						Yii::app()->user->setFlash('success','Transaction Complete :)');
					}
				} 
				else{
					//rollback process here and error report : stcoks cant be subtract
				}
			}
			else{
				echo "pass ehre"; exit();

			}
			
		}







		
	}
	
}