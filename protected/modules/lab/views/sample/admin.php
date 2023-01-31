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

<h1>Manage Samples</h1>

<!--p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p-->

<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<fieldset class="legend-border">
    <legend class="legend-border">Filter</legend>
    <div style="padding: 0 10px">
    	<div class="switch-toggle switch-candy" style="width: 60%">
		  <?php
			$badge = array(
				'1'=>'badge-warning', 
				'2'=>'badge-success', 
				'3'=>'badge-inverse'
			);
			$labArray = Initializecode::listLabName(); 
			foreach($labArray as $key => $value)
			{
				echo '<input id="'.$badge[$key].'" name="view" type="radio" checked>';
		  		echo '<label for="'.$badge[$key].'" onclick="$(\'#Sample_lab_search\').val('.$key.'); $(\'.search-form form\').submit()">'.$value.'</label>';
			}
		  ?>
		  <a></a>
		</div>
    </div>
</fieldset>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sample-grid',
	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	'htmlOptions'=>array('class'=>'grid-view padding0'),
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'sampleCode',
		array( 
				'name'=>'sampleCode', 
				'htmlOptions' => array('style' => 'width: 70px; text-align: left; padding-left: 10px')
		),
		//'sampleName',
		array( 
				'name'=>'sampleName', 
				'htmlOptions' => array('style' => 'width: 175px; text-align: left; padding-left: 10px')
		),
		array( 
				'name'=>'requestRefNum', 
				'value'=>'$data->request->requestRefNum',
				'htmlOptions' => array('style' => 'width: 125px; text-align: left; padding-left: 10px')
		),
		array( 
				'name'=>'requestDate', 
				'value'=>'$data->request->requestDate',
				'htmlOptions' => array('style' => 'width: 100x; text-align: center;')
		),	
		array( 
				'name'=>'reportDue', 
				'value'=>'$data->request->reportDue',
				'htmlOptions' => array('style' => 'width: 75px; text-align: center;')
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
				'name'=>'dateAnalyzed', 
				'value'=>'$data->request->reportDue',
				'htmlOptions' => array('style' => 'width: 75px; text-align: center;')
		),
		array( 
				'name'=>'reportDue', 
				'value'=>'$data->request->reportDue',
				'htmlOptions' => array('style' => 'width: 75px; text-align: center;')
		),
		array( 
				'name'=>'reportDue', 
				'value'=>'$data->request->reportDue',
				'htmlOptions' => array('style' => 'width: 75px; text-align: center;')
		),
		//'description',
		//'remarks',
		//'requestId',
		/*
		'request_id',
		'sampleMonth',
		'sampleYear',
		'cancelled',
		*/
		/*array(
			'class'=>'CButtonColumn',
			'htmlOptions' => array('style' => 'width: 80px; text-align: center;')
		),*/
	),
)); ?>