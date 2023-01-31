<?php
/* @var $this EquipmentcalibrationController */
/* @var $model Equipmentcalibration */

$this->breadcrumbs=array(
	'Equipmentcalibrations'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Equipmentcalibration', 'url'=>array('index')),
	array('label'=>'Manage Equipmentcalibration', 'url'=>array('admin')),
);
?>

<h1>Create Calibration</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'item'=>$item)); ?>