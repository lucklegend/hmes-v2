<?php
/*******************************************************
		name: Bergel T. Cutara
		org: DOST-IX, 991-1024
		date created: MArch 20 2017
		description: custom model for orders, handles the temporary data of add to cart
/*******************************************************
 * OrderForm class.
 * OrderForm is the data structure for keeping
 * Order form data. It is used by the 'Consumption' action of 'ConsumptionController'.
 */
class OrderForm extends CFormModel
{
	// public $user_id;
	public $Item;
	public $Quantity;
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
			array('Item, Quantity', 'required'),
			array('Quantity', 'numerical', 'integerOnly'=>true,'min'=>1),
			array('Item', 'checkitem'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'Item' => 'Item',
			'Quantity' => 'Quantity',
		);
	}

	/**
	 * Check if the Item and stocks are valid
	 * This is the 'checkstock' validator as declared in rules().
	 */
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


	public function checkitem($attribute,$params)
	{
			$Stock = Stocks::model()->findByAttributes(array("stockCode"=>$this->Item));
			if(!$Stock){
				$this->addError('Item','Item not found');
			}else{
				if($Stock->expiry_date<date('Y-m-d')){
					$this->addError('Item','Item already expired!');
				}
				//check if the stock quantity is withdrawable?
				//check if theres same item in session
				$session =  Yii::app()->session;
				$unserialize = unserialize($session['orders']);
				$s_qty = 0 ;
				$key = $this->find_order_with_item($unserialize,$this->Item);

				//there is an existing item in session then get the quantity
				if($key!==false){
					$s_qty=$unserialize[$key]['Quantity'];
				}

				//we compare the item (in session plus the new one) to the quantity on stock in db
				if(($s_qty + $this->Quantity)>$Stock->quantity){
					$this->addError('Item','"'.$this->Item.'" Stock quantity left :'.$Stock->quantity);
				}
								
			}
			 
		
	}

	public function find_order_with_item($orders, $item) {
	    foreach($orders as $index => $order) {
	        if($order['Item'] == $item) return $index;
	    }
	    return FALSE;
	}

	//march 27 2017 gets the total amount using array_sum with the help of array_map because it is a multidimensional array
	public  function getTotal($orders){

		$sum  = array_sum( array_map(
                 function($element){
                     return $element['Subtotal'];
                 }, 
             $orders));
		
		if($sum){
			return number_format((float)$sum, 2, '.', '');
		}
		else{
			return "0.00";
		}

	}
	//ends here march 27 2017
	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function customsave()
	{
		$session = Yii::app()->session;
		$unserialize = unserialize($session['orders']);
		$Stock = Stocks::model()->findByAttributes(array("stockCode"=>$this->Item));
		$mthrarry = array();
		$myarry = array(
		 	'Item'=> $this->Item,
		 	'Name'=>$Stock->name,
		 	'Quantity'=> $this->Quantity,
		 	'Cost'=> $Stock->amount,
		 	'Subtotal'=>$Stock->amount * $this->Quantity,
		 	);
		
		$key = $this->find_order_with_item($unserialize,$myarry['Item']);

		if($key!==false){
			$unserialize[$key]['Quantity'] = $unserialize[$key]['Quantity'] + $myarry['Quantity'];
			$unserialize[$key]['Subtotal'] = $unserialize[$key]['Subtotal'] + $myarry['Subtotal'];
			$session['orders'] = serialize($unserialize);
		}else if($unserialize==""){

			array_push($mthrarry, $myarry);
			$session['orders'] = serialize($mthrarry);
		}else{
			array_push($unserialize, $myarry);
			$session['orders'] = serialize($unserialize);
		}
	}
	
}
