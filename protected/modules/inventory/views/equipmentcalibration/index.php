<?php
/* @var $this EquipmentcalibrationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Equipmentcalibrations',
);

$this->menu=array(
	array('label'=>'Create Equipmentcalibration', 'url'=>array('create')),
	array('label'=>'Manage Equipmentcalibration', 'url'=>array('admin')),
);
?>

<h1>Calibrations</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
