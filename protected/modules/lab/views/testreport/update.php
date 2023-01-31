<?php
/* @var $this TestreportController */
/* @var $model Testreport */

$this->breadcrumbs=array(
	'Testreports'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Testreport', 'url'=>array('index')),
	array('label'=>'Create Testreport', 'url'=>array('create')),
	array('label'=>'View Testreport', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Testreport', 'url'=>array('admin')),
);
?>

<h1>Update Testreport <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>