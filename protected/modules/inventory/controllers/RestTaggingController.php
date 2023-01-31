<?php

class RestTaggingController extends Controller
{
	
	public function actionGetAnalyses(){
		$data = CJSON::decode($_POST["data"]);
		$sample_code = explode(' ', $data['code']);
		$id = $sample_code[0];
		$analyses = Analysis::model()->findAllByAttributes(array('sample_id'=>$id));
		//$sample = Sample::model()->findbyPK(14513);
		$sample = Sample::model()->findbyPK($id);
		$tagging = Tagging::model()->findByAttributes(array('analysisId'=>$analyses->taggingId));

		$Criteria = new CDbCriteria();
        $Criteria->select="*";
        $Criteria->with = "tags";
       // $Criteria->condition="`sample_id`=14513";
        $Criteria->condition="`sample_id`=".$id;
		$analyses=Analysis::model()->findAll($Criteria);

		// echo "<pre>";
		// print_r($analyses);
		// echo "</pre>";

		$sample = CJSON::encode($sample);
		$analyses = CJSON::encode($analyses);

		if ($analyses){
		echo CJSON::encode(array("result"=>true,"analyses"=>$analyses, "sample"=>$sample));
		}else{
			echo CJSON::encode(array("result"=>false,"message"=>"sample code not found"));
		}
		exit();
	}

 public function actionStartAnalyses(){
		 	$data = CJSON::decode($_POST["data"]);
		 	$analysis_id = explode ('-', $data['id']);
		 	$id = $analysis_id[1];
		 	$new = $id;

		 	$Criteria = new CDbCriteria;
		 	$Criteria->condition = '(status=1 OR status=2 OR user_id!='.$data['user_id'].') AND analysisId='.$id;
		 	$taggingModel = Tagging::model()->find($Criteria);

		 	if ($taggingModel){
		 		$taggingUser = Tagging::model()->findByAttributes(array('analysisId'=>$new));
		 		$analystname = Profile::model()->findbyPK($taggingUser->user_id);
		 		$name = $analystname->firstname." ".$analystname->lastname;

		 		if ($taggingUser->user_id!=$data['user_id']){
		 			$message = "Analysis already already assigned to ".$name.".";
		 			echo CJSON::encode(array("result"=>false, "message"=>$message));
		 		}else{
		 			$message = "Analysis already tagged as ongoing.";
		 			echo CJSON::encode(array("result"=>false, "message"=>$message));
		 		}
		 	}else{
		 		$tagging = new Tagging;
			 	$tagging->analysisId = $id;
			 	$tagging->startDate = date('Y-m-d');
			 	$tagging->status = 1;
			 	$tagging->user_id = $data['user_id'];
			 	if ($tagging->save(false)){
			 		Analysis::model()->updateByPk($new, array ("taggingId"=>$tagging->id));
			 		$tag = Tagging::model()->findByAttributes(array('analysisId'=>$new));
		 			$analyst = Profile::model()->findbyPK($tag->user_id);
		 			$fullname = $analyst->firstname." ".$analyst->lastname;
			 		echo CJSON::encode(array("result"=>true, "new"=>$new, "fullname"=>$fullname));
			 	}else{
			 		echo CJSON::encode(array("result"=>false, "new"=>$new, "fullname"=>$fullname));
			 	}

		 	}

 }

 public function actionCompletedAnalyses(){
 	$data = CJSON::decode($_POST["data"]);
 	$analysis_id = explode ('-', $data['id']);
 	$id = $analysis_id[1];
 	$new = $id;




 	$Criteria = new CDbCriteria;
 	$Criteria->condition = '(status=2 OR status=4 OR user_id!='.$data['user_id'].') AND analysisId='.$id;
 	$taggingModel = Tagging::model()->find($Criteria);
 	if ($taggingModel){
 			$taggingUser = Tagging::model()->findByAttributes(array('analysisId'=>$new));
 			$analystname = Profile::model()->findbyPK($taggingUser->user_id);
 			$name = $analystname->firstname." ".$analystname->lastname;
 			if ($taggingUser->user_id!=$data['user_id']){
		 			$message = "Analysis already already assigned to ".$name.".";
		 			echo CJSON::encode(array("result"=>false, "message"=>$message));
		 		}else{
		 			$message = "Analysis already tagged as completed.";
	 				echo CJSON::encode(array("result"=>false, "message"=>$message));
		 		}
	 	}else{
		 	$Analysis = Analysis::model()->findbyPK($id);
		 	$tagging = Tagging::model()->findByAttributes(array('analysisId'=>$Analysis->id));
		 	if ($tagging){
		 		Tagging::model()->updateByPk($tagging->id, array ("status"=>2, "endDate"=>date('Y-m-d')));
		 		echo CJSON::encode(array("result"=>true, "new"=>$new));
		 	}else{
		 		$message = "Please start analysis.";
		 		echo CJSON::encode(array("result"=>false, "new"=>$new, "message"=>$message));
	 	}
 	}
 	
 }

  public function actionTransferAnalyst(){
 	$data = CJSON::decode($_POST["data"]);
 	$analysis_id = explode ('-', $data['id']);
 	$id = $analysis_id[1];
 	//$id = 31442;
 	$new = $id;


 	$Criteria = new CDbCriteria;
	$Criteria->condition = '(status=2) AND analysisId='.$id;
	$taggingModel = Tagging::model()->find($Criteria);
	 	if ($taggingModel){

	 				$message = "Can't transfer analyst. Status is completed.";
	 				echo CJSON::encode(array("result"=>false, "message"=>$message, "new"=>$new));
	 		}else{
	 			$tagging = Tagging::model()->findByAttributes(array('analysisId'=>$id));
	 					if ($tagging){
	 						Tagging::model()->updateByPk($tagging->id, array ("user_id"=>$data['user_id']));
	 						//Tagging::model()->updateByPk($tagging->id, array ("status"=>4, "user_id"=>1));
	 					}else{

	 					}
	 			$tag = Tagging::model()->findByAttributes(array('analysisId'=>$id));
	 			$analyst = Profile::model()->findbyPK($tag->user_id);
	 			$fullname = $analyst->firstname." ".$analyst->lastname;
	 			echo CJSON::encode(array("result"=>true, "fullname"=>$fullname, "new"=>$new));
	 		}
 }

 public function actionGetAnalyst(){
 	//transfer na wala pang tag
 		$data =CJSON::decode($_POST["data"]);
 		$analysis_id = explode ('-', $data['id']);
 		$id = $analysis_id[1];
 		//$id = 31442;

 		$tagStatus = Tagging::model()->findByAttributes(array('analysisId'=>$id));
	 		if (!$tagStatus){
	 				$message = "Please start analysis.";
	 				echo CJSON::encode(array("result"=>false, "message"=>$message, "new"=>$new));
	 		}else if ($tagStatus->user_id!=$data['user_id']){
	 				$analystname = Profile::model()->findbyPK($tagStatus->user_id);
 					$name = $analystname->firstname." ".$analystname->lastname;
	 				$message = "Analysis already already assigned to ".$name.".";
	 				echo CJSON::encode(array("result"=>false, "message"=>$message, "new"=>$new));
	 		}else{
	 			$Criteria = new CDbCriteria;
			 	$Criteria->condition = '(status=2) AND analysisId='.$id;
			 	$taggingModel = Tagging::model()->find($Criteria);
	 			if ($taggingModel){
	 			
		 			$message = "Can't transfer analyst. Status is completed.";
		 			echo CJSON::encode(array("result"=>false, "message"=>$message));
	 				
	 		
	 			}else{
	 				$Analysis = Analysis::model()->findbyPK($analysis_id);
 					$profile = Profile::model()->findByAttributes(array('user_id'=>$data['user_id']));
 					//$profile = Profile::model()->findByAttributes(array('user_id'=>1));
 					$code_data = Profile::model()->findAll(array("condition"=>"labId='".$profile->labId."'"));
 		
	 				if ($code_data){
	 					echo CJSON::encode(array("result"=>true, "analyst"=>$code_data, "testname"=>$Analysis->testName));
	 				}else{
	 					echo CJSON::encode(array("result"=>false));
	 				}
	 	}	
	 		}
 		
 }

  public function actionGetSampleCode(){
 		$data =CJSON::decode($_POST["data"]);

 		$profile = Profile::model()->findByAttributes(array('user_id'=>$data['user_id']));
 		//$profile = Profile::model()->findByAttributes(array('user_id'=>1));
 		$lab = Lab::model()->findbyPK($profile->labId);
 		$year = date('Y');
 		$code_data = Sample::model()->findAll(array("condition"=>'sampleCode LIKE"'.$lab->labCode.'-%" AND sampleYear='.$year));
 		if ($code_data){
 			echo CJSON::encode(array("result"=>true, "samplecode"=>$code_data));
 		}else{
 			echo CJSON::encode(array("result"=>false));
 		}
 }

  public function actionGetTags(){
 		$data =CJSON::decode($_POST["data"]);
		$analysis_id = explode ('-', $data['id']);
 		$tagging = Tagging::model()->findByAttributes(array('analysisId'=>$analysis_id));
 		$analyst = Profile::model()->findbyPK($tagging->user_id);
 		$fullname = $analyst->firstname." ".$analyst->lastname;
 		$status = $tagging->status;
 		if ($status==1){
 			echo CJSON::encode(array("result"=>true, "status"=>"Ongoing", "fullname"=>$fullname));
 		}else if ($status==2){
 			echo CJSON::encode(array("result"=>true, "status"=>"Completed", "fullname"=>$fullname));
 		}else if ($status==4){
 			echo CJSON::encode(array("result"=>true, "status"=>"Assigned", "fullname"=>$fullname));
 		}else if (!$status){
 			echo CJSON::encode(array("result"=>true, "status"=>"Pending", "fullname"=>$fullname));
 		}
 }
}

?>