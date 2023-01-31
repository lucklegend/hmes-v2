<?php
/* @var $this EquipmentmaintenanceController */
/* @var $model Equipmentmaintenance */

$this->breadcrumbs=array(
	'Equipmentmaintenances'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Equipmentmaintenance', 'url'=>array('index')),
	array('label'=>'Manage Equipmentmaintenance', 'url'=>array('admin')),
);
?>

<h1>Create Maintenance</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'item'=>$item)); ?>