<?php
/* @var $this StocksController */
/* @var $model Stocks */

$this->breadcrumbs=array(
	'Stocks'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Stocks', 'url'=>array('index')),
	array('label'=>'Manage Stocks', 'url'=>array('admin')),
);
?>

<h1>Create Stock</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>