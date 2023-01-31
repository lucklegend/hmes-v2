<?php
/* @var $this ConsumptionsController */
/* @var $model Consumptions */

$this->breadcrumbs=array(
	'Consumptions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Consumptions', 'url'=>array('index')),
	array('label'=>'Manage Consumptions', 'url'=>array('admin')),
);
?>

<h1>Create Consumptions</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>