<?php
/* @var $this ConsumptionsController */
/* @var $model Consumptions */

$this->breadcrumbs=array(
	'Consumptions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Consumptions', 'url'=>array('index')),
	array('label'=>'Create Consumptions', 'url'=>array('create')),
	array('label'=>'Update Consumptions', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Consumptions', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Consumptions', 'url'=>array('admin')),
);
?>

<h1>View Consumptions #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'stockID',
		'balance',
		'amountused',
		'dateconsumed',
		'withdrawnby',
		'remarks',
	),
)); ?>
