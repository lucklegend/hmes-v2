<?php
/* @var $this ReorderstocksController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Reorderstocks',
);

$this->menu=array(
	array('label'=>'Create Reorderstocks', 'url'=>array('create')),
	array('label'=>'Manage Reorderstocks', 'url'=>array('admin')),
);
?>

<h1>Reorderstocks</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
