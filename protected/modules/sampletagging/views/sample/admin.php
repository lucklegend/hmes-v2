<?php
/* @var $this SampleController */
/* @var $model Sample */

$this->breadcrumbs=array(
	'Samples'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Sample', 'url'=>array('index')),
	array('label'=>'Create Sample', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#sample-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");




?>
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogSample',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Sample',
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

<h1>Manage Samples</h1>
<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>
<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->


<fieldset class="legend-border">
    <legend class="legend-border">Legend/Status</legend>
   	    <div style="padding: 0 10px">
		<span class="badge badge-default">Pending</span>
    	<span class="badge badge-info">Ongoing</span>
        <span class="badge badge-success">Completed</span>
		<span class="badge badge-danger">Cancelled</span>
    </div>
</fieldset>

<?php
	$profile = Profile::model()->findByAttributes(
					array('user_id'=>Yii::app()->user->id)
					);
	$lab= Lab::model()->findByPk($profile->labId);
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'request-grid',
	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	'rowHtmlOptionsExpression' => 'array("title"=>"Click on Sample Code to view details", "class"=>$data->status["class"])',
	'htmlOptions'=>array('class'=>'grid-view padding0'),
	
	'dataProvider'=>$samplemodel->search($lab->labCode),	
	'filter'=>$samplemodel,
	'columns'=>array(
			array(
 			'name'=>'sampleCode',
 			'type'=>'raw',
 			'value'=>'Chtml::link($data->sampleCode,Yii::app()->Controller->createUrl("sample/sampleTagging",array("id"=>$data->id), array("target"=>"_blank")))',
 			'htmlOptions'=>array('title'=>'Click to view details', 'style'=>'font-weight:bold; width: 150px; text-align:center')
 		),
		array( 
				'name'=>'requestRefNum', 
				'value'=>'$data->request->requestRefNum',
				'htmlOptions' => array('style' => 'width: 200px; text-align: left; padding-left: 10px')
		),
		array( 
				'name'=>'sampleName', 
				'htmlOptions' => array('style' => 'width: 150px; text-align: left; padding-left: 10px;  text-align:center')
		),
			array( 
				'name'=>'description', 
				'htmlOptions' => array('style' => 'width: 350px; text-align: left; padding-left: 10px')
		),
			array( 
				'name'=>'parameters', 
				'value'=>function($data){
					$count = count($data->analyses);
					$i = 1;
					foreach($data->analyses as $analysis)
					{
						echo $analysis->testName;
						if($i < $count)
							echo '<br/>';
						$i++;
					}
				},
				'htmlOptions' => array('style' => 'width: 200px; text-align: left;  padding-left: 10px')
		),
		
	
	 
			 array(
			'name'=>'Status',
			 'value'=> function($data){
				  $sample = Sample::model()->findByPk($data->id);
				  $analysis = Analysis::model()->findAllByAttributes(
						array('sample_id'=>$sample->id)
						);
                  $count_total = count($analysis);
				  $percent = $sample->completed / $count_total * 100;

						if (($percent==100) || ($percent>100)){
							return CHtml::link(
								'<center><span class="label label-success">Completed</span></center>',
								'javascript:void(0)'
						);} else if (($percent<100) && ($percent!=0)) {
								return CHtml::label(
								'<center><span class="badge badge-info">Ongoing</span></center>',
								'javascript:void(0)'
						);}else if ($percent===0) {
								return CHtml::label(
								'<center><span class="badge badge-default">Pending</span></center>',
								'javascript:void(0)'
						);}
 				},
				 'filter'=>false,
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'width: 75px; padding-left: 10px;'),
			),
			array(
					'header'=>'Remarks',
					'type'=>'raw',
					'value'=>'" <b>Request Date:</b> ".$data->request->requestDate." </br>
					<b>Report Due:</b>&nbsp;&nbsp;&nbsp; ".$data->request->reportDue',
					'htmlOptions' => array('style' => 'width: 200px; padding-left: 10px;'),
			),
	),
)); ?>