<?php
/* @var $this ReportcategoryController */
/* @var $model Reportcategory */

$this->breadcrumbs=array(
	'Reportcategories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Reportcategory', 'url'=>array('index')),
	array('label'=>'Manage Reportcategory', 'url'=>array('admin')),
);
?>

<h1>Create Reportcategory</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>