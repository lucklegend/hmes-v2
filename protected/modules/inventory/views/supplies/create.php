<?php
/* @var $this SuppliesController */
/* @var $model Supplies */

$this->breadcrumbs=array(
	'Supplies'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Supplies', 'url'=>array('index')),
	array('label'=>'Manage Supplies', 'url'=>array('admin')),
);
?>

<h1>Create Supply</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>