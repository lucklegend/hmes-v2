<?php
/* @var $this ReorderstocksController */
/* @var $model Reorderstocks */

$this->breadcrumbs=array(
	'Reorderstocks'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Reorderstocks', 'url'=>array('index')),
	array('label'=>'Create Reorderstocks', 'url'=>array('create')),
	array('label'=>'View Reorderstocks', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Reorderstocks', 'url'=>array('admin')),
);
?>

<h1>Update Reorderstocks <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>