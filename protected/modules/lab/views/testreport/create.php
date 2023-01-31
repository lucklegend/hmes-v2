<?php
/* @var $this TestreportController */
/* @var $model Testreport */

$this->breadcrumbs=array(
	'Testreports'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List Testreport', 'url'=>array('index')),
	array('label'=>'Manage Testreport', 'url'=>array('admin')),
);
?>

<h1>Create Testreport</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'request'=>$request, 'gridDataProvider'=> $gridDataProvider)); ?>