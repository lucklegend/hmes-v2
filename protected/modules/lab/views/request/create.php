<?php
/* @var $this RequestController */
/* @var $model Request */

$this->breadcrumbs=array(
	'Requests'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Request', 'url'=>array('admin')),
);
?>

<h1>Create Request</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>