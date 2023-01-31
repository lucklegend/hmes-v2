<?php
/* @var $this FundingsController */
/* @var $model Fundings */

$this->breadcrumbs=array(
	'Fundings'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Fundings', 'url'=>array('index')),
	array('label'=>'Create Fundings', 'url'=>array('create')),
	array('label'=>'Update Fundings', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete Fundings', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Fundings', 'url'=>array('admin')),
);
?>

<h1>View Fundings #<?php echo $model->ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'name',
		'code',
	),
)); ?>
