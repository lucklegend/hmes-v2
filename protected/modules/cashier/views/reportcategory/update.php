<?php
/* @var $this ReportcategoryController */
/* @var $model Reportcategory */

$this->breadcrumbs=array(
	'Reportcategories'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Reportcategory', 'url'=>array('index')),
	array('label'=>'Create Reportcategory', 'url'=>array('create')),
	array('label'=>'View Reportcategory', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Reportcategory', 'url'=>array('admin')),
);
?>

<h1>Update Reportcategory <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>