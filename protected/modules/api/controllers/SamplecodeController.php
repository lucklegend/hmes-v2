<?php

class SamplecodeController extends Controller
{
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */

	public function actionIndex()
	{
		$lab = Lab::model()->findAll();
		return CJSON::encode($lab);
		exit();
	}
	
	public function actionGetsamplecode($lab_id, $year, $sampleCount)
	{
		
		//{"id":"1","rstl_id":"11","requestId":"012013-M-0001-R9","labId":"2","number":"1","year":"2013","cancelled":"0"}
		//$model = Samplecode::model()->findByPk(1);
		$sampleCode = Samplecode::model()->find(array(
	   			'select'=>'*',
				'order'=>'number DESC, id DESC',
	    		'condition'=>'rstl_id = :rstl_id AND labId = :labId AND year = :year AND cancelled = 0',
	    		'params'=>array(':rstl_id' => Yii::app()->Controller->getRstlId(), ':labId' => $lab_id, ':year' => $year )
			));
		$labCode = Lab::model()->findByPk($lab_id)->labCode;
		
		$codes = array();
		
		for($i=1; $i<=$sampleCount; $i++){
			$code = array(
				"samplecode" => $labCode."-".Yii::app()->Controller->addZeros($sampleCode->number + $i)
			);
			array_push($codes, $code);
		}
		//echo CJSON::encode($codes);
		$this->sendJSONResponse($codes);

		//echo '{"id":"1","rstl_id":"11","requestId":"012013-M-0001-R9","labId":"2","number":"1","year":"2013","cancelled":"0"}';
		//exit();
		
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/

	public function sendJSONResponse( $arr)
    {
        header('Content-type: application/json');
        echo json_encode($arr);
        Yii::app()->end();
    }


}