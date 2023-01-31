<?php
/* @var $this ReorderstocksController */
/* @var $model Reorderstocks */

$this->breadcrumbs=array(
	'Reorderstocks'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Reorderstocks', 'url'=>array('index')),
	array('label'=>'Create Reorderstocks', 'url'=>array('create')),
	array('label'=>'Update Reorderstocks', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Reorderstocks', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Reorderstocks', 'url'=>array('admin')),
);
?>

<h1>View Reorderstocks #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'supplyID',
		'reorderdate',
		'daterequested',
		'datereceived',
		'supplierID',
		'remarks',
	),
)); ?>
