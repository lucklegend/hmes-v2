<?php
/* @var $this EquipmentcalibrationController */
/* @var $model Equipmentcalibration */

$this->breadcrumbs=array(
	'Equipmentcalibrations'=>array('index'),
	$model->ID=>array('view','id'=>$model->ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Equipmentcalibration', 'url'=>array('index')),
	array('label'=>'Create Equipmentcalibration', 'url'=>array('create')),
	array('label'=>'View Equipmentcalibration', 'url'=>array('view', 'id'=>$model->ID)),
	array('label'=>'Manage Equipmentcalibration', 'url'=>array('admin')),
);
?>

<h1>Update Calibration <?php echo $model->ID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>