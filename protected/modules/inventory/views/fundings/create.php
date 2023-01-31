<?php
/* @var $this FundingsController */
/* @var $model Fundings */

$this->breadcrumbs=array(
	'Fundings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Fundings', 'url'=>array('index')),
	array('label'=>'Manage Fundings', 'url'=>array('admin')),
);
?>

<h1>Create Fundings</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>