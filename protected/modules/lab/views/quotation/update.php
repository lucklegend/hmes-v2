<?php
/* @var $this QuotationController */
/* @var $model Quotation */

$this->breadcrumbs=array(
	'Quotations'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Quotation', 'url'=>array('index')),
	array('label'=>'Create Quotation', 'url'=>array('create')),
	array('label'=>'View Quotation', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Quotation', 'url'=>array('admin')),
);
?>

<h1>Update Quotation <?php echo $model->quotationCode; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>