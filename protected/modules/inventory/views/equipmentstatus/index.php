<?php
/* @var $this EquipmentstatusController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Equipmentstatuses',
);

$this->menu=array(
	array('label'=>'Create Equipmentstatus', 'url'=>array('create')),
	array('label'=>'Manage Equipmentstatus', 'url'=>array('admin')),
);
?>

<h1>Equipmentstatuses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
