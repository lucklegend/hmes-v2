<?php
/* @var $this EquipmentclassificationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Equipmentclassifications',
);

$this->menu=array(
	array('label'=>'Create Equipmentclassification', 'url'=>array('create')),
	array('label'=>'Manage Equipmentclassification', 'url'=>array('admin')),
);
?>

<h1>Equipmentclassifications</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
