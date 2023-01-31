<?php
/* @var $this FundingsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Fundings',
);

$this->menu=array(
	array('label'=>'Create Fundings', 'url'=>array('create')),
	array('label'=>'Manage Fundings', 'url'=>array('admin')),
);
?>

<h1>Fundings</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
