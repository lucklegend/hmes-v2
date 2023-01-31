<?php

/*******************************************************
		name: Janeedi A. Grapa
		org: DOST-IX, 991-1024
		date created: May 5, 2017
		description: 

********************************************************/
Yii::app()->clientscript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
$image = CHtml::Image(Yii::app()->theme->baseUrl . '/images/ajax.loader.gif', '');

?>

<?php echo CHtml::hiddenField('countRows',$countdata);?>
<?php
$rew=4;


$this->widget('zii.widgets.grid.CGridView', array(
    	'id'=>'sample-grid',
	    'summaryText'=>false,
		'emptyText'=>'No samples.',
		'htmlOptions'=>array('class'=>'grid-view padding0 paddingLeftRight10'),
		'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
        'dataProvider' =>$sample,
        'columns' => array(
    		array(
				'name'=>'SAMPLE NAME',
				'value'=>'$data->sampleName',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'width: 250px; padding-left: 10px;'),
			),
    		array(
				'name'=>'DESCRIPTION',
                'value'=>
				function ($data){ 
                        if($data->request->labId == 2) 
                            echo '(Sampling Date: <b>'.$data->samplingDate.'</b>) '.$data->description;
                        else
                            echo $data->description;
                    },
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'padding-left: 10px;'),
			),
        ),
    ));
?>
<h4>Testing and Calibration Services</h4>
<?php
	    $this->widget('zii.widgets.grid.CGridView', array(
    	'id'=>'analysis-grid',
		'emptyText'=>'No analyses.',
		'htmlOptions'=>array('class'=>'grid-view padding0 paddingLeftRight10'),
		'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	    'rowHtmlOptionsExpression' => 'array("title" => "Select analysis", "style"=>"cursor:pointer")', 
        'dataProvider' => $analysis,
      	'columns'=>array(
          	array(
			'name'=>"",   
			'value'=>'CHtml::checkBox("cid[]",0,
						array( "onclick"=>"checkvalue();", "value"=>$data->id, 
						"class"=>"chk", 
						"disabled" => ($data->tags->user_id==null) ? "" : ($data->tags->status==6 || $data->tags->status==2 || $data->tags->status==3 || $data->tags->user_id !=Yii::app()->user->id) ? "true" : ""))',
						'type'=>'raw',
						'htmlOptions'=>array('width'=>10, 'style'=>'font-weight:bold;'),
				),
				array(
					'name'=>'testName',
					'type'=>'raw',
					'value'=>'$data->testName',
					'htmlOptions'=>array('style'=>'font-weight:bold;'),
					
			 ),
			'method',
				array(          
				'name'=>'Analyst',
				'type'=>'html',
				'value'=>function($data){
					$profile = Profiles::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
					$lab= Lab::model()->findByPk($profile->labId);
					$code_data=CHtml::listData(Profile::model()->findAll(
						array("condition"=>"labId=".$lab->id."")),
						'user_id',
						'fullname');
                    
					$this->widget('ext.select2.ESelect2',array(
					'attribute'=>'user_id',
					'data'=>$code_data,
					'value'=>$data->user_id,
					'id'=>"myid".$data->id,
					'name'=>"status".$data->id,
					'options'=>array(
							'width'=>'100%',
							'style' => 'padding-left: 10px; align:center;',
							'class'=>'center',
							'placeholder'=>'Analyst',
							),
					'htmlOptions'=>array(
						'disabled'=> Yii::app()->getModule('lab')->isLabAdmin()?false:($data->tags->status==2 || $data->tags->status==3 || ($data->tags->user_id!=Yii::app()->user->id)) ? true:false,
							'onChange'=>CHtml::ajax(array('type'=>'POST',
									'url'=>$this->createUrl('sample/updateUser',array('id'=>$data->id, 'status'=>$data->tags->status)),
									'data'=> "js:'&status='+$(this).val()",
									'type'=>'post',
									'success'=>'function(data)
									{
									alert(data);
									//	$("#barcode").val(data.sample_code);
										$("#scanbarcode").click();
									}',
									'error'=>'function(data)
									{
										alert("Contact administrator : error cause "+data);
									}', 
									)),
							),
					));
				}
			),
			array(
					'header'=>'ISO accredited',
					'type'=>'raw',
					'value'=>'CHtml::checkBox("iso[]",$data->tags->isoAccredited,
						array( "onclick"=>"checkvalue();", "value"=>$data->id, 
						//"class"=>"chk", 
							"disabled" => ($data->tags->user_id==null) ? "" : ($data->tags->status==6 || $data->tags->status==2 || $data->tags->status==3 || $data->tags->status==1 || $data->tags->user_id !=Yii::app()->user->id) ? "true" : ""))',
						'type'=>'raw',
						'htmlOptions'=>array('width'=>'10px', 'style'=>'text-align:center'),
			),
				array(
				'header' =>'Status',
				'type'=>'raw',
				'filter'=>false,
				'value'=> function($data){
					$tagStatus=$data->tags->status;
					$cancelled=$analysis->cancelled;
					if ($tagStatus==null){
						return CHtml::label(
							'<center><span class="badge badge-default">Pending</span></center>',
							'javascript:void(0)'	
					);} else if ($tagStatus==1) {
							return CHtml::label(
							'<center><span class="badge badge-info">Ongoing</span></center>',
							'javascript:void(0)'	
					);}else if ($tagStatus==2) {
							return CHtml::label(
							'<center><span class="badge badge-success">Completed</span></center>',
							'javascript:void(0)'	
					);}
					else if ($tagStatus==3) {
							return CHtml::label(
							'<center><span class="badge badge-danger">Cancelled</span></center>',
							'javascript:void(0)',
							array(
 							'onclick'=>"js:{ viewAnalysis({$data['id']}); $('#dialogCancelledAnalysis').dialog('open');}",
 						)		
					);}
					else if ($tagStatus==4) {
							return CHtml::label(
							'<center><span class="badge badge-warning">Uncancelled</span></center>',
							'javascript:void(0)'	
					);}
					else if ($tagStatus==5) {
							return CHtml::label(
							'<center><span class="badge badge-warning">Assigned</span></center>',
							'javascript:void(0)'	
					);}
					else if ($tagStatus==9) {
							return CHtml::label(
						'<center><span class="badge badge-success">Completed</span></center>',
							'javascript:void(0)'	
					);}
					},
		 ),
		//  array(
		//     'header'=>'No.',
		//    	'value'=> '$this->grid->dataProvider->getTotalItemCount();',
		// 	'visible'=>'false',
		
		//   ),
			array(
					'name'=>'Remarks',
					'type'=>'raw',
					'value'=>'" <b>Start Date:</b> ".$data->tags->startDate." </br>
					<b>End Date:</b>&nbsp;&nbsp;&nbsp; ".$data->tags->endDate',
			),
    // array(
	// 	'header'=>'Actions', 
	// 	'class'=>'bootstrap.widgets.TbButtonColumn',
	// 	'template'=> '{cancel}{uncancel}',
	// 	'visible' => ((Yii::app()->getModule('lab')->isLabAdmin() || Yii::app()->getModule('lab')->isLabAnalyst() ) ? true : false),
	// 	'buttons'=>array
	// 	(
	// 	'cancel' => array (
	// 			'label'=>'Cancel',
	// 			'url'=>'Yii::app()->createUrl("sampletagging/sample/cancelAnalysis/id/$data->id")', 
	// 			'htmlOptions'=>array('style'=>'text-align:center;width:120px;'),
	// 			'visible'=>'(($data->tags->status==null) || ($data->tags->status==2) ||  ($data->tags->status==3) || ($data->tags->status==4)  || ($data->tags->status==5) || ($data->tags->status==6) || ($data->tags->status==6) || ($data->tags->user_id) !=Yii::app()->user->id) ? false:true',
	// 			'options' => array (
	// 			'onclick'=>"js:{ $('#dialogCancel').dialog('open');}",
	// 				'ajax' => array (
	// 					'type' => 'post',
	// 					'dataType'=>'json',
	// 					'data'=> 'js:{"samplecode":$("#barcode").val()}',
	// 					'url'=>'js:$(this).attr("href")', 
	// 					'success'=>"function(data){
	// 							if (data.status=='form'){
	// 							//BAKIT NAG REREFRESH???
	// 							$('#dialogCancel').html(data.form);

	// 							}else{
									
	// 							}
								
	// 						}",
	// 					'beforeSend'=>'function(jqXHR, settings){
    //                 $("#dialogAnalyst").html(
	// 					\'<div class="loader">'.$image.'<br\><br\>Retrieving record.<br\> Please wait...</div>\'
	// 				);
    //         }',
	// 		 'error'=>"function(request, status, error){
	// 			 	$('#dialogAnalyst').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
	// 				}",
	// 				)	
	// 			),
	// 		),
	// 		'uncancel' => array (
	// 			'label'=>'Uncancel',
	// 			'url'=>'Yii::app()->createUrl("sampletagging/sample/uncancelAnalysis", array("id"=>$data->id, "tid"=>$data->taggingId))',
	// 			'visible'=>'(($data->tags->status=="0") || ($data->tags->status=="1") ||  ($data->tags->status=="2") || ($data->tags->status=="4") || ($data->tags->status=="6") || ($data->tags->status==null))  ? false:false',
	// 			'options' => array (
	// 				'ajax' => array (
	// 					 'type' => 'post',
	// 					 'dataType'=>'json',
	// 					 'data'=> 'js:{"samplecode":$("#barcode").val()}',
	// 					 'url'=>'js:$(this).attr("href")', 
	// 					 'success'=>"function(data){
	// 							if (data.status=='success'){
	// 							 $('#barcode').val(data);
	// 							 $('#scanbarcode').click();
	// 							 }
	// 							}",
	// 				)
					
	// 			),
	// 		),	
	// 	),
	// 	),


		),
    ));
   ?>
<div class="row-fluid">		                              
 <?php
$countdata = $analysis->getItemCount();
if ($countdata===0){
echo '<script language="javascript">';
echo 'alert("Scan a valid sample barcode.");';  
echo '</script>';
?>
<script>
$('#barcode').val('');
</script>
<?php
exit;
}
?>

 <?php




	$sample = Sample::model()->findByPk($sample_id);
	$analysis = Analysis::model()->findByAttributes(
							array('sample_id'=>$sample_id)						);
	$tagging = Tagging::model()->findByAttributes(
							array('id'=>$analysis->taggingId)
							);
	$request = Request::model()->findByPk($request_id);

	$c = $sample->completed;
	$c = $sample->completed;
	$t = $tagging->status;


	if ($c==$countdata){
		if ($t==2){
			Request::model()->updateByPk($sample->request_id, 
							array('completed'=>$request->completed + 1 ,
							));	

			Sample::model()->updateByPk($sample_id, 
							array('completed'=>$sample->completed + 1 ,
							));		
		}
	}
	?>
<?php
//  $retransfer =  Chtml::link(
// 	'<span class="icon-white icon-pencil"></span> Transfer Analyst', 
// 	 '', 
// 	  array( 
// 	//	  'confirm'=>'Are you sure you want to transfer analysis?',
// 			'style'=>'cursor:pointer;',
// 			'class'=>'btn btn-primary',
// 			//'onclick'=>"alert($('#analysis-grid').yiiGridView('getChecked', 'cid'));",
// 			//'onclick'=>'';
// 			'onclick'=>"js:{ transferAnalysis($('#analysis-grid').yiiGridView('getChecked', 'cid')); $('#dialogAnalyst').dialog('open');}",
// 			));
// $transfer =  Chtml::link(
// 	'<span class="icon-white icon-pencil"></span> Assign Analyst', 
// 	 '', 
// 	  array( 
// 	//	  'confirm'=>'Are you sure you want to transfer analysis?',
// 			'style'=>'cursor:pointer;',
// 			'class'=>'btn btn-primary',
// 			//'onclick'=>"alert($('#analysis-grid').yiiGridView('getChecked', 'cid'));",
// 			//'onclick'=>'';
// 			'onclick'=>"js:{ transferAnalysis($('#analysis-grid').yiiGridView('getChecked', 'cid')); $('#dialogAnalyst').dialog('open');}",
// 			));
// 		echo (Yii::app()->getModule('lab')->isLabAdmin())  ? $transfer : $retransfer ;
// 		//echo ($countdata ===0) ? '' : $transfer;
?>	
<?php
 $startAnalysis =  Chtml::link(
	'<span class="icon-white icon-tags"></span> Start Analysis', 
	 '', 
	  array( 
		  //	'confirm'=>'Are you sure you want to start analysis?',
		  	'style'=>'cursor:pointer;',
			'class'=>'btn btn-primary',
			'style'=>'cursor:pointer;',
			'onClick'=>'startAnalysis();',			
			));
			echo ($countdata ===0) ? '' : $startAnalysis;  ?>
    
<script>
function startAnalysis()
{
    <?php echo CHtml::ajax(array(
            'url'=>array('/sampletagging/sample/tagAnalysis'),
            'data'=> 'js: { "xyz":$("#analysis-grid").yiiGridView("getChecked", "cid").toString(),  "samplecode":$("#barcode").val(), "iso":$("#analysis-grid").yiiGridView("getChecked", "iso").toString(),}',
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data){
				if (data.message != null){
					if (data.message){		
						alert(data.message);
						//alert('Hello again! This is how we' +'\\n' +'add line breaks to an alert box!');
					}
					$('#barcode').val(data.sample_code);
					$('#scanbarcode').click();

					//$('#Analysis_user_id').val('13210 2017 CHE-0004');
				}else {
					$('#barcode').val(data.sample_code);
					$('#scanbarcode').click();
				}
			}",
			'error'=>"function(request, status, error){
				 alert(error);
			}"
	))
?>
}
function forTransferAnalysis()
{
    <?php echo CHtml::ajax(array(
            'url'=>array('/sampletagging/sample/forTransferAnalysis'),
            'data'=> 'js: { "xyz":$("#analysis-grid").yiiGridView("getChecked", "cid").toString(),  "samplecode":$("#barcode").val()}',
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data){
				if (data.message != null){
					if (data.message){	
						alert(data.message);		
					}
					$('#barcode').val(data.sample_code);
					$('#scanbarcode').click();
				}else {
					$('#barcode').val(data.sample_code);
					$('#scanbarcode').click();
				}
			}",
			'error'=>"function(request, status, error){
				 alert(error);
			}"
	))
?>
}
function viewAnalysis(id)
{
	<?php 
	echo CHtml::ajax(array(
			'url'=>$this->createUrl('/sampletagging/request/cancelledAnalysis'),
			'data'=> "js:$(this).serialize()+ '&id='+id",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
            	$('#dialogCancelledAnalysis').html(data.div);
            }",
			'beforeSend'=>'function(jqXHR, settings){		
                    $("#dialogCancelledAnalysis").html(
						\'<div class="loader"><br\><br\>Retrieving record.<br\> Please wait...</div>\'
					);
            }',
			 'error'=>"function(request, status, error){
					
				 	$('#dialogCancelledAnalysis').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
					}",
            ))?>;
    return false; 
}
function checkall()
{
	var chk = $('#cid').prop('checked');
	$('.chk').each(function(i, obj) {
	$(obj).prop('checked', chk);
	});
}
function checkvalue()
{
	// $('.chkheader').each(function(i, obj) {
	// $(obj).prop('checked');
	// });
}
function endAnalysis()
{
    <?php echo CHtml::ajax(array(
            'url'=>array ('/sampletagging/sample/tagAnalysisEnd'),
            'data'=> 'js: { "xyz":$("#analysis-grid").yiiGridView("getChecked", "cid").toString(), "samplecode":$("#barcode").val()}',
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data){
				//put here if data.status == null hindi sya mag execute!!!
					
					
					if (data.message != null){
						if (data.message){
							alert(data.message);
						}

					$('#barcode').val(data.sample_code);
					$('#scanbarcode').click();
				}else if (data.message == null){
					$('#barcode').val(data.sample_code);
					$('#scanbarcode').click();
				}
				 }",
			'error'=>"function(request, status, error){
				 alert(error);
			}"
	))
?>
}
function transferAnalysis(id)
{
    <?php echo CHtml::ajax(array(
            'url'=>array('/sampletagging/request/transferAnalysis'),
            'data'=> "js:$(this).serialize()+ '&id='+id",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {	
				if (data.status == 'failure')
                {
					$('#dialogAnalyst').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                //    $('#dialogAnalyst form').submit(addAnalysis);
                }
				else if (data.status == 'exit')
				{
					setTimeout(\"$('#dialogAnalyst').dialog('close') \",1000);
				}
				else{
					$('#dialogAnalyst').html(data);
				}
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#dialogAnalyst").html(
						\'<div class="loader">'.$image.'<br\><br\>Retrieving record.<br\> Please wait...</div>\'
					);
            }',
			 'error'=>"function(request, status, error){
				 	$('#dialogAnalyst').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
					}",
            )	
			)?>;
    return false; 
}
function cancelAnalysis(id)
{
    <?php echo CHtml::ajax(array(
			'url'=>$this->createUrl('sample/cancelAnalysis'),
			'data'=> "js:$(this).serialize()+ '&id='+id",
            'type'=>'post',
            'success'=>"function(data)
            {	
                    $('#dialogCancel').html(data);
                    // Here is the trick: on submit-> once again this function!
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#dialogCancel").html(
						\'<div class="loader">'.$image.'<br\><br\>Processing...<br\> Please wait...</div>\'
					);
             }',
			 'error'=>"function(request, status, error){
				 	$('#dialogCancel').html(status+'('+error+')'+': '+ request.responseText );
					}",
            ))?>;
    return false; 
}
</script>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php
$completed = Chtml::link(
	'<span class="icon-white icon-ok"></span> Completed', 
	 '', 
	  array( 
		 //'confirm'=>'Are you sure you want to tag analysis as completed?',
			'style'=>'cursor:pointer;',
			'class'=>'btn btn-primary',
			//'onclick'=>"js:{ transferAnalysis($('#analysis-grid').yiiGridView('getChecked', 'cid')); $('#dialogAnalyst').dialog('open');}",
			'onClick'=>'js:{endAnalysis()}'
			));
			echo ($countdata ===0)   ? '' : $completed;
?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php
// $completed = Chtml::link(
// 	'<span class="icon-white icon-trash"></span> Dispose', 
// 	 '', 
// 	 	  array( 
// 			'style'=>'cursor:pointer;',
// 			'id'=>'scanbarcode',
// 			'class'=>'btn btn-primary',
// 			'onclick'=>"js:{ transferAnalysis($('#analysis-grid').yiiGridView('getChecked', 'cid')); $('#dialogAnalyst').dialog('open');}",
// 			));
// 			echo ($countdata ===0) ? '' : $completed;
?>
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogAnalyst',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Transfer Analysis',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>360,
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));
	$this->endWidget('zii.widgets.jui.CJuiDialog');

	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogCancel',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Cancel Analysis',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>'auto',
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));
	$this->endWidget('zii.widgets.jui.CJuiDialog');

	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogCancelledAnalysis',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Cancel Analysis',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>auto,
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));
	$this->endWidget('zii.widgets.jui.CJuiDialog');	
 ?>
</div>
 