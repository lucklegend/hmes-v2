<?php
/* @var $this TestreportController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Testreports',
);

$this->menu=array(
	array('label'=>'Create Testreport', 'url'=>array('create')),
	array('label'=>'Manage Testreport', 'url'=>array('admin')),
);
?>

<h1>Testreports</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
