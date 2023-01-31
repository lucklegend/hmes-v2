<?php
/* @var $this EquipmentmaintenanceController */
/* @var $model Equipmentmaintenance */

$this->breadcrumbs=array(
	'Equipmentmaintenances'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Equipmentmaintenance', 'url'=>array('index')),
	array('label'=>'Create Equipmentmaintenance', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#equipmentmaintenance-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Maintenances</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'equipmentmaintenance-grid',
	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		// 'ID',
		'user_id',
		'equipmentID',
		'date',
		'type',
		'isdone',
		/*
		'maintenancedata',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
