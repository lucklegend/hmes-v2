<?php
/* @var $this RequestController */
/* @var $model Request */

$this->breadcrumbs=array(
	'Requests'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Request', 'url'=>array('index')),
	array('label'=>'Manage Request', 'url'=>array('admin')),
);
?>

<h1>Add On-site Charge</h1>

<?php $this->renderPartial('formadditional', array('model'=>$model, 'datas'=>$datas)); ?>