<?php
/* @var $this EquipmentusageController */
/* @var $model Equipmentusage */

$this->breadcrumbs=array(
	'Equipmentusages'=>array('index'),
	$model->ID,
);

$this->menu=array(
	array('label'=>'List Equipmentusage', 'url'=>array('index')),
	array('label'=>'Create Equipmentusage', 'url'=>array('create')),
	array('label'=>'Update Equipmentusage', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete Equipmentusage', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Equipmentusage', 'url'=>array('admin')),
);
?>

<h1>View Usage #<?php echo $model->ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'equipmentID',
		'status',
		'startdate',
		'enddate',
		'remarks',
	),
)); ?>
