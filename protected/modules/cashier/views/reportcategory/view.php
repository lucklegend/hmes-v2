<?php
/* @var $this ReportcategoryController */
/* @var $model Reportcategory */

$this->breadcrumbs=array(
	'Reportcategories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Reportcategory', 'url'=>array('index')),
	array('label'=>'Create Reportcategory', 'url'=>array('create')),
	array('label'=>'Update Reportcategory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Reportcategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Reportcategory', 'url'=>array('admin')),
);
?>

<h1>View Reportcategory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'code',
	),
)); ?>
