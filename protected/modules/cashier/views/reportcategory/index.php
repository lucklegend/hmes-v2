<?php
/* @var $this ReportcategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Reportcategories',
);

$this->menu=array(
	array('label'=>'Create Reportcategory', 'url'=>array('create')),
	array('label'=>'Manage Reportcategory', 'url'=>array('admin')),
);
?>

<h1>Reportcategories</h1>

<div class="report-icon-container">
        <?php echo CHtml::link('',array('reportcategory/index'),array('class'=>'cashier-icon-rcd'));?>
        <div class="dashIconText "><?php echo CHtml::link('Report of Collections and Deposits',array('/reportcategory/index'));?></div>
</div>

<div class="report-icon-container">
        <?php echo CHtml::link('',array('receiving/index'),array('class'=>'cashier-icon-rcd'));?>
        <div class="dashIconText "><?php echo CHtml::link('Cash Receipts Register',array('receiving/index'));?></div>
</div>