<?php
/* @var $this EquipmentstatusController */
/* @var $model Equipmentstatus */

$this->breadcrumbs=array(
	'Equipmentstatuses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Equipmentstatus', 'url'=>array('index')),
	array('label'=>'Manage Equipmentstatus', 'url'=>array('admin')),
);
?>

<h1>Create Equipmentstatus</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>