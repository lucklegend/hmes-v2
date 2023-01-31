<?php
/* @var $this FundingsController */
/* @var $model Fundings */

$this->breadcrumbs=array(
	'Fundings'=>array('index'),
	$model->name=>array('view','id'=>$model->ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Fundings', 'url'=>array('index')),
	array('label'=>'Create Fundings', 'url'=>array('create')),
	array('label'=>'View Fundings', 'url'=>array('view', 'id'=>$model->ID)),
	array('label'=>'Manage Fundings', 'url'=>array('admin')),
);
?>

<h1>Update Fundings <?php echo $model->ID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>