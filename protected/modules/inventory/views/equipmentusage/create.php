<?php
/* @var $this EquipmentusageController */
/* @var $model Equipmentusage */

$this->breadcrumbs=array(
	'Equipmentusages'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Equipmentusage', 'url'=>array('index')),
	array('label'=>'Manage Equipmentusage', 'url'=>array('admin')),
);
?>

<h1>Create Usage</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'item'=>$item)); ?>