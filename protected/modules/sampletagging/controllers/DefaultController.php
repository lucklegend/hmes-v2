<?php
/*******************************************************
		name: Janeedi A. Grapa
		org: DOST-IX, 991-1024
		date created: April 12, 2017
	
********************************************************/
class DefaultController extends Controller
{
	public function actionIndex()
	{
		$tagging = Analysis::model()->findByPk(251);

        $analysis=new CActiveDataProvider('Analysis',
	 	array(
			 'criteria'=>array( 'condition'=>'id=0'))
		);

		 $sample=new CActiveDataProvider('Analysis',
	 	array(
			 'criteria'=>array( 'condition'=>'id=0'))
		);

		$this->render('index',array(
			'analysisDataProvider'=>$analysis,
			'sample'=>$sample,
			'tagging'=>$tagging,


		));
	}

	
}