<?php
/* @var $this EquipmentmaintenanceController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Equipmentmaintenances',
);

$this->menu=array(
	array('label'=>'Create Equipmentmaintenance', 'url'=>array('create')),
	array('label'=>'Manage Equipmentmaintenance', 'url'=>array('admin')),
);
?>

<h1>Equipmentmaintenances</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
