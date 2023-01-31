<?php
/* @var $this EquipmentclassificationController */
/* @var $model Equipmentclassification */

$this->breadcrumbs=array(
	'Equipmentclassifications'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Equipmentclassification', 'url'=>array('index')),
	array('label'=>'Manage Equipmentclassification', 'url'=>array('admin')),
);
?>

<h1>Create Equipmentclassification</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>