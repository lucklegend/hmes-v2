<?php
/* @var $this ReorderstocksController */
/* @var $model Reorderstocks */

$this->breadcrumbs=array(
	'Reorderstocks'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Reorderstocks', 'url'=>array('index')),
	array('label'=>'Manage Reorderstocks', 'url'=>array('admin')),
);
?>

<h1>Create Reorderstocks</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>