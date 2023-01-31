<?php
/* @var $this ConsumptionsController */
/* @var $model Consumptions */

$this->breadcrumbs=array(
	'Consumptions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Consumptions', 'url'=>array('index')),
	array('label'=>'Create Consumptions', 'url'=>array('create')),
	array('label'=>'View Consumptions', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Consumptions', 'url'=>array('admin')),
);
?>

<h1>Update Consumptions <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>