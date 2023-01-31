<?php
/* @var $this QuotationController */
/* @var $model Quotation */

$this->breadcrumbs=array(
	'Quotations'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Quotation', 'url'=>array('index')),
	array('label'=>'Manage Quotation', 'url'=>array('admin')),
);
?>

<h1>Create Quotation</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>