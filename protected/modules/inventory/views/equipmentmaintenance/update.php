<?php
/* @var $this EquipmentmaintenanceController */
/* @var $model Equipmentmaintenance */

$this->breadcrumbs=array(
	'Equipmentmaintenances'=>array('index'),
	$model->ID=>array('view','id'=>$model->ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Equipmentmaintenance', 'url'=>array('index')),
	array('label'=>'Create Equipmentmaintenance', 'url'=>array('create')),
	array('label'=>'View Equipmentmaintenance', 'url'=>array('view', 'id'=>$model->ID)),
	array('label'=>'Manage Equipmentmaintenance', 'url'=>array('admin')),
);
?>

<h1>Update Maintenance <?php echo $model->ID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>