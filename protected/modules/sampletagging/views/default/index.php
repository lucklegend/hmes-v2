
<?php 
/*******************************************************
		name: Janeedi A. Grapa
		org: DOST-IX, 991-1024
		date created: April 18, 2017
		description: 

********************************************************/
?>
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

<h1>Sample Tagging</h1>

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
<div class="alert alert-success alert-dismissable">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Note: </strong>Please scan barcode in the dropdown list below.
</div>

	<div class="form">
			<?php echo CHtml::beginForm(); ?>
			<div class="row">
				<?php echo CHtml::label('',"");?> 
				<?php echo CHtml::hiddenField('barcode',"");?>

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
		</div>
 <?php

Yii::app()->clientScript->registerScript('select2Event', "
$('#Analysis_user_id').on('change',function(){
	var v = $('#Analysis_user_id').select2('data').text;

	$('#barcode').val(v);




});
");
 
$profile = Profiles::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
$lab= Lab::model()->findByPk($profile->labId);

$year = date('Y');
$code_data=CHtml::listData(Sample::model()->findAll(
	array("condition"=>'sampleCode LIKE "'.$lab->labCode.'-%" AND sampleYear='.$year)),
	  'QRcode', 'QRcode');

          $this->widget('ext.select2.ESelect2',array(
                 'model'=>$tagging,
                 'attribute' =>'user_id',
                 'data'=>$code_data,
                 'options'  => array(
                 'placeholder'=>'Scan Barcode',
                 'width'=>'250',
                 ),'htmlOptions'=>array(
							'onChange'=>CHtml::ajax(array('type'=>'POST',
									'url'=>$this->createUrl('/sampletagging/sample/getAnalysis'),
									 'data'=> 'js: {"barcode": $("#Analysis_user_id").val()}',
									'type'=>'post',
									'success'=>'function(data)
									{

										$("#xyz").html(data);
									}',
									'error'=>'function(data)
									{
										alert("Contact administrator : error cause "+data);
									}', 
									)),
							),
          ));

      ?>



<div class="row-fluid" id ="xyz">
<?php   $this->widget('zii.widgets.grid.CGridView', array(
    	'id'=>'sample-grid',
	    'summaryText'=>false,
		'emptyText'=>'No samples.',
		'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
		'rowHtmlOptionsExpression' => 'array("title" => "Click to update", "class"=>"link-hand")', 
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
		'rowHtmlOptionsExpression' => 'array("title" => "Click to update", "style"=>"cursor:pointer")', 
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
				'name' =>'Status',
			),
				array(
					'name'=>'Remarks',
					),  
		),
    ));
    ?>
</div>


















