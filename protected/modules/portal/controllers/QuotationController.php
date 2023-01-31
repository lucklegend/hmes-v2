<?php

class QuotationController extends Controller
{

	public $layout='//layouts/column2';

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionView()
	{
		$this->render('view'
			//array('quotation'=>$this->getQ('QT-042016-b5cf8'))
		);
	}

	public function actionGetQuotation()
	{
		$url = 'http://quotation.onelab.ph/api/customer_portal/getQuotation/'.$_POST['quotationCode'];
		$quotation = json_decode(Yii::app()->curl->get($url));
		
		$count = 0;
		foreach($quotation->data->analysesCart as $analysis){
			$gridDataProvider[$count] = new CArrayDataProvider($analysis, array('pagination'=>false));	
			$count += 1;
		}
		
		//print_r($gridDataProvider);
		$this->renderPartial('quotation', 
			array(
				'gridDataProvider'=>$gridDataProvider,
				'quotation'=>$quotation
			), false, true);
	
	}


	private function getQ($code)
	{
		$url = 'http://quotation.onelab.ph/api/customer_portal/getQuotation/'.$code;
		$quotation = Yii::app()->curl->get($url);
		return $quotation;
	}
}