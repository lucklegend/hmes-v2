<?php
/* @var $this SuppliesController */
/* @var $model Supplies */

$this->breadcrumbs=array(
	'Supplies'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Supplies', 'url'=>array('index')),
	array('label'=>'Create Supplies', 'url'=>array('create')),
	array('label'=>'View Supplies', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Supplies', 'url'=>array('admin')),
);
?>

<h1>Update Supplies <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>