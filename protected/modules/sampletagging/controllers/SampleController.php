<?php
/*******************************************************
		name: Janeedi A. Grapa
		org: DOST-IX, 991-1024
		date created: April 24, 2017


********************************************************/
class SampleController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		// return array(
		// 	'accessControl', // perform access control for CRUD operations
		// 	'postOnly + delete', // we only allow deletion via POST request
		// );
		return array('rights');
	}
		
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array( 'getAnalysis', 'tagAnalysis', 'tagAnalysisEnd', 'updateUser', 'cancelAnalysis', 'uncancelAnalysis', 'forTransferAnalysis'),
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Returns sample code(s) to be assigned for the incoming Referral 
	 * @param integer $lab_id, $sampleCount and $year are passed
	 */

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	

	public function actionGetAnalysis()
	{
		$sample_code = $_POST["barcode"];
		$barcode_data = explode(" ", $sample_code);
		$sample_id = $barcode_data[0];
		
		$analysiscount = Analysis::model()->findByAttributes(
						array('sampleCode'=>$barcode_data[2], 'sample_id'=>$barcode_data[0], 'analysisYear'=>$barcode_data[1])
						);

		$profile = Profiles::model()->findByAttributes(
					array('user_id'=>Yii::app()->user->id)
					);
		
		$lab= Lab::model()->findByPk($profile->labId);
		$s= Sample::model()->findByPk($barcode_data[0]);
		$request_id = $s->request_id;

				$sample=new CActiveDataProvider('Sample', 
						array(
							'criteria'=>array(
							'condition'=>"sampleCode='" .$barcode_data[2]."' 
							AND id='" .$barcode_data[0]."' AND sampleCode LIKE '".$lab->labCode."-%' ",
								),
							)
						);		
		
				$analysis=new CActiveDataProvider('Analysis', 
					array(
						'criteria'=>array(
						'condition'=>"sampleCode='" .$barcode_data[2]."' AND sample_id='" .$barcode_data[0]."' AND sampleCode LIKE '".$lab->labCode."-%'",
							),
						)
					);

				$taggingcount = Tagging::model()->findAllByAttributes(
						array('status'=>2)
						);

			if ($analysis){
						$completed = count($taggingcount);

						echo    $this->renderPartial('_viewAnalysis', 
						array('analysis'=>$analysis, 'sample'=>$sample, 'sample_code'=>$sample_code, 'completed'=>$completed, 'sample_code'=>$sample_code, 'sample_id'=>$sample_id, 'request_id'=>$request_id)  ,true , true);
				
				}else {
						echo "<div style='text-align:center;' class='alert alert-error'><i class='icon icon-warning-sign'>
						</i><font style='font-size:14px;'> Sample code not found. </font><br \>";
					  }
	}

	public function actionTagAnalysis()
	{
	
	if(isset($_POST['xyz'])){
		$ids = $_POST['xyz'];
		$analysisID = explode(",", $ids);
		$message = "";
		$msg = "Analysis already tagged as ongoing.";
		if ($ids){
			foreach ($analysisID as $aid){
				$testname = Analysis::model()->findbyPk($aid); 
				$criteria = new CDbCriteria;
				$criteria->condition = '(status=1 OR status=2 OR status=3) AND analysisId='.$aid;
				$taggingModel = Tagging::model()->find($criteria);
					if ($taggingModel){
						$msg1 .= $testname->testName." ";
						//alert('Hello again! This is how we' +'\\n' +'add line breaks to an alert box!');
					}else{
							$Analysis = Analysis::model()->findByPk($aid);
							$tag = Tagging::model()->findByAttributes(
							array('analysisId'=>$Analysis->id)
							);
							if ($tag->status==null){
							$Tagging = new Tagging;
							$Tagging->startDate = Date('Y-m-d');
							$Tagging->status = 1;
							$Tagging->user_id = Yii::app()->user->id;
						if ($Tagging->save(false))
							{
								Analysis::model()->updateByPk($aid, 
								array('taggingId'=>$Tagging->id, 'user_id'=>Yii::app()->user->id,
								));
								Tagging::model()->updateByPk($Tagging->id, 
									array('analysisId'=>$aid,
								));	
							}	
						}else{
								$a = Analysis::model()->findByPk($aid);
								$tags = Tagging::model()->findByAttributes(
								array('analysisId'=>$a->id)
								);
								Analysis::model()->updateByPk($aid, 
								array('taggingId'=>$tags->id, 'user_id'=>Yii::app()->user->id,
								));
								Tagging::model()->updateByPk($tags->id, 
								array('analysisId'=>$aid, 'status'=>1, 'startDate'=>Date('Y-m-d'),
							));	
						}		
				}				
			}
			if(isset($_POST['iso'])){
							$iso = $_POST['iso'];
							$iso_id = explode(",", $iso);
								foreach ($iso_id as $iso){
									$a = Analysis::model()->findByPk($iso);
									$tag = Tagging::model()->findByAttributes(
									array('analysisId'=>$a->id)
								);	
									Tagging::model()->updateByPk($tag->id, 
									array('isoAccredited'=>1,
								));	
								}
			}
		if ($msg1)
			$message = $msg;	
		}else
		{
			$message = "Please select at least one(1) analysis.";
		}
					$sample_code = $_POST["samplecode"];
					echo CJSON::encode( array ('message'=>$message, 'sample_code'=>$sample_code));
		}
	}
public function actionForTransferAnalysis()
{
		if(isset($_POST['xyz'])){
			$ids = $_POST['xyz'];
			$analysisID = explode(",", $ids);
			$message = "";
			$msg = "Can't tag analysis as ongoing for the test";
			if ($ids){
				foreach ($analysisID as $aid){
					$testname = Analysis::model()->findbyPk($aid); 
					$criteria = new CDbCriteria;
					$criteria->condition = '(status=1 OR status=2 OR status=3) AND analysisId='.$aid;
					$taggingModel = Tagging::model()->find($criteria);
						if ($taggingModel){
							$msg1 .= $testname->testName." ";	
						}else{
							$Tagging = new Tagging;
							$Tagging->startDate = Date('Y-m-d');
							$Tagging->status = 6;
							$Tagging->user_id = Yii::app()->user->id;
							if ($Tagging->save(false))
								{
									Analysis::model()->updateByPk($aid, 
									array('taggingId'=>$Tagging->id,
									));
									Tagging::model()->updateByPk($Tagging->id, 
										array('analysisId'=>$aid,
								));	
						}				
					}				
				}
			if ($msg1)
				$message = $msg." ".$msg1;	
			}else
			{
				$message = "Please select at least one(1) analysis.";
			}
								$sample_code = $_POST["samplecode"];
								echo CJSON::encode( array ('message'=>$message, 'sample_code'=>$sample_code));
			}
	}
public function actionTagAnalysisEnd()
{
	if(isset($_POST['xyz'])){
		$ids = $_POST['xyz'];
		$analysisID = explode(",", $ids);
		$message = "";
		if ($ids){
			foreach ($analysisID as $aid){
			$Analysis = Analysis::model()->findByPk($aid);
			$tag= Tagging::model()->findByPk($Analysis->taggingId);
			$statuses = $tag->status;

				if ($statuses!=5){
				$completed = Tagging::model()->findAllByAttributes(
						array('id'=>$Analysis->taggingId, 'status'=>2)
						);	
				$mycount = count($completed);
				$Sample = Sample::model()->findByPk($Analysis->sample_id);
				
				Tagging::model()->updateByPk($Analysis->taggingId, 
				array('endDate'=>Date('Y-m-d'),
				'status'=>2
				));
				}
					if ($statuses==1){
					Sample::model()->updateByPk($Sample->id, 
					array('completed'=>$Sample->completed + 1 ,

				));
			}
				}
			
		}
		else 
		{
				$message =  "Please select at least one(1) analysis.";
		}
				$sample_code = $_POST["samplecode"];
				echo CJSON::encode( array ('message'=>$message, 'sample_code'=>$sample_code, 'mycount'=>$mycount, 'mycounts'=>$mycounts, 'taggingModel'=>$taggingModel, 'exist'=>$exist, 'statuses'=>$statuses));
		}
	}

public function actionUpdateUser()
{
if(isset($_POST['status'])){
		$user_id = $_POST['status'];
					if(isset($_GET['id']))
					{
						$aid = $_GET['id'];
						$Analysis = Analysis::model()->findByPk($aid);
						$tag = Tagging::model()->findByAttributes(
						array('analysisId'=>$Analysis->id)
						);

						if ($tag->status==null){
								$Tagging = new Tagging;
								$Tagging->analysisId = $aid;
								$Tagging->status = 5;
								$Tagging->user_id = $_POST['status'];
						if ($Tagging->save(false))
							{
								$Analysis = Analysis::model()->findByPk($aid);
								Tagging::model()->updateByPk($Analysis->taggingId, 
								array('analysisId'=>$aid, 'user_id'=>$_POST['status'],
								'status'=>$_GET['status'],
								));

								Analysis::model()->updateByPk($aid, 
										array('taggingId'=>$Tagging->id,'user_id'=>$_POST['status']
								));
								echo "Analyst Updated!";
							}		
						}else{
								Tagging::model()->updateByPk($Analysis->taggingId, 
								array('analysisId'=>$aid, 'user_id'=>$_POST['status']
								));

								Analysis::model()->updateByPk($aid, 
										array('user_id'=>$_POST['status']
								));
								echo "Analyst Updated!";
						}
					}			
		}	
}		
public function actionCancelForm()
{  		
		if(isset($_POST['id']))
		{
			$id = $_POST['id'];
			$model=new tagging;
			$model->reason=$_POST['reason'];
			$model->cancelledBy=$_POST['cancelledBy'];
			$model->cancelDate=$_POST['cancelDate'];
			$model->status=3;
			$model->cancelled=1;
			$model->user_id = Yii::app()->user->id;
			if($model->save(false))
			{	
					Analysis::model()->updateByPk($id, 
					array('taggingId'=>$model->id,
					));

					Tagging::model()->updateByPk($model->id, 
					array('analysisId'=>$id,
					));	
					 $sample_code = $_POST["samplecode"];
		 				 echo CJSON::encode(array(
                        'status'=>'exit', 
                        'div'=>"Analysis has been cancelled",
						'sample_code'=>$sample_code,
                        ));
                    exit; 
			}
		}
	}

public function actionCancelAnalysis($id)
{
		$model=new tagging;
		if(isset($_GET['id']))
		{
			$analysisId = $_GET['id'];
			$analysis = Analysis::model()->findByPk($analysisId);

			$sample = Sample::model()->findByAttributes(
			 	array('id'=>$analysis->sample_id)
				);
			}	
			echo CJSON::encode(array(
			'status' => 'form',
			'form'=>$this->renderPartial('_cancelAnalysis', array('model'=>$model, 'analysis'=>$analysis, 'sample'=>$sample, 'analysisId'=>$analysisId),true,true)
		));		
}

public function actionAdmin()
{
		$model=new Sample('search');
		$samplemodel=new Sample('searchbyjaneedi');
		$samplemodel->unsetAttributes(); 
		$model->unsetAttributes();
		if(isset($_GET['Sample']))
		{
		$model->attributes=$_GET['Sample'];
		$samplemodel->attributes=$_GET['Sample'];
		}
		$this->render('admin',array(
			'model'=>$model, 'samplemodel'=>$samplemodel,
		));
}

public function actionCreate()
{
		$model=new Sample;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_GET['id']))
		{
			$requestId = $_GET['id'];
			$request = Request::model()->findByPk($requestId); 	
		}	

		if(isset($_POST['Sample']))
		{
			$model->attributes=$_POST['Sample'];
			
			if(isset($_POST['saveAsTemplate']))
			{
				$sampleName = new Samplename;
				$sampleName->name = $model->sampleName;
				$sampleName->description = $model->description;
				$sampleName->save();
			}
			
			$model->request_id = $requestId;
			$model->rstl_id = Yii::app()->user->rstlId;
			if($model->save()){
				if (Yii::app()->request->isAjaxRequest)
                {
						echo "<pre>";
					print_r($model);
					echo "</pre>";
					echo "save!"; exit();

                    echo CJSON::encode(array(
                        'status'=>'success', 
                        'div'=>"Sample successfully added"
                        ));
                    exit;               
                }
                else
                    $this->redirect(array('view','id'=>$model->id));
			}
		}
		if (Yii::app()->request->isAjaxRequest)
        {
            echo CJSON::encode(array(
                'status'=>'failure',
                'div'=>$this->renderPartial('_form', 
                            array(
                                'model'=>$model, 
                                'requestId'=>$requestId, 
                                'request'=>$request) 
            ,true , true)));
            exit;               
        }else{
            $this->render('create',array('model'=>$model,));
        }
	}

public function actionIndex()
{
		$dataProvider=new CActiveDataProvider('Sample');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
}

public function actionView($id)
{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));	
}

public function loadModel($id)
{
		$model=Sample::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
}

public function actionUpdate($id=NULL)
{
		if(isset($_POST['Sample']['id'])){
			$id=$_POST['Sample']['id'];
		}else{
			if(isset($_POST['id']))
			$id=$_POST['id'];
		}	
		$model=$this->loadModel($id);
		$requestId=$model->request_id;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Sample']))
		{
			$model->attributes=$_POST['Sample'];
			$model->request_id = $requestId;
			if($model->save()){
				if (Yii::app()->request->isAjaxRequest)
				{
					echo CJSON::encode(array(
                        'status'=>'success', 
                        'div'=>"Sample updated"
                        ));
                      
                    exit;    
				}
				else
					$this->redirect(array('view','id'=>$model->id));
			}
		}
		if (Yii::app()->request->isAjaxRequest)
        {
			echo CJSON::encode(array(
                'status'=>'failure',
                'div'=>$this->renderPartial('_form', array('model'=>$model,'requestId'=>$requestId,
				), true, true)));
			
            exit;               
        }else{
			$this->render('update',array('model'=>$model,'requestId'=>$requestId));
        }
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
public function actionDelete($id)
{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
}

public function actionSampleTagging($id)
{
	// $this->layout = 'column';

		$id = $_GET['id'];

		$sampleModel = Sample::model()->findByPk($id);
		$sample_id = $sampleModel->id;
		
		$barcode = $sampleModel->id." ".$sampleModel->sampleYear." ".$sampleModel->sampleCode;
		$sampleCode = $sampleModel->sampleCode;

	
		$profile = Profiles::model()->findByAttributes(
					array('user_id'=>Yii::app()->user->id)
					);
		
		$lab= Lab::model()->findByPk($profile->labId);
		$request_id = $sampleModel->request_id;

				$sample=new CActiveDataProvider('Sample', 
						array(
							'criteria'=>array(
							'condition'=>"id='" .$sampleModel->id."'",
								),
							)
						);	
					// echo "<pre>";
					// print_r( $sample) ;
					// echo "</pre>";
					//  exit();
		
				$analysis=new CActiveDataProvider('Analysis', 
					array(
						'criteria'=>array(
						'condition'=>"sampleCode='" .$sampleModel->sampleCode."' AND sample_id='" .$sampleModel->id."' AND sampleCode LIKE '".$lab->labCode."-%'",
							),
						)
					);

				$taggingcount = Tagging::model()->findAllByAttributes(
						array('status'=>2)
						);

			if ($analysis){
						$completed = count($taggingcount);

						// echo    $this->render('_viewAnalysis', 
					//	array('analysis'=>$analysis, 'sample'=>$sample, 'sample_code'=>$sample_code, 'completed'=>$completed, 'sample_code'=>$sample_code, 'sample_id'=>$sample_id, 'request_id'=>$request_id)  ,true , true);
				
			echo 	$this->render('sampletagging',array(
			'analysisDataProvider'=>$analysis,
			'sample'=>$sample,
		//	'tagging'=>$tagging,
			'barcode'=>$barcode,
			'sampleCode'=>$sampleCode,
			'sample_id'=>$sample_id,
			


		));

				}else {
						echo "<div style='text-align:center;' class='alert alert-error'><i class='icon icon-warning-sign'>
						</i><font style='font-size:14px;'> Sample code not found. </font><br \>";
					  }
	}

	public function actionCalendarEvents()
    {       
        $items = array();
        $model = Tagging::model()->findAll();

        // $model = RequestVehicle::model()->findAll(array("condition"=>"hospitalid = $model->hospitalid"));

        // $userCriteria = new CDbCriteria();
        // $userCriteria->select="req";
        // $userCriteria->condition="`req_path`='RequestVehicle' and `remark`='Approved'";
		// $model=Requests::model()->findAll($userCriteria);



        foreach ($model as $value) {

        	//$model2 = RequestVehicle::model()->findByPk($value->req);
        	// $start = "";
        	// $end = "";
			//$analysis = Analysis::findAllByAttributes("taggingId"=>$value->analysisId);

			$analysis = Analysis::model()->findAllByAttributes(
						array('taggingId'=>$value->analysisId)
						);


        	$days = explode(",",$model2->dates);
    		$start=$value->startDate;
    		$end = $value->endDate;
        	


            $items[]=array(
               // 'title'=>"Driver : ".myhelper::getfullname($model2->driver)." (".$model2->vehicles->name. ") Filed By: ".myhelper::getfullname($model2->user_id),
				  'title'=>"Analyst : ".$value->user_id." Test: Status:".$value->status." ",

                'start'=>date("Y-m-d H:i:s", strtotime($start." 00:00:00")),
                'end'=>date("Y-m-d H:i:s", strtotime($end." 24:00:00")),
                // 'start'=>$start." 00:00:00",
                // 'end'=>$end." 10:00:00",
                'color'=> '#'.substr(md5(rand()), 0, 10),
				// 'color'=> '#212f3d',

                // 'mintime'=> "24:00:00",
                // 'editable'=>true,
                'allDay'=>true,
                //'url'=>'http://anyurl.com'
            );
       }
        echo CJSON::encode($items); 
        Yii::app()->end();
    }

public function actionScheduling()
    {       
        $dataProvider=new CActiveDataProvider('Sample');
		$this->render('scheduling',array(
			'dataProvider'=>$dataProvider,
		));
    }

	public function actionBooking()
    {       
        $dataProvider=new CActiveDataProvider('Sample');
		$this->render('booking',array(
			'dataProvider'=>$dataProvider,
		));
    }

	public function actionWorkloads()
    {       
		$tagging = Analysis::model()->findByPk(251);

		  $analysis=new CActiveDataProvider('Analysis',
	 	array(
			 'criteria'=>array( 'condition'=>'id=0'))
		);
        $dataProvider=new CActiveDataProvider('Sample');
		$this->render('workloads',array(
			'dataProvider'=>$dataProvider,
			'analysisDataProvider'=>$analysis,
			'tagging'=>$tagging,
		));
    }

	public function actionSamplescheduling()
    {       
      	$tagging = Analysis::model()->findByPk(251);

		  $analysis=new CActiveDataProvider('Analysis',
	 	array(
			 'criteria'=>array( 'condition'=>'id=0'))
		);
        $dataProvider=new CActiveDataProvider('Sample');
		$this->render('samplescheduling',array(
			'dataProvider'=>$dataProvider,
			'analysisDataProvider'=>$analysis,
			'tagging'=>$tagging,
		));
    }



}



