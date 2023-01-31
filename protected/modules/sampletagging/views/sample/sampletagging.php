
<?php 
/*******************************************************
		name: Janeedi A. Grapa
		org: DOST-IX, 991-1024
		date created: April 18, 2017
		description: 

********************************************************/
?>

<?php echo CHtml::beginForm(); ?>
		
				<?php echo CHtml::label('',"");?> 
				<?php echo CHtml::hiddenField('barcode',$barcode);?>

			<?php echo CHtml::ajaxSubmitButton(
			   	'Scan Barcode',
			   		 Yii::app()->createUrl('/sampletagging/sample/getAnalysis'),
			   		 array(
           				   'type'=>'post',
						   'success'=>'js:function(data){
										/// $("#xyz").html(data.status);
										$("#xyz").html(data);
		  	 						  }'),
									
			    	array(
			        'class'=>'btn btn-success',
					'style'=>'display:none',
					'id'=>'scanbarcode',
			   			 )
						
			 );?>
		
			 <?php echo CHtml::endForm(); ?>
<?php
		$this->beginWidget('zii.widgets.jui.CJuiDialog', array (
		'id'=>'dialogTransfer',
		'options'=>array(
			'title'=>'Transfer Analysis',
			'show'=>'scale',
			'hide'=>'scale',
			'width'=>350,
			'modal'=>true,
			'resizable'=>false,
			'autoOpen'=>false,
		)
	));
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<h1>View Sample Details:<?php echo $sampleCode; ?></h1>

<fieldset class="legend-border">
    <legend class="legend-border">Legend/Status</legend>
   	    <div style="padding: 0 10px">
		<span class="badge badge-default">Pending</span>
    	<span class="badge badge-info">Ongoing</span>
        <span class="badge badge-success">Completed</span>
		<span class="badge badge-warning">Assigned</span> 
		<span class="badge badge-danger">Cancelled</span>
    </div>
</fieldset>
<div class="form">


 <?php

Yii::app()->clientScript->registerScript('select2Event', "
$('#Analysis_user_id').on('change',function(){
	var v = $('#Analysis_user_id').select2('data').text;

	$('#barcode').val(v);

});
");
      ?>

<div class="row-fluid" id ="xyz">
<?php   
$this->widget('zii.widgets.grid.CGridView', array(
    	'id'=>'sample-grid',
	    'summaryText'=>false,
		'emptyText'=>'No samples.',
		'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
        'dataProvider' =>$sample,
        'columns' => array(
    		array(
				'header'=>'Sample Name',
				'value'=>'$data->sampleName',
			),
    		array(
				'header'=>'Description',
                'value'=>
				function ($data){ 
                        if($data->request->labId == 2) 
                            echo '(Sampling Date: <b>'.$data->samplingDate.'</b>) '.$data->description;
                        else
                            echo $data->description;
                    },
			),
        ),
    ));
?>
<h4>Testing and Calibration Services</h4>
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
    	'id'=>'analysis-grid',
		'emptyText'=>'No analysis.',
		'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
		'rowHtmlOptionsExpression' => 'array("title" => "Select analysis", "style"=>"cursor:pointer")', 
        'dataProvider' => $analysisDataProvider,
		'columns'=>array(
				array(
				'name'=>"",   
				'value'=>'CHtml::checkBox("cid[]",0,
						array("value"=>$data->id, 
						"class"=>"chk", 
						"disabled" => ($data->tags->user_id==null) ? "" : ($data->tags->status==6 || $data->tags->status==2 || $data->tags->status==3 || $data->tags->user_id !=Yii::app()->user->id) ? "true" : ""))',
						'type'=>'raw',
						'htmlOptions'=>array('width'=>10, 'style'=>'font-weight:bold;'),
				),
				'testName',
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
				array(
					'name'=>'Remarks',
					'type'=>'raw',
					'value'=>'" <b>Start Date:</b> ".$data->tags->startDate." </br>
					<b>End Date:</b>&nbsp;&nbsp;&nbsp; ".$data->tags->endDate',
			),
		),
    ));
    ?>

<div class="row-fluid">		                              
 

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

function endAnalysis()
{
    <?php echo CHtml::ajax(array(
            'url'=>array ('/sampletagging/sample/tagAnalysisEnd'),
            'data'=> 'js: { "xyz":$("#analysis-grid").yiiGridView("getChecked", "cid").toString(), "samplecode":$("#barcode").val()}',
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data){
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
			echo ($countdata ===0)  ? '' : $completed;
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



















