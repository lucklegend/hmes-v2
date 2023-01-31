<?php
/* @var $this EquipmentstatusController */
/* @var $model Equipmentstatus */

$this->breadcrumbs=array(
	'Equipmentstatuses'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Equipmentstatus', 'url'=>array('index')),
	array('label'=>'Create Equipmentstatus', 'url'=>array('create')),
	array('label'=>'Update Equipmentstatus', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete Equipmentstatus', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Equipmentstatus', 'url'=>array('admin')),
);
?>

<h1>View Equipmentstatus #<?php echo $model->ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'name',
		'description',
	),
)); ?>
